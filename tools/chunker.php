<?php
/**
 * ChunkShield Chunking Functions
 * 
 * This file contains functions for splitting obfuscated PHP code into chunks,
 * encrypting those chunks, and managing chunk files.
 */

// Include utilities if not already included
if (!function_exists('generate_random_string')) {
    require_once __DIR__ . '/utils.php';
}

/**
 * Generates a random encryption key
 * 
 * @param int $length Key length in bytes
 * @return string Encryption key
 */
function generate_encryption_key($length = 32) {
    return bin2hex(secure_random_bytes($length / 2));
}

/**
 * Encrypts data using AES-256-CBC
 * 
 * @param string $data Data to encrypt
 * @param string $key Encryption key
 * @param bool $use_gzip Whether to compress data with gzip before encryption
 * @param bool $use_base64 Whether to base64 encode the encrypted data
 * @return array Array containing encrypted data and IV
 */
function encrypt_data($data, $key, $use_gzip = true, $use_base64 = true) {
    // Compress data if gzip is enabled
    if ($use_gzip && function_exists('gzencode')) {
        $data = gzencode($data, 9);
    }
    
    // Generate initialization vector
    $iv = secure_random_bytes(16);
    
    // Encrypt data
    $encrypted = openssl_encrypt(
        $data,
        'AES-256-CBC',
        $key,
        OPENSSL_RAW_DATA,
        $iv
    );
    
    if ($encrypted === false) {
        log_message("Encryption failed: " . openssl_error_string(), 'error');
        return false;
    }
    
    // Combine IV and encrypted data
    $result = $iv . $encrypted;
    
    // Base64 encode if enabled
    if ($use_base64) {
        $result = base64_encode($result);
    }
    
    return [
        'data' => $result,
        'iv' => bin2hex($iv),
        'method' => 'AES-256-CBC',
        'gzip' => $use_gzip,
        'base64' => $use_base64
    ];
}

/**
 * Decrypts data encrypted with AES-256-CBC
 * 
 * @param string $encrypted_data Encrypted data
 * @param string $key Encryption key
 * @param bool $use_gzip Whether the data was compressed with gzip
 * @param bool $use_base64 Whether the data was base64 encoded
 * @return string|bool Decrypted data or false on failure
 */
function decrypt_data($encrypted_data, $key, $use_gzip = true, $use_base64 = true) {
    // Base64 decode if enabled
    if ($use_base64) {
        $encrypted_data = base64_decode($encrypted_data);
        if ($encrypted_data === false) {
            log_message("Base64 decoding failed", 'error');
            return false;
        }
    }
    
    // Extract IV from the beginning of the data
    $iv_size = 16;
    $iv = substr($encrypted_data, 0, $iv_size);
    $encrypted_data = substr($encrypted_data, $iv_size);
    
    // Decrypt data
    $decrypted = openssl_decrypt(
        $encrypted_data,
        'AES-256-CBC',
        $key,
        OPENSSL_RAW_DATA,
        $iv
    );
    
    if ($decrypted === false) {
        log_message("Decryption failed: " . openssl_error_string(), 'error');
        return false;
    }
    
    // Decompress data if gzip was used
    if ($use_gzip && function_exists('gzdecode')) {
        $decrypted = gzdecode($decrypted);
        if ($decrypted === false) {
            log_message("Gzip decompression failed", 'error');
            return false;
        }
    }
    
    return $decrypted;
}

/**
 * Splits a string into chunks of specified size
 * 
 * @param string $data Data to be chunked
 * @param int $chunk_size Size of each chunk in bytes
 * @param int $min_chunks Minimum number of chunks to generate
 * @return array Array of data chunks
 */
function split_into_chunks($data, $chunk_size = 4096, $min_chunks = 3) {
    $length = strlen($data);
    $chunks = [];
    
    // Ensure we have at least the minimum number of chunks
    $actual_chunk_size = $chunk_size;
    if ($length / $actual_chunk_size < $min_chunks) {
        $actual_chunk_size = ceil($length / $min_chunks);
    }
    
    // Split the data
    for ($i = 0; $i < $length; $i += $actual_chunk_size) {
        $chunks[] = substr($data, $i, $actual_chunk_size);
    }
    
    return $chunks;
}

/**
 * Generates a simple hash for verification
 * 
 * @param string $data Data to hash
 * @param string $key Secret key to use in the hash
 * @return string Hash value
 */
function generate_hash($data, $key) {
    return hash_hmac('sha256', $data, $key);
}

/**
 * Creates chunk files for the obfuscated code
 * 
 * @param string $obfuscated_code Obfuscated PHP code
 * @param string $output_dir Directory to store chunk files
 * @param string $encryption_key Encryption key (generated if null)
 * @param array $options Chunking options
 * @return array|bool Chunk information or false on failure
 */
function create_chunks($obfuscated_code, $output_dir, $encryption_key = null, $options = []) {
    // Set default options
    $default_options = [
        'chunk_size' => 4096,
        'min_chunks' => 3,
        'use_gzip' => true,
        'use_base64' => true
    ];
    
    $options = array_merge($default_options, $options);
    
    // Ensure the output directory exists
    if (!ensure_directory($output_dir)) {
        log_message("Failed to create output directory: $output_dir", 'error');
        return false;
    }
    
    // Generate encryption key if not provided
    if ($encryption_key === null) {
        $encryption_key = generate_encryption_key();
    }
    
    // Split the code into chunks
    $chunks = split_into_chunks($obfuscated_code, $options['chunk_size'], $options['min_chunks']);
    $chunk_count = count($chunks);
    
    if ($chunk_count === 0) {
        log_message("No chunks were created", 'error');
        return false;
    }
    
    log_message("Splitting code into $chunk_count chunks", 'info');
    
    // Process each chunk
    $chunk_info = [
        'key' => $encryption_key,
        'total' => $chunk_count,
        'chunks' => [],
        'options' => $options
    ];
    
    // Clear existing chunk files
    $files = glob($output_dir . '/*.chunk');
    foreach ($files as $file) {
        unlink($file);
    }
    
    // Create new chunk files
    foreach ($chunks as $index => $chunk) {
        // Generate unique chunk ID
        $chunk_id = generate_random_string(16);
        $filename = $output_dir . '/' . $chunk_id . '.chunk';
        
        // Encrypt the chunk
        $encrypted = encrypt_data(
            $chunk,
            $encryption_key,
            $options['use_gzip'],
            $options['use_base64']
        );
        
        if ($encrypted === false) {
            log_message("Failed to encrypt chunk $index", 'error');
            return false;
        }
        
        // Add metadata
        $chunk_data = [
            'id' => $chunk_id,
            'index' => $index,
            'size' => strlen($chunk),
            'hash' => generate_hash($chunk, $encryption_key),
            'encrypted_size' => strlen($encrypted['data'])
        ];
        
        // Write the encrypted chunk to file
        if (file_put_contents($filename, $encrypted['data']) === false) {
            log_message("Failed to write chunk file: $filename", 'error');
            return false;
        }
        
        // Add to chunk info
        $chunk_info['chunks'][] = $chunk_data;
    }
    
    return $chunk_info;
}

/**
 * Generates metadata file for chunks
 * 
 * @param array $chunk_info Chunk information
 * @param string $output_dir Directory to store metadata file
 * @return string|bool Path to metadata file or false on failure
 */
function create_chunk_metadata($chunk_info, $output_dir) {
    $metadata_file = $output_dir . '/metadata.json';
    
    // Encrypt metadata
    $encrypted = encrypt_data(
        json_encode($chunk_info),
        $chunk_info['key'],
        $chunk_info['options']['use_gzip'] ?? true,
        $chunk_info['options']['use_base64'] ?? true
    );
    
    if ($encrypted === false) {
        log_message("Failed to encrypt chunk metadata", 'error');
        return false;
    }
    
    // Write metadata file
    if (file_put_contents($metadata_file, $encrypted['data']) === false) {
        log_message("Failed to write metadata file: $metadata_file", 'error');
        return false;
    }
    
    return $metadata_file;
}

/**
 * Creates a decryption function string for the loader
 * 
 * @param string $key Encryption key
 * @param bool $use_gzip Whether gzip compression is used
 * @param bool $use_base64 Whether base64 encoding is used
 * @return string PHP code for decryption function
 */
function create_decrypt_function($key, $use_gzip = true, $use_base64 = true) {
    // Generate random function name
    $func_name = generate_random_var_name();
    
    // Start building the function
    $function = "function $func_name(\$d) {\n";
    
    // Add base64 decode if enabled
    if ($use_base64) {
        $function .= "    \$d = base64_decode(\$d);\n";
    }
    
    // Extract IV and ciphertext
    $function .= "    \$i = substr(\$d, 0, 16);\n";
    $function .= "    \$c = substr(\$d, 16);\n";
    
    // Add decryption code
    $function .= "    \$r = openssl_decrypt(\$c, 'AES-256-CBC', '$key', OPENSSL_RAW_DATA, \$i);\n";
    
    // Add gzip decode if enabled
    if ($use_gzip) {
        $function .= "    if (\$r !== false) {\n";
        $function .= "        \$r = gzdecode(\$r);\n";
        $function .= "    }\n";
    }
    
    // Return the result
    $function .= "    return \$r;\n";
    $function .= "}\n";
    
    return $function;
}

/**
 * Generates a loader file for the chunked code
 * 
 * @param array $chunk_info Chunk information
 * @param string $output_dir Output directory
 * @param array $license_info License information
 * @param array $options Loader options
 * @return array|bool Result with loader information or false on failure
 */
function generate_loader($chunk_info, $output_dir, $license_info = [], $options = []) {
    // Set default options
    $default_options = [
        'add_junk_eval' => true,
        'junk_count' => 5,
        'add_fingerprinting' => true
    ];
    
    $options = array_merge($default_options, $options);
    
    // Ensure the output directory exists
    if (!ensure_directory($output_dir)) {
        log_message("Failed to create output directory: $output_dir", 'error');
        return false;
    }
    
    // Generate random variable names
    $var_data = generate_random_var_name();
    $var_chunks = generate_random_var_name();
    $var_chunk = generate_random_var_name();
    $var_code = generate_random_var_name();
    $var_key = generate_random_var_name();
    $var_result = generate_random_var_name();
    $var_i = generate_random_var_name();
    
    // Create the decrypt function and extract its name
    $decrypt_function = create_decrypt_function(
        $chunk_info['key'],
        $chunk_info['options']['use_gzip'] ?? true,
        $chunk_info['options']['use_base64'] ?? true
    );
    
    // Extract the function name from the generated code
    preg_match('/function\s+([a-zA-Z0-9_]+)\s*\(/i', $decrypt_function, $matches);
    $decrypt_function_name = $matches[1];
    
    // Start building the loader
    $loader = "<?php\n/* ChunkShield Protected File */\n\n";
    
    // Add junk eval code at the beginning
    if ($options['add_junk_eval']) {
        $junk_count = rand(1, $options['junk_count']);
        for ($i = 0; $i < $junk_count; $i++) {
            $loader .= generate_junk_eval() . "\n";
        }
    }
    
    // Add fingerprinting code if license validation is enabled
    if ($options['add_fingerprinting'] && !empty($license_info)) {
        $loader .= generate_fingerprinting_code($license_info) . "\n";
    }
    
    // Add decrypt function
    $loader .= $decrypt_function . "\n";
    
    // Add more junk eval - use a safer approach
    if ($options['add_junk_eval']) {
        $loader .= 'eval(base64_decode("' . base64_encode('$' . generate_random_var_name() . ' = ' . rand(1, 1000) . ';') . '"));' . "\n";
    }
    
    // Build chunk data array
    $loader .= "\$" . $var_chunks . " = [\n";
    foreach ($chunk_info['chunks'] as $chunk) {
        $loader .= "    [";
        $loader .= "'id' => '" . $chunk['id'] . "', ";
        $loader .= "'index' => " . $chunk['index'] . ", ";
        $loader .= "'hash' => '" . $chunk['hash'] . "'";
        $loader .= "],\n";
    }
    $loader .= "];\n\n";
    
    // Sort chunks by index
    $loader .= "usort(\$" . $var_chunks . ", function(\$a, \$b) { return \$a['index'] - \$b['index']; });\n";
    
    // Add more junk eval
    if ($options['add_junk_eval']) {
        $loader .= generate_junk_eval() . "\n";
    }
    
    // Initialize result variable
    $loader .= "\$" . $var_code . " = '';\n";
    
    // Load and decrypt each chunk
    $loader .= "foreach (\$" . $var_chunks . " as \$" . $var_i . " => \$" . $var_chunk . ") {\n";
    $loader .= "    \$" . $var_data . " = file_get_contents(__DIR__ . '/chunks/' . \$" . $var_chunk . "['id'] . '.chunk');\n";
    $loader .= "    \$" . $var_result . " = " . $decrypt_function_name . "(\$" . $var_data . ");\n";
    
    // Hash verification
    $loader .= "    if (hash_hmac('sha256', \$" . $var_result . ", '" . $chunk_info['key'] . "') !== \$" . $var_chunk . "['hash']) {\n";
    $loader .= "        die('Integrity check failed for chunk ' . \$" . $var_i . ");\n";
    $loader .= "    }\n";
    
    // Add chunk to the code
    $loader .= "    \$" . $var_code . " .= \$" . $var_result . ";\n";
    $loader .= "}\n\n";
    
    // Add final junk eval
    if ($options['add_junk_eval']) {
        $loader .= generate_junk_eval() . "\n";
    }
    
    // Check content type and handle accordingly instead of direct eval
    $loader .= "// Check if the decrypted content is valid PHP code\n";
    $loader .= "if (substr(trim(\$" . $var_code . "), 0, 5) === '<?php' || substr(trim(\$" . $var_code . "), 0, 2) === '<?') {\n";
    $loader .= "    // It's PHP code, evaluate it after removing PHP tags\n";
    $loader .= "    eval(preg_replace('/^<\\?php|\?>$/i', '', \$" . $var_code . "));\n";
    $loader .= "} else {\n";
    $loader .= "    // If it's not PHP code, output it as HTML content\n";
    $loader .= "    echo \$" . $var_code . ";\n";
    $loader .= "}\n";
    
    // Add closing PHP tag
    $loader .= "?>";
    
    // Write loader to file
    $output_file = $output_dir . '/loader.php';
    if (file_put_contents($output_file, $loader) === false) {
        log_message("Failed to write loader file: $output_file", 'error');
        return false;
    }
    
    return [
        'file' => $output_file,
        'size' => strlen($loader),
        'decrypt_function' => $decrypt_function_name
    ];
}

/**
 * Generates junk eval code for obfuscation
 * 
 * @return string PHP code containing junk eval statements
 */
function generate_junk_eval() {
    $junk_types = [
        // Type 1: Simple variable assignment wrapped in eval
        function() {
            $var_name = generate_random_var_name();
            $value = rand(1, 1000);
            return 'eval(\'$' . $var_name . ' = ' . $value . ';\');';
        },
        
        // Type 2: Base64 encoded junk
        function() {
            $var_name = generate_random_var_name();
            $junk_code = '$' . $var_name . ' = ' . rand(1, 1000) . ';';
            return 'eval(base64_decode("' . base64_encode($junk_code) . '"));';
        },
        
        // Type 3: String concatenation in eval - FIXED to avoid escaped quotes
        function() {
            $var_name = generate_random_var_name();
            $string = '$' . $var_name . ' = ' . rand(1, 1000) . ';';
            return 'eval(base64_decode("' . base64_encode($string) . '"));';
        },
        
        // Type 4: Conditional eval that always executes
        function() {
            $condition = rand(1, 10) . ' > ' . rand(0, 9);
            $var_name = generate_random_var_name();
            $value = rand(1, 1000);
            return 'if(' . $condition . ') { eval(\'$' . $var_name . ' = ' . $value . ';\'); }';
        }
    ];
    
    // Select a random junk type and generate code
    $junk_generator = $junk_types[array_rand($junk_types)];
    return $junk_generator();
}

/**
 * Generates fingerprinting code to validate execution environment
 * 
 * @param array $restrictions Environment restrictions
 * @return string PHP code for fingerprinting
 */
function generate_fingerprinting_code($restrictions = []) {
    $code = '';
    $var_exit = generate_random_var_name();
    $var_message = generate_random_var_name();
    
    // Start with exit variable set to false
    $code .= '$' . $var_exit . '=false;';
    $code .= '$' . $var_message . '="";';
    
    // Domain check
    if (isset($restrictions['domain']) && !empty($restrictions['domain'])) {
        $domains = is_array($restrictions['domain']) ? $restrictions['domain'] : [$restrictions['domain']];
        $domain_check = [];
        
        foreach ($domains as $domain) {
            $domain_check[] = '$_SERVER["HTTP_HOST"]=="' . $domain . '"';
        }
        
        $code .= 'if(!(' . implode('||', $domain_check) . ')){';
        $code .= '$' . $var_exit . '=true;';
        $code .= '$' . $var_message . '.="Invalid domain. ";';
        $code .= '}';
    }
    
    // IP check
    if (isset($restrictions['ip']) && !empty($restrictions['ip'])) {
        $ips = is_array($restrictions['ip']) ? $restrictions['ip'] : [$restrictions['ip']];
        $ip_check = [];
        
        foreach ($ips as $ip) {
            // Handle IP ranges with wildcards (e.g., 192.168.1.*)
            if (strpos($ip, '*') !== false) {
                $ip_pattern = str_replace('.', '\.', $ip);
                $ip_pattern = str_replace('*', '[0-9]+', $ip_pattern);
                $ip_check[] = 'preg_match("/^' . $ip_pattern . '$/", $_SERVER["REMOTE_ADDR"])';
            } else {
                $ip_check[] = '$_SERVER["REMOTE_ADDR"]=="' . $ip . '"';
            }
        }
        
        $code .= 'if(!(' . implode('||', $ip_check) . ')){';
        $code .= '$' . $var_exit . '=true;';
        $code .= '$' . $var_message . '.="Invalid IP address. ";';
        $code .= '}';
    }
    
    // Path check
    if (isset($restrictions['path']) && !empty($restrictions['path'])) {
        $paths = is_array($restrictions['path']) ? $restrictions['path'] : [$restrictions['path']];
        $path_check = [];
        
        foreach ($paths as $path) {
            // Allow partial path matching with wildcards
            if (strpos($path, '*') !== false) {
                $path_pattern = str_replace('/', '\/', $path);
                $path_pattern = str_replace('*', '.*', $path_pattern);
                $path_check[] = 'preg_match("/^' . $path_pattern . '$/", $_SERVER["SCRIPT_FILENAME"])';
            } else {
                $path_check[] = 'strpos($_SERVER["SCRIPT_FILENAME"], "' . $path . '")!==false';
            }
        }
        
        $code .= 'if(!(' . implode('||', $path_check) . ')){';
        $code .= '$' . $var_exit . '=true;';
        $code .= '$' . $var_message . '.="Invalid execution path. ";';
        $code .= '}';
    }
    
    // Add exit code
    $code .= 'if($' . $var_exit . '){';
    $code .= 'die("License verification failed. " . $' . $var_message . ');';
    $code .= '}';
    
    return $code;
}