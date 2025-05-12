<?php
/**
 * PHP Code Chunker and Encryptor
 * 
 * This file provides functions to split PHP code into chunks and encrypt them.
 */

/**
 * Create chunks from PHP code
 * 
 * @param string $code The PHP code to split into chunks
 * @param int $maxSizeKB Maximum chunk size in KB
 * @param bool $encrypt Whether to encrypt chunks
 * @param string $encryptionKey Key for encryption
 * @return array Information about created chunks
 */
function createChunks($code, $maxSizeKB = 5, $encrypt = true, $encryptionKey = '') {
    // Create output directories if they don't exist
    $chunksDir = __DIR__ . '/../output/chunks';
    $mapDir = __DIR__ . '/../output/map';
    
    if (!file_exists($chunksDir)) {
        mkdir($chunksDir, 0755, true);
    }
    
    if (!file_exists($mapDir)) {
        mkdir($mapDir, 0755, true);
    }
    
    // Convert KB to bytes
    $maxSizeBytes = $maxSizeKB * 1024;
    
    // Generate a unique ID for this chunking operation
    $uniqueId = uniqid();
    
    // Initialize result
    $result = [
        'chunks' => [],
        'mapFile' => ''
    ];
    
    // Array to store chunk information for the map file
    $chunksMap = [
        'chunks' => [],
        'key_hint' => 'manual',
        'timestamp' => time(),
        'checksum' => md5($code)
    ];
    
    // If the code is smaller than the maximum size, create just one chunk
    if (strlen($code) <= $maxSizeBytes) {
        $chunkNumber = sprintf('%02d', 1);
        $chunkFilename = $chunksDir . '/chunk_' . $uniqueId . '_' . $chunkNumber . '.php.enc';
        
        // Process and save the chunk
        $chunkContent = $code;
        $chunkChecksum = md5($chunkContent);
        
        if ($encrypt) {
            // Encrypt the chunk
            $iv = generateIV();
            $encryptedContent = encryptContent($chunkContent, $encryptionKey, $iv);
            file_put_contents($chunkFilename, $encryptedContent);
            
            // Add chunk info to the map
            $chunksMap['chunks'][] = [
                'file' => basename($chunkFilename),
                'order' => 1,
                'checksum' => $chunkChecksum,
                'iv' => base64_encode($iv)
            ];
        } else {
            // Save unencrypted
            file_put_contents($chunkFilename, $chunkContent);
            
            // Add chunk info to the map
            $chunksMap['chunks'][] = [
                'file' => basename($chunkFilename),
                'order' => 1,
                'checksum' => $chunkChecksum,
                'iv' => null
            ];
        }
        
        // Add chunk to result
        $result['chunks'][] = [
            'file' => $chunkFilename,
            'order' => 1,
            'checksum' => $chunkChecksum
        ];
    } else {
        // Split the code into chunks based on size
        
        // First, extract PHP opening tag if present
        $phpOpen = '';
        if (strpos($code, '<?php') === 0) {
            $phpOpen = '<?php';
            $code = substr($code, 5);
        }
        
        // Split the code into chunks
        $chunks = str_split($code, $maxSizeBytes);
        
        // Process each chunk
        foreach ($chunks as $index => $chunk) {
            $chunkNumber = sprintf('%02d', $index + 1);
            $chunkFilename = $chunksDir . '/chunk_' . $uniqueId . '_' . $chunkNumber . '.php.enc';
            
            // Prepare chunk content
            $chunkContent = ($index === 0 ? $phpOpen : '') . $chunk;
            $chunkChecksum = md5($chunkContent);
            
            if ($encrypt) {
                // Encrypt the chunk
                $iv = generateIV();
                $encryptedContent = encryptContent($chunkContent, $encryptionKey, $iv);
                file_put_contents($chunkFilename, $encryptedContent);
                
                // Add chunk info to the map
                $chunksMap['chunks'][] = [
                    'file' => basename($chunkFilename),
                    'order' => $index + 1,
                    'checksum' => $chunkChecksum,
                    'iv' => base64_encode($iv)
                ];
            } else {
                // Save unencrypted
                file_put_contents($chunkFilename, $chunkContent);
                
                // Add chunk info to the map
                $chunksMap['chunks'][] = [
                    'file' => basename($chunkFilename),
                    'order' => $index + 1,
                    'checksum' => $chunkChecksum,
                    'iv' => null
                ];
            }
            
            // Add chunk to result
            $result['chunks'][] = [
                'file' => $chunkFilename,
                'order' => $index + 1,
                'checksum' => $chunkChecksum
            ];
        }
    }
    
    // Create the map file
    $mapFilename = $mapDir . '/chunks_' . $uniqueId . '.map.json';
    file_put_contents($mapFilename, json_encode($chunksMap, JSON_PRETTY_PRINT));
    $result['mapFile'] = $mapFilename;
    
    return $result;
}

/**
 * Generate a random initialization vector (IV) for encryption
 * 
 * @return string Random IV
 */
function generateIV() {
    return openssl_random_pseudo_bytes(openssl_cipher_iv_length('aes-256-cbc'));
}

/**
 * Encrypt content using AES-256-CBC
 * 
 * @param string $content Content to encrypt
 * @param string $key Encryption key
 * @param string $iv Initialization vector
 * @return string Encrypted content
 */
function encryptContent($content, $key, $iv) {
    // Hash the key to ensure it's the right length
    $key = hash('sha256', $key, true);
    
    // Encrypt the content
    $encrypted = openssl_encrypt($content, 'aes-256-cbc', $key, OPENSSL_RAW_DATA, $iv);
    
    // Return base64 encoded encrypted content and IV
    return base64_encode($encrypted);
}

/**
 * Decrypt content encrypted with AES-256-CBC
 * 
 * @param string $encryptedContent Base64 encoded encrypted content
 * @param string $key Encryption key
 * @param string $iv Base64 encoded IV
 * @return string Decrypted content
 */
function decryptContent($encryptedContent, $key, $iv) {
    // Hash the key to ensure it's the right length
    $key = hash('sha256', $key, true);
    
    // Decode base64 content
    $encryptedContent = base64_decode($encryptedContent);
    
    // Decrypt the content
    return openssl_decrypt($encryptedContent, 'aes-256-cbc', $key, OPENSSL_RAW_DATA, $iv);
}
?>
