<?php
/* Protected with ChunkShield Anti-Debug */
// Anti-debugging protection
$ENABLE_SELF_DESTRUCT = true;

function f8OT0eyq3k() {
    // Check for xdebug
    if (function_exists('xdebug_get_code_coverage') || 
        function_exists('xdebug_start_trace') || 
        function_exists('xdebug_break') ||
        extension_loaded('xdebug')) {
        return 'XDebug extension detected';
    }
    
    // Check debug backtrace depth
    $backtrace = debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS);
    if (count($backtrace) > 5) {
        return 'Abnormal call stack depth (' . count($backtrace) . ')';
    }
    
    // Check for debug headers
    foreach (['HTTP_X_DEBUG', 'HTTP_X_DEBUG_SESSION', 'HTTP_X_XDEBUG_SESSION'] as $h) {
        if (isset($_SERVER[$h])) return 'Debug header detected: ' . $h;
    }
    
    // All checks passed
    return false;
}

function yz7FYGdupBcaysh($reason) {
    $log_file = __DIR__ . '/runtime.log';
    $timestamp = date('Y-m-d H:i:s');
    $ip = isset($_SERVER['REMOTE_ADDR']) ? $_SERVER['REMOTE_ADDR'] : 'unknown';
    $ua = isset($_SERVER['HTTP_USER_AGENT']) ? $_SERVER['HTTP_USER_AGENT'] : 'unknown';
    
    $entry = "[$timestamp] [SECURITY_ALERT] Debug attempt detected from $ip\n";
    $entry .= "User Agent: $ua\n";
    $entry .= "Reason: $reason\n";
    $entry .= "-------------------------\n";
    
    @file_put_contents($log_file, $entry, FILE_APPEND);
}

function JkJRWbl($chunks_dir, $loader = null, $reason = 'Security violation') {
    global $ENABLE_SELF_DESTRUCT;
    if (!$ENABLE_SELF_DESTRUCT) return false;
    
    // Log the event
    $log_file = __DIR__ . '/runtime.log';
    $timestamp = date('Y-m-d H:i:s');
    $ip = isset($_SERVER['REMOTE_ADDR']) ? $_SERVER['REMOTE_ADDR'] : 'unknown';
    
    $entry = "[$timestamp] [SELF_DESTRUCT] Protected code self-destructing\n";
    $entry .= "IP: $ip\n";
    $entry .= "Reason: $reason\n";
    $entry .= "-------------------------\n";
    
    @file_put_contents($log_file, $entry, FILE_APPEND);
    
    // Delete all chunks
    if (is_dir($chunks_dir)) {
        $files = glob($chunks_dir . '/*');
        foreach ($files as $file) {
            if (is_file($file)) @unlink($file);
        }
        @rmdir($chunks_dir);
    }
    
    // Delete loader if specified
    if ($loader !== null && file_exists($loader)) {
        @unlink($loader);
    }
    
    return true;
}

// Run security checks
$JJaz2JkH_ldDC8 = f8OT0eyq3k();
if ($JJaz2JkH_ldDC8 !== false) {
    // Debug attempt detected
    yz7FYGdupBcaysh($JJaz2JkH_ldDC8);
    
    // Execute self-destruct if enabled
    $dXv_lllsu = __DIR__ . '/chunks';
    $u_Bzx8lPuo4b = __FILE__;
    $Q68SdjftNsw = 'Debug attempt: ' . $JJaz2JkH_ldDC8;
    JkJRWbl($dXv_lllsu, $u_Bzx8lPuo4b, $Q68SdjftNsw);
    
    // Terminate with generic error
    die('Security violation detected. This attempt has been logged.');
}


/**
 * Basic Test Function
 */
function testBasicFunction($param1, $param2 = null) {
    $result = [];
    
    // Process input parameters
    if (is_string($param1)) {
        $result['type'] = 'string';
        $result['value'] = $param1;
    } else if (is_array($param1)) {
        $result['type'] = 'array';
        $result['value'] = implode(',', $param1);
    } else {
        $result['type'] = 'other';
        $result['value'] = (string)$param1;
    }
    
    // Add optional parameter
    if ($param2 !== null) {
        $result['extra'] = $param2;
    }
    
    return $result;
}

// Example usage
$testVar = "Hello World";
$output = testBasicFunction($testVar, 123);
echo "Output: " . json_encode($output);