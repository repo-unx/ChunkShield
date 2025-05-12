<?php
/**
 * PHP Loader Generator
 * 
 * This file provides functions to generate a loader for encrypted chunks.
 * It includes support for polymorphic loaders that change with each build.
 */

// Include the polymorphic loader generator
require_once __DIR__ . '/polymorphic_loader.php';

/**
 * Generate a loader script for encrypted chunks
 * 
 * @param array $chunksInfo Information about the chunks
 * @param string $encryptionKey Key used for encryption
 * @param bool $licenseCheck Whether to include license verification
 * @return string Generated loader code
 */
function generateLoader($chunksInfo, $encryptionKey, $licenseCheck = false) {
    // Generate a polymorphic loader with unique variable names, encryption methods, and structure
    return generatePolymorphicLoader($chunksInfo, $encryptionKey, $licenseCheck);
}
?>
