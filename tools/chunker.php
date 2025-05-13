<?php
/**
 * ChunkShield Chunking Functions
 * 
 * This file contains functions for splitting obfuscated PHP code into chunks,
 * encrypting those chunks, and managing chunk files.
 */

// Include utilities and anti-reverse engineering tools
if (!function_exists('generate_random_string')) {
    require_once __DIR__ . '/utils.php';
}

// Include anti-debugging, anti-cracking and obfuscation tools
require_once __DIR__ . '/anti_debug.php';
require_once __DIR__ . '/anti_crack.php';
require_once __DIR__ . '/obfuscation.php';

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
function generate_loader_file($chunk_info, $output_dir, $license_info = [], $options = []) {
    // Include anti-debugging functionality
    require_once __DIR__ . '/anti_debug.php';
    
    // Set default options
    $default_options = [
        'add_junk_eval' => true,
        'junk_count' => 5,
        'add_fingerprinting' => true,
        'add_anti_debugging' => true,
        'enable_self_destruct' => true,
        'encrypt_loader' => true
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
    
    // Add anti-debugging code if enabled
    if ($options['add_anti_debugging']) {
        // Add direct anti-debugging check at the beginning
        $var_debug_check = generate_random_var_name();
        $var_debug_reason = generate_random_var_name();
        $var_debug_log = generate_random_var_name();
        $var_self_destruct = generate_random_var_name();
        
        $loader .= "// Anti-debugging detection\n";
        $loader .= "\$ENABLE_SELF_DESTRUCT = " . ($options['enable_self_destruct'] ? 'true' : 'false') . ";\n";
        $loader .= "\$" . $var_debug_reason . " = false;\n\n";
        
        // Check for Xdebug
        $loader .= "// Check for debugging tools\n";
        $loader .= "if (function_exists('xdebug_get_code_coverage') || extension_loaded('xdebug')) {\n";
        $loader .= "    \$" . $var_debug_reason . " = 'Xdebug detected';\n";
        $loader .= "}\n\n";
        
        // Check debug headers
        $loader .= "// Check for debug headers\n";
        $loader .= "foreach (['HTTP_X_DEBUG', 'HTTP_XDEBUG_SESSION', 'HTTP_X_XDEBUG_SESSION'] as \$" . $var_debug_check . ") {\n";
        $loader .= "    if (isset(\$_SERVER[\$" . $var_debug_check . "])) {\n";
        $loader .= "        \$" . $var_debug_reason . " = 'Debug header detected: ' . \$" . $var_debug_check . ";\n";
        $loader .= "    }\n";
        $loader .= "}\n\n";
        
        // Check debug environment variables
        $loader .= "// Check for debug environment variables\n";
        $loader .= "foreach (['XDEBUG_CONFIG', 'XDEBUG_SESSION', 'XDEBUG_TRIGGER', 'PHP_IDE_CONFIG'] as \$" . $var_debug_check . ") {\n";
        $loader .= "    if (getenv(\$" . $var_debug_check . ") !== false) {\n";
        $loader .= "        \$" . $var_debug_reason . " = 'Debug environment variable detected: ' . \$" . $var_debug_check . ";\n";
        $loader .= "    }\n";
        $loader .= "}\n\n";
        
        // Self-destruct function
        if ($options['enable_self_destruct']) {
            $loader .= "// Self-destruct function\n";
            $loader .= "function " . $var_self_destruct . "(\$reason) {\n";
            $loader .= "    \$log_file = __DIR__ . '/runtime.log';\n";
            $loader .= "    \$timestamp = date('Y-m-d H:i:s');\n";
            $loader .= "    \$ip = isset(\$_SERVER['REMOTE_ADDR']) ? \$_SERVER['REMOTE_ADDR'] : 'CLI';\n";
            $loader .= "    \$entry = \"[\$timestamp] [SELF-DESTRUCT] Protected code self-destructing\\n\";\n";
            $loader .= "    \$entry .= \"IP: \$ip\\n\";\n";
            $loader .= "    \$entry .= \"Reason: \$reason\\n\";\n";
            $loader .= "    @file_put_contents(\$log_file, \$entry, FILE_APPEND);\n\n";
            $loader .= "    // Delete chunks\n";
            $loader .= "    \$chunks_dir = __DIR__ . '/chunks';\n";
            $loader .= "    if (is_dir(\$chunks_dir)) {\n";
            $loader .= "        \$files = glob(\$chunks_dir . '/*');\n";
            $loader .= "        foreach (\$files as \$file) {\n";
            $loader .= "            if (is_file(\$file)) @unlink(\$file);\n";
            $loader .= "        }\n";
            $loader .= "        @rmdir(\$chunks_dir);\n";
            $loader .= "    }\n\n";
            $loader .= "    // Delete self (will happen after script finishes)\n";
            $loader .= "    @register_shutdown_function(function() {\n";
            $loader .= "        @unlink(__FILE__);\n";
            $loader .= "    });\n";
            $loader .= "}\n\n";
            
            // Execute self-destruct if debugging detected
            $loader .= "// Execute self-destruct if debugging detected\n";
            $loader .= "if (\$" . $var_debug_reason . " !== false && \$ENABLE_SELF_DESTRUCT) {\n";
            $loader .= "    " . $var_self_destruct . "(\$" . $var_debug_reason . ");\n";
            $loader .= "    die('Security violation detected. This attempt has been logged.');\n";
            $loader .= "}\n\n";
        } else {
            // Just log and exit if debugging detected
            $loader .= "// Handle debugging detection\n";
            $loader .= "if (\$" . $var_debug_reason . " !== false) {\n";
            $loader .= "    \$" . $var_debug_log . " = __DIR__ . '/runtime.log';\n";
            $loader .= "    \$timestamp = date('Y-m-d H:i:s');\n";
            $loader .= "    \$ip = isset(\$_SERVER['REMOTE_ADDR']) ? \$_SERVER['REMOTE_ADDR'] : 'CLI';\n";
            $loader .= "    \$entry = \"[\$timestamp] [SECURITY ALERT] Debug attempt detected from \$ip\\n\";\n";
            $loader .= "    \$entry .= \"Reason: \$" . $var_debug_reason . "\\n\";\n";
            $loader .= "    @file_put_contents(\$" . $var_debug_log . ", \$entry, FILE_APPEND);\n";
            $loader .= "    die('Security violation detected. This attempt has been logged.');\n";
            $loader .= "}\n\n";
        }
    }
    
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
    
    // Make variable initialization safer to avoid syntax errors
    $loader = preg_replace('/\$([a-zA-Z_\x7f-\xff][a-zA-Z0-9_\x7f-\xff]*)(?=\s*=\s*[0-9]+)/', '\$$1', $loader);
    
    // Add more junk eval
    if ($options['add_junk_eval']) {
        $loader .= generate_junk_eval() . "\n";
    }
    
    // Initialize result variable
    $loader .= "\$" . $var_code . " = '';\n";
    
    // Add honeypot license variables that crackers might try to tamper with
    $var_lic_key = generate_random_var_name();
    $var_valid_status = generate_random_var_name();
    $var_license_check = generate_random_var_name();
    
    // Create a fake license structure that looks like it controls access but doesn't actually do anything
    $loader .= "// License verification (appears important but is a honeypot)\n";
    $loader .= "\${$var_lic_key} = '" . bin2hex(random_bytes(16)) . "';\n";
    $loader .= "\${$var_valid_status} = false; // Appears to block execution if tampered with\n\n";
    
    // Add fake check that appears to verify the license but is never actually called
    $loader .= "// License check function (honeypot for crackers)\n";
    $loader .= "function {$var_license_check}() {\n";
    $loader .= "    global \${$var_lic_key}, \${$var_valid_status};\n";
    $loader .= "    \n";
    $loader .= "    if (!\${$var_valid_status}) {\n";
    $loader .= "        // This condition appears to block execution but is never checked\n";
    $loader .= "        // Crackers will try to modify this variable\n";
    $loader .= "        \$GLOBALS['_cs_tampered'] = true; // Flag for detection\n";
    $loader .= "        return false;\n";
    $loader .= "    }\n";
    $loader .= "    return true;\n";
    $loader .= "}\n\n";
    
    // Advanced integrity verification and self-destruct for chunks
    $var_integrity_failures = generate_random_var_name();
    $var_chunks_dir = generate_random_var_name();
    $var_chunk_path = generate_random_var_name();
    $var_runtime_log = generate_random_var_name();
    $var_timestamp = generate_random_var_name();
    
    if ($options['add_anti_debugging']) {
        $loader .= "\$" . $var_integrity_failures . " = 0; // Track integrity failures\n";
        $loader .= "\$" . $var_chunks_dir . " = __DIR__ . '/chunks';\n";
        $loader .= "\$" . $var_runtime_log . " = __DIR__ . '/runtime.log';\n";
        $loader .= "\$" . $var_timestamp . " = date('Y-m-d H:i:s');\n\n";
    }
    
    // Load and decrypt each chunk
    $loader .= "foreach (\$" . $var_chunks . " as \$" . $var_i . " => \$" . $var_chunk . ") {\n";
    $loader .= "    \$" . $var_chunk_path . " = __DIR__ . '/chunks/' . \$" . $var_chunk . "['id'] . '.chunk';\n";
    $loader .= "    if (!file_exists(\$" . $var_chunk_path . ")) {\n";
    
    if ($options['add_anti_debugging']) {
        $loader .= "        @file_put_contents(\$" . $var_runtime_log . ", \"[\" . \$" . $var_timestamp . " . \"] ERROR: Missing chunk file: \" . \$" . $var_chunk . "['id'] . \".chunk\\n\", FILE_APPEND);\n";
        $loader .= "        \$" . $var_integrity_failures . "++;\n";
    }
    
    $loader .= "        die('Missing chunk file: ' . \$" . $var_chunk . "['id'] . '.chunk');\n";
    $loader .= "    }\n";
    $loader .= "    \$" . $var_data . " = file_get_contents(\$" . $var_chunk_path . ");\n";
    $loader .= "    \$" . $var_result . " = " . $decrypt_function_name . "(\$" . $var_data . ");\n";
    
    // Enhanced hash verification with anti-tampering
    $loader .= "    if (hash_hmac('sha256', \$" . $var_result . ", '" . $chunk_info['key'] . "') !== \$" . $var_chunk . "['hash']) {\n";
    
    if ($options['add_anti_debugging']) {
        $loader .= "        @file_put_contents(\$" . $var_runtime_log . ", \"[\" . \$" . $var_timestamp . " . \"] SECURITY ALERT: Integrity check failed for chunk \" . \$" . $var_i . " . \"\\n\", FILE_APPEND);\n";
        $loader .= "        \$" . $var_integrity_failures . "++;\n";
        
        if ($options['enable_self_destruct']) {
            $loader .= "        // Self-destruct triggered on integrity failure\n";
            $loader .= "        if (is_dir(\$" . $var_chunks_dir . ")) {\n";
            $loader .= "            \$files = glob(\$" . $var_chunks_dir . ".'/*');\n";
            $loader .= "            foreach (\$files as \$file) { if (is_file(\$file)) @unlink(\$file); }\n";
            $loader .= "            @rmdir(\$" . $var_chunks_dir . ");\n";
            $loader .= "        }\n";
            $loader .= "        // Delete self\n";
            $loader .= "        @unlink(__FILE__);\n";
        }
    }
    
    $loader .= "        die('Integrity check failed for chunk ' . \$" . $var_i . " . '. Tampered or corrupted code detected.');\n";
    $loader .= "    }\n";
    
    // Add chunk to the code
    $loader .= "    \$" . $var_code . " .= \$" . $var_result . ";\n";
    $loader .= "}\n\n";
    
    // Add summary check
    if ($options['add_anti_debugging']) {
        $loader .= "// Verification summary\n";
        $loader .= "if (\$" . $var_integrity_failures . " > 0) {\n";
        $loader .= "    @file_put_contents(\$" . $var_runtime_log . ", \"[\" . \$" . $var_timestamp . " . \"] FATAL: \" . \$" . $var_integrity_failures . " . \" integrity failures detected. Execution terminated.\\n\", FILE_APPEND);\n";
        $loader .= "    die('Multiple integrity failures detected. Execution terminated.');\n";
        $loader .= "}\n\n";
    }
    
    // Add final junk eval
    if ($options['add_junk_eval']) {
        $loader .= generate_junk_eval() . "\n";
    }
    
    // Enhanced security: Log execution in runtime.log
    if ($options['add_anti_debugging']) {
        $var_runtime_log = generate_random_var_name();
        $var_timestamp = generate_random_var_name();
        $var_ip = generate_random_var_name();
        
        $loader .= "// Log execution for security audit\n";
        $loader .= "\$" . $var_runtime_log . " = __DIR__ . '/runtime.log';\n";
        $loader .= "\$" . $var_timestamp . " = date('Y-m-d H:i:s');\n";
        $loader .= "\$" . $var_ip . " = isset(\$_SERVER['REMOTE_ADDR']) ? \$_SERVER['REMOTE_ADDR'] : 'CLI';\n";
        $loader .= "@file_put_contents(\$" . $var_runtime_log . ", \"[\" . \$" . $var_timestamp . " . \"] Code executed from \" . \$" . $var_ip . " . \"\\n\", FILE_APPEND);\n\n";
    }
    
    // Check content type and handle accordingly instead of direct eval
    $loader .= "// Check if the decrypted content is valid PHP code and prepare it for execution\n";
    $loader .= "// First do thorough check for PHP tags with whitespace handling\n";
    $loader .= "\$" . $var_code . "_trimmed = trim(\$" . $var_code . ");\n";
    $loader .= "if (preg_match('/^<\\?(?:php)?\\s/i', \$" . $var_code . "_trimmed) || substr(\$" . $var_code . "_trimmed, 0, 2) === '<?') {\n";
    $loader .= "    // It's PHP code, evaluate it after carefully removing PHP tags\n";
    
    // Add additional safety check before eval
    if ($options['add_anti_debugging']) {
        $var_cleaned_code = generate_random_var_name();
        $loader .= "    \$" . $var_cleaned_code . " = preg_replace('/^\\s*<\\?(?:php)?\\s+|\\s*\\?>\\s*$/i', '', \$" . $var_code . ");\n";
        $loader .= "    \n";
        $loader .= "    // Final safety check before execution\n";
        $loader .= "    if (strpos(\$" . $var_cleaned_code . ", 'system(') !== false || \n";
        $loader .= "        strpos(\$" . $var_cleaned_code . ", 'passthru(') !== false || \n";
        $loader .= "        strpos(\$" . $var_cleaned_code . ", 'shell_exec(') !== false) {\n";
        $loader .= "        // Potential dangerous code detected\n";
        $loader .= "        @file_put_contents(\$" . $var_runtime_log . ", \"[\" . \$" . $var_timestamp . " . \"] SECURITY ALERT: Dangerous functions detected in code. Execution blocked.\\n\", FILE_APPEND);\n";
        
        // Self-destruct if enabled
        if ($options['enable_self_destruct']) {
            $loader .= "        // Self-destruct triggered\n";
            $loader .= "        \$chunks_dir = __DIR__ . '/chunks';\n";
            $loader .= "        if (is_dir(\$chunks_dir)) {\n";
            $loader .= "            \$files = glob(\$chunks_dir . '/*');\n";
            $loader .= "            foreach (\$files as \$file) { if (is_file(\$file)) @unlink(\$file); }\n";
            $loader .= "            @rmdir(\$chunks_dir);\n";
            $loader .= "        }\n";
            $loader .= "        // Delete self\n";
            $loader .= "        @unlink(__FILE__);\n";
        }
        
        $loader .= "        die('Security violation: Execution terminated');\n";
        $loader .= "    }\n";
        $loader .= "    eval(\$" . $var_cleaned_code . ");\n";
    } else {
        $loader .= "    eval(preg_replace('/^\\s*<\\?(?:php)?\\s+|\\s*\\?>\\s*$/i', '', \$" . $var_code . "));\n";
    }
    
    $loader .= "} else {\n";
    $loader .= "    // If it's not PHP code, output it as HTML content\n";
    $loader .= "    echo \$" . $var_code . ";\n";
    $loader .= "}\n";
    
    // Add a final anti-tampering trap before the closing tag
    $loader .= "// Anti-reverse engineering trap (obfuscated to look like more code follows)\n";
    $loader .= "\$_cs_f = function(\$d, \$k) { \n";
    $loader .= "        \$r=''; \n";
    $loader .= "        for(\$i=0; \$i<strlen(\$d); \$i++) {\n";
    $loader .= "            \$r .= chr(ord(\$d[\$i])^ord(\$k[\$i%strlen(\$k)])); \n";
    $loader .= "        }\n";
    $loader .= "        return \$r; \n";
    $loader .= "    };\n";
    $loader .= "// The following appears to be encrypted important data but is actually a red herring\n";
    $loader .= "\$_cs_d='" . base64_encode(random_bytes(32)) . "';\n";
    $loader .= "\$_cs_k='" . bin2hex(random_bytes(8)) . "';\n";
    $loader .= "// This function looks like it does something critical\n";
    $loader .= "function _cs_validate_execution() { \n";
    $loader .= "    global \$_cs_d, \$_cs_k, \$_cs_f; \n";
    $loader .= "    if (!isset(\$_cs_f) || !is_callable(\$_cs_f)) { \n";
    $loader .= "        return false; \n";
    $loader .= "    } \n";
    $loader .= "    \$x = \$_cs_f(base64_decode(\$_cs_d), \$_cs_k); \n";
    $loader .= "    return strlen(\$x) > 0; \n";
    $loader .= "}\n";
    
    // Add some HTML comments that look like they contain important data
    $loader .= "// HTML comments to mislead reverse engineers\n";
    $loader .= "?>\n";
    $loader .= "<!-- \n";
    $loader .= "    LICENSE_INFO=" . bin2hex(random_bytes(16)) . "\n";
    $loader .= "    VERSION=2.5.3\n";
    $loader .= "    EXPIRY_CHECK=base64_decode('" . base64_encode("License valid until " . date('Y-m-d', strtotime('+1 year'))) . "')\n";
    $loader .= "    ACTIVATION_STATUS=1\n";
    $loader .= "-->";
    
    // Write loader to file
    $output_file = $output_dir . '/loader.php';
    if (file_put_contents($output_file, $loader) === false) {
        log_message("Failed to write loader file: $output_file", 'error');
        return false;
    }
    
    // Validate the loader for syntax errors 
    if (file_exists(__DIR__ . '/loader_validator.php')) {
        require_once __DIR__ . '/loader_validator.php';
        
        $validation_result = validate_loader($output_file);
        if (!$validation_result['valid']) {
            log_message("WARNING: Generated loader has potential issues:", 'warning');
            foreach ($validation_result['errors'] as $error) {
                log_message("  - " . $error, 'warning');
            }
            
            // We still return true as the loader was created, 
            // but errors are logged for the developer
        }
    }
    
    // Apply anti-reverse engineering obfuscation if enabled
    if ($options['add_junk_eval']) {
        log_message("Applying anti-reverse engineering protection to loader", 'info');
        $loader = obfuscate_loader($loader, true, true, $options['add_anti_debugging']);
    }
    
    // Create encrypted loader if enabled
    $encrypted_loader_file = null;
    if ($options['encrypt_loader']) {
        // Generate a unique encryption key for the loader
        $loader_key = generate_encryption_key();
        
        // Create the encrypted loader
        $loader_result = create_encrypted_loader($loader, $output_dir, $loader_key);
        
        if ($loader_result !== false) {
            $encrypted_loader_file = $loader_result['encrypted'];
            log_message("Encrypted loader created successfully", 'info');
            
            // Additional protection for encrypted loader
            $encrypted_loader_content = file_get_contents($encrypted_loader_file);
            $obfuscated_encrypted_loader = obfuscate_loader($encrypted_loader_content, true, true, false);
            file_put_contents($encrypted_loader_file, $obfuscated_encrypted_loader);
            log_message("Applied additional obfuscation to encrypted loader", 'info');
        } else {
            log_message("Failed to create encrypted loader, using standard loader", 'warning');
        }
    }
    
    return [
        'file' => $output_file,
        'encrypted_file' => $encrypted_loader_file,
        'size' => strlen($loader),
        'decrypt_function' => $decrypt_function_name
    ];
}

/**
 * Chunk a file into multiple encrypted pieces (advanced implementation)
 *
 * @param string $file_path Path to the file to chunk
 * @param string $output_dir Directory to save chunks to
 * @param array $options Chunking options
 * @return array|bool Chunk information or false on error
 */
function chunk_file_advanced($file_path, $output_dir, $options = []) {
    // Set default options
    $default_options = [
        'chunk_size' => 4096,
        'min_chunks' => 2,
        'max_chunks' => 10,
        'use_gzip' => true,
        'use_base64' => true,
        'add_junk' => true
    ];
    
    $options = array_merge($default_options, $options);
    
    // Ensure file exists
    if (!file_exists($file_path)) {
        if (function_exists('log_message')) {
            log_message("File not found: $file_path", 'error');
        }
        return false;
    }
    
    // Ensure output directory exists
    if (!is_dir($output_dir)) {
        if (!mkdir($output_dir, 0755, true)) {
            if (function_exists('log_message')) {
                log_message("Failed to create output directory: $output_dir", 'error');
            }
            return false;
        }
    }
    
    // Read file
    $code = file_get_contents($file_path);
    if ($code === false) {
        if (function_exists('log_message')) {
            log_message("Failed to read file: $file_path", 'error');
        }
        return false;
    }
    
    // Generate a random key for encryption
    $key = generate_random_key();
    
    // Determine chunk size and count
    $file_size = strlen($code);
    $chunk_size = $options['chunk_size'];
    
    // Adjust chunk size to ensure we have at least min_chunks
    if ($file_size / $chunk_size < $options['min_chunks']) {
        $chunk_size = floor($file_size / $options['min_chunks']);
    }
    
    // Ensure we don't exceed max_chunks
    if ($file_size / $chunk_size > $options['max_chunks']) {
        $chunk_size = ceil($file_size / $options['max_chunks']);
    }
    
    $chunks = [];
    $chunk_count = ceil($file_size / $chunk_size);
    
    // Create a chunks directory if it doesn't exist
    $chunks_dir = rtrim($output_dir, '/') . '/';
    
    // Create chunks
    for ($i = 0; $i < $chunk_count; $i++) {
        $chunk_id = generate_random_string(16);
        $chunk_data = substr($code, $i * $chunk_size, $chunk_size);
        
        // Encrypt chunk
        $encrypted = encrypt_data(
            $chunk_data,
            $key,
            $options['use_gzip'],
            $options['use_base64']
        );
        
        if ($encrypted === false) {
            if (function_exists('log_message')) {
                log_message("Failed to encrypt chunk $i", 'error');
            }
            return false;
        }
        
        // Write chunk to file
        $chunk_file = $chunks_dir . $chunk_id . '.chunk';
        if (file_put_contents($chunk_file, $encrypted['data']) === false) {
            if (function_exists('log_message')) {
                log_message("Failed to write chunk file: $chunk_file", 'error');
            }
            return false;
        }
        
        // Add chunk info
        $chunks[] = [
            'id' => $chunk_id,
            'index' => $i,
            'hash' => isset($encrypted['hash']) ? $encrypted['hash'] : md5($chunk_data)
        ];
    }
    
    // Create chunk info
    $chunk_info = [
        'source' => basename($file_path),
        'chunks' => $chunks,
        'key' => $key,
        'options' => $options,
        'timestamp' => time(),
        'directory' => $chunks_dir,
        'version' => '1.0'
    ];
    
    // Create metadata file
    $metadata_file = create_chunk_metadata($chunk_info, $chunks_dir);
    if ($metadata_file === false) {
        if (function_exists('log_message')) {
            log_message("Failed to create chunk metadata", 'error');
        }
        return false;
    }
    
    return $chunk_info;
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