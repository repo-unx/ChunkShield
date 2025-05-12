<?php
/**
 * Utility Functions for PHP Obfuscation Tool
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
?>
