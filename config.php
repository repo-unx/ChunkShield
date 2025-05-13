<?php
/**
 * ChunkShield Configuration File
 * 
 * This file contains all the configuration parameters for the ChunkShield system.
 * Edit this file to customize the behavior of the code protection system.
 */

// Require performance optimizations for Replit environment
if (file_exists(dirname(__FILE__) . '/performance.php')) {
    require_once dirname(__FILE__) . '/performance.php';
}

// Set environment mode (development or production)
define('DEBUG_MODE', true); // Set to false in production

// Error reporting and display settings
error_reporting(DEBUG_MODE ? E_ALL : E_ERROR | E_WARNING | E_PARSE);
ini_set('display_errors', DEBUG_MODE ? 1 : 0);

// Base paths
define('ROOT_PATH', dirname(__FILE__));
define('CHUNKS_DIR', ROOT_PATH . '/chunks');
define('OUTPUT_DIR', ROOT_PATH . '/output');

// Create directories if they don't exist
if (!file_exists(CHUNKS_DIR)) {
    mkdir(CHUNKS_DIR, 0755, true);
}
if (!file_exists(OUTPUT_DIR)) {
    mkdir(OUTPUT_DIR, 0755, true);
}

// Obfuscation settings
define('OBFUSCATE_REMOVE_COMMENTS', true);
define('OBFUSCATE_REMOVE_WHITESPACE', true);
define('OBFUSCATE_RENAME_VARIABLES', true);
define('OBFUSCATE_INSERT_JUNK', true);
define('OBFUSCATE_JUNK_DENSITY', 3); // 1-10, higher means more junk code

// Chunking settings
define('CHUNK_SIZE', 4096); // Size in bytes for each chunk
define('CHUNK_MIN_COUNT', 3); // Minimum number of chunks to generate

// Encryption settings
define('ENCRYPTION_METHOD', 'AES-256-CBC');
define('ENCRYPTION_USE_GZIP', true);
define('ENCRYPTION_USE_BASE64', true);

// License settings
define('LICENSE_VALIDATION_ENABLED', true);
define('LICENSE_FILE_PATH', ROOT_PATH . '/license.key');
define('LICENSE_CHECK_DOMAIN', true);
define('LICENSE_CHECK_IP', true);
define('LICENSE_CHECK_PATH', true);

// Loader settings
define('LOADER_ADD_FINGERPRINTING', true);
define('LOADER_ADD_JUNK_EVAL', true);
define('LOADER_JUNK_COUNT', 5); // Number of junk eval blocks

// Security settings
define('SECURITY_KEY_LENGTH', 32); // Length of random keys
define('SECURITY_IV_LENGTH', 16); // Length of initialization vector

// Performance settings for Replit
define('USE_OPTIMIZED_FILE_OPS', true); // Use optimized file operations
define('CACHE_ENABLED', true); // Enable caching for improved performance
define('FILE_CACHE_TTL', 300); // Cache files for 5 minutes
define('CHUNK_PROCESSING_MEMORY_LIMIT', 4194304); // 4MB per chunking operation
define('ENABLE_MEMORY_TRACKING', DEBUG_MODE); // Track memory usage in debug mode

// Include helper files
require_once ROOT_PATH . '/tools/notifications.php';
require_once ROOT_PATH . '/tools/wrappers.php';
require_once ROOT_PATH . '/tools/chunk_functions.php';

// Toast notification settings
define('USE_TOAST_NOTIFICATIONS', true); // Enable toast notifications
define('TOAST_POSITION', 'top-right'); // Position: top-right, top-left, bottom-right, bottom-left, top-center, bottom-center
define('TOAST_DURATION', 5000); // Duration in milliseconds
