<?php
/**
 * Utility Functions for PHP Obfuscation Tool
 * 
 * This file contains utility functions used throughout the application
 * for common tasks such as validation, random string generation,
 * file handling, and security-related operations.
 */

/**
 * Generate a random string of specified length
 * 
 * @param int $length Length of the random string
 * @return string Random string
 */
function generateRandomString($length = 10) {
    $characters = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
    $charactersLength = strlen($characters);
    $randomString = '';
    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[rand(0, $charactersLength - 1)];
    }
    return $randomString;
}

/**
 * Get human-readable file upload error message
 * 
 * @param int $error The error code from $_FILES['file']['error']
 * @return string Human-readable error message
 */
function getUploadErrorMessage($error) {
    switch ($error) {
        case UPLOAD_ERR_INI_SIZE:
            return 'The uploaded file exceeds the upload_max_filesize directive in php.ini.';
        case UPLOAD_ERR_FORM_SIZE:
            return 'The uploaded file exceeds the MAX_FILE_SIZE directive that was specified in the HTML form.';
        case UPLOAD_ERR_PARTIAL:
            return 'The uploaded file was only partially uploaded.';
        case UPLOAD_ERR_NO_FILE:
            return 'No file was uploaded.';
        case UPLOAD_ERR_NO_TMP_DIR:
            return 'Missing a temporary folder.';
        case UPLOAD_ERR_CANT_WRITE:
            return 'Failed to write file to disk.';
        case UPLOAD_ERR_EXTENSION:
            return 'A PHP extension stopped the file upload.';
        default:
            return 'Unknown upload error.';
    }
}

/**
 * Format file size in human-readable form
 * 
 * @param int $bytes File size in bytes
 * @param int $precision Precision (decimal places)
 * @return string Human-readable file size
 */
function formatFileSize($bytes, $precision = 2) {
    $units = ['B', 'KB', 'MB', 'GB', 'TB'];
    
    $bytes = max($bytes, 0);
    $pow = floor(($bytes ? log($bytes) : 0) / log(1024));
    $pow = min($pow, count($units) - 1);
    
    $bytes /= (1 << (10 * $pow));
    
    return round($bytes, $precision) . ' ' . $units[$pow];
}

/**
 * Log a message to the log file
 * 
 * @param string $message The message to log
 * @param string $type The type of message (info, error, warning)
 */
function logMessage($message, $type = 'info') {
    $logDir = __DIR__ . '/../logs';
    
    if (!file_exists($logDir)) {
        mkdir($logDir, 0755, true);
    }
    
    $logFile = $logDir . '/app_' . date('Y-m-d') . '.log';
    $timestamp = date('Y-m-d H:i:s');
    
    $logEntry = "[{$timestamp}] [{$type}] {$message}" . PHP_EOL;
    
    file_put_contents($logFile, $logEntry, FILE_APPEND);
}

/**
 * Clean output directories
 * 
 * Remove files older than the specified time
 * 
 * @param int $maxAge Maximum age of files in seconds (default: 24h)
 */
function cleanOutputDirectories($maxAge = 86400) {
    $directories = [
        __DIR__ . '/../output/obfuscated',
        __DIR__ . '/../output/chunks',
        __DIR__ . '/../output/map'
    ];
    
    $now = time();
    
    foreach ($directories as $directory) {
        if (!file_exists($directory)) {
            continue;
        }
        
        $files = glob($directory . '/*');
        
        foreach ($files as $file) {
            if (is_file($file) && ($now - filemtime($file) > $maxAge)) {
                unlink($file);
                logMessage("Removed old file: {$file}", 'info');
            }
        }
    }
}

/**
 * Check if a string is valid JSON
 * 
 * @param string $string The string to check
 * @return bool True if valid JSON, false otherwise
 */
function isValidJson($string) {
    json_decode($string);
    return (json_last_error() === JSON_ERROR_NONE);
}

/**
 * Create a backup of a file
 * 
 * @param string $filePath Path to the file to backup
 * @return string|bool Path to the backup file or false on failure
 */
function createFileBackup($filePath) {
    if (!file_exists($filePath)) {
        return false;
    }
    
    $backupPath = $filePath . '.' . date('YmdHis') . '.bak';
    
    if (copy($filePath, $backupPath)) {
        return $backupPath;
    }
    
    return false;
}

/**
 * Extract the map file destination from a full path
 * 
 * @param string $mapFilePath Full path to map file
 * @return string Relative path for loading in the browser
 */
function getMapFileUrl($mapFilePath) {
    $baseDir = __DIR__ . '/..';
    $relativePath = str_replace($baseDir, '', $mapFilePath);
    return '/output' . $relativePath;
}

/**
 * Validate a list of domains (supports wildcard for subdomains)
 * 
 * @param string $domains Comma-separated list of domains
 * @return array Validated domains and any errors
 */
function validateDomains($domains) {
    $domains = trim($domains);
    if (empty($domains)) {
        return [
            'valid' => false,
            'message' => 'Domain list cannot be empty',
            'domains' => []
        ];
    }
    
    $domainList = array_map('trim', explode(',', $domains));
    $validDomains = [];
    $errors = [];
    
    foreach ($domainList as $domain) {
        // Check for wildcard subdomain format (*.example.com)
        if (strpos($domain, '*.') === 0) {
            $rootDomain = substr($domain, 2); // Remove *. prefix
            if (!validateDomainName($rootDomain)) {
                $errors[] = "Invalid domain format: $domain";
                continue;
            }
        } 
        // Regular domain validation
        else if (!validateDomainName($domain) && $domain !== 'localhost') {
            $errors[] = "Invalid domain format: $domain";
            continue;
        }
        
        $validDomains[] = $domain;
    }
    
    return [
        'valid' => count($errors) === 0,
        'message' => count($errors) > 0 ? implode(', ', $errors) : 'Domains validated successfully',
        'domains' => $validDomains
    ];
}

/**
 * Validate a domain name format
 * 
 * @param string $domain Domain name to validate
 * @return bool True if valid, false otherwise
 */
function validateDomainName($domain) {
    // Regex for domain validation (simplified)
    $pattern = '/^(?:[a-z0-9](?:[a-z0-9-]{0,61}[a-z0-9])?\.)+[a-z0-9][a-z0-9-]{0,61}[a-z0-9]$/i';
    return (bool) preg_match($pattern, $domain);
}

/**
 * Validate a list of IP addresses (supports wildcards and CIDR notation)
 * 
 * @param string $ips Comma-separated list of IP addresses
 * @return array Validated IPs and any errors
 */
function validateIpAddresses($ips) {
    $ips = trim($ips);
    if (empty($ips)) {
        return [
            'valid' => true, // Empty is allowed (no IP restriction)
            'message' => 'No IP restrictions applied',
            'ips' => []
        ];
    }
    
    $ipList = array_map('trim', explode(',', $ips));
    $validIps = [];
    $errors = [];
    
    foreach ($ipList as $ip) {
        // Allow "any" IP (*)
        if ($ip === '*') {
            $validIps[] = $ip;
            continue;
        }
        
        // CIDR notation (e.g., 192.168.1.0/24)
        if (strpos($ip, '/') !== false) {
            list($subnet, $mask) = explode('/', $ip);
            if (!filter_var($subnet, FILTER_VALIDATE_IP) || !is_numeric($mask) || $mask < 0 || $mask > 32) {
                $errors[] = "Invalid CIDR notation: $ip";
                continue;
            }
        } 
        // Regular IP
        else if (!filter_var($ip, FILTER_VALIDATE_IP) && $ip !== 'localhost') {
            $errors[] = "Invalid IP address: $ip";
            continue;
        }
        
        $validIps[] = $ip;
    }
    
    return [
        'valid' => count($errors) === 0,
        'message' => count($errors) > 0 ? implode(', ', $errors) : 'IP addresses validated successfully',
        'ips' => $validIps
    ];
}

/**
 * Validate server paths
 * 
 * @param string $paths Comma-separated list of server paths
 * @return array Validated paths and any errors
 */
function validateServerPaths($paths) {
    $paths = trim($paths);
    if (empty($paths)) {
        return [
            'valid' => true, // Empty is allowed (no path restriction)
            'message' => 'No path restrictions applied',
            'paths' => []
        ];
    }
    
    $pathList = array_map('trim', explode(',', $paths));
    $validPaths = [];
    $errors = [];
    
    foreach ($pathList as $path) {
        // Allow "any" path (*)
        if ($path === '*') {
            $validPaths[] = $path;
            continue;
        }
        
        // Basic path validation (no special characters except standard path chars)
        if (!preg_match('/^[a-zA-Z0-9_.\/\\\\-]+$/', $path)) {
            $errors[] = "Path contains invalid characters: $path";
            continue;
        }
        
        $validPaths[] = $path;
    }
    
    return [
        'valid' => count($errors) === 0,
        'message' => count($errors) > 0 ? implode(', ', $errors) : 'Paths validated successfully',
        'paths' => $validPaths
    ];
}

/**
 * Sanitize input to prevent XSS attacks
 * 
 * @param string $input User input to sanitize
 * @return string Sanitized input
 */
function sanitizeInput($input) {
    return htmlspecialchars($input, ENT_QUOTES, 'UTF-8');
}

/**
 * Securely generate a random encryption key
 * 
 * @param int $length Key length in bytes (32 for AES-256)
 * @return string Base64 encoded encryption key
 */
function generateSecureKey($length = 32) {
    // Use secure random bytes if available
    if (function_exists('random_bytes')) {
        return base64_encode(random_bytes($length));
    }
    
    // Fallback to openssl_random_pseudo_bytes
    if (function_exists('openssl_random_pseudo_bytes')) {
        $secure = true;
        $bytes = openssl_random_pseudo_bytes($length, $secure);
        if ($secure) {
            return base64_encode($bytes);
        }
    }
    
    // Last resort - less secure but better than nothing
    $chars = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789!@#$%^&*()-_=+[]{}|;:,.<>?';
    $key = '';
    for ($i = 0; $i < $length; $i++) {
        $key .= $chars[mt_rand(0, strlen($chars) - 1)];
    }
    
    return base64_encode($key);
}
?>
