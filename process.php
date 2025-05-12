<?php
/**
 * ChunkShield Command Line Processor
 * 
 * This file provides a command line interface for ChunkShield code protection system.
 * Usage: php process.php input_file.php [output_dir]
 */

// Check if script is being run from the command line
if (php_sapi_name() !== 'cli') {
    die("This script can only be run from the command line.");
}

// Include configuration and required functions
require_once 'config.php';
require_once 'functions/utils.php';
require_once 'functions/obfuscation.php';
require_once 'functions/chunking.php';
require_once 'functions/encryption.php';
require_once 'functions/loader_generator.php';
require_once 'functions/license.php';

// Function to display help
function display_help() {
    echo "ChunkShield - PHP Code Protection System\n";
    echo "Usage: php process.php [options] input_file.php [output_dir]\n\n";
    echo "Options:\n";
    echo "  --help                Show this help message\n";
    echo "  --disable-comments    Disable comment removal\n";
    echo "  --disable-whitespace  Disable whitespace removal\n";
    echo "  --disable-renaming    Disable variable renaming\n";
    echo "  --disable-junk        Disable junk code insertion\n";
    echo "  --disable-license     Disable license validation\n";
    echo "  --license=<file>      Specify a license file\n";
    echo "  --chunks=<count>      Minimum number of chunks to generate\n";
    echo "  --junk-density=<1-10> Junk code density (1-10)\n\n";
}

// Function to parse command line arguments
function parse_arguments($args) {
    $options = [
        'input_file' => null,
        'output_dir' => null,
        'disable_comments' => false,
        'disable_whitespace' => false,
        'disable_renaming' => false,
        'disable_junk' => false,
        'disable_license' => false,
        'license_file' => LICENSE_FILE_PATH,
        'min_chunks' => CHUNK_MIN_COUNT,
        'junk_density' => OBFUSCATE_JUNK_DENSITY
    ];
    
    foreach ($args as $arg) {
        if ($arg === '--help') {
            display_help();
            exit(0);
        } elseif ($arg === '--disable-comments') {
            $options['disable_comments'] = true;
        } elseif ($arg === '--disable-whitespace') {
            $options['disable_whitespace'] = true;
        } elseif ($arg === '--disable-renaming') {
            $options['disable_renaming'] = true;
        } elseif ($arg === '--disable-junk') {
            $options['disable_junk'] = true;
        } elseif ($arg === '--disable-license') {
            $options['disable_license'] = true;
        } elseif (strpos($arg, '--license=') === 0) {
            $options['license_file'] = substr($arg, 10);
        } elseif (strpos($arg, '--chunks=') === 0) {
            $options['min_chunks'] = intval(substr($arg, 9));
        } elseif (strpos($arg, '--junk-density=') === 0) {
            $options['junk_density'] = intval(substr($arg, 15));
            $options['junk_density'] = max(1, min(10, $options['junk_density']));
        } elseif ($options['input_file'] === null && !strpos($arg, '--')) {
            $options['input_file'] = $arg;
        } elseif ($options['output_dir'] === null && !strpos($arg, '--')) {
            $options['output_dir'] = $arg;
        }
    }
    
    return $options;
}

// Parse command line arguments
$options = parse_arguments(array_slice($argv, 1));

// Check if input file is specified
if ($options['input_file'] === null) {
    echo "Error: No input file specified.\n";
    display_help();
    exit(1);
}

// Check if input file exists
if (!file_exists($options['input_file'])) {
    echo "Error: Input file not found: {$options['input_file']}\n";
    exit(1);
}

// Set output directory
if ($options['output_dir'] === null) {
    $options['output_dir'] = dirname($options['input_file']) . '/protected';
}

// Create output directory if it doesn't exist
if (!file_exists($options['output_dir'])) {
    if (!mkdir($options['output_dir'], 0755, true)) {
        echo "Error: Failed to create output directory: {$options['output_dir']}\n";
        exit(1);
    }
}

// Create chunks directory inside output directory
$chunks_dir = $options['output_dir'] . '/chunks';
if (!file_exists($chunks_dir)) {
    if (!mkdir($chunks_dir, 0755, true)) {
        echo "Error: Failed to create chunks directory: {$chunks_dir}\n";
        exit(1);
    }
}

// Update configuration based on options
if ($options['disable_comments']) {
    define('OBFUSCATE_REMOVE_COMMENTS', false);
}

if ($options['disable_whitespace']) {
    define('OBFUSCATE_REMOVE_WHITESPACE', false);
}

if ($options['disable_renaming']) {
    define('OBFUSCATE_RENAME_VARIABLES', false);
}

if ($options['disable_junk']) {
    define('OBFUSCATE_INSERT_JUNK', false);
}

if ($options['disable_license']) {
    define('LICENSE_VALIDATION_ENABLED', false);
}

define('CHUNK_MIN_COUNT', $options['min_chunks']);
define('OBFUSCATE_JUNK_DENSITY', $options['junk_density']);

// Start the protection process
echo "Starting ChunkShield protection process...\n";

// Step 1: Obfuscate the code
echo "Step 1: Obfuscating code...\n";
$temp_output = $options['output_dir'] . '/temp_obfuscated.php';
$obfuscation_result = obfuscate_php($options['input_file'], $temp_output);

if ($obfuscation_result === false) {
    echo "Error: Obfuscation failed.\n";
    exit(1);
}

echo "  - Code obfuscated successfully.\n";

// Step 2: Create chunks
echo "Step 2: Creating and encrypting chunks...\n";
$obfuscated_code = file_get_contents($temp_output);
$encryption_key = generate_encryption_key();
$chunk_info = create_chunks($obfuscated_code, $chunks_dir, $encryption_key);

if ($chunk_info === false) {
    echo "Error: Chunking failed.\n";
    exit(1);
}

echo "  - Created " . count($chunk_info['chunks']) . " encrypted chunks.\n";

// Step 3: Create metadata
echo "Step 3: Creating metadata...\n";
$metadata_file = create_chunk_metadata($chunk_info, $chunks_dir);

if ($metadata_file === false) {
    echo "Error: Failed to create metadata.\n";
    exit(1);
}

echo "  - Metadata created successfully.\n";

// Step 4: Load license if enabled
$license_info = [];

if (!$options['disable_license'] && file_exists($options['license_file'])) {
    echo "Step 4: Loading license...\n";
    $license_data = load_license_file($options['license_file']);
    
    if ($license_data !== false) {
        if (is_license_valid($license_data)) {
            $license_info = get_all_restrictions($license_data);
            echo "  - License valid and loaded successfully.\n";
        } else {
            echo "  - Warning: License invalid or expired.\n";
        }
    } else {
        echo "  - Warning: Failed to load license file.\n";
    }
} else {
    echo "Step 4: License validation disabled.\n";
}

// Step 5: Generate loader
echo "Step 5: Generating loader...\n";
$loader_file = $options['output_dir'] . '/loader.php';
$loader_result = create_loader_file($chunk_info, $loader_file, $license_info);

if ($loader_result === false) {
    echo "Error: Failed to create loader.\n";
    exit(1);
}

echo "  - Loader created successfully.\n";

// Clean up temporary files
@unlink($temp_output);

// All done!
echo "\nProtection complete!\n";
echo "Protected files saved to: {$options['output_dir']}\n";
echo "Loader: {$loader_file}\n";
echo "Chunks: " . count($chunk_info['chunks']) . " files in {$chunks_dir}\n";

exit(0);
