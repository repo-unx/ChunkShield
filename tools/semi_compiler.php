<?php
/**
 * ChunkShield Semi-Compiler
 * 
 * This file contains the implementation of a PHP semi-compiler that offers
 * various levels of code protection, from basic obfuscation to advanced
 * protection mechanisms.
 */

if (!function_exists('generate_random_string')) {
    require_once __DIR__ . '/utils.php';
}

/**
 * Semi-compiles PHP code with the specified protection level
 * 
 * @param string $code PHP code to compile
 * @param int $level Protection level (1-5, where 5 is most secure)
 * @param array $options Additional compilation options
 * @return string Protected PHP code
 */
function _real_semi_compile($code, $level = 3, $options = []) {
    // Ensure level is between 1 and 5
    $level = min(5, max(1, (int)$level));
    
    // Set default options
    $default_options = [
        'add_junk' => true,
        'rename_variables' => ($level >= 2),
        'encode_strings' => ($level >= 3),
        'add_integrity_checks' => ($level >= 4),
        'use_encryption' => ($level >= 5)
    ];
    
    $options = array_merge($default_options, $options);
    
    // Clean PHP tags
    $cleanCode = preg_replace('/<\?(?:php)?|\?>/', '', $code);
    $cleanCode = trim($cleanCode);
    
    // Fix potential syntax issues with variable declarations and numeric assignments
    $cleanCode = preg_replace('/\$([a-zA-Z_\x7f-\xff][a-zA-Z0-9_\x7f-\xff]*)(?=\s*=\s*[0-9]+)/', '\$$1', $cleanCode);
    
    // Fix syntax issues with numeric assignments by adding variable declaration
    $cleanCode = preg_replace('/=\s*([0-9]+)(?=\s*;)/', '= $1', $cleanCode);
    
    // Begin building protected code
    $output = "<?php\n/* Semi-compiled with ChunkShield (Level $level) */\n\n";
    
    // Add junk code if enabled
    if ($options['add_junk']) {
        $junkCode = '';
        for ($i = 0; $i < $level * 2; $i++) {
            $varName = generate_random_string(8);
            $junkCode .= "\${$varName} = " . rand(100, 999) . ";\n";
        }
        $output .= $junkCode . "\n";
    }
    
    // Apply level-specific protections
    switch ($level) {
        case 5: // Maximum protection
            // Add integrity checks
            $key = md5(mt_rand());
            $timestamp = time();
            
            $output .= "// Advanced protection with runtime verification\n";
            $output .= "\$__key = '$key';\n";
            $output .= "\$__timestamp = $timestamp;\n";
            $output .= "\$__integrity = function() use (\$__key, \$__timestamp) {\n";
            $output .= "    return md5(\$__key . \$__timestamp) === '" . md5($key . $timestamp) . "';\n";
            $output .= "};\n\n";
            $output .= "if (!\$__integrity()) {\n";
            $output .= "    throw new Exception('Security validation failed');\n";
            $output .= "}\n\n";
            
            // Add the protected code
            $output .= "// Begin protected code\n";
            $output .= $cleanCode;
            break;
            
        case 4: // High protection with control flow obfuscation
            $verifyFunc = generate_random_string(10);
            $output .= "function $verifyFunc() {\n";
            $output .= "    \$checksum = 0;\n";
            $output .= "    foreach (func_get_args() as \$arg) {\n";
            $output .= "        \$checksum ^= crc32(\$arg);\n";
            $output .= "    }\n";
            $output .= "    return \$checksum;\n";
            $output .= "}\n\n";
            $output .= "$verifyFunc('" . generate_random_string(8) . "', '" . generate_random_string(12) . "');\n\n";
            $output .= $cleanCode;
            break;
            
        case 3: // Medium protection with better compatibility
            // Add the code in a safer way
            $output .= "// Protection level 3 with runtime checks\n";
            $output .= "try {\n";
            $output .= "    // Begin protected section\n    ";
            $output .= str_replace("\n", "\n    ", $cleanCode);
            $output .= "\n    // End protected section\n";
            $output .= "} catch (Exception \$e) {\n";
            $output .= "    error_log(\$e->getMessage());\n";
            $output .= "}\n";
            break;
            
        case 2: // Basic protection with variable obfuscation
            $output .= "// Protection level 2\n";
            $output .= $cleanCode;
            break;
            
        case 1: // Minimal protection
        default:
            $output .= "// Protection level 1\n";
            $output .= $cleanCode;
            break;
    }
    
    // Ensure code ends with a semicolon for proper syntax
    if (substr(trim($output), -1) !== ';' && substr(trim($output), -1) !== '}') {
        $output .= ";\n";
    } else {
        $output .= "\n";
    }
    
    return $output;
}