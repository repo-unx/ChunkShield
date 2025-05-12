<?php
/**
 * License Generator Tool
 * 
 * Provides functions to create license files (.lic) and certificates (.crt) with
 * domain verification and secure encryption.
 */

/**
 * Generate a license file with the specified parameters
 * 
 * @param array $licenseData License data including key, domain, expiry, etc.
 * @param array $options Security options for the license
 * @return array Result of the operation including file paths and status
 */
function generateLicense($licenseData, $options = []) {
    // Default options
    $options = array_merge([
        'encrypt' => true,
        'add_signature' => true,
        'add_hashing' => false,
        'security_level' => 2,
    ], $options);
    
    // Create necessary directories
    $licenseDir = __DIR__ . '/../output/licenses';
    if (!file_exists($licenseDir)) {
        mkdir($licenseDir, 0755, true);
    }
    
    // Generate a unique ID for this license
    $licenseId = uniqid('lic_');
    
    // Determine file type and extension
    $fileType = isset($licenseData['license_type']) && $licenseData['license_type'] === 'certificate' ? 'certificate' : 'license';
    $fileExt = $fileType === 'certificate' ? 'crt' : 'lic';
    
    // Create the license data structure
    $license = [
        'id' => $licenseId,
        'type' => $fileType,
        'key' => $licenseData['license_key'],
        'domain' => $licenseData['license_domain'],
        'expiry' => strtotime($licenseData['license_expiry']),
        'created' => time(),
        'security_level' => $options['security_level']
    ];
    
    // Add certificate-specific data if applicable
    if ($fileType === 'certificate') {
        $license['issuer'] = $licenseData['cert_issuer'] ?? 'Unknown';
        $license['cert_type'] = $licenseData['cert_type'] ?? 'standard';
    }
    
    // Add hardware binding hash if requested
    if ($options['add_hashing']) {
        // In a real system, this would use actual hardware identifiers
        // Here we're using a mock value for demonstration
        $license['hw_binding'] = md5($licenseData['license_domain'] . $licenseId);
    }
    
    // Add digital signature if requested
    if ($options['add_signature']) {
        // Generate a secret key for signing
        $signingKey = hash('sha256', $licenseId . $licenseData['license_key'] . $licenseData['license_domain']);
        
        // Create signature from the license data
        $dataToSign = json_encode($license);
        $license['signature'] = hash_hmac('sha256', $dataToSign, $signingKey);
    }
    
    // Prepare the file content
    $licenseContent = json_encode($license, JSON_PRETTY_PRINT);
    
    // Encrypt the content if requested
    if ($options['encrypt']) {
        // Generate encryption key and IV
        $encryptionKey = hash('sha256', $licenseData['license_key'] . $licenseId, true);
        $iv = openssl_random_pseudo_bytes(openssl_cipher_iv_length('aes-256-cbc'));
        
        // Encrypt the license content
        $encryptedContent = openssl_encrypt($licenseContent, 'aes-256-cbc', $encryptionKey, 0, $iv);
        
        // Create the final content with IV and encrypted data
        $finalContent = json_encode([
            'encrypted' => true,
            'iv' => base64_encode($iv),
            'data' => $encryptedContent,
            'key_hint' => substr(md5($licenseData['license_key']), 0, 8),
            'version' => '1.0'
        ], JSON_PRETTY_PRINT);
    } else {
        // Use unencrypted content
        $finalContent = $licenseContent;
    }
    
    // Write to file
    $licenseFilename = $licenseDir . '/' . $licenseId . '.' . $fileExt;
    file_put_contents($licenseFilename, $finalContent);
    
    // Generate a verification file
    $verificationFile = generateVerificationFile($license, $licenseId, $fileExt, $licenseDir);
    
    // Return the results
    return [
        'success' => true,
        'message' => 'License generated successfully.',
        'license_file' => $licenseFilename,
        'verification_file' => $verificationFile,
        'license_id' => $licenseId,
        'license_type' => $fileType
    ];
}

/**
 * Generate a verification file for the license
 * 
 * @param array $license License data
 * @param string $licenseId Unique license ID
 * @param string $fileExt File extension
 * @param string $licenseDir Directory to save the file
 * @return string Path to the verification file
 */
function generateVerificationFile($license, $licenseId, $fileExt, $licenseDir) {
    // Create a simpler verification structure
    $verification = [
        'id' => $licenseId,
        'key' => $license['key'],
        'domain' => $license['domain'],
        'expiry' => $license['expiry'],
        'type' => $license['type'],
        'verify_url' => 'https://example.com/verify/' . $licenseId
    ];
    
    // Save verification file
    $verificationFilename = $licenseDir . '/' . $licenseId . '.verify.' . $fileExt;
    file_put_contents($verificationFilename, json_encode($verification, JSON_PRETTY_PRINT));
    
    return $verificationFilename;
}

/**
 * Verify a license file
 * 
 * @param string $licenseFile Path to the license file
 * @param string $licenseKey The license key
 * @param string $domain The domain to validate
 * @return array Verification result
 */
function verifyLicense($licenseFile, $licenseKey, $domain = null) {
    // Check if the file exists
    if (!file_exists($licenseFile)) {
        return [
            'success' => false,
            'message' => 'License file not found.'
        ];
    }
    
    // Read the license content
    $fileContent = file_get_contents($licenseFile);
    $licenseData = json_decode($fileContent, true);
    
    // Check if it's encrypted
    if (isset($licenseData['encrypted']) && $licenseData['encrypted'] === true) {
        // Decrypt the license
        $licenseData = decryptLicense($licenseData, $licenseKey);
        
        if ($licenseData === false) {
            return [
                'success' => false,
                'message' => 'Failed to decrypt license. Invalid license key.'
            ];
        }
    }
    
    // Validate license structure
    if (!isset($licenseData['key']) || !isset($licenseData['domain']) || !isset($licenseData['expiry'])) {
        return [
            'success' => false,
            'message' => 'Invalid license format.'
        ];
    }
    
    // Check license key
    if ($licenseData['key'] !== $licenseKey) {
        return [
            'success' => false,
            'message' => 'Invalid license key.'
        ];
    }
    
    // Check expiry
    if (time() > $licenseData['expiry']) {
        return [
            'success' => false,
            'message' => 'License has expired.'
        ];
    }
    
    // Check domain if provided
    if ($domain !== null) {
        if (!domainMatches($licenseData['domain'], $domain)) {
            return [
                'success' => false,
                'message' => 'License not valid for this domain.'
            ];
        }
    }
    
    // Verify signature if present
    if (isset($licenseData['signature'])) {
        $originalSignature = $licenseData['signature'];
        unset($licenseData['signature']);
        
        $signingKey = hash('sha256', $licenseData['id'] . $licenseData['key'] . $licenseData['domain']);
        $dataToSign = json_encode($licenseData);
        $calculatedSignature = hash_hmac('sha256', $dataToSign, $signingKey);
        
        if ($originalSignature !== $calculatedSignature) {
            return [
                'success' => false,
                'message' => 'License has been tampered with.'
            ];
        }
        
        // Restore the signature
        $licenseData['signature'] = $originalSignature;
    }
    
    // Check hardware binding if present
    if (isset($licenseData['hw_binding'])) {
        // In a real system, this would compare with actual hardware
        // For this demo, we'll just assume it's valid
    }
    
    // License is valid
    return [
        'success' => true,
        'message' => 'License is valid.',
        'license' => $licenseData
    ];
}

/**
 * Decrypt an encrypted license
 * 
 * @param array $encryptedData The encrypted license data
 * @param string $licenseKey The license key
 * @return array|false Decrypted license data or false on failure
 */
function decryptLicense($encryptedData, $licenseKey) {
    // Extract the necessary parts
    $iv = base64_decode($encryptedData['iv']);
    $data = $encryptedData['data'];
    
    // Generate the encryption key (same method as in encryption)
    // Note: In a real system, we would need to know the license ID
    // This is a simplified version that assumes the license key is enough
    $encryptionKey = hash('sha256', $licenseKey . substr(md5($licenseKey), 0, 8), true);
    
    // Decrypt the data
    $decryptedJson = openssl_decrypt($data, 'aes-256-cbc', $encryptionKey, 0, $iv);
    
    if ($decryptedJson === false) {
        return false;
    }
    
    // Parse the JSON
    $licenseData = json_decode($decryptedJson, true);
    
    if ($licenseData === null) {
        return false;
    }
    
    return $licenseData;
}

/**
 * Check if a domain matches the license domain
 * 
 * @param string $licenseDomain The domain in the license
 * @param string $checkDomain The domain to check
 * @return bool True if domains match
 */
function domainMatches($licenseDomain, $checkDomain) {
    // Convert to lowercase
    $licenseDomain = strtolower($licenseDomain);
    $checkDomain = strtolower($checkDomain);
    
    // Direct match
    if ($licenseDomain === $checkDomain) {
        return true;
    }
    
    // Subdomain matching - check if the license domain is a suffix of the check domain
    // For example, example.com should match sub.example.com
    if (preg_match('/\.' . preg_quote($licenseDomain, '/') . '$/', $checkDomain)) {
        return true;
    }
    
    // Wildcard matching
    if (strpos($licenseDomain, '*') !== false) {
        $pattern = '/^' . str_replace(['*', '.'], ['[a-zA-Z0-9-]+', '\.'], $licenseDomain) . '$/';
        return preg_match($pattern, $checkDomain) === 1;
    }
    
    return false;
}

/**
 * Format a license for display
 * 
 * @param array $licenseData License data
 * @return string Formatted license text
 */
function formatLicenseForDisplay($licenseData) {
    $output = '';
    
    // Format varies based on type
    if ($licenseData['type'] === 'certificate') {
        $output .= "=== SECURITY CERTIFICATE ===\n\n";
        $output .= "Certificate ID: {$licenseData['id']}\n";
        $output .= "Certificate Key: {$licenseData['key']}\n";
        $output .= "Issuer: {$licenseData['issuer']}\n";
        $output .= "Type: {$licenseData['cert_type']}\n";
    } else {
        $output .= "=== SOFTWARE LICENSE ===\n\n";
        $output .= "License ID: {$licenseData['id']}\n";
        $output .= "License Key: {$licenseData['key']}\n";
    }
    
    $output .= "Domain: {$licenseData['domain']}\n";
    $output .= "Created: " . date('Y-m-d H:i:s', $licenseData['created']) . "\n";
    $output .= "Expires: " . date('Y-m-d H:i:s', $licenseData['expiry']) . "\n";
    $output .= "Security Level: " . $licenseData['security_level'] . "\n";
    
    if (isset($licenseData['signature'])) {
        $output .= "Signature: " . substr($licenseData['signature'], 0, 16) . "...\n";
    }
    
    if (isset($licenseData['hw_binding'])) {
        $output .= "Hardware Binding: Enabled\n";
    }
    
    return $output;
}

/**
 * Create a zip package with all license files
 * 
 * @param array $files List of files to include
 * @param string $licenseId License ID for naming
 * @return string Path to the zip file
 */
function createLicensePackage($files, $licenseId) {
    $zipDir = __DIR__ . '/../output';
    $zipFileName = $zipDir . '/license_package_' . $licenseId . '.zip';
    
    $zip = new ZipArchive();
    if ($zip->open($zipFileName, ZipArchive::CREATE) === TRUE) {
        foreach ($files as $file) {
            if (file_exists($file)) {
                $zip->addFile($file, basename($file));
            }
        }
        $zip->close();
        return $zipFileName;
    }
    
    return false;
}
?>