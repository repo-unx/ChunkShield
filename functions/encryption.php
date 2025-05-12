<?php
/**
 * ChunkShield Encryption Functions
 * 
 * This file contains functions for encrypting and decrypting code chunks,
 * using AES-256-CBC encryption with gzip compression and base64 encoding.
 */

/**
 * Generates a random encryption key
 * 
 * @param int $length Key length in bytes
 * @return string Encryption key
 */
function generate_encryption_key($length = SECURITY_KEY_LENGTH) {
    return bin2hex(secure_random_bytes($length / 2));
}

/**
 * Encrypts data using AES-256-CBC
 * 
 * @param string $data Data to encrypt
 * @param string $key Encryption key
 * @param bool $use_gzip Whether to compress data with gzip before encryption
 * @param bool $use_base64 Whether to base64 encode the encrypted data
 * @return array|false Array containing encrypted data and metadata or false on failure
 */
function encrypt_data($data, $key, $use_gzip = true, $use_base64 = true) {
    // Validate input
    if (empty($data)) {
        log_message("Encryption failed: Empty data provided", 'error');
        return false;
    }
    
    if (empty($key)) {
        log_message("Encryption failed: Empty key provided", 'error');
        return false;
    }
    
    try {
        // Normalize the key to ensure it's the right length
        $normalized_key = substr(hash('sha256', $key, true), 0, 32);
        
        // Compress data if gzip is enabled
        if ($use_gzip && function_exists('gzencode')) {
            $compressed = gzencode($data, 9);
            // Only use compressed data if it's actually smaller
            if ($compressed !== false && strlen($compressed) < strlen($data)) {
                $data = $compressed;
            } else {
                // Compression didn't work or didn't reduce size
                $use_gzip = false;
            }
        }
        
        // Generate initialization vector
        $iv = secure_random_bytes(SECURITY_IV_LENGTH);
        
        // Encrypt data
        $encrypted = openssl_encrypt(
            $data,
            ENCRYPTION_METHOD,
            $normalized_key,
            OPENSSL_RAW_DATA,
            $iv
        );
        
        if ($encrypted === false) {
            log_message("Encryption failed: " . openssl_error_string(), 'error');
            return false;
        }
        
        // Combine IV and encrypted data
        $result = $iv . $encrypted;
        
        // Base64 encode if enabled
        if ($use_base64) {
            $result = base64_encode($result);
            
            // Verify encoding was successful
            if ($result === false) {
                log_message("Base64 encoding failed", 'error');
                return false;
            }
        }
        
        return [
            'data' => $result,
            'iv' => bin2hex($iv),
            'method' => ENCRYPTION_METHOD,
            'gzip' => $use_gzip,
            'base64' => $use_base64,
            'key_hash' => substr(hash('sha256', $key), 0, 16) // Store partial key hash for verification
        ];
    } catch (Exception $e) {
        log_message("Encryption exception: " . $e->getMessage(), 'error');
        return false;
    }
}

/**
 * Decrypts data encrypted with AES-256-CBC
 * 
 * @param string $encrypted_data Encrypted data
 * @param string $key Encryption key
 * @param bool $use_gzip Whether the data was compressed with gzip
 * @param bool $use_base64 Whether the data was base64 encoded
 * @return string|bool Decrypted data or false on failure
 */
function decrypt_data($encrypted_data, $key, $use_gzip = true, $use_base64 = true) {
    // Validate input
    if (empty($encrypted_data)) {
        log_message("Decryption failed: Empty encrypted data provided", 'error');
        return false;
    }
    
    if (empty($key)) {
        log_message("Decryption failed: Empty key provided", 'error');
        return false;
    }
    
    try {
        // Normalize the key to ensure it's the right length
        $normalized_key = substr(hash('sha256', $key, true), 0, 32);
        
        // Base64 decode if enabled
        if ($use_base64) {
            $decoded = base64_decode($encrypted_data, true);
            if ($decoded === false) {
                log_message("Base64 decoding failed - invalid base64 data", 'error');
                return false;
            }
            $encrypted_data = $decoded;
        }
        
        // Extract IV from the beginning of the data
        $iv_size = SECURITY_IV_LENGTH;
        if (strlen($encrypted_data) <= $iv_size) {
            log_message("Decryption failed: Encrypted data too short", 'error');
            return false;
        }
        
        $iv = substr($encrypted_data, 0, $iv_size);
        $ciphertext = substr($encrypted_data, $iv_size);
        
        // Decrypt data
        $decrypted = openssl_decrypt(
            $ciphertext,
            ENCRYPTION_METHOD,
            $normalized_key,
            OPENSSL_RAW_DATA,
            $iv
        );
        
        if ($decrypted === false) {
            log_message("Decryption failed: " . openssl_error_string(), 'error');
            return false;
        }
        
        // Decompress data if gzip was used
        if ($use_gzip && function_exists('gzdecode')) {
            // Try to decompress, but handle gracefully if it fails
            $decompressed = @gzdecode($decrypted);
            if ($decompressed !== false) {
                $decrypted = $decompressed;
            } else {
                // Log a warning but continue with the uncompressed data
                log_message("Gzip decompression failed - continuing with raw data", 'warning');
            }
        }
        
        return $decrypted;
    } catch (Exception $e) {
        log_message("Decryption exception: " . $e->getMessage(), 'error');
        return false;
    }
}

/**
 * Creates a decryption function string for the loader
 * 
 * @param string $key Encryption key
 * @param bool $use_gzip Whether gzip compression is used
 * @param bool $use_base64 Whether base64 encoding is used
 * @return string PHP code for decryption function
 */
function create_decrypt_function($key, $use_gzip = true, $use_base64 = true) {
    // Generate random function name
    $func_name = generate_random_var_name();
    
    // Normalize and properly escape the key for inclusion in PHP string
    $normalized_key = substr(hash('sha256', $key, true), 0, 32);
    $safe_key = bin2hex($normalized_key); // Convert to hex for safe inclusion in code
    
    // Start building the function with proper formatting
    $function = "function $func_name(\$d) {\n";
    $function .= "    if (empty(\$d)) { return false; }\n";
    $function .= "    \$k = hex2bin('" . $safe_key . "');\n";
    
    // Add try-catch for error handling
    $function .= "    try {\n";
    
    // Add base64 decode if enabled with proper error checking
    if ($use_base64) {
        $function .= "        \$d = base64_decode(\$d, true);\n";
        $function .= "        if (\$d === false) { return false; }\n";
    }
    
    // Extract IV and ciphertext with proper error checking
    $function .= "        if (strlen(\$d) <= " . SECURITY_IV_LENGTH . ") { return false; }\n";
    $function .= "        \$i = substr(\$d, 0, " . SECURITY_IV_LENGTH . ");\n";
    $function .= "        \$c = substr(\$d, " . SECURITY_IV_LENGTH . ");\n";
    
    // Add decryption code
    $function .= "        \$r = openssl_decrypt(\$c, '" . ENCRYPTION_METHOD . "', \$k, OPENSSL_RAW_DATA, \$i);\n";
    $function .= "        if (\$r === false) { return false; }\n";
    
    // Add gzip decode if enabled with proper error handling
    if ($use_gzip) {
        $function .= "        // Try to decompress if gzip was used\n";
        $function .= "        if (function_exists('gzdecode')) {\n";
        $function .= "            \$g = @gzdecode(\$r);\n";
        $function .= "            if (\$g !== false) { \$r = \$g; }\n";
        $function .= "        }\n";
    }
    
    // Close try block and add catch
    $function .= "        return \$r;\n";
    $function .= "    } catch (Exception \$e) {\n";
    $function .= "        return false;\n";
    $function .= "    }\n";
    $function .= "}";
    
    return $function;
}

/**
 * Generates a simple hash for verification
 * 
 * @param string $data Data to hash
 * @param string $key Secret key to use in the hash
 * @return string Hash value
 */
function generate_hash($data, $key) {
    return hash_hmac('sha256', $data, $key);
}
