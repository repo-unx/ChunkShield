<?php
/**
 * ChunkShield Anti-Reverse Engineering Features
 * 
 * This file contains functions for advanced code obfuscation and protection
 * against reverse engineering attempts.
 */

// Include utilities if not already included
if (!function_exists('generate_random_string')) {
    require_once __DIR__ . '/utils.php';
}

/**
 * Generates random junk code to confuse decompilers
 * 
 * @param int $complexity Level of complexity for the junk code
 * @return string Generated junk code
 */
function generate_advanced_junk_code($complexity = 2) {
    $junk_patterns = [
        // Pattern 1: Variable assignments
        function() {
            $var = generate_random_var_name();
            $value = rand(1, 10000);
            return "\$" . $var . " = " . $value . ";";
        },
        
        // Pattern 2: String operations
        function() {
            $var1 = generate_random_var_name();
            $var2 = generate_random_var_name();
            $str1 = "'" . bin2hex(random_bytes(4)) . "'";
            $str2 = "'" . bin2hex(random_bytes(3)) . "'";
            return "\$" . $var1 . " = " . $str1 . "; \$" . $var2 . " = \$" . $var1 . " . " . $str2 . ";";
        },
        
        // Pattern 3: Conditional statements
        function() {
            $var = generate_random_var_name();
            $value = rand(1, 100);
            $condition = rand(0, 1) ? '>' : '<';
            return "if (rand(1, 100) " . $condition . " " . $value . ") { \$" . $var . " = true; } else { \$" . $var . " = false; }";
        },
        
        // Pattern 4: Array operations
        function() {
            $var = generate_random_var_name();
            $elements = rand(2, 5);
            $array = "[\n";
            for ($i = 0; $i < $elements; $i++) {
                $array .= "        " . rand(1, 1000) . ",\n";
            }
            $array .= "    ]";
            return "\$" . $var . " = " . $array . ";";
        },
        
        // Pattern 5: Function calls
        function() {
            $functions = ['time', 'rand', 'intval', 'round', 'ceil', 'floor'];
            $function = $functions[array_rand($functions)];
            $var = generate_random_var_name();
            return "\$" . $var . " = " . $function . "(" . rand(1, 100) . ");";
        }
    ];
    
    // Generate code based on complexity
    $result = "// Junk code for obfuscation\n";
    
    for ($i = 0; $i < $complexity; $i++) {
        $pattern = $junk_patterns[rand(0, count($junk_patterns) - 1)];
        $result .= $pattern() . "\n";
    }
    
    return $result;
}

/**
 * Generates random variable and function transformations for obfuscation
 *
 * @param string $code The PHP code to transform
 * @return string Transformed code
 */
function generate_variable_transformations($code) {
    // Extract variable names from the code
    preg_match_all('/\$([a-zA-Z_\x7f-\xff][a-zA-Z0-9_\x7f-\xff]*)/', $code, $matches);
    
    $variables = array_unique($matches[1]);
    $replacements = [];
    
    // Create random replacements for variables
    foreach ($variables as $variable) {
        // Skip superglobals and common variables
        $skip_vars = ['_GET', '_POST', '_SERVER', '_FILES', '_REQUEST', '_SESSION', '_COOKIE', '_ENV'];
        if (in_array($variable, $skip_vars)) {
            continue;
        }
        
        $replacements['$' . $variable] = '$' . generate_random_var_name();
    }
    
    // Apply replacements
    return str_replace(array_keys($replacements), array_values($replacements), $code);
}

/**
 * Injects dummy functions to confuse code analysis
 *
 * @param string $code The PHP code
 * @param int $count Number of dummy functions to insert
 * @return string Code with dummy functions
 */
function inject_dummy_functions($code, $count = 3) {
    $functions = "";
    
    for ($i = 0; $i < $count; $i++) {
        $function_name = "dummy_" . bin2hex(random_bytes(4));
        $param_count = rand(1, 3);
        $params = [];
        
        for ($j = 0; $j < $param_count; $j++) {
            $params[] = '$param' . ($j + 1);
        }
        
        $param_string = implode(', ', $params);
        
        $body = generate_junk_code(rand(2, 4));
        $return = rand(0, 1) ? 'return null;' : 'return ' . rand(1, 100) . ';';
        
        $functions .= "function " . $function_name . "(" . $param_string . ") {\n";
        $functions .= "    " . str_replace("\n", "\n    ", $body) . "\n";
        $functions .= "    " . $return . "\n";
        $functions .= "}\n\n";
    }
    
    // Split code at <?php and insert dummy functions after it
    $parts = explode('<?php', $code, 2);
    if (count($parts) > 1) {
        return $parts[0] . '<?php' . "\n\n" . $functions . $parts[1];
    }
    
    return $functions . $code;
}

/**
 * Generates anti-reverse engineering function calls
 * 
 * @param bool $include_eval Whether to include eval code
 * @return string Code with anti-reverse engineering techniques
 */
function generate_anti_reverse_engineering($include_eval = true) {
    $code = "// Anti-reverse engineering\n";
    
    // Add timing checks to detect debugging/stepping through code
    $var_start = generate_random_var_name();
    $var_end = generate_random_var_name();
    $var_diff = generate_random_var_name();
    
    $code .= "\$" . $var_start . " = microtime(true);\n";
    
    // Add junk calculation
    $code .= "for (\$i = 0; \$i < " . rand(5, 10) . "; \$i++) {\n";
    $code .= "    \$x" . rand(1000, 9999) . " = " . rand(1, 1000) . " * " . rand(1, 100) . ";\n";
    $code .= "}\n";
    
    $code .= "\$" . $var_end . " = microtime(true);\n";
    $code .= "\$" . $var_diff . " = \$" . $var_end . " - \$" . $var_start . ";\n";
    
    // Check if execution took too long (indicating potential debugging/stepping)
    $code .= "if (\$" . $var_diff . " > 1.0) { // If execution takes more than 1 second\n";
    $code .= "    // Possible debugging detected\n";
    $code .= "    @file_put_contents(__DIR__ . '/runtime.log', date('Y-m-d H:i:s') . ' - Possible debugging detected (slow execution: ' . \$" . $var_diff . " . ' seconds)\\n', FILE_APPEND);\n";
    
    if ($include_eval) {
        // Add confusing eval code
        $enc = base64_encode('// Anti-debugging triggered');
        $code .= "    @eval(base64_decode('$enc'));\n";
    }
    
    $code .= "}\n\n";
    
    return $code;
}

/**
 * Encrypts a string using an obscured algorithm to avoid pattern detection
 * 
 * @param string $input The string to encrypt
 * @param string $key Encryption key
 * @return string Encrypted string
 */
function anti_pattern_encrypt($input, $key) {
    // Generate a key variant to make each encryption unique
    $key_variant = substr(md5($key . mt_rand(1000, 9999)), 0, 16);
    
    // Basic XOR encryption with rotating key
    $result = '';
    $key_len = strlen($key_variant);
    
    for ($i = 0; $i < strlen($input); $i++) {
        $result .= chr(ord($input[$i]) ^ ord($key_variant[$i % $key_len]));
    }
    
    // Apply a secondary encoding to avoid pattern detection
    $result = strrev(base64_encode($result)); // Reverse the base64 result
    
    // Add confusion by splitting the result into multiple segments
    $segments = str_split($result, rand(5, 8));
    $segments_count = count($segments);
    
    // Insert random separators between segments
    $separators = ['~', '#', '@', '!', '%', '^'];
    $separator = $separators[array_rand($separators)];
    
    // Join with the selected separator and add signature
    $output = implode($separator, $segments);
    
    // Prepend a marker with the separator and segment count
    return $separator . dechex($segments_count) . $output;
}

/**
 * Generates code to decrypt a string that was encrypted with anti_pattern_encrypt
 * 
 * @param string $var_name Variable name to assign decrypted result
 * @param string $encrypted_data The encrypted data
 * @param string $key Decryption key
 * @return string PHP code for decryption
 */
function generate_anti_pattern_decrypt_code($var_name, $encrypted_data, $key) {
    $code = "// Deobfuscation function\n";
    $code .= "\$" . $var_name . " = (function(\$enc, \$key) {\n";
    $code .= "    \$sep = \$enc[0];\n";
    $code .= "    \$segments_count = hexdec(\$enc[1]);\n";
    $code .= "    \$enc_data = substr(\$enc, 2);\n";
    $code .= "    \$segments = explode(\$sep, \$enc_data);\n";
    $code .= "    \$enc_result = implode('', \$segments);\n";
    $code .= "    \$enc_result = base64_decode(strrev(\$enc_result));\n\n";
    $code .= "    \$key_variant = substr(md5(\$key . '3857'), 0, 16);\n";
    $code .= "    \$result = '';\n";
    $code .= "    \$key_len = strlen(\$key_variant);\n\n";
    $code .= "    for (\$i = 0; \$i < strlen(\$enc_result); \$i++) {\n";
    $code .= "        \$result .= chr(ord(\$enc_result[\$i]) ^ ord(\$key_variant[\$i % \$key_len]));\n";
    $code .= "    }\n\n";
    $code .= "    return \$result;\n";
    $code .= "})('$encrypted_data', '$key');\n";
    
    return $code;
}

/**
 * Obfuscates a loader with multiple anti-reverse engineering techniques
 * 
 * @param string $loader_content Original loader content
 * @param bool $add_junk Whether to add junk code
 * @param bool $rename_vars Whether to rename variables
 * @param bool $add_timing_checks Whether to add timing checks
 * @return string Obfuscated loader
 */
function obfuscate_loader($loader_content, $add_junk = true, $rename_vars = true, $add_timing_checks = true) {
    // Add junk code
    if ($add_junk) {
        // Helper function to generate junk code if not defined elsewhere
        if (!function_exists('generate_junk_code')) {
            function generate_junk_code($level = 1) {
                $junk = "";
                
                // Variables with random names
                for ($i = 0; $i < $level; $i++) {
                    $var = '_' . md5(rand() . microtime(true));
                    $value = rand(1, 10000);
                    $junk .= "\${$var} = {$value};\n";
                }
                
                // Comments
                if ($level > 1) {
                    $comments = [
                        "// System initialization check",
                        "// Runtime verification",
                        "// Security validation sequence",
                        "// Memory allocation verification",
                        "// Performance monitoring"
                    ];
                    $junk .= $comments[array_rand($comments)] . "\n";
                }
                
                // Timestamp check
                if ($level > 2) {
                    $timeVarName = '_' . md5(rand());
                    $junk .= "\${$timeVarName} = time();\n";
                }
                
                return $junk;
            }
        }
        
        // Split the loader content at key points to inject junk
        $patterns = [
            '<?php' => "<?php\n\n" . generate_junk_code(3),
            'function ' => generate_junk_code(2) . "\nfunction ",
            'foreach' => generate_junk_code(1) . "\nforeach",
            'return' => generate_junk_code(1) . "\nreturn"
        ];
        
        foreach ($patterns as $pattern => $replacement) {
            $count = 0;
            $loader_content = str_replace($pattern, $replacement, $loader_content, $count);
            if ($count === 0 && strpos($pattern, ' ') === false) {
                // Try with leading space if no replacements were made and pattern has no spaces
                $loader_content = str_replace(' ' . $pattern, ' ' . $replacement, $loader_content);
            }
        }
        
        // Add dummy functions
        $loader_content = inject_dummy_functions($loader_content, rand(2, 4));
    }
    
    // Rename variables for further obfuscation
    if ($rename_vars) {
        $loader_content = generate_variable_transformations($loader_content);
    }
    
    // Add timing-based checks to detect debugging
    if ($add_timing_checks) {
        // Add timing check after <?php
        $parts = explode('<?php', $loader_content, 2);
        if (count($parts) > 1) {
            $loader_content = $parts[0] . '<?php' . "\n\n" . generate_anti_reverse_engineering() . $parts[1];
        }
    }
    
    return $loader_content;
}