<?php
/**
 * ChunkShield Chunking Functions
 * 
 * This file contains functions for splitting obfuscated PHP code into chunks,
 * encrypting those chunks, and managing chunk files.
 */

/**
 * Splits a string into chunks of specified size
 * 
 * @param string $data Data to be chunked
 * @param int $chunk_size Size of each chunk in bytes
 * @return array Array of data chunks
 */
function split_into_chunks($data, $chunk_size = CHUNK_SIZE) {
    $length = strlen($data);
    $chunks = [];

    // Ensure we have at least the minimum number of chunks
    $actual_chunk_size = $chunk_size;
    if ($length / $actual_chunk_size < CHUNK_MIN_COUNT) {
        $actual_chunk_size = ceil($length / CHUNK_MIN_COUNT);
    }

    // Split the data
    for ($i = 0; $i < $length; $i += $actual_chunk_size) {
        $chunks[] = substr($data, $i, $actual_chunk_size);
    }

    return $chunks;
}

/**
 * Creates chunk files for the obfuscated code
 * 
 * @param string $obfuscated_code Obfuscated PHP code
 * @param string $output_dir Directory to store chunk files
 * @param string $encryption_key Encryption key
 * @return array|bool Chunk information or false on failure
 */
function create_chunks($obfuscated_code, $output_dir = CHUNKS_DIR, $encryption_key = null) {
    // Ensure the output directory exists
    if (!ensure_directory($output_dir)) {
        log_message("Failed to create output directory: $output_dir", 'error');
        return false;
    }

    // Generate encryption key if not provided
    if ($encryption_key === null) {
        $encryption_key = generate_encryption_key();
    }

    // Split the code into chunks
    $chunks = split_into_chunks($obfuscated_code);
    $chunk_count = count($chunks);

    if ($chunk_count === 0) {
        log_message("No chunks were created", 'error');
        return false;
    }

    log_message("Splitting code into $chunk_count chunks", 'info');

    // Process each chunk
    $chunk_info = [
        'key' => $encryption_key,
        'total' => $chunk_count,
        'chunks' => []
    ];

    // Clear existing chunk files
    $files = glob($output_dir . '/*.chunk');
    foreach ($files as $file) {
        unlink($file);
    }

    // Create new chunk files
    foreach ($chunks as $index => $chunk) {
        // Generate unique chunk ID
        $chunk_id = generate_random_string(16);
        $filename = $output_dir . '/' . $chunk_id . '.chunk';

        // Encrypt the chunk
        $encrypted = encrypt_data(
            $chunk,
            $encryption_key,
            ENCRYPTION_USE_GZIP,
            ENCRYPTION_USE_BASE64
        );

        if ($encrypted === false) {
            log_message("Failed to encrypt chunk $index", 'error');
            return false;
        }

        // Add metadata
        $chunk_data = [
            'id' => $chunk_id,
            'index' => $index,
            'size' => strlen($chunk),
            'hash' => generate_hash($chunk, $encryption_key),
            'encrypted_size' => strlen($encrypted['data'])
        ];

        // Write the encrypted chunk to file
        if (file_put_contents($filename, $encrypted['data']) === false) {
            log_message("Failed to write chunk file: $filename", 'error');
            return false;
        }

        // Add to chunk info
        $chunk_info['chunks'][] = $chunk_data;
    }

    return $chunk_info;
}

/**
 * Generates metadata file for chunks
 * 
 * @param array $chunk_info Chunk information
 * @param string $output_dir Directory to store metadata file
 * @return string|bool Path to metadata file or false on failure
 */
function create_chunk_metadata($chunk_info, $output_dir = CHUNKS_DIR) {
    $metadata_file = $output_dir . '/metadata.json';

    // Encrypt metadata
    $encrypted = encrypt_data(
        json_encode($chunk_info),
        $chunk_info['key'],
        ENCRYPTION_USE_GZIP,
        ENCRYPTION_USE_BASE64
    );

    if ($encrypted === false) {
        log_message("Failed to encrypt chunk metadata", 'error');
        return false;
    }

    // Write metadata file
    if (file_put_contents($metadata_file, $encrypted['data']) === false) {
        log_message("Failed to write metadata file: $metadata_file", 'error');
        return false;
    }

    return $metadata_file;
}

/**
 * Loads and decrypts chunk files
 * 
 * @param array $chunk_info Chunk information
 * @param string $chunks_dir Directory containing chunk files
 * @return string|bool Reconstructed code or false on failure
 */
function load_chunks($chunk_info, $chunks_dir = CHUNKS_DIR) {
    $reconstructed = '';
    $key = $chunk_info['key'];

    // Sort chunks by index
    usort($chunk_info['chunks'], function($a, $b) {
        return $a['index'] - $b['index'];
    });

    // Process each chunk
    foreach ($chunk_info['chunks'] as $chunk) {
        $filename = $chunks_dir . '/' . $chunk['id'] . '.chunk';

        if (!file_exists($filename)) {
            log_message("Chunk file not found: $filename", 'error');
            return false;
        }

        // Read and decrypt chunk
        $encrypted_data = file_get_contents($filename);
        $decrypted = decrypt_data(
            $encrypted_data,
            $key,
            ENCRYPTION_USE_GZIP,
            ENCRYPTION_USE_BASE64
        );

        if ($decrypted === false) {
            log_message("Failed to decrypt chunk: " . $chunk['id'], 'error');
            return false;
        }

        // Verify hash
        $chunk_hash = generate_hash($decrypted, $key);
        if ($chunk_hash !== $chunk['hash']) {
            log_message("Chunk hash verification failed: " . $chunk['id'], 'error');
            return false;
        }

        // Add to reconstructed code
        $reconstructed .= $decrypted;
    }

    return $reconstructed;
}

/**
 * Splits a code string into chunks of specified size
 *
 * @param string $code Code to be chunked
 * @param int $chunk_size Size of each chunk in bytes
 * @return array Array of code chunks
 */
function code_split_into_chunks($code, $chunk_size = CHUNK_SIZE) {
    // Clean the code before chunking
    $code = trim(preg_replace('/^\s*<\?php|\?>\s*$/', '', $code));
    $chunks = str_split($code, $chunk_size);
    return $chunks;
}