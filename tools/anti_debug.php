<?php
/**
 * ChunkShield Anti-Debugging Functions
 * 
 * This file contains functions for detecting debuggers, preventing runtime analysis,
 * and implementing self-destruct mechanisms when tampered with.
 */

// Include utilities if not already included
if (!function_exists('log_message')) {
    require_once __DIR__ . '/utils.php';
}

/**
 * Detects if the code is being debugged
 * 
 * @return bool True if debugging is detected, false otherwise
 */
function detect_debugging() {
    // Check for xdebug
    if (function_exists('xdebug_get_code_coverage') || 
        function_exists('xdebug_start_trace') || 
        function_exists('xdebug_break') ||
        extension_loaded('xdebug')) {
        return true;
    }
    
    // Check debug backtrace depth
    $backtrace = debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS);
    if (count($backtrace) > 5) {
        // More than expected function calls in stack might indicate debugger
        return true;
    }
    
    // Check for known debug-related environment variables
    $debug_env = [
        'XDEBUG_CONFIG',
        'XDEBUG_SESSION',
        'XDEBUG_TRIGGER',
        'PHP_IDE_CONFIG'
    ];
    
    foreach ($debug_env as $env) {
        if (getenv($env) !== false) {
            return true;
        }
    }
    
    // Check for debug-related headers
    $debug_headers = [
        'HTTP_X_DEBUG',
        'HTTP_X_DEBUG_SESSION',
        'HTTP_X_XDEBUG_SESSION'
    ];
    
    foreach ($debug_headers as $header) {
        if (isset($_SERVER[$header])) {
            return true;
        }
    }
    
    // Override popular debugging functions
    $debug_functions = [
        'var_dump',
        'print_r',
        'debug_zval_dump',
        'debug_print_backtrace'
    ];
    
    foreach ($debug_functions as $func) {
        // If function is being called in this process but not defined by us
        if (function_exists($func) && strpos($func, 'ChunkShield') === false) {
            // Check if function was redefined to detect hook attempts
            $reflection = new ReflectionFunction($func);
            if ($reflection->isUserDefined()) {
                return true;
            }
        }
    }
    
    return false;
}

/**
 * Writes debug detection event to runtime log
 * 
 * @param string $reason Reason debugging was detected
 * @return bool True if log was written successfully
 */
function log_debug_detection($reason) {
    $log_file = dirname(__DIR__) . '/logs/runtime.log';
    $timestamp = date('Y-m-d H:i:s');
    $ip = isset($_SERVER['REMOTE_ADDR']) ? $_SERVER['REMOTE_ADDR'] : 'unknown';
    $user_agent = isset($_SERVER['HTTP_USER_AGENT']) ? $_SERVER['HTTP_USER_AGENT'] : 'unknown';
    
    $log_entry = "[$timestamp] [SECURITY_ALERT] Debug attempt detected from $ip" . PHP_EOL;
    $log_entry .= "User Agent: $user_agent" . PHP_EOL;
    $log_entry .= "Reason: $reason" . PHP_EOL;
    $log_entry .= "-------------------------" . PHP_EOL;
    
    return file_put_contents($log_file, $log_entry, FILE_APPEND);
}

/**
 * Self-destructs the protected code by removing chunks and encrypted loader
 * 
 * @param string $chunks_dir Directory containing chunks
 * @param string $loader_file Path to loader file
 * @param string $reason Reason for self-destruction
 * @return void
 */
function self_destruct($chunks_dir, $loader_file = null, $reason = 'Security violation detected') {
    // Log the self-destruct event
    $log_file = dirname(__DIR__) . '/logs/runtime.log';
    $timestamp = date('Y-m-d H:i:s');
    $ip = isset($_SERVER['REMOTE_ADDR']) ? $_SERVER['REMOTE_ADDR'] : 'unknown';
    
    $log_entry = "[$timestamp] [SELF_DESTRUCT] Protected code self-destructing" . PHP_EOL;
    $log_entry .= "IP: $ip" . PHP_EOL;
    $log_entry .= "Reason: $reason" . PHP_EOL;
    $log_entry .= "-------------------------" . PHP_EOL;
    
    file_put_contents($log_file, $log_entry, FILE_APPEND);
    
    // Delete all chunks
    if (is_dir($chunks_dir)) {
        $files = glob($chunks_dir . '/*');
        foreach ($files as $file) {
            if (is_file($file)) {
                unlink($file);
            }
        }
        
        // Try to remove the directory
        rmdir($chunks_dir);
    }
    
    // Delete encrypted loader if specified
    if ($loader_file !== null && file_exists($loader_file)) {
        unlink($loader_file);
    }
}

/**
 * Creates anti-debugging code for insertion into loader
 * 
 * @param bool $enable_self_destruct Whether to enable self-destruct mechanism
 * @return string PHP code for anti-debugging
 */
function generate_anti_debugging_code($enable_self_destruct = true) {
    // Create random variable names for obfuscation
    $var_debug = generate_random_var_name();
    $var_detect = generate_random_var_name();
    $var_log = generate_random_var_name();
    $var_self_destruct = generate_random_var_name();
    $var_chunks_dir = generate_random_var_name();
    $var_loader = generate_random_var_name();
    $var_reason = generate_random_var_name();
    
    // Generate code template
    $code = "// Anti-debugging protection\n";
    $code .= "\$ENABLE_SELF_DESTRUCT = " . ($enable_self_destruct ? 'true' : 'false') . ";\n\n";
    
    // Debug detection function
    $code .= "function {$var_detect}() {\n";
    $code .= "    // Check for xdebug\n";
    $code .= "    if (function_exists('xdebug_get_code_coverage') || \n";
    $code .= "        function_exists('xdebug_start_trace') || \n";
    $code .= "        function_exists('xdebug_break') ||\n";
    $code .= "        extension_loaded('xdebug')) {\n";
    $code .= "        return 'XDebug extension detected';\n";
    $code .= "    }\n";
    $code .= "    \n";
    $code .= "    // Check debug backtrace depth\n";
    $code .= "    \$backtrace = debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS);\n";
    $code .= "    if (count(\$backtrace) > 5) {\n";
    $code .= "        return 'Abnormal call stack depth (' . count(\$backtrace) . ')';\n";
    $code .= "    }\n";
    $code .= "    \n";
    $code .= "    // Check for debug headers\n";
    $code .= "    foreach (['HTTP_X_DEBUG', 'HTTP_X_DEBUG_SESSION', 'HTTP_X_XDEBUG_SESSION'] as \$h) {\n";
    $code .= "        if (isset(\$_SERVER[\$h])) return 'Debug header detected: ' . \$h;\n";
    $code .= "    }\n";
    $code .= "    \n";
    $code .= "    // All checks passed\n";
    $code .= "    return false;\n";
    $code .= "}\n\n";
    
    // Logging function
    $code .= "function {$var_log}(\$reason) {\n";
    $code .= "    \$log_file = __DIR__ . '/runtime.log';\n";
    $code .= "    \$timestamp = date('Y-m-d H:i:s');\n";
    $code .= "    \$ip = isset(\$_SERVER['REMOTE_ADDR']) ? \$_SERVER['REMOTE_ADDR'] : 'unknown';\n";
    $code .= "    \$ua = isset(\$_SERVER['HTTP_USER_AGENT']) ? \$_SERVER['HTTP_USER_AGENT'] : 'unknown';\n";
    $code .= "    \n";
    $code .= "    \$entry = \"[\$timestamp] [SECURITY_ALERT] Debug attempt detected from \$ip\\n\";\n";
    $code .= "    \$entry .= \"User Agent: \$ua\\n\";\n";
    $code .= "    \$entry .= \"Reason: \$reason\\n\";\n";
    $code .= "    \$entry .= \"-------------------------\\n\";\n";
    $code .= "    \n";
    $code .= "    @file_put_contents(\$log_file, \$entry, FILE_APPEND);\n";
    $code .= "}\n\n";
    
    // Self-destruct function
    $code .= "function {$var_self_destruct}(\$chunks_dir, \$loader = null, \$reason = 'Security violation') {\n";
    $code .= "    global \$ENABLE_SELF_DESTRUCT;\n";
    $code .= "    if (!\$ENABLE_SELF_DESTRUCT) return false;\n";
    $code .= "    \n";
    $code .= "    // Log the event\n";
    $code .= "    \$log_file = __DIR__ . '/runtime.log';\n";
    $code .= "    \$timestamp = date('Y-m-d H:i:s');\n";
    $code .= "    \$ip = isset(\$_SERVER['REMOTE_ADDR']) ? \$_SERVER['REMOTE_ADDR'] : 'unknown';\n";
    $code .= "    \n";
    $code .= "    \$entry = \"[\$timestamp] [SELF_DESTRUCT] Protected code self-destructing\\n\";\n";
    $code .= "    \$entry .= \"IP: \$ip\\n\";\n";
    $code .= "    \$entry .= \"Reason: \$reason\\n\";\n";
    $code .= "    \$entry .= \"-------------------------\\n\";\n";
    $code .= "    \n";
    $code .= "    @file_put_contents(\$log_file, \$entry, FILE_APPEND);\n";
    $code .= "    \n";
    $code .= "    // Delete all chunks\n";
    $code .= "    if (is_dir(\$chunks_dir)) {\n";
    $code .= "        \$files = glob(\$chunks_dir . '/*');\n";
    $code .= "        foreach (\$files as \$file) {\n";
    $code .= "            if (is_file(\$file)) @unlink(\$file);\n";
    $code .= "        }\n";
    $code .= "        @rmdir(\$chunks_dir);\n";
    $code .= "    }\n";
    $code .= "    \n";
    $code .= "    // Delete loader if specified\n";
    $code .= "    if (\$loader !== null && file_exists(\$loader)) {\n";
    $code .= "        @unlink(\$loader);\n";
    $code .= "    }\n";
    $code .= "    \n";
    $code .= "    return true;\n";
    $code .= "}\n\n";
    
    // Main anti-debug check
    $code .= "// Run security checks\n";
    $code .= "\${$var_debug} = {$var_detect}();\n";
    $code .= "if (\${$var_debug} !== false) {\n";
    $code .= "    // Debug attempt detected\n";
    $code .= "    {$var_log}(\${$var_debug});\n";
    $code .= "    \n";
    $code .= "    // Execute self-destruct if enabled\n";
    $code .= "    \${$var_chunks_dir} = __DIR__ . '/chunks';\n";
    $code .= "    \${$var_loader} = __FILE__;\n";
    $code .= "    \${$var_reason} = 'Debug attempt: ' . \${$var_debug};\n";
    $code .= "    {$var_self_destruct}(\${$var_chunks_dir}, \${$var_loader}, \${$var_reason});\n";
    $code .= "    \n";
    $code .= "    // Terminate with generic error\n";
    $code .= "    die('Security violation detected. This attempt has been logged.');\n";
    $code .= "}\n\n";
    
    return $code;
}

/**
 * Adds anti-debug protection to PHP code
 *
 * @param string $code PHP code to protect
 * @param array $options Protection options
 * @return string Protected PHP code
 */
function add_anti_debug_protection($code, $options = []) {
    $default_options = [
        'enable_self_destruct' => true,
        'add_debug_detection' => true,
        'add_time_check' => true,
        'add_honeypot' => true
    ];
    
    $options = array_merge($default_options, $options);
    
    // Generate anti-debugging code
    $anti_debug_code = generate_anti_debugging_code($options['enable_self_destruct']);
    
    // Clean any existing PHP tags
    $code = preg_replace('/<\?(?:php)?|\?>/', '', $code);
    $code = trim($code);
    
    // Combine code with anti-debugging
    $protected_code = "<?php\n" .
                     "/* Protected with ChunkShield Anti-Debug */\n" .
                     $anti_debug_code . "\n" .
                     $code;
    
    // Validate the protected code for syntax errors
    if (function_exists('validate_obfuscated_code')) {
        validate_obfuscated_code($protected_code);
    }
    
    return $protected_code;
}

/**
 * Creates a loader file with advanced encryption and anti-debugging protection
 * 
 * @param string $loader_content Original loader content
 * @param string $output_dir Output directory
 * @param string $encryption_key Key for encryption
 * @return array|bool Result with paths or false on failure
 */
function create_encrypted_loader($loader_content, $output_dir, $encryption_key) {
    // Ensure output directory exists
    if (!is_dir($output_dir) && !mkdir($output_dir, 0755, true)) {
        log_message("Failed to create output directory: $output_dir", 'error');
        return false;
    }
    
    // Save the original loader for backup
    $original_loader = $output_dir . '/loader.php';
    file_put_contents($original_loader, $loader_content);
    
    // Create the encrypted loader
    $encrypted_loader = $output_dir . '/loader_encrypted.php';
    
    // Generate variable names to make the code harder to analyze
    $var_decrypt = generate_random_var_name();
    $var_data = generate_random_var_name();
    $var_key = generate_random_var_name();
    $var_iv = generate_random_var_name();
    $var_salt = generate_random_var_name();
    $var_hmac = generate_random_var_name();
    $var_decrypted = generate_random_var_name();
    $var_decompressed = generate_random_var_name();
    $var_result = generate_random_var_name();
    $var_runtime_log = generate_random_var_name();
    $var_start_time = generate_random_var_name();
    $var_end_time = generate_random_var_name();
    $var_diff_time = generate_random_var_name();
    
    // Compress the loader content if possible (reduces size and improves encryption)
    if (function_exists('gzencode')) {
        $compressed = gzencode($loader_content, 9);
        if ($compressed !== false) {
            log_message("Compressed loader content from " . strlen($loader_content) . " bytes to " . strlen($compressed) . " bytes", 'info');
            $loader_content = $compressed;
        }
    }
    
    // Generate a random IV for encryption
    $iv = random_bytes(16);
    
    // Generate a salt for key derivation
    $salt = random_bytes(16);
    
    // Derive a more secure key using PBKDF2
    $derived_key = hash_pbkdf2('sha256', $encryption_key, $salt, 10000, 32, true);
    
    // Encrypt the loader content with AES-256-CBC
    $encrypted = openssl_encrypt(
        $loader_content,
        'AES-256-CBC',
        $derived_key,
        OPENSSL_RAW_DATA,
        $iv
    );
    
    if ($encrypted === false) {
        log_message("Failed to encrypt loader content: " . openssl_error_string(), 'error');
        return false;
    }
    
    // Add an HMAC for integrity verification
    $hmac = hash_hmac('sha256', $encrypted, $derived_key, true);
    
    // Base64 encode all binary data for safe storage
    $b64_encrypted = base64_encode($encrypted);
    $b64_iv = base64_encode($iv);
    $b64_salt = base64_encode($salt);
    $b64_hmac = base64_encode($hmac);
    
    // We don't store the actual key in the loader for security
    // Instead we store a hash of it which we can use for verification
    $key_hash = hash('sha256', $encryption_key);
    $b64_key_hash = substr($key_hash, 0, 16); // Only use a portion of the hash
    
    // Create the self-decrypting wrapper with anti-debugging features
    $wrapper = "<?php\n";
    $wrapper .= "/* ChunkShield Encrypted Loader - Advanced Protection */\n\n";
    
    // Add timing check to detect debuggers (more sophisticated version)
    $wrapper .= "// Anti-debugging: Timing checks and anti-cracking measures\n";
    $wrapper .= "function {$var_decrypt}_detect_tampering() {\n";
    $wrapper .= "    // Multiple timing checks to detect debuggers and code stepping\n";
    $wrapper .= "    \$start_time = microtime(true);\n";
    $wrapper .= "    usleep(100000); // 100ms delay\n";
    $wrapper .= "    \$check_1 = (microtime(true) - \$start_time > 0.5); // Should take ~100ms, if >500ms = suspicious\n";
    $wrapper .= "    \n";
    $wrapper .= "    // Additional timing check with different pattern\n";
    $wrapper .= "    \$start_time_2 = microtime(true);\n";
    $wrapper .= "    for (\$i = 0; \$i < 1000; \$i++) { \${'temp'.\$i} = md5(\$i); } // CPU intensive operation\n";
    $wrapper .= "    \$check_2 = (microtime(true) - \$start_time_2 > 1.0); // Should be quick, if >1s = suspicious\n";
    $wrapper .= "    \n";
    
    // Add code to detect cracking tools if our anti_crack file exists
    if (file_exists(__DIR__ . '/anti_crack.php')) {
        $wrapper .= "    // Check for known cracking and reverse engineering tools\n";
        $wrapper .= "    \$cracking_tools_detected = false;\n";
        $wrapper .= "    if (function_exists('shell_exec') && !ini_get('safe_mode')) {\n";
        $wrapper .= "        \$processes = strtolower(@shell_exec('ps aux'));\n";
        $wrapper .= "        if (\$processes) {\n";
        $wrapper .= "            \$dangerous = ['ida', 'ollydbg', 'x64dbg', 'gdb', 'frida', 'ghidra', 'radare2', 'r2', 'cheat engine'];\n";
        $wrapper .= "            foreach (\$dangerous as \$tool) {\n";
        $wrapper .= "                if (strpos(\$processes, \$tool) !== false) {\n";
        $wrapper .= "                    \$cracking_tools_detected = true;\n";
        $wrapper .= "                    break;\n";
        $wrapper .= "                }\n";
        $wrapper .= "            }\n";
        $wrapper .= "        }\n";
        $wrapper .= "    }\n";
        $wrapper .= "    \n";
    }
    
    // Additional checks for JIT debuggers and browser tools
    $wrapper .= "    // Check for debug headers and parameters\n";
    $wrapper .= "    \$debug_headers = false;\n";
    $wrapper .= "    if (function_exists('getallheaders')) {\n";
    $wrapper .= "        \$headers = getallheaders();\n";
    $wrapper .= "        foreach (\$headers as \$name => \$value) {\n";
    $wrapper .= "            \$name = strtolower(\$name);\n";
    $wrapper .= "            if (strpos(\$name, 'debug') !== false || strpos(\$name, 'trace') !== false) {\n";
    $wrapper .= "                \$debug_headers = true;\n";
    $wrapper .= "                break;\n";
    $wrapper .= "            }\n";
    $wrapper .= "        }\n";
    $wrapper .= "    }\n";
    $wrapper .= "    \n";
    $wrapper .= "    // Check for debug cookies and GET parameters\n";
    $wrapper .= "    \$debug_params = false;\n";
    $wrapper .= "    if ((isset(\$_COOKIE) && (isset(\$_COOKIE['XDEBUG_SESSION']) || isset(\$_COOKIE['XDEBUG_PROFILE']))) ||\n";
    $wrapper .= "        (isset(\$_GET) && (isset(\$_GET['XDEBUG_SESSION_START']) || isset(\$_GET['debug'])))) {\n";
    $wrapper .= "        \$debug_params = true;\n";
    $wrapper .= "    }\n";
    $wrapper .= "    \n";
    
    // Make the final determination with multiple criteria
    $wrapper .= "    // Compile all checks - multiple flags increase certainty\n";
    $wrapper .= "    \$tampering_score = 0;\n";
    $wrapper .= "    if (\$check_1) \$tampering_score += 2;\n";
    $wrapper .= "    if (\$check_2) \$tampering_score += 2;\n";
    $wrapper .= "    if (isset(\$cracking_tools_detected) && \$cracking_tools_detected) \$tampering_score += 3;\n";
    $wrapper .= "    if (\$debug_headers) \$tampering_score += 1;\n";
    $wrapper .= "    if (\$debug_params) \$tampering_score += 1;\n";
    $wrapper .= "    \n";
    $wrapper .= "    return \$tampering_score;\n";
    $wrapper .= "}\n\n";
    
    // Execute the tampering detection and take appropriate action
    $wrapper .= "// Execute tampering detection\n";
    $wrapper .= "\${$var_diff_time} = {$var_decrypt}_detect_tampering();\n";
    $wrapper .= "if (\${$var_diff_time} >= 3) { // Threshold for detection\n";
    $wrapper .= "    // Tampering detected\n";
    $wrapper .= "    \${$var_runtime_log} = __DIR__ . '/runtime.log';\n";
    $wrapper .= "    @file_put_contents(\${$var_runtime_log}, date('Y-m-d H:i:s') . ' - Tampering detected (score: ' . \${$var_diff_time} . ')\\n', FILE_APPEND);\n";
    
    // Include delayed/tricky self-defense mechanisms
    $wrapper .= "    // Execute self-defense with unpredictable behavior\n";
    $wrapper .= "    if (mt_rand(1, 10) <= 7) { // 70% chance of immediate response\n";
    $wrapper .= "        header('HTTP/1.0 404 Not Found');\n";
    $wrapper .= "        exit('<!-- Page not found -->');\n";
    $wrapper .= "    } else { // 30% chance of delayed or subtle response\n";
    $wrapper .= "        // Schedule self-destruct after execution\n";
    $wrapper .= "        register_shutdown_function(function() {\n";
    $wrapper .= "            // Corrupt chunk files and loaders\n";
    $wrapper .= "            \$chunks_dir = __DIR__ . '/chunks';\n";
    $wrapper .= "            if (is_dir(\$chunks_dir)) {\n";
    $wrapper .= "                \$files = glob(\$chunks_dir . '/*');\n";
    $wrapper .= "                foreach (\$files as \$file) { if (is_file(\$file)) { @unlink(\$file); } }\n";
    $wrapper .= "                @rmdir(\$chunks_dir);\n";
    $wrapper .= "            }\n";
    $wrapper .= "        });\n";
    $wrapper .= "        // Allow execution to continue, but sabotage results later\n";
    $wrapper .= "        \$GLOBALS['_cs_sabotage'] = true; // This flag will be used by chunks to inject errors\n";
    $wrapper .= "    }\n";
    $wrapper .= "}\n\n";
    
    // Advanced anti-debugging, anti-cracking, and anti-reverse engineering checks
    $wrapper .= "// Comprehensive security checks against debugging, tampering and reverse engineering\n";
    $wrapper .= "function {$var_decrypt}_security_check() {\n";
    $wrapper .= "    // Check for debuggers and debug extensions (comprehensive check)\n";
    $wrapper .= "    if (function_exists('xdebug_get_code_coverage') || \n";
    $wrapper .= "        function_exists('xdebug_start_trace') || \n";
    $wrapper .= "        function_exists('xdebug_break') || \n";
    $wrapper .= "        function_exists('xdebug_connect_to_client') ||\n";
    $wrapper .= "        extension_loaded('xdebug') ||\n";
    $wrapper .= "        extension_loaded('xhprof') ||\n";
    $wrapper .= "        in_array('xdebug', get_loaded_extensions()) ||\n";
    $wrapper .= "        getenv('XDEBUG_CONFIG') !== false ||\n";
    $wrapper .= "        getenv('XDEBUG_SESSION') !== false ||\n";
    $wrapper .= "        (isset(\$_COOKIE) && \n";
    $wrapper .= "         (isset(\$_COOKIE['XDEBUG_SESSION']) || \n";
    $wrapper .= "          isset(\$_COOKIE['XDEBUG_PROFILE']) || \n";
    $wrapper .= "          isset(\$_COOKIE['XDEBUG_TRACE'])))\n";
    $wrapper .= "       ) {\n";
    $wrapper .= "        return false;\n";
    $wrapper .= "    }\n";
    $wrapper .= "    \n";
    $wrapper .= "    // Check for debug URL parameters\n";
    $wrapper .= "    if (isset(\$_GET) && \n";
    $wrapper .= "        (isset(\$_GET['XDEBUG_SESSION_START']) || \n";
    $wrapper .= "         isset(\$_GET['XDEBUG_PROFILE']) || \n";
    $wrapper .= "         isset(\$_GET['XDEBUG_TRACE']))) {\n";
    $wrapper .= "        return false;\n";
    $wrapper .= "    }\n";
    $wrapper .= "    \n";
    $wrapper .= "    // Check for common debugging headers\n";
    $wrapper .= "    \$headers = function_exists('getallheaders') ? getallheaders() : [];\n";
    $wrapper .= "    foreach (\$headers as \$name => \$value) {\n";
    $wrapper .= "        \$name = strtolower(\$name);\n";
    $wrapper .= "        if (strpos(\$name, 'debug') !== false || strpos(\$name, 'developer') !== false) {\n";
    $wrapper .= "            return false;\n";
    $wrapper .= "        }\n";
    $wrapper .= "    }\n";
    $wrapper .= "    \n";
    $wrapper .= "    // Check if running in a VM or sandbox (check for common indicators)\n";
    $wrapper .= "    if (function_exists('sys_getloadavg') && count(sys_getloadavg()) === 0) {\n";
    $wrapper .= "        return false; // Unusual system load - possible sandbox\n";
    $wrapper .= "    }\n";
    $wrapper .= "    \n";
    $wrapper .= "    // Check for known cracking tools in process list\n";
    $wrapper .= "    if (function_exists('shell_exec') && !ini_get('safe_mode')) {\n";
    $wrapper .= "        \$processes = strtolower(@shell_exec('ps aux'));\n";
    $wrapper .= "        if (\$processes) {\n";
    $wrapper .= "            \$dangerous = ['IDA', 'OllyDbg', 'x64dbg', 'gdb', 'frida', 'brida', 'ghidra', 'cheat engine'];\n";
    $wrapper .= "            foreach (\$dangerous as \$tool) {\n";
    $wrapper .= "                if (strpos(\$processes, strtolower(\$tool)) !== false) {\n";
    $wrapper .= "                    return false;\n";
    $wrapper .= "                }\n";
    $wrapper .= "            }\n";
    $wrapper .= "        }\n";
    $wrapper .= "    }\n";
    $wrapper .= "    \n";
    $wrapper .= "    // Advanced file integrity verification\n";
    $wrapper .= "    // Check file size integrity\n";
    $wrapper .= "    \$original_size = " . filesize($encrypted_loader) . ";\n";
    $wrapper .= "    if (filesize(__FILE__) !== \$original_size) {\n";
    $wrapper .= "        return false; // File has been modified\n";
    $wrapper .= "    }\n";
    
    // Generate a critical lines array for integrity checks
    $wrapper .= "    // Advanced content integrity verification\n";
    $wrapper .= "    \$lines = @file(__FILE__, FILE_IGNORE_NEW_LINES);\n";
    $wrapper .= "    if (\$lines === false) {\n";
    $wrapper .= "        return false; // Can't read own file, possible tampering\n";
    $wrapper .= "    }\n";
    $wrapper .= "    \n";
    $wrapper .= "    // Critical line check\n";
    $wrapper .= "    \$important_content = '';\n";
    $wrapper .= "    \$critical_lines = [10, 20, 30, 50, 100, 150, 200];\n";
    $wrapper .= "    foreach (\$critical_lines as \$line_num) {\n";
    $wrapper .= "        if (isset(\$lines[\$line_num])) {\n";
    $wrapper .= "            \$important_content .= \$lines[\$line_num];\n";
    $wrapper .= "        }\n";
    $wrapper .= "    }\n";
    $wrapper .= "    \n";
    
    // Create signature with a value that will be calculated at runtime
    // We'll use file creation time + file size + a salt to make it unique but predictable
    $wrapper .= "    // Generate expected signature\n";
    $wrapper .= "    \$initial_signature = md5(__FILE__ . filemtime(__FILE__) . filesize(__FILE__));\n";
    $wrapper .= "    \$expected_signature = substr(\$initial_signature, 0, 8) . substr(\$initial_signature, 24, 8);\n";
    $wrapper .= "    \n";
    $wrapper .= "    // Compare with content signature\n";
    $wrapper .= "    \$content_signature = md5(\$important_content);\n";
    $wrapper .= "    if (\$content_signature !== md5(\$important_content)) { // This always passes, but confuses static analysis tools\n";
    $wrapper .= "        return false;\n";
    $wrapper .= "    }\n";
    
    // Last modification time check
    $wrapper .= "    // Check for unexpected modifications\n";
    $wrapper .= "    \$last_modified = @filemtime(__FILE__);\n";
    $wrapper .= "    \$current_time = time();\n";
    $wrapper .= "    \$creation_time = @filectime(__FILE__);\n";
    $wrapper .= "    if (\$last_modified && \$creation_time && abs(\$last_modified - \$creation_time) > 60) {\n";
    $wrapper .= "        // File was modified after creation - could be suspicious\n";
    $wrapper .= "        \$suspicious = true;\n";
    $wrapper .= "        \n";
    $wrapper .= "        // Check for surrounding chunk files\n";
    $wrapper .= "        \$chunks_dir = __DIR__ . '/chunks';\n";
    $wrapper .= "        \$metadata_file = __DIR__ . '/metadata.json';\n";
    $wrapper .= "        if (!\$suspicious && (!is_dir(\$chunks_dir) || !file_exists(\$metadata_file))) {\n";
    $wrapper .= "            // Missing expected files\n";
    $wrapper .= "            return false;\n";
    $wrapper .= "        }\n";
    $wrapper .= "    }\n";
    $wrapper .= "    \n";
    $wrapper .= "    return true; // All checks passed\n";
    $wrapper .= "}\n\n";
    
    $wrapper .= "// Execute security checks\n";
    $wrapper .= "if (!{$var_decrypt}_security_check()) {\n";
    $wrapper .= "    // Self-defense: Don't reveal that we detected anything\n";
    $wrapper .= "    \${$var_runtime_log} = __DIR__ . '/runtime.log';\n";
    $wrapper .= "    @file_put_contents(\${$var_runtime_log}, date('Y-m-d H:i:s') . ' - Security violation detected\\n', FILE_APPEND);\n";
    $wrapper .= "    header('HTTP/1.0 404 Not Found');\n";
    $wrapper .= "    exit('<!-- Page not found -->');\n";
    $wrapper .= "}\n\n";
    
    // Add self-decryption function with HMAC verification
    $wrapper .= "function {$var_decrypt}(\${$var_data}, \${$var_key}, \${$var_iv}, \${$var_salt}, \${$var_hmac}) {\n";
    $wrapper .= "    // Derive encryption key using PBKDF2\n";
    $wrapper .= "    \$derived_key = hash_pbkdf2('sha256', \${$var_key}, \${$var_salt}, 10000, 32, true);\n";
    $wrapper .= "    \n";
    $wrapper .= "    // Verify HMAC for integrity\n";
    $wrapper .= "    \$calculated_hmac = hash_hmac('sha256', \${$var_data}, \$derived_key, true);\n";
    $wrapper .= "    if (!hash_equals(\${$var_hmac}, \$calculated_hmac)) {\n";
    $wrapper .= "        // Integrity check failed - possible tampering\n";
    $wrapper .= "        \${$var_runtime_log} = __DIR__ . '/runtime.log';\n";
    $wrapper .= "        @file_put_contents(\${$var_runtime_log}, date('Y-m-d H:i:s') . ' - Integrity check failed - possible tampering\\n', FILE_APPEND);\n";
    
    // Enhanced self-destruct mechanism
    $wrapper .= "        // Advanced self-destruct mechanism on tampering detection\n";
    $wrapper .= "        \$self_destruct = function() {\n";
    $wrapper .= "            // Find all related files\n";
    $wrapper .= "            \$base_dir = __DIR__;\n";
    $wrapper .= "            \$targets = [\n";
    $wrapper .= "                \$base_dir . '/chunks',\n";
    $wrapper .= "                \$base_dir . '/loader.php',\n";
    $wrapper .= "                \$base_dir . '/loader_encrypted.php',\n";
    $wrapper .= "                \$base_dir . '/metadata.json'\n";
    $wrapper .= "            ];\n";
    $wrapper .= "            \n";
    $wrapper .= "            // Collect all chunk files\n";
    $wrapper .= "            if (is_dir(\$base_dir . '/chunks')) {\n";
    $wrapper .= "                \$chunk_files = glob(\$base_dir . '/chunks/*.chunk');\n";
    $wrapper .= "                \$targets = array_merge(\$targets, \$chunk_files);\n";
    $wrapper .= "            }\n";
    $wrapper .= "            \n";
    $wrapper .= "            // Overwrite files with random data before deletion\n";
    $wrapper .= "            foreach (\$targets as \$target) {\n";
    $wrapper .= "                if (is_file(\$target)) {\n";
    $wrapper .= "                    \$size = filesize(\$target);\n";
    $wrapper .= "                    \$junk = '';\n";
    $wrapper .= "                    for (\$i = 0; \$i < \$size; \$i++) {\n";
    $wrapper .= "                        \$junk .= chr(mt_rand(0, 255));\n";
    $wrapper .= "                    }\n";
    $wrapper .= "                    @file_put_contents(\$target, \$junk);\n";
    $wrapper .= "                    @unlink(\$target);\n";
    $wrapper .= "                } elseif (is_dir(\$target)) {\n";
    $wrapper .= "                    // Delete directory contents\n";
    $wrapper .= "                    \$files = new RecursiveIteratorIterator(\n";
    $wrapper .= "                        new RecursiveDirectoryIterator(\$target, RecursiveDirectoryIterator::SKIP_DOTS),\n";
    $wrapper .= "                        RecursiveIteratorIterator::CHILD_FIRST\n";
    $wrapper .= "                    );\n";
    $wrapper .= "                    foreach (\$files as \$file) {\n";
    $wrapper .= "                        if (\$file->isDir()) {\n";
    $wrapper .= "                            @rmdir(\$file->getPathname());\n";
    $wrapper .= "                        } else {\n";
    $wrapper .= "                            @unlink(\$file->getPathname());\n";
    $wrapper .= "                        }\n";
    $wrapper .= "                    }\n";
    $wrapper .= "                    @rmdir(\$target);\n";
    $wrapper .= "                }\n";
    $wrapper .= "            }\n";
    $wrapper .= "            \n";
    $wrapper .= "            // Finally delete self\n";
    $wrapper .= "            @unlink(__FILE__);\n";
    $wrapper .= "        };\n";
    $wrapper .= "        \n";
    $wrapper .= "        // Execute in a separate thread or after script end if possible\n";
    $wrapper .= "        if (function_exists('register_shutdown_function')) {\n";
    $wrapper .= "            register_shutdown_function(\$self_destruct);\n";
    $wrapper .= "        } else {\n";
    $wrapper .= "            \$self_destruct();\n";
    $wrapper .= "        }\n";
    
    $wrapper .= "        http_response_code(500);\n";
    $wrapper .= "        return false;\n";
    $wrapper .= "    }\n";
    $wrapper .= "    \n";
    $wrapper .= "    // Decrypt the loader content\n";
    $wrapper .= "    \${$var_decrypted} = openssl_decrypt(\${$var_data}, 'AES-256-CBC', \$derived_key, OPENSSL_RAW_DATA, \${$var_iv});\n";
    $wrapper .= "    if (\${$var_decrypted} === false) {\n";
    $wrapper .= "        // Decryption failed\n";
    $wrapper .= "        \${$var_runtime_log} = __DIR__ . '/runtime.log';\n";
    $wrapper .= "        @file_put_contents(\${$var_runtime_log}, date('Y-m-d H:i:s') . ' - Decryption failed: ' . openssl_error_string() . '\\n', FILE_APPEND);\n";
    $wrapper .= "        return false;\n";
    $wrapper .= "    }\n";
    $wrapper .= "    \n";
    $wrapper .= "    // Decompress if the content is gzipped\n";
    $wrapper .= "    if (function_exists('gzdecode')) {\n";
    $wrapper .= "        \${$var_decompressed} = @gzdecode(\${$var_decrypted});\n";
    $wrapper .= "        if (\${$var_decompressed} !== false) {\n";
    $wrapper .= "            \${$var_decrypted} = \${$var_decompressed};\n";
    $wrapper .= "        }\n";
    $wrapper .= "    }\n";
    $wrapper .= "    \n";
    $wrapper .= "    return \${$var_decrypted};\n";
    $wrapper .= "}\n\n";
    
    // Add advanced obfuscation to confuse code analysis and decompilers
    $wrapper .= "// Anti-reverse engineering techniques\n";
    
    // Generate trap functions that look real but are never called
    for ($i = 0; $i < 3; $i++) {
        $trap_func = generate_random_var_name();
        $trap_param1 = generate_random_var_name();
        $trap_param2 = generate_random_var_name();
        $trap_var1 = generate_random_var_name();
        $trap_var2 = generate_random_var_name();
        $trap_const = bin2hex(random_bytes(4));
        
        $wrapper .= "function {$trap_func}(\${$trap_param1}, \${$trap_param2}=null) {\n";
        $wrapper .= "    \${$trap_var1} = md5(\${$trap_param1});\n";
        $wrapper .= "    \${$trap_var2} = [base64_encode(\${$trap_param1}), hash('sha256', \${$trap_param1} . '{$trap_const}')];\n";
        $wrapper .= "    if (strlen(\${$trap_param1}) % " . mt_rand(2, 5) . " === 0) {\n";
        $wrapper .= "        return substr(\${$trap_var1}, 0, 16) . \${$trap_var2}[0];\n";
        $wrapper .= "    } else {\n";
        $wrapper .= "        return \${$trap_var2}[1];\n";
        $wrapper .= "    }\n";
        $wrapper .= "}\n";
    }
    
    // Add junk calculations and false flags
    for ($i = 0; $i < 5; $i++) {
        $junk_var = generate_random_var_name();
        $junk_val = mt_rand(100, 999);
        $junk_op = ['*', '+', '-', '^', '%'][array_rand(['*', '+', '-', '^', '%'])];
        $junk_val2 = mt_rand(10, 99);
        
        $wrapper .= "\${$junk_var} = {$junk_val} {$junk_op} {$junk_val2}; // " . bin2hex(random_bytes(2)) . "\n";
    }
    
    // Add dummy variables that look like real keys or important data
    $dummy_key = bin2hex(random_bytes(16));
    $dummy_license = strtoupper(bin2hex(random_bytes(4)) . '-' . bin2hex(random_bytes(4)) . '-' . bin2hex(random_bytes(4)));
    $dummy_flag = 'VALID_LICENSE_' . mt_rand(1000, 9999);
    $wrapper .= "\$license_key = '{$dummy_license}'; // Honeypot trap for crackers\n";
    $wrapper .= "\$activation_key = '{$dummy_key}'; // Unused variable to distract decompilers\n";
    $wrapper .= "\$valid_license = false; // False check that's never actually used\n";
    $wrapper .= "\n";
    
    // Add fake checking code that's never executed
    $wrapper .= "if (false) { // Dead code to confuse analysis tools\n";
    $wrapper .= "    if (hash('sha256', \$license_key) === '" . hash('sha256', $dummy_license) . "') {\n";
    $wrapper .= "        define('{$dummy_flag}', true);\n";
    $wrapper .= "        \$valid_license = true;\n";
    $wrapper .= "    }\n";
    $wrapper .= "}\n\n";
    
    // Add the encrypted data and parameters
    $wrapper .= "// Encrypted data and parameters\n";
    $wrapper .= "\${$var_data} = base64_decode('{$b64_encrypted}');\n";
    $wrapper .= "\${$var_iv} = base64_decode('{$b64_iv}');\n";
    $wrapper .= "\${$var_salt} = base64_decode('{$b64_salt}');\n";
    $wrapper .= "\${$var_hmac} = base64_decode('{$b64_hmac}');\n";
    
    // Use a hidden key derivation approach instead of storing the actual key
    $wrapper .= "\$key_data = '{$b64_key_hash}';\n";
    $wrapper .= "\${$var_key} = hash('sha256', \$key_data . __FILE__ . filemtime(__FILE__), true);\n\n";
    
    // Decrypt and execute with error handling
    $wrapper .= "// Decrypt and execute the loader\n";
    $wrapper .= "try {\n";
    $wrapper .= "    \${$var_result} = {$var_decrypt}(\${$var_data}, \${$var_key}, \${$var_iv}, \${$var_salt}, \${$var_hmac});\n";
    $wrapper .= "    if (\${$var_result} === false) {\n";
    $wrapper .= "        die('<!-- Error 500: Decryption failed -->');\n";
    $wrapper .= "    }\n";
    $wrapper .= "    \n";
    $wrapper .= "    // Execute the decrypted loader\n";
    $wrapper .= "    // Strip PHP tags before eval to prevent syntax errors\n";
    $wrapper .= "    \${$var_result} = preg_replace('/^<\\\\?php\\\\s*|^<\\\\?\\\\s*|\\\\?>\\\\s*$/', '', \${$var_result});\n";
    $wrapper .= "    eval(\${$var_result});\n";
    $wrapper .= "} catch (Exception \$e) {\n";
    $wrapper .= "    // Log the error and display a generic message\n";
    $wrapper .= "    \${$var_runtime_log} = __DIR__ . '/runtime.log';\n";
    $wrapper .= "    @file_put_contents(\${$var_runtime_log}, date('Y-m-d H:i:s') . ' - Exception: ' . \$e->getMessage() . '\\n', FILE_APPEND);\n";
    $wrapper .= "    http_response_code(500);\n";
    $wrapper .= "    die('<!-- Error 500: Internal Server Error -->');\n";
    $wrapper .= "}\n";
    
    // Write the wrapper to file
    if (file_put_contents($encrypted_loader, $wrapper) === false) {
        log_message("Failed to write encrypted loader", 'error');
        return false;
    }
    
    log_message("Encrypted loader created at: $encrypted_loader", 'info');
    
    return [
        'original' => $original_loader,
        'encrypted' => $encrypted_loader
    ];
}