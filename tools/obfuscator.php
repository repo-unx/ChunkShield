<?php
/**
 * ChunkShield Obfuscation Functions
 */

// Include utilities if not already included
if (!function_exists('generate_random_var_name')) {
    require_once __DIR__ . '/utils.php';
}

/**
 * Removes comments from PHP code
 */
function remove_comments($code) {
    $code = preg_replace('!//.*?$!m', '', $code);
    $code = preg_replace('!#.*?$!m', '', $code);
    $code = preg_replace('!/\*.*?\*/!s', '', $code);
    $code = preg_replace('!/\*\*.*?\*/!s', '', $code);
    return $code;
}

/**
 * Removes extra whitespace from PHP code
 */
function remove_whitespace($code) {
    $code = preg_replace('/\s+/', ' ', $code);
    $code = preg_replace('/\s*([\{\}\[\]\(\)\=\+\-\*\/\.,;:><&\|!?%^])\s*/', '$1', $code);
    $code = preg_replace('/(as|if|for|while|foreach|return|new|use|function)\(/', '$1 (', $code);
    $code = str_replace(["\r\n", "\r", "\n"], '', $code);
    return $code;
}

/**
 * Extracts all variable names from PHP code
 */
function extract_variables($code) {
    $variables = [];
    preg_match_all('/\$([a-zA-Z_\x7f-\xff][a-zA-Z0-9_\x7f-\xff]*)/', $code, $matches);

    if (isset($matches[1]) && is_array($matches[1])) {
        $variables = array_unique($matches[1]);
    }

    $exclusions = [
        'GLOBALS', '_SERVER', '_GET', '_POST', '_FILES', '_COOKIE', '_SESSION', '_REQUEST', '_ENV',
        'this', 'self', 'static', 'parent'
    ];

    return array_diff($variables, $exclusions);
}

/**
 * Extracts all function names from PHP code
 */
function extract_functions($code) {
    $functions = [];
    preg_match_all('/function\s+([a-zA-Z_\x7f-\xff][a-zA-Z0-9_\x7f-\xff]*)\s*\(/', $code, $matches);

    if (isset($matches[1]) && is_array($matches[1])) {
        $functions = array_unique($matches[1]);
    }

    $exclusions = [
        '__construct', '__destruct', '__call', '__callStatic', '__get', '__set', '__isset', '__unset',
        '__sleep', '__wakeup', '__toString', '__invoke', '__set_state', '__clone', '__debugInfo'
    ];

    return array_diff($functions, $exclusions);
}

/**
 * Renames variables in PHP code
 */
function rename_variables($code) {
    $variables = extract_variables($code);
    $var_map = [];

    if (empty($variables)) {
        return [
            'code' => $code,
            'var_map' => $var_map
        ];
    }

    $pattern = '/\$(' . implode('|', array_map('preg_quote', $variables)) . ')([^a-zA-Z0-9_]|$)/';

    $renamed_code = preg_replace_callback($pattern, function($matches) use (&$var_map) {
        $var = $matches[1];
        $suffix = $matches[2];

        if (strtoupper($var) === $var || strpos($var, '_') === 0) {
            return '$' . $var . $suffix;
        }

        if (!isset($var_map[$var])) {
            $var_map[$var] = generate_random_var_name();
        }

        return '$' . $var_map[$var] . $suffix;
    }, $code);

    if ($renamed_code === null) {
        return [
            'code' => $code,
            'var_map' => []
        ];
    }

    return [
        'code' => $renamed_code,
        'var_map' => $var_map
    ];
}

/**
 * Renames functions in PHP code
 */
function rename_functions($code) {
    $functions = extract_functions($code);
    $func_map = [];

    if (empty($functions)) {
        return [
            'code' => $code,
            'func_map' => $func_map
        ];
    }

    $pattern_decl = '/function\s+(' . implode('|', array_map('preg_quote', $functions)) . ')\s*\(/';

    $code = preg_replace_callback($pattern_decl, function($matches) use (&$func_map) {
        $func = $matches[1];

        if (strpos($func, '_') === 0) {
            return 'function ' . $func . '(';
        }

        if (!isset($func_map[$func])) {
            $func_map[$func] = generate_random_var_name();
        }

        return 'function ' . $func_map[$func] . '(';
    }, $code);

    if ($code === null || empty($func_map)) {
        return [
            'code' => $code,
            'func_map' => []
        ];
    }

    $pattern_calls = '/\b(' . implode('|', array_map('preg_quote', array_keys($func_map))) . ')\s*\(/';

    $code = preg_replace_callback($pattern_calls, function($matches) use ($func_map) {
        $func = $matches[1];

        if (isset($func_map[$func])) {
            return $func_map[$func] . '(';
        }

        return $matches[0];
    }, $code);

    if ($code === null) {
        return [
            'code' => $code,
            'func_map' => $func_map
        ];
    }

    return [
        'code' => $code,
        'func_map' => $func_map
    ];
}

/**
 * Generates junk code for obfuscation
 */
function generate_junk_code() {
    $var_name = generate_random_var_name();
    $value = rand(1, 1000);
    return '$' . $var_name . ' = ' . $value . ';';
}

/**
 * Inserts junk code into PHP code
 */
function insert_junk_code($code, $density = 3) {
    $density = max(1, min(10, $density));

    if (strlen(trim($code)) < 5) {
        return $code;
    }

    $statements = explode(';', $code);
    $result = [];

    foreach ($statements as $statement) {
        $statement = trim($statement);
        if (empty($statement)) continue;

        $result[] = $statement . ';';

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
 */
function obfuscate_code($code, $options = []) {
    $default_options = [
        'remove_comments' => true,
        'remove_whitespace' => true,
        'rename_variables' => true,
        'rename_functions' => true,
        'insert_junk' => true,
        'junk_density' => 3
    ];

    $options = array_merge($default_options, $options);

    $metadata = [
        'original_size' => strlen($code),
        'maps' => [
            'variables' => [],
            'functions' => []
        ]
    ];

    $php_code = $code;

    if ($options['remove_comments']) {
        $php_code = remove_comments($php_code);
    }

    if ($options['rename_variables']) {
        $result = rename_variables($php_code);
        $php_code = $result['code'];
        $metadata['maps']['variables'] = $result['var_map'];
    }

    if ($options['rename_functions']) {
        $result = rename_functions($php_code);
        $php_code = $result['code'];
        $metadata['maps']['functions'] = $result['func_map'];
    }

    if ($options['insert_junk']) {
        $php_code = insert_junk_code($php_code, $options['junk_density']);
    }

    if ($options['remove_whitespace']) {
        $php_code = remove_whitespace($php_code);
    }

    // Clean any existing PHP tags
    $php_code = preg_replace('/<\?(?:php)?|\?>/', '', $php_code);
    $php_code = trim($php_code);
    $obfuscated_code = "<?php\n/* Obfuscated with ChunkShield */\n" . $php_code;

    $metadata['obfuscated_size'] = strlen($obfuscated_code);
    $metadata['size_reduction'] = $metadata['original_size'] > 0 ? 
        round((1 - ($metadata['obfuscated_size'] / $metadata['original_size'])) * 100, 2) : 0;

    return [
        'code' => $obfuscated_code,
        'metadata' => $metadata
    ];
}

/**
 * Main obfuscation function that works with files
 */
function obfuscate_php_file($input_file, $output_file = null, $options = []) {
    if (!file_exists($input_file)) {
        log_message("Input file not found: $input_file", 'error');
        return false;
    }

    $code = file_get_contents($input_file);

    if ($code === false) {
        log_message("Failed to read input file: $input_file", 'error');
        return false;
    }

    $result = obfuscate_code($code, $options);

    if ($output_file !== null) {
        $write_result = file_put_contents($output_file, $result['code']);
        if ($write_result === false) {
            log_message("Failed to write to output file: $output_file", 'error');
            return false;
        }
        $result['output_file'] = $output_file;
    }

    return $result;
}

/**
 * Prepares code for obfuscation by handling PHP tags
 * 
 * @param string $code PHP code with or without PHP tags
 * @param bool $preserve_tags Whether to preserve PHP tags in the result
 * @return array Prepared code and information about tags
 */
function prepare_code_for_obfuscation($code, $preserve_tags = false) {
    $has_php_tags = is_php_code($code);
    $result = [
        'original_code' => $code,
        'has_php_tags' => $has_php_tags,
        'code' => $code
    ];
    
    if ($has_php_tags && !$preserve_tags) {
        $result['code'] = strip_php_tags($code);
    }
    
    return $result;
}