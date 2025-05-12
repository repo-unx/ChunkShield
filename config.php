<?php
/**
 * ChunkShield Configuration File
 * 
 * This file contains all the configuration parameters for the ChunkShield system.
 * Edit this file to customize the behavior of the code protection system.
 */

// Error reporting and display settings
error_reporting(E_ALL);
ini_set('display_errors', 1);

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
