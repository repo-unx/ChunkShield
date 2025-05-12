<?php
/**
 * ChunkShield Obfuscation Functions
 * 
 * This file contains functions for PHP code obfuscation, including:
 * - Comment removal
 * - Whitespace removal
 * - Variable renaming
 * - Junk code insertion
 */

/**
 * Removes comments from PHP code
 * 
 * @param string $code PHP code to process
 * @return string Code without comments
 */
function remove_comments($code) {
    // Remove single-line comments
    $code = preg_replace('!//.*?$!m', '', $code);
    
    // Remove single-line # comments
    $code = preg_replace('!#.*?$!m', '', $code);
    
    // Remove multi-line comments
    $code = preg_replace('!/\*.*?\*/!s', '', $code);
    
    // Remove doc comments
    $code = preg_replace('!/\*\*.*?\*/!s', '', $code);
    
    return $code;
}

/**
 * Removes extra whitespace from PHP code
 * 
 * @param string $code PHP code to process
 * @return string Code with minimal whitespace
 */
function remove_whitespace($code) {
    // Replace multiple spaces with a single space
    $code = preg_replace('/\s+/', ' ', $code);
    
    // Remove spaces after certain tokens
    $code = preg_replace('/\s*([\{\}\[\]\(\)\=\+\-\*\/\.,;:><&\|!?%^])\s*/', '$1', $code);
    
    // Fix spacing for tokens that need at least one space
    $code = preg_replace('/(as|if|for|while|foreach|return|new|use|function)\(/', '$1 (', $code);
    
    // Remove newlines
    $code = str_replace(["\r\n", "\r", "\n"], '', $code);
    
    return $code;
}

/**
 * Extracts all variable names from PHP code
 * 
 * @param string $code PHP code to analyze
 * @return array List of variable names
 */
function extract_variables($code) {
    $variables = [];
    preg_match_all('/\$([a-zA-Z_\x7f-\xff][a-zA-Z0-9_\x7f-\xff]*)/', $code, $matches);
    
    if (isset($matches[1]) && is_array($matches[1])) {
        $variables = array_unique($matches[1]);
    }
    
    // Filter out superglobals and other special variables
    $exclusions = [
        'GLOBALS', '_SERVER', '_GET', '_POST', '_FILES', '_COOKIE', '_SESSION', '_REQUEST', '_ENV',
        'this', 'self', 'static', 'parent'
    ];
    
    return array_diff($variables, $exclusions);
}

/**
 * Extracts all function names from PHP code
 * 
 * @param string $code PHP code to analyze
 * @return array List of function names
 */
function extract_functions($code) {
    $functions = [];
    preg_match_all('/function\s+([a-zA-Z_\x7f-\xff][a-zA-Z0-9_\x7f-\xff]*)\s*\(/', $code, $matches);
    
    if (isset($matches[1]) && is_array($matches[1])) {
        $functions = array_unique($matches[1]);
    }
    
    // Filter out magic methods and other special functions
    $exclusions = [
        '__construct', '__destruct', '__call', '__callStatic', '__get', '__set', '__isset', '__unset',
        '__sleep', '__wakeup', '__toString', '__invoke', '__set_state', '__clone', '__debugInfo'
    ];
    
    return array_diff($functions, $exclusions);
}

/**
 * Renames variables in PHP code
 * 
 * @param string $code PHP code to process
 * @return string Code with renamed variables
 */
function rename_variables($code) {
    $variables = extract_variables($code);
    $replacements = [];
    
    foreach ($variables as $variable) {
        // Skip variables that appear to be constants or system variables
        if (strtoupper($variable) === $variable || strpos($variable, '_') === 0) {
            continue;
        }
        
        $new_name = generate_random_var_name();
        $replacements['$' . $variable] = '$' . $new_name;
    }
    
    // Perform replacements
    return str_replace(array_keys($replacements), array_values($replacements), $code);
}

/**
 * Renames functions in PHP code
 * 
 * @param string $code PHP code to process
 * @return string Code with renamed functions
 */
function rename_functions($code) {
    $functions = extract_functions($code);
    $replacements = [];
    
    foreach ($functions as $function) {
        // Skip functions that appear to be system functions
        if (strpos($function, '_') === 0) {
            continue;
        }
        
        $new_name = generate_random_var_name();
        $replacements['function ' . $function] = 'function ' . $new_name;
        $replacements[$function . '('] = $new_name . '(';
    }
    
    // Perform replacements
    return str_replace(array_keys($replacements), array_values($replacements), $code);
}

/**
 * Generates junk code for obfuscation
 * 
 * @return string Junk PHP code that does nothing
 */
function generate_junk_code() {
    $junk_types = [
        // Type 1: Random variable assignment
        function() {
            $var_name = generate_random_var_name();
            $value = rand(1, 1000);
            return '$' . $var_name . '=' . $value . ';';
        },
        
        // Type 2: Random if statement
        function() {
            $var_name = generate_random_var_name();
            $value1 = rand(1, 100);
            $value2 = rand(1, 100);
            $operators = ['==', '!=', '<', '>', '<=', '>='];
            $operator = $operators[array_rand($operators)];
            
            return 'if(' . $value1 . $operator . $value2 . '){$' . $var_name . '=' . rand(1, 1000) . ';}';
        },
        
        // Type 3: Random array
        function() {
            $var_name = generate_random_var_name();
            $array_size = rand(1, 5);
            $array_elements = [];
            
            for ($i = 0; $i < $array_size; $i++) {
                $array_elements[] = rand(1, 1000);
            }
            
            return '$' . $var_name . '=[' . implode(',', $array_elements) . '];';
        },
        
        // Type 4: Random string operations
        function() {
            $var_name = generate_random_var_name();
            $str_value = "'" . generate_random_string(rand(5, 15)) . "'";
            $operations = [
                'strtolower(' . $str_value . ')',
                'strtoupper(' . $str_value . ')',
                'strlen(' . $str_value . ')',
                'substr(' . $str_value . ',0,' . rand(1, 3) . ')',
                'str_replace("a","b",' . $str_value . ')'
            ];
            
            return '$' . $var_name . '=' . $operations[array_rand($operations)] . ';';
        }
    ];
    
    // Select a random junk type and generate code
    $junk_generator = $junk_types[array_rand($junk_types)];
    return $junk_generator();
}

/**
 * Inserts junk code into PHP code
 * 
 * @param string $code PHP code to process
 * @param int $density Junk code density (1-10)
 * @return string Code with junk code inserted
 */
function insert_junk_code($code, $density = 3) {
    // Make sure density is within range
    $density = max(1, min(10, $density));
    
    // Split code into statements
    $statements = preg_split('/;/', $code, -1, PREG_SPLIT_NO_EMPTY);
    $result = [];
    
    foreach ($statements as $statement) {
        $result[] = $statement . ';';
        
        // Determine if we should add junk after this statement
        // Higher density means more junk code
        if (rand(1, 10) <= $density) {
            $junk_count = rand(1, $density);
            
            for ($i = 0; $i < $junk_count; $i++) {
                $result[] = generate_junk_code();
            }
        }
    }
    
    return implode('', $result);
}

/**
 * Main obfuscation function
 * 
 * @param string $input_file Input PHP file path
 * @param string $output_file Output file path (optional)
 * @return string|bool Obfuscated code or true if written to file
 */
function obfuscate_php($input_file, $output_file = null) {
    // Check if input file exists
    if (!file_exists($input_file)) {
        log_message("Input file not found: $input_file", 'error');
        return false;
    }
    
    // Read the input file
    $code = file_get_contents($input_file);
    
    // Extract PHP code if file contains mixed HTML/PHP
    if (strpos($code, '<?php') !== false) {
        preg_match_all('/<\?php(.*?)\?>/s', $code, $matches);
        $php_code = implode('', $matches[1]);
    } else {
        $php_code = $code;
    }
    
    // Apply obfuscation steps according to configuration
    if (OBFUSCATE_REMOVE_COMMENTS) {
        $php_code = remove_comments($php_code);
    }
    
    if (OBFUSCATE_RENAME_VARIABLES) {
        $php_code = rename_variables($php_code);
        $php_code = rename_functions($php_code);
    }
    
    if (OBFUSCATE_INSERT_JUNK) {
        $php_code = insert_junk_code($php_code, OBFUSCATE_JUNK_DENSITY);
    }
    
    if (OBFUSCATE_REMOVE_WHITESPACE) {
        $php_code = remove_whitespace($php_code);
    }
    
    // Wrap in PHP tags
    $obfuscated_code = "<?php\n/* Obfuscated with ChunkShield */\n" . $php_code . "\n?>";
    
    // Write to output file if specified
    if ($output_file !== null) {
        $result = file_put_contents($output_file, $obfuscated_code);
        if ($result === false) {
            log_message("Failed to write to output file: $output_file", 'error');
            return false;
        }
        return true;
    }
    
    // Return the obfuscated code
    return $obfuscated_code;
}
