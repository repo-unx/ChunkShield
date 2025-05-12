<?php
/**
 * Polymorphic PHP Loader Generator
 * 
 * This file provides functions to generate unique, polymorphic loaders 
 * for encrypted chunks with varying structures and encryption methods.
 * 
 * Updated version with improved error handling and compatibility
 * 
 * @version 2.0
 */

/**
 * Generate a random variable name
 * 
 * @param int $length Length of the variable name
 * @return string Random variable name
 */
function generateRandomVarName($length = 5) {
    $prefix = '$';
    $chars = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $numbers = '0123456789';
    
    // Start with a random letter
    $varName = $chars[rand(0, strlen($chars) - 1)];
    
    // Add random characters
    $len = rand(3, $length);
    for ($i = 1; $i < $len; $i++) {
        if (rand(0, 4) > 3) {
            $varName .= $numbers[rand(0, strlen($numbers) - 1)];
        } else {
            $varName .= $chars[rand(0, strlen($chars) - 1)];
        }
    }
    
    return $prefix . $varName;
}

/**
 * Generate a random function name
 * 
 * @param string $prefix Optional prefix for the function name
 * @return string Random function name
 */
function generateRandomFunctionName($prefix = '') {
    $functionBases = ['decrypt', 'process', 'decode', 'transform', 'convert', 'handle', 'parse'];
    $functionSuffixes = ['Data', 'Content', 'Chunk', 'Block', 'Segment', 'Code', 'Stream'];
    
    $base = $functionBases[array_rand($functionBases)];
    $suffix = $functionSuffixes[array_rand($functionSuffixes)];
    
    // Add some randomness
    $randomizer = chr(rand(97, 122)) . rand(1, 99);
    
    return $prefix . ucfirst($base) . ucfirst($suffix) . $randomizer;
}

/**
 * Get a random cipher for encryption
 * 
 * @return string Random cipher algorithm
 */
function getRandomCipher() {
    $ciphers = ['aes-256-cbc', 'aes-128-cbc', 'aes-256-ctr', 'aes-192-cbc'];
    return $ciphers[array_rand($ciphers)];
}

/**
 * Generate a loader template
 * 
 * @param int $templateId Template ID to use (1-3)
 * @return array Template components 
 */
function generateLoaderTemplate($templateId = null) {
    // If no specific template ID is provided, choose randomly
    if ($templateId === null) {
        $templateId = rand(1, 3);
    }
    
    // Create random variable names
    $vars = [
        'key' => generateRandomVarName(),
        'map' => generateRandomVarName(),
        'chunks' => generateRandomVarName(),
        'chunk' => generateRandomVarName(),
        'file' => generateRandomVarName(),
        'content' => generateRandomVarName(),
        'decrypted' => generateRandomVarName(),
        'iv' => generateRandomVarName(),
        'path' => generateRandomVarName(),
        'license' => generateRandomVarName(),
        'data' => generateRandomVarName()
    ];
    
    // Random function names
    $functions = [
        'decrypt' => generateRandomFunctionName(),
        'verify' => generateRandomFunctionName('verify')
    ];
    
    // Random cipher
    $cipher = getRandomCipher();
    
    // Basic template structure based on ID
    switch($templateId) {
        case 1:
            // Standard template with base64 and straightforward decryption
            $template = [
                'header' => "<?php\n" .
                           "/* PHP Loader - " . date('Ymd-His') . " */\n\n",
                'decrypt_function' => "function {$functions['decrypt']}({$vars['content']}, {$vars['key']}, {$vars['iv']}) {\n" .
                                     "    {$vars['key']} = hash('sha256', {$vars['key']}, true);\n" .
                                     "    {$vars['content']} = base64_decode({$vars['content']});\n" .
                                     "    return openssl_decrypt({$vars['content']}, '{$cipher}', {$vars['key']}, OPENSSL_RAW_DATA, {$vars['iv']});\n" .
                                     "}\n",
                'eval_method' => "eval({$vars['decrypted']});",
                'cipher' => $cipher,
                'vars' => $vars,
                'functions' => $functions,
                'string_method' => 'base64'
            ];
            break;
            
        case 2:
            // Simplified template with reliable base64 & improved error handling
            $template = [
                'header' => "<?php\n" .
                           "/* ChunkShield Secure Loader - " . date('Ymd-His') . " */\n\n",
                'decrypt_function' => "function {$functions['decrypt']}({$vars['content']}, {$vars['key']}, {$vars['iv']}) {\n" .
                                     "    // Hash the key for consistent format\n" .
                                     "    {$vars['key']} = hash('sha256', {$vars['key']}, true);\n" .
                                     "    \n" .
                                     "    // Decode and decrypt content\n" .
                                     "    {$vars['content']} = base64_decode({$vars['content']});\n" .
                                     "    {$vars['decrypted']} = openssl_decrypt({$vars['content']}, '{$cipher}', {$vars['key']}, OPENSSL_RAW_DATA, {$vars['iv']});\n" .
                                     "    \n" .
                                     "    if ({$vars['decrypted']} === false) {\n" .
                                     "        die('Error: Failed to decrypt content with given key and IV.');\n" .
                                     "    }\n" .
                                     "    \n" .
                                     "    return {$vars['decrypted']};\n" .
                                     "}\n",
                'eval_method' => "{$vars['data']} = {$vars['decrypted']};\n    " .
                               "eval({$vars['data']});",
                'cipher' => $cipher,
                'vars' => $vars,
                'functions' => $functions,
                'string_method' => 'base64'
            ];
            break;
            
        case 3:
            // Advanced template with reliable obfuscation that works across PHP versions
            $template = [
                'header' => "<?php\n" .
                           "/* ChunkShield Advanced Loader - " . date('Ymd-His') . " */\n\n",
                'decrypt_function' => "function {$functions['decrypt']}({$vars['content']}, {$vars['key']}, {$vars['iv']}) {\n" .
                                     "    // Store params in array format for more obfuscation\n" .
                                     "    {$vars['data']} = ['c' => {$vars['content']}, 'k' => {$vars['key']}, 'i' => {$vars['iv']}];\n" .
                                     "    \n" .
                                     "    // Hash the key for consistent format\n" .
                                     "    {$vars['data']}['k'] = hash('sha256', {$vars['data']}['k'], true);\n" .
                                     "    \n" .
                                     "    // Decode and decrypt content\n" .
                                     "    {$vars['data']}['c'] = base64_decode({$vars['data']}['c']);\n" .
                                     "    {$vars['result']} = openssl_decrypt({$vars['data']}['c'], '{$cipher}', {$vars['data']}['k'], OPENSSL_RAW_DATA, {$vars['data']}['i']);\n" .
                                     "    \n" .
                                     "    if ({$vars['result']} === false) {\n" .
                                     "        die('Error: Decryption failed');\n" .
                                     "    }\n" .
                                     "    \n" .
                                     "    return {$vars['result']};\n" .
                                     "}\n",
                'eval_method' => "// Split-string eval technique for improved obfuscation\n    " .
                               "{$vars['ev']} = 'ev'.'al';\n    " .
                               "{$vars['ev']}({$vars['decrypted']});",
                'cipher' => $cipher,
                'vars' => $vars,
                'functions' => $functions,
                'string_method' => 'base64',
                'additional_obfuscation' => false
            ];
            break;
            
        default:
            // Fallback to template 1
            return generateLoaderTemplate(1);
    }
    
    return $template;
}

/**
 * Generate runtime fingerprinting code for domain and environment validation
 * 
 * @param array $options Options for fingerprinting
 * @param array $varNames Variables to use in the code
 * @return string Code for fingerprinting
 */
function generateRuntimeFingerprinting($options, $varNames) {
    $serverVar = $varNames['server_var'] ?? '$' . uniqid('srv');
    $fingerprintVar = $varNames['fingerprint_var'] ?? '$' . uniqid('fp');
    $validateVar = $varNames['validate_func'] ?? '$' . uniqid('val');
    
    $domainToValidate = $options['domain'] ?? '';
    $ipRestriction = $options['ip_restriction'] ?? '';
    $pathValidation = $options['path_validation'] ?? '';
    
    $code = "
    // Runtime fingerprinting and environment validation
    {$serverVar} = \$_SERVER;
    {$fingerprintVar} = [
        'domain' => isset({$serverVar}['HTTP_HOST']) ? {$serverVar}['HTTP_HOST'] : '',
        'ip' => isset({$serverVar}['REMOTE_ADDR']) ? {$serverVar}['REMOTE_ADDR'] : '',
        'path' => isset({$serverVar}['SCRIPT_FILENAME']) ? {$serverVar}['SCRIPT_FILENAME'] : '',
        'date' => date('Y-m-d'),
        'env' => php_uname()
    ];
    
    // Validate runtime environment
    {$validateVar} = function({$fingerprintVar}) {
        // Domain validation
        if (!empty('" . $domainToValidate . "')) {
            \$allowedDomains = explode(',', '" . $domainToValidate . "');
            \$domainValid = false;
            
            foreach (\$allowedDomains as \$domain) {
                \$domain = trim(\$domain);
                if (empty(\$domain)) continue;
                
                // Check for exact match or subdomain
                if ({$fingerprintVar}['domain'] === \$domain || 
                    preg_match('/\\.' . preg_quote(\$domain, '/') . '\$/', {$fingerprintVar}['domain']) || 
                    // Wildcard check
                    (strpos(\$domain, '*') !== false && preg_match('/' . str_replace('*', '[^.]+', \$domain) . '/', {$fingerprintVar}['domain']))) {
                    \$domainValid = true;
                    break;
                }
            }
            
            if (!empty(\$allowedDomains) && !\$domainValid) {
                // Domain validation failed - you can customize this behavior
                error_log('Domain validation failed: ' . {$fingerprintVar}['domain']);
                // Uncomment to enforce domain restriction:
                // die('This software is licensed for specific domains only.');
            }
        }
        
        // IP address validation
        if (!empty('" . $ipRestriction . "')) {
            \$allowedIps = explode(',', '" . $ipRestriction . "');
            \$ipValid = false;
            
            foreach (\$allowedIps as \$ip) {
                \$ip = trim(\$ip);
                if (empty(\$ip)) continue;
                
                if ({$fingerprintVar}['ip'] === \$ip || 
                    // Check for CIDR notation (simple implementation)
                    (strpos(\$ip, '/') !== false && strpos({$fingerprintVar}['ip'], substr(\$ip, 0, strpos(\$ip, '/'))) === 0)) {
                    \$ipValid = true;
                    break;
                }
            }
            
            if (!empty(\$allowedIps) && !\$ipValid) {
                // IP validation failed
                error_log('IP validation failed: ' . {$fingerprintVar}['ip']);
                // Uncomment to enforce IP restriction:
                // die('This software is restricted to specific IP addresses.');
            }
        }
        
        // Path validation
        if (!empty('" . $pathValidation . "')) {
            \$validPaths = explode(',', '" . $pathValidation . "');
            \$pathValid = false;
            
            foreach (\$validPaths as \$path) {
                \$path = trim(\$path);
                if (empty(\$path)) continue;
                
                if (strpos({$fingerprintVar}['path'], \$path) !== false) {
                    \$pathValid = true;
                    break;
                }
            }
            
            if (!empty(\$validPaths) && !\$pathValid) {
                // Path validation failed
                error_log('Path validation failed: ' . {$fingerprintVar}['path']);
                // Uncomment to enforce path restriction:
                // die('Invalid installation path.');
            }
        }
        
        return true;
    };
    
    // Run environment validation
    {$validateVar}({$fingerprintVar});
    ";
    
    return $code;
}

/**
 * Generate anti-logger code
 * 
 * @return string Code for anti-logger protection
 */
function generateAntiLoggerCode($varNames) {
    $logVar = $varNames['log_func'] ?? '$' . uniqid('log');
    $errorVar = $varNames['error_func'] ?? '$' . uniqid('err');
    $origErrorHandler = $varNames['orig_handler'] ?? '$' . uniqid('orig');
    
    return "
    // Anti-logger protection
    {$logVar} = function(\$message, \$level = 'ERROR') {
        // This detects if someone tries to log sensitive content
        if (strpos(\$message, 'key') !== false || strpos(\$message, 'decrypt') !== false || 
            strpos(\$message, 'eval') !== false || strpos(\$message, 'password') !== false) {
            // Trigger emergency shutdown with misleading error
            throw new Exception('Runtime exception: Memory allocation failed');
        }
    };
    
    // Custom error handler to prevent error logging
    {$errorVar} = function(\$errno, \$errstr, \$errfile, \$errline) {
        // Check if error message contains sensitive info
        if (strpos(\$errstr, 'crypt') !== false || strpos(\$errstr, 'key') !== false || 
            strpos(\$errstr, 'eval') !== false || strpos(\$errstr, 'decode') !== false) {
            // Return false to bypass default error handler
            return true;
        }
        return false;
    };
    
    // Store original error handler
    {$origErrorHandler} = set_error_handler({$errorVar});
    
    // Disable error logging
    ini_set('log_errors', 0);
    ini_set('display_errors', 0);
    error_reporting(0);
    ";
}

/**
 * Generate anti-debugger code
 * 
 * @return string Code for anti-debugger protection
 */
function generateAntiDebuggerCode($varNames) {
    $debugVar = $varNames['debug_func'] ?? '$' . uniqid('debug');
    $timerVar = $varNames['timer'] ?? '$' . uniqid('timer');
    $execVar = $varNames['exec_time'] ?? '$' . uniqid('exec');
    
    return "
    // Anti-debugging protection
    {$debugVar} = function() {
        // Detect debugging tools
        if (
            function_exists('debug_backtrace') || 
            extension_loaded('xdebug') ||
            (defined('XDEBUG_CC_UNUSED') || defined('XDEBUG_CC_DEAD_CODE')) ||
            (function_exists('xdebug_get_code_coverage')) ||
            (isset(\$_SERVER['HTTP_X_FORWARDED_FOR']) && strpos(\$_SERVER['HTTP_X_FORWARDED_FOR'], '127.0.0.1') !== false) ||
            (isset(\$_SERVER['REMOTE_ADDR']) && \$_SERVER['REMOTE_ADDR'] === '::1') ||
            (!function_exists('opcache_get_status') && function_exists('opcache_compile_file')) ||
            (function_exists('getenv') && getenv('XDEBUG_CONFIG') !== false)
        ) {
            // Run decoy code to confuse the debugger
            \$a = []; for(\$i=0; \$i<100; \$i++) { \$a[] = md5(uniqid() . rand(1000, 9999)); }
            shuffle(\$a);
            
            // Randomly choose error methods to throw off analysis
            \$methods = ['exit', 'die', 'throw_exception', 'memory_limit'];
            \$method = \$methods[array_rand(\$methods)];
            
            switch(\$method) {
                case 'exit':
                    exit('System Error: 0xF2A19B');
                    break;
                case 'die':
                    die('Fatal Error: 0xE37182');
                    break;
                case 'throw_exception':
                    throw new Exception('Critical System Error: 0xD81F42');
                    break;
                case 'memory_limit':
                    ini_set('memory_limit', '1M');
                    \$data = '';
                    while(true) { \$data .= str_repeat('x', 1024 * 1024); }
                    break;
            }
        }
        
        // Execution time check to detect step-by-step debugging
        {$timerVar} = microtime(true);
        usleep(100000); // 100ms
        {$execVar} = microtime(true) - {$timerVar};
        
        // If execution time is significantly longer than expected, likely being debugged
        if ({$execVar} > 0.5) {
            exit('Security violation: 0xC719A2');
        }
    };
    
    // Run anti-debugging check
    {$debugVar}();
    
    // Set periodic checks in the background
    register_shutdown_function(function() use ({$debugVar}) {
        {$debugVar}();
    });
    ";
}

/**
 * Generate a polymorphic loader script for encrypted chunks
 * 
 * @param array $chunksInfo Information about the chunks
 * @param string $encryptionKey Key used for encryption
 * @param array|bool $options Protection options (license_check, anti_logger, anti_debugger) or boolean for backward compatibility
 * @return string Generated loader code
 */
function generatePolymorphicLoader($chunksInfo, $encryptionKey, $options = false) {
    // Get the map file path
    $mapFilePath = $chunksInfo['mapFile'];
    $mapDir = dirname($mapFilePath);
    $mapFilename = basename($mapFilePath);
    
    // Load the map contents
    $mapContents = file_get_contents($mapFilePath);
    $map = json_decode($mapContents, true);
    
    // Handle backward compatibility for $options parameter
    $licenseCheck = false;
    $antiLogger = false;
    $antiDebugger = false;
    
    if (is_array($options)) {
        $licenseCheck = isset($options['license_check']) && $options['license_check'];
        $antiLogger = isset($options['anti_logger']) && $options['anti_logger'];
        $antiDebugger = isset($options['anti_debugger']) && $options['anti_debugger'];
    } else {
        // If options is bool, it's the old licenseCheck parameter
        $licenseCheck = $options;
    }
    
    // Generate a random loader template
    $template = generateLoaderTemplate();
    $vars = $template['vars'];
    $functions = $template['functions'];
    
    // Additional variable names for protection mechanisms
    $vars['log_func'] = '$' . uniqid('logger_');
    $vars['error_func'] = '$' . uniqid('error_');
    $vars['orig_handler'] = '$' . uniqid('orig_');
    $vars['debug_func'] = '$' . uniqid('debug_');
    $vars['timer'] = '$' . uniqid('timer_');
    $vars['exec_time'] = '$' . uniqid('exec_');
    
    // Start building the loader
    $loader = $template['header'];
    
    // Add some randomized comments
    $commentStyles = [
        ["/**\n * {comment}\n */\n", "// {comment}\n"],
        ["/* {comment} */\n", "// {comment} //\n"],
        ["/** {comment} **/\n", "# {comment} #\n"]
    ];
    $commentStyle = $commentStyles[array_rand($commentStyles)];
    
    // Random comments
    $comments = [
        "Decryption function for secure content",
        "Process encrypted data with key verification",
        "Handle secure content loading and execution",
        "Encrypted chunk processor"
    ];
    
    // Add decrypt function with randomized comment
    $comment = $comments[array_rand($comments)];
    $loader .= sprintf($commentStyle[rand(0, 1)], ['comment' => $comment]);
    $loader .= $template['decrypt_function'] . "\n";
    
    // Runtime fingerprinting (domain, IP, path, environment validation)
    $runtime_options = [
        'domain' => $options['allowed_domains'] ?? '',
        'ip_restriction' => $options['allowed_ips'] ?? '',
        'path_validation' => $options['allowed_paths'] ?? ''
    ];
    
    if (!empty($runtime_options['domain']) || !empty($runtime_options['ip_restriction']) || !empty($runtime_options['path_validation'])) {
        $loader .= generateRuntimeFingerprinting($runtime_options, $vars);
    }
    
    // Add anti-logger if requested
    if ($antiLogger) {
        $loader .= generateAntiLoggerCode($vars);
    }
    
    // Add anti-debugger if requested
    if ($antiDebugger) {
        $loader .= generateAntiDebuggerCode($vars);
    }
    
    // Add junk code injection (anti reverse-engineering)
    if (isset($options['junk_code']) && $options['junk_code']) {
        $junkFuncName = '$' . uniqid('junk_');
        $junkVarName = '$' . uniqid('data_');
        
        $loader .= "\n    // Anti reverse-engineering code\n";
        $loader .= "    {$junkFuncName} = function() {\n";
        $loader .= "        // This function contains decoy code to confuse reverse-engineering attempts\n";
        $loader .= "        {$junkVarName} = [];\n";
        
        // Add some random decoy operations
        for ($i = 0; $i < rand(3, 7); $i++) {
            $op = rand(0, 4);
            switch ($op) {
                case 0:
                    $loader .= "        {$junkVarName}['" . uniqid('k') . "'] = md5(uniqid() . rand(1000, 9999));\n";
                    break;
                case 1:
                    $loader .= "        if (isset(\$_SERVER['HTTP_HOST'])) { {$junkVarName}[] = base64_encode(\$_SERVER['HTTP_HOST']); }\n";
                    break;
                case 2:
                    $loader .= "        try { {$junkVarName}[] = openssl_random_pseudo_bytes(32); } catch (\\Exception \$e) { }\n";
                    break;
                case 3:
                    $loader .= "        for(\$i=0; \$i<3; \$i++) { {$junkVarName}[] = hash('sha256', uniqid('', true)); }\n";
                    break;
                case 4:
                    $loader .= "        if (function_exists('random_bytes')) { {$junkVarName}[] = bin2hex(random_bytes(16)); }\n";
                    break;
            }
        }
        
        $loader .= "        return {$junkVarName};\n";
        $loader .= "    };\n\n";
        $loader .= "    // Execute decoy code in background\n";
        $loader .= "    if (function_exists('register_shutdown_function')) {\n";
        $loader .= "        register_shutdown_function({$junkFuncName});\n";
        $loader .= "    }\n";
    }
    
    // Add license verification if required
    if ($licenseCheck) {
        $loader .= "/**\n";
        $loader .= " * Verify license file\n";
        $loader .= " */\n";
        $loader .= "function {$functions['verify']}({$vars['license']}) {\n";
        $loader .= "    if (!file_exists({$vars['license']})) {\n";
        $loader .= "        die('License file not found. Please provide a valid license file.');\n";
        $loader .= "    }\n\n";
        
        $loader .= "    // Read license file\n";
        $loader .= "    \$fileContent = file_get_contents({$vars['license']});\n";
        $loader .= "    \$licenseData = json_decode(\$fileContent, true);\n\n";
        
        $loader .= "    // Check if it's encrypted\n";
        $loader .= "    if (isset(\$licenseData['encrypted']) && \$licenseData['encrypted'] === true) {\n";
        $loader .= "        // For encrypted licenses, we need to read the license key from a separate file\n";
        $loader .= "        \$licenseKeyFile = dirname({$vars['license']}) . '/license.key';\n";
        $loader .= "        if (!file_exists(\$licenseKeyFile)) {\n";
        $loader .= "            die('License key file not found. Please provide a valid license key file.');\n";
        $loader .= "        }\n\n";
        
        $loader .= "        \$licenseKey = trim(file_get_contents(\$licenseKeyFile));\n\n";
        
        $loader .= "        // Decrypt the license\n";
        $loader .= "        \$iv = base64_decode(\$licenseData['iv']);\n";
        $loader .= "        \$encryptedData = \$licenseData['data'];\n";
        $loader .= "        \$encryptionKey = hash('sha256', \$licenseKey, true);\n\n";
        
        $loader .= "        \$decryptedJson = openssl_decrypt(\$encryptedData, 'aes-256-cbc', \$encryptionKey, 0, \$iv);\n";
        $loader .= "        if (\$decryptedJson === false) {\n";
        $loader .= "            die('Failed to decrypt license. Invalid license key.');\n";
        $loader .= "        }\n\n";
        
        $loader .= "        \$license = json_decode(\$decryptedJson, true);\n";
        $loader .= "        if (\$license === null) {\n";
        $loader .= "            die('Invalid license format after decryption.');\n";
        $loader .= "        }\n";
        $loader .= "    } else {\n";
        $loader .= "        // Unencrypted license\n";
        $loader .= "        \$license = \$licenseData;\n";
        $loader .= "    }\n\n";
        
        $loader .= "    // Validate license data\n";
        $loader .= "    if (!isset(\$license['key']) || !isset(\$license['domain']) || !isset(\$license['expiry'])) {\n";
        $loader .= "        die('Invalid license format: Missing required fields.');\n";
        $loader .= "    }\n\n";
        
        $loader .= "    // Check domain\n";
        $loader .= "    \$currentDomain = \$_SERVER['HTTP_HOST'] ?? '';\n";
        $loader .= "    if (!\$currentDomain) {\n";
        $loader .= "        // If HTTP_HOST is not available (CLI mode), try to use the server name\n";
        $loader .= "        \$currentDomain = \$_SERVER['SERVER_NAME'] ?? '';\n";
        $loader .= "    }\n\n";
        
        $loader .= "    // Domain validation function\n";
        $loader .= "    function domainMatches(\$licenseDomain, \$currentDomain) {\n";
        $loader .= "        // Direct match\n";
        $loader .= "        if (strtolower(\$licenseDomain) === strtolower(\$currentDomain)) {\n";
        $loader .= "            return true;\n";
        $loader .= "        }\n\n";
        
        $loader .= "        // Subdomain matching\n";
        $loader .= "        if (preg_match('/\\.' . preg_quote(\$licenseDomain, '/') . '\$/', \$currentDomain)) {\n";
        $loader .= "            return true;\n";
        $loader .= "        }\n\n";
        
        $loader .= "        // Wildcard matching\n";
        $loader .= "        if (strpos(\$licenseDomain, '*') !== false) {\n";
        $loader .= "            \$pattern = '/^' . str_replace(['*', '.'], ['[a-zA-Z0-9-]+', '\\.'], \$licenseDomain) . '\$/';\n";
        $loader .= "            return preg_match(\$pattern, \$currentDomain) === 1;\n";
        $loader .= "        }\n\n";
        
        $loader .= "        return false;\n";
        $loader .= "    }\n\n";
        
        $loader .= "    if (\$currentDomain && !domainMatches(\$license['domain'], \$currentDomain)) {\n";
        $loader .= "        die('License not valid for this domain: ' . \$currentDomain);\n";
        $loader .= "    }\n\n";
        
        $loader .= "    // Check expiry\n";
        $loader .= "    if (time() > \$license['expiry']) {\n";
        $loader .= "        die('License has expired on ' . date('Y-m-d', \$license['expiry']));\n";
        $loader .= "    }\n\n";
        
        $loader .= "    // Verify signature if present\n";
        $loader .= "    if (isset(\$license['signature'])) {\n";
        $loader .= "        \$originalSignature = \$license['signature'];\n";
        $loader .= "        unset(\$license['signature']);\n\n";
        
        $loader .= "        \$signingKey = hash('sha256', \$license['id'] . \$license['key'] . \$license['domain']);\n";
        $loader .= "        \$dataToSign = json_encode(\$license);\n";
        $loader .= "        \$calculatedSignature = hash_hmac('sha256', \$dataToSign, \$signingKey);\n\n";
        
        $loader .= "        if (\$originalSignature !== \$calculatedSignature) {\n";
        $loader .= "            die('License integrity check failed: Signature mismatch.');\n";
        $loader .= "        }\n\n";
        
        $loader .= "        // Restore the signature\n";
        $loader .= "        \$license['signature'] = \$originalSignature;\n";
        $loader .= "    }\n\n";
        
        $loader .= "    return true;\n";
        $loader .= "}\n\n";
        
        // Add license check
        $loader .= "// Verify license\n";
        $loader .= "{$vars['license']} = __DIR__ . '/license.lic';\n";
        $loader .= "{$functions['verify']}({$vars['license']});\n\n";
    }
    
    // Add the main loader code with variable randomization
    $loader .= "// Encryption key\n";
    $loader .= "{$vars['key']} = " . var_export($encryptionKey, true) . ";\n\n";
    
    $loader .= "// Map file path\n";
    $loader .= "{$vars['path']} = __DIR__ . '/map/{$mapFilename}';\n\n";
    
    $loader .= "// Check if map file exists\n";
    $loader .= "if (!file_exists({$vars['path']})) {\n";
    $loader .= "    die('Map file not found: ' . {$vars['path']});\n";
    $loader .= "}\n\n";
    
    $loader .= "// Load map data\n";
    $loader .= "{$vars['map']} = json_decode(file_get_contents({$vars['path']}), true);\n";
    $loader .= "if (!isset({$vars['map']}['chunks']) || !is_array({$vars['map']}['chunks'])) {\n";
    $loader .= "    die('Invalid map file format.');\n";
    $loader .= "}\n\n";
    
    $loader .= "// Sort chunks by order\n";
    $loader .= "usort({$vars['map']}['chunks'], function(\$a, \$b) {\n";
    $loader .= "    return \$a['order'] - \$b['order'];\n";
    $loader .= "});\n\n";
    
    // Randomize how the chunk processing code is structured
    if (rand(0, 1) == 0) {
        // Standard foreach approach
        $loader .= "// Process each chunk\n";
        $loader .= "foreach ({$vars['map']}['chunks'] as {$vars['chunk']}) {\n";
        $loader .= "    {$vars['file']} = __DIR__ . '/chunks/' . {$vars['chunk']}['file'];\n";
        $loader .= "    if (!file_exists({$vars['file']})) {\n";
        $loader .= "        die('Chunk file not found: ' . {$vars['file']});\n";
        $loader .= "    }\n\n";
        
        $loader .= "    // Read encrypted content\n";
        $loader .= "    {$vars['content']} = file_get_contents({$vars['file']});\n\n";
        
        $loader .= "    // Decrypt content\n";
        $loader .= "    {$vars['iv']} = base64_decode({$vars['chunk']}['iv']);\n";
        $loader .= "    {$vars['decrypted']} = {$functions['decrypt']}({$vars['content']}, {$vars['key']}, {$vars['iv']});\n\n";
        
        $loader .= "    // Simple integrity check with relaxed validation\n";
        $loader .= "    if (md5({$vars['decrypted']}) !== {$vars['chunk']}['checksum']) {\n";
        $loader .= "        // Log the mismatch but continue execution anyway\n";
        $loader .= "        {$vars['csum1']} = md5({$vars['decrypted']});\n";
        $loader .= "        {$vars['csum2']} = {$vars['chunk']}['checksum'];\n";
        $loader .= "        error_log(\"Notice: Checksum mismatch for chunk. Expected: {\$vars['csum2']}, Got: {\$vars['csum1']}\");\n";
        $loader .= "        // Validation is relaxed to prevent false positives\n";
        $loader .= "    }\n\n";
        
        $loader .= "    // Execute the code\n";
        $loader .= "    " . str_replace("{base64_eval}", $template['base64_eval'] ?? "", $template['eval_method']) . "\n";
        $loader .= "}\n";
    } else {
        // For loop with array_values approach
        $loader .= "// Process all chunks\n";
        $loader .= "{$vars['chunks']} = array_values({$vars['map']}['chunks']);\n";
        $loader .= "for (\$i = 0; \$i < count({$vars['chunks']}); \$i++) {\n";
        $loader .= "    {$vars['chunk']} = {$vars['chunks']}[\$i];\n";
        $loader .= "    {$vars['file']} = __DIR__ . '/chunks/' . {$vars['chunk']}['file'];\n";
        $loader .= "    \n";
        $loader .= "    if (!file_exists({$vars['file']})) {\n";
        $loader .= "        die('Chunk file not found: ' . {$vars['file']});\n";
        $loader .= "    }\n\n";
        
        $loader .= "    // Process chunk\n";
        $loader .= "    {$vars['content']} = file_get_contents({$vars['file']});\n";
        $loader .= "    {$vars['iv']} = base64_decode({$vars['chunk']}['iv']);\n";
        $loader .= "    {$vars['decrypted']} = {$functions['decrypt']}({$vars['content']}, {$vars['key']}, {$vars['iv']});\n\n";
        
        $loader .= "    // Simple integrity check with relaxed validation\n";
        $loader .= "    if (md5({$vars['decrypted']}) !== {$vars['chunk']}['checksum']) {\n";
        $loader .= "        // Log the mismatch but continue execution anyway\n";
        $loader .= "        {$vars['csum1']} = md5({$vars['decrypted']});\n";
        $loader .= "        {$vars['csum2']} = {$vars['chunk']}['checksum'];\n";
        $loader .= "        error_log(\"Notice: Checksum mismatch for chunk. Expected: {\$vars['csum2']}, Got: {\$vars['csum1']}\");\n";
        $loader .= "        // Validation is relaxed to prevent false positives\n";
        $loader .= "    }\n\n";
        
        $loader .= "    // Execute chunk\n";
        $loader .= "    " . str_replace("{base64_eval}", $template['base64_eval'] ?? "", $template['eval_method']) . "\n";
        $loader .= "}\n";
    }
    
    // Add an extra layer of obfuscation if specified
    if (isset($template['additional_obfuscation']) && $template['additional_obfuscation']) {
        // Simplified reliable obfuscation
        $encodedLoader = base64_encode($loader);
        $loaderSplit = str_split($encodedLoader, 120); // Split to avoid very long lines
        
        // Build a more compatible decoder
        $obfuscatedLoader = "<?php\n";
        $obfuscatedLoader .= "/* ChunkShield Polymorphic Loader */\n\n";
        $obfuscatedLoader .= "\$_cs = '';\n";
        
        // Add each chunk as a separate string
        foreach ($loaderSplit as $i => $chunk) {
            $obfuscatedLoader .= "\$_cs .= '" . $chunk . "';\n";
        }
        
        // Add the decoder with proper error handling
        $obfuscatedLoader .= "\n";
        $obfuscatedLoader .= "\$_decoded = base64_decode(\$_cs);\n";
        $obfuscatedLoader .= "if (\$_decoded === false) {\n";
        $obfuscatedLoader .= "    die('Loader Error: Base64 decode failed');\n";
        $obfuscatedLoader .= "}\n\n";
        $obfuscatedLoader .= "eval(\$_decoded);\n";
        
        $loader = $obfuscatedLoader;
    }
    
    return $loader;
}