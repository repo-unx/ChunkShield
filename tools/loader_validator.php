<?php
/**
 * ChunkShield Loader Validator
 * 
 * This tool validates generated loaders to ensure they are free from syntax errors
 * and will execute correctly.
 */

// Check if called directly
if (basename(__FILE__) === basename($_SERVER['SCRIPT_FILENAME'])) {
    // Standalone mode
    if ($argc < 2) {
        echo "Usage: php loader_validator.php /path/to/loader.php\n";
        exit(1);
    }
    
    $loader_path = $argv[1];
    $result = validate_loader($loader_path);
    
    if ($result['valid']) {
        echo "✅ VALID: The loader passed all validation checks.\n";
    } else {
        echo "❌ INVALID: The loader has errors:\n";
        foreach ($result['errors'] as $error) {
            echo "- " . $error . "\n";
        }
    }
    
    exit($result['valid'] ? 0 : 1);
}

/**
 * Validates a loader file
 * 
 * @param string $loader_path Path to the loader file
 * @return array Validation result with 'valid' boolean and 'errors' array
 */
function validate_loader($loader_path) {
    $result = [
        'valid' => true,
        'errors' => []
    ];
    
    // Check if the file exists
    if (!file_exists($loader_path)) {
        $result['valid'] = false;
        $result['errors'][] = "Loader file does not exist at path: $loader_path";
        return $result;
    }
    
    // Read the loader content
    $loader_content = file_get_contents($loader_path);
    if ($loader_content === false) {
        $result['valid'] = false;
        $result['errors'][] = "Could not read loader file at path: $loader_path";
        return $result;
    }
    
    // Check for PHP tags
    if (strpos($loader_content, '<?php') === false) {
        $result['valid'] = false;
        $result['errors'][] = "Loader doesn't contain opening PHP tag";
    }
    
    // Check for syntax errors
    $syntax_check = check_php_syntax($loader_path);
    if (!$syntax_check['valid']) {
        $result['valid'] = false;
        $result['errors'][] = "PHP syntax error: " . $syntax_check['error'];
    }
    
    // Check for common issues in loader code
    if (strpos($loader_content, 'function (') !== false) {
        $result['valid'] = false;
        $result['errors'][] = "Invalid anonymous function declaration (missing variable)";
    }
    
    // Check for unclosed brackets, quotes, etc.
    $structure_check = check_code_structure($loader_content);
    if (!$structure_check['valid']) {
        $result['valid'] = false;
        foreach ($structure_check['errors'] as $error) {
            $result['errors'][] = $error;
        }
    }
    
    // Check for valid variable references in string interpolation
    $var_check = check_variable_references($loader_content);
    if (!$var_check['valid']) {
        $result['valid'] = false;
        foreach ($var_check['errors'] as $error) {
            $result['errors'][] = $error;
        }
    }
    
    return $result;
}

/**
 * Check PHP syntax using linter
 * 
 * @param string $file_path Path to the PHP file
 * @return array Result with 'valid' boolean and 'error' string
 */
function check_php_syntax($file_path) {
    $result = [
        'valid' => true,
        'error' => ''
    ];
    
    $cmd = "php -l " . escapeshellarg($file_path) . " 2>&1";
    exec($cmd, $output, $return_var);
    
    if ($return_var !== 0) {
        $result['valid'] = false;
        $result['error'] = implode("\n", $output);
    }
    
    return $result;
}

/**
 * Check code structure (matching brackets, quotes, etc.)
 * 
 * @param string $code PHP code content
 * @return array Result with 'valid' boolean and 'errors' array
 */
function check_code_structure($code) {
    $result = [
        'valid' => true,
        'errors' => []
    ];
    
    // Check for matching brackets
    $brackets = [
        '(' => ')',
        '{' => '}',
        '[' => ']'
    ];
    
    $stack = [];
    $in_string = false;
    $string_delimiter = '';
    $escaped = false;
    $line = 1;
    $col = 0;
    
    for ($i = 0; $i < strlen($code); $i++) {
        $char = $code[$i];
        $col++;
        
        if ($char === "\n") {
            $line++;
            $col = 0;
            continue;
        }
        
        if ($in_string) {
            if ($escaped) {
                $escaped = false;
            } elseif ($char === '\\') {
                $escaped = true;
            } elseif ($char === $string_delimiter) {
                $in_string = false;
            }
            continue;
        }
        
        if ($char === '"' || $char === "'") {
            $in_string = true;
            $string_delimiter = $char;
            continue;
        }
        
        if (array_key_exists($char, $brackets)) {
            array_push($stack, [
                'char' => $char,
                'line' => $line,
                'col' => $col
            ]);
        } elseif (in_array($char, $brackets)) {
            if (empty($stack)) {
                $result['valid'] = false;
                $result['errors'][] = "Unexpected closing bracket '$char' at line $line, column $col";
                continue;
            }
            
            $last = array_pop($stack);
            $expected_char = $brackets[$last['char']];
            
            if ($char !== $expected_char) {
                $result['valid'] = false;
                $result['errors'][] = "Mismatched brackets: expected '$expected_char' but found '$char' at line $line, column $col";
            }
        }
    }
    
    if ($in_string) {
        $result['valid'] = false;
        $result['errors'][] = "Unclosed string starting at line $line, column $col";
    }
    
    foreach ($stack as $bracket) {
        $result['valid'] = false;
        $expected_char = $brackets[$bracket['char']];
        $result['errors'][] = "Unclosed bracket '{$bracket['char']}' at line {$bracket['line']}, column {$bracket['col']} (expecting $expected_char)";
    }
    
    return $result;
}

/**
 * Check for invalid variable references in string interpolation
 * 
 * @param string $code PHP code content
 * @return array Result with 'valid' boolean and 'errors' array
 */
function check_variable_references($code) {
    $result = [
        'valid' => true,
        'errors' => []
    ];
    
    // Find all double-quoted strings
    $pattern = '/"([^"\\\\]|\\\\.)*"/';
    preg_match_all($pattern, $code, $matches);
    
    foreach ($matches[0] as $match) {
        // Look for $var but not \$var (escaped)
        if (preg_match('/(?<!\\\\)\$[a-zA-Z_][a-zA-Z0-9_]*\s*(?!\[|\->|\'|")/', $match)) {
            $result['valid'] = false;
            $result['errors'][] = "Potential invalid variable reference in string: $match";
        }
    }
    
    return $result;
}

/**
 * Add this function to automatically validate a loader after generation
 * 
 * @param string $loader_path Path to the generated loader
 * @return bool True if loader is valid, false otherwise
 */
function auto_validate_loader($loader_path) {
    $validation = validate_loader($loader_path);
    
    if (!$validation['valid']) {
        error_log("Loader validation failed!");
        foreach ($validation['errors'] as $error) {
            error_log("- " . $error);
        }
    }
    
    return $validation['valid'];
}