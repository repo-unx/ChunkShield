<?php
/**
 * ChunkShield Loader Generator Functions
 * 
 * This file contains functions for generating polymorphic loader scripts
 * that will decrypt and execute the chunked, encrypted PHP code.
 */

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
            return "eval('\$" . $var_name . " = " . $value . ";');";
        },

        // Type 2: Base64 encoded junk
        function() {
            $var_name = generate_random_var_name();
            $junk_code = '$' . $var_name . ' = ' . rand(1, 1000) . ';';
            return 'eval(base64_decode("' . base64_encode($junk_code) . '"));';
        },

        // Type 3: String concatenation in eval
        function() {
            $var_name = generate_random_var_name();
            $parts = [];
            $string = "\$" . $var_name . " = \"" . generate_random_string(rand(5, 15)) . "\";";

            // Split the string into 2-4 parts
            $chunk_count = rand(2, 4);
            $chunk_size = ceil(strlen($string) / $chunk_count);

            for ($i = 0; $i < $chunk_count; $i++) {
                $parts[] = "'" . addslashes(substr($string, $i * $chunk_size, $chunk_size)) . "'";
            }

            return 'eval(' . implode('.', $parts) . ');';
        },

        // Type 4: Conditional eval that always executes
        function() {
            $condition = rand(1, 10) . '>' . rand(0, 9);
            $var_name = generate_random_var_name();
            $value = rand(1, 1000);
            return "if(" . $condition . "){eval('\$" . $var_name . " = " . $value . ";');}";
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

/**
 * Generates loader code for executing chunked PHP code
 * 
 * @param array $chunk_info Chunk information
 * @param array $license_info License information for validation
 * @param bool $add_junk_eval Whether to add junk eval code
 * @param bool $add_fingerprinting Whether to add environment fingerprinting
 * @return string PHP loader code
 */
function generate_loader_code($chunk_info, $license_info = [], $add_junk_eval = true, $add_fingerprinting = true) {
    // Generate random variable names
    $var_data = generate_random_var_name();
    $var_chunks = generate_random_var_name();
    $var_chunk = generate_random_var_name();
    $var_code = generate_random_var_name();
    $var_key = generate_random_var_name();
    $var_result = generate_random_var_name();
    $var_i = generate_random_var_name();

    // Create the decrypt function
    $decrypt_function = create_decrypt_function(
        $chunk_info['key'],
        ENCRYPTION_USE_GZIP,
        ENCRYPTION_USE_BASE64
    );

    // Start building the loader
    $loader = "<?php\n/* ChunkShield Protected File */\n\n";

    // Add junk eval code at the beginning
    if ($add_junk_eval && LOADER_ADD_JUNK_EVAL) {
        $junk_count = rand(1, LOADER_JUNK_COUNT);
        for ($i = 0; $i < $junk_count; $i++) {
            $loader .= generate_junk_eval() . "\n";
        }
    }

    // Add fingerprinting code if license validation is enabled
    if ($add_fingerprinting && LOADER_ADD_FINGERPRINTING && !empty($license_info)) {
        $loader .= generate_fingerprinting_code($license_info) . "\n";
    }

    // Add decrypt function
    $loader .= $decrypt_function . "\n";

    // Add more junk eval
    if ($add_junk_eval && LOADER_ADD_JUNK_EVAL) {
        $loader .= generate_junk_eval() . "\n";
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
    if ($add_junk_eval && LOADER_ADD_JUNK_EVAL) {
        $loader .= generate_junk_eval() . "\n";
    }

    // Initialize result variable
    $loader .= "\$" . $var_code . " = '';\n";

    // Load and decrypt each chunk
    $loader .= "foreach (\$" . $var_chunks . " as \$" . $var_i . " => \$" . $var_chunk . ") {\n";
    $loader .= "    \$" . $var_data . " = file_get_contents(__DIR__ . '/chunks/' . \$" . $var_chunk . "['id'] . '.chunk');\n";
    $loader .= "    \$" . $var_result . " = " . generate_random_var_name() . "(\$" . $var_data . ");\n";

    // Hash verification
    $loader .= "    if (hash_hmac('sha256', \$" . $var_result . ", '" . $chunk_info['key'] . "') !== \$" . $var_chunk . "['hash']) {\n";
    $loader .= "        die('Integrity check failed for chunk ' . \$" . $var_i . ");\n";
    $loader .= "    }\n";

    // Add chunk to the code
    $loader .= "    \$" . $var_code . " .= \$" . $var_result . ";\n";
    $loader .= "}\n\n";

    // Add final junk eval
    if ($add_junk_eval && LOADER_ADD_JUNK_EVAL) {
        $loader .= generate_junk_eval() . "\n";
    }

    // Sanitize and execute the reconstructed code
    $loader .= "// Clean up code before evaluation\n";
    $loader .= "\$" . $var_code . " = preg_replace('/[\\x00-\\x08\\x0B\\x0C\\x0E-\\x1F\\x7F]/', '', \$" . $var_code . ");\n";
    $loader .= "\$" . $var_code . " = preg_replace('/<\\?(?:php)?(?:\\s+|\\r?\\n)/', '', \$" . $var_code . ");\n";
    $loader .= "\$" . $var_code . " = preg_replace('/\\?>(?:\\s+|\\r?\\n)*$/', '', \$" . $var_code . ");\n";
    $loader .= "// Execute the code\n";
    $loader .= "try {\n";
    $loader .= "    eval(\$" . $var_code . ");\n";
    $loader .= "} catch (ParseError \$e) {\n";
    $loader .= "    echo 'Error executing protected code: ' . \$e->getMessage();\n";
    $loader .= "}\n";

    // Add closing PHP tag
    $loader .= "?>";

    return $loader;
}

/**
 * Creates a loader file for the protected application
 * 
 * @param array $chunk_info Chunk information
 * @param string $output_file Path to output loader file
 * @param array $license_info License information
 * @return bool True on success, false on failure
 */
function create_loader_file($chunk_info, $output_file, $license_info = []) {
    // Generate the loader code
    $loader_code = generate_loader_code(
        $chunk_info,
        $license_info,
        LOADER_ADD_JUNK_EVAL,
        LOADER_ADD_FINGERPRINTING
    );

    // Write the loader to file
    if (file_put_contents($output_file, $loader_code) === false) {
        log_message("Failed to write loader file: $output_file", 'error');
        return false;
    }

    log_message("Loader file created: $output_file", 'info');
    return true;
}