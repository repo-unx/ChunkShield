<?php
/**
 * ChunkShield Utility Functions
 * 
 * This file contains utility functions used across the ChunkShield system.
 */

/**
 * Generates a random string of specified length
 * 
 * @param int $length Length of the string to generate
 * @param string $chars Characters to use (default: alphanumeric)
 * @return string Random string
 */
function generate_random_string($length, $chars = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ') {
    $chars_length = strlen($chars);
    $random_string = '';
    
    for ($i = 0; $i < $length; $i++) {
        $random_string .= $chars[rand(0, $chars_length - 1)];
    }
    
    return $random_string;
}

/**
 * Generates a random variable name
 * 
 * @param int $length Length of the variable name (default: random between 5-15)
 * @return string Random variable name
 */
function generate_random_var_name($length = 0) {
    if ($length <= 0) {
        $length = rand(5, 15);
    }
    
    $first_char = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ_';
    $rest_chars = $first_char . '0123456789';
    
    $var_name = $first_char[rand(0, strlen($first_char) - 1)];
    $var_name .= generate_random_string($length - 1, $rest_chars);
    
    return $var_name;
}

/**
 * Secure random bytes generator that works across PHP versions
 * 
 * @param int $length Number of bytes to generate
 * @return string Random bytes
 */
function secure_random_bytes($length) {
    if (function_exists('random_bytes')) {
        return random_bytes($length);
    } elseif (function_exists('openssl_random_pseudo_bytes')) {
        return openssl_random_pseudo_bytes($length);
    } else {
        // Fallback to less secure method
        $bytes = '';
        for ($i = 0; $i < $length; $i++) {
            $bytes .= chr(mt_rand(0, 255));
        }
        return $bytes;
    }
}

/**
 * Creates a directory if it doesn't exist
 * 
 * @param string $dir Directory path
 * @return bool True if directory exists or was created successfully
 */
function ensure_directory($dir) {
    if (!file_exists($dir)) {
        return mkdir($dir, 0755, true);
    }
    return is_dir($dir);
}

/**
 * Gets the current domain name
 * 
 * @return string Current domain name
 */
function get_current_domain() {
    return isset($_SERVER['HTTP_HOST']) ? $_SERVER['HTTP_HOST'] : '';
}

/**
 * Gets the client IP address
 * 
 * @return string Client IP address
 */
function get_client_ip() {
    $ip = '';
    if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
        $ip = $_SERVER['HTTP_CLIENT_IP'];
    } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
        $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
    } else {
        $ip = isset($_SERVER['REMOTE_ADDR']) ? $_SERVER['REMOTE_ADDR'] : '';
    }
    return $ip;
}

/**
 * Gets the current script path
 * 
 * @return string Current script path
 */
function get_script_path() {
    return isset($_SERVER['SCRIPT_FILENAME']) ? $_SERVER['SCRIPT_FILENAME'] : '';
}

/**
 * Logs messages to a file
 * 
 * @param string $message Message to log
 * @param string $level Log level (info, warning, error)
 * @return bool True if log was written successfully
 */
function log_message($message, $level = 'info') {
    $log_file = dirname(__DIR__) . '/logs/chunkshield.log';
    $timestamp = date('Y-m-d H:i:s');
    $log_entry = "[$timestamp] [$level] $message" . PHP_EOL;
    return file_put_contents($log_file, $log_entry, FILE_APPEND);
}

/**
 * Get a user-friendly error message for file upload errors
 * 
 * @param int $error_code PHP file upload error code
 * @return string User-friendly error message
 */
function getUploadErrorMessage($error_code) {
    switch ($error_code) {
        case UPLOAD_ERR_INI_SIZE:
            return 'The uploaded file exceeds the upload_max_filesize directive in php.ini';
        case UPLOAD_ERR_FORM_SIZE:
            return 'The uploaded file exceeds the MAX_FILE_SIZE directive in the HTML form';
        case UPLOAD_ERR_PARTIAL:
            return 'The uploaded file was only partially uploaded';
        case UPLOAD_ERR_NO_FILE:
            return 'No file was uploaded';
        case UPLOAD_ERR_NO_TMP_DIR:
            return 'Missing a temporary folder';
        case UPLOAD_ERR_CANT_WRITE:
            return 'Failed to write file to disk';
        case UPLOAD_ERR_EXTENSION:
            return 'A PHP extension stopped the file upload';
        default:
            return 'Unknown upload error';
    }
}

/**
 * Format file size to human-readable format
 * 
 * @param int $bytes File size in bytes
 * @param int $precision Number of decimal places
 * @return string Formatted file size
 */
function formatFileSize($bytes, $precision = 2) {
    $units = ['B', 'KB', 'MB', 'GB', 'TB'];
    
    $bytes = max($bytes, 0);
    $pow = floor(($bytes ? log($bytes) : 0) / log(1024));
    $pow = min($pow, count($units) - 1);
    
    $bytes /= pow(1024, $pow);
    
    return round($bytes, $precision) . ' ' . $units[$pow];
}

/**
 * Generate a unique ID for a project
 * 
 * @return string Unique project ID
 */
function generateProjectId() {
    return uniqid('cs_') . '_' . bin2hex(random_bytes(4));
}

/**
 * Get the appropriate alert class based on message type
 * 
 * @param string $type Message type (success, error, warning, info)
 * @return string Bootstrap alert class
 */
function getAlertClass($type) {
    switch ($type) {
        case 'success':
            return 'alert-success';
        case 'error':
            return 'alert-danger';
        case 'warning':
            return 'alert-warning';
        case 'info':
        default:
            return 'alert-info';
    }
}

/**
 * Strips PHP tags from code
 * 
 * @param string $code PHP code
 * @return string Code without PHP tags
 */
function strip_php_tags($code) {
    // Remove opening and closing PHP tags
    return preg_replace('/^<\?php|\?>$/i', '', $code);
}

/**
 * Detects if content is PHP code
 * 
 * @param string $content Content to check
 * @return bool True if content appears to be PHP code
 */
function is_php_code($content) {
    $trimmed = trim($content);
    return substr($trimmed, 0, 5) === '<?php' || substr($trimmed, 0, 2) === '<?';
}

/**
 * Creates a safe string for use in eval statements
 * 
 * @param string $string The string to make safe for eval
 * @return string A safely encoded string for eval
 */
function safe_string_for_eval($string) {
    // Use base64 encoding to avoid quote escaping issues
    return 'base64_decode("' . base64_encode($string) . '")';
}

/**
 * Safely creates an eval statement
 * 
 * @param string $code PHP code to be evaluated
 * @return string Safe eval statement
 */
function safe_eval_statement($code) {
    return 'eval(' . safe_string_for_eval($code) . ');';
}

