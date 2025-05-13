<?php
/**
 * ChunkShield - PHP Code Protection System
 * Main entry point - Redirects to the web interface
 * 
 * Optimized for Replit environment
 */

// Apply optimizations
require_once __DIR__ . '/config.php';

// Log application start
if (function_exists('log_message')) {
    log_message("ChunkShield application started. PHP version: " . PHP_VERSION, "info");
}

// Start output buffering for performance
ob_start();

// Enable zlib compression if available
if (extension_loaded('zlib') && !ini_get('zlib.output_compression')) {
    ini_set('zlib.output_compression', 'On');
    ini_set('zlib.output_compression_level', '5');
}

// Redirect to the web interface
header('Cache-Control: no-store, no-cache, must-revalidate, max-age=0');
header('Pragma: no-cache');
header('Location: web/index.php');

// Flush buffer and exit
ob_end_flush();
exit;