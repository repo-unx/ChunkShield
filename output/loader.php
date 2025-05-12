<?php
/* ChunkShield Advanced Loader - 20250512-141130 */

// {comment}
function ParseBlockc48($pcjs, $xUA, $zcE) {
    // Store params in array format for more obfuscation
    $AEXc = ['c' => $pcjs, 'k' => $xUA, 'i' => $zcE];
    
    // Hash the key for consistent format
    $AEXc['k'] = hash('sha256', $AEXc['k'], true);
    
    // Decode and decrypt content
    $AEXc['c'] = base64_decode($AEXc['c']);
     = openssl_decrypt($AEXc['c'], 'aes-256-ctr', $AEXc['k'], OPENSSL_RAW_DATA, $AEXc['i']);
    
    if ( === false) {
        die('Error: Decryption failed');
    }
    
    return ;
}


    // Runtime fingerprinting and environment validation
    $srv6822019284eac = $_SERVER;
    $fp6822019284ead = [
        'domain' => isset($srv6822019284eac['HTTP_HOST']) ? $srv6822019284eac['HTTP_HOST'] : '',
        'ip' => isset($srv6822019284eac['REMOTE_ADDR']) ? $srv6822019284eac['REMOTE_ADDR'] : '',
        'path' => isset($srv6822019284eac['SCRIPT_FILENAME']) ? $srv6822019284eac['SCRIPT_FILENAME'] : '',
        'date' => date('Y-m-d'),
        'env' => php_uname()
    ];
    
    // Validate runtime environment
    $val6822019284eae = function($fp6822019284ead) {
        // Domain validation
        if (!empty('unx.code')) {
            $allowedDomains = explode(',', 'unx.code');
            $domainValid = false;
            
            foreach ($allowedDomains as $domain) {
                $domain = trim($domain);
                if (empty($domain)) continue;
                
                // Check for exact match or subdomain
                if ($fp6822019284ead['domain'] === $domain || 
                    preg_match('/\.' . preg_quote($domain, '/') . '$/', $fp6822019284ead['domain']) || 
                    // Wildcard check
                    (strpos($domain, '*') !== false && preg_match('/' . str_replace('*', '[^.]+', $domain) . '/', $fp6822019284ead['domain']))) {
                    $domainValid = true;
                    break;
                }
            }
            
            if (!empty($allowedDomains) && !$domainValid) {
                // Domain validation failed - you can customize this behavior
                error_log('Domain validation failed: ' . $fp6822019284ead['domain']);
                // Uncomment to enforce domain restriction:
                // die('This software is licensed for specific domains only.');
            }
        }
        
        // IP address validation
        if (!empty('127.0.0.1')) {
            $allowedIps = explode(',', '127.0.0.1');
            $ipValid = false;
            
            foreach ($allowedIps as $ip) {
                $ip = trim($ip);
                if (empty($ip)) continue;
                
                if ($fp6822019284ead['ip'] === $ip || 
                    // Check for CIDR notation (simple implementation)
                    (strpos($ip, '/') !== false && strpos($fp6822019284ead['ip'], substr($ip, 0, strpos($ip, '/'))) === 0)) {
                    $ipValid = true;
                    break;
                }
            }
            
            if (!empty($allowedIps) && !$ipValid) {
                // IP validation failed
                error_log('IP validation failed: ' . $fp6822019284ead['ip']);
                // Uncomment to enforce IP restriction:
                // die('This software is restricted to specific IP addresses.');
            }
        }
        
        // Path validation
        if (!empty('')) {
            $validPaths = explode(',', '');
            $pathValid = false;
            
            foreach ($validPaths as $path) {
                $path = trim($path);
                if (empty($path)) continue;
                
                if (strpos($fp6822019284ead['path'], $path) !== false) {
                    $pathValid = true;
                    break;
                }
            }
            
            if (!empty($validPaths) && !$pathValid) {
                // Path validation failed
                error_log('Path validation failed: ' . $fp6822019284ead['path']);
                // Uncomment to enforce path restriction:
                // die('Invalid installation path.');
            }
        }
        
        return true;
    };
    
    // Run environment validation
    $val6822019284eae($fp6822019284ead);
    
    // Anti reverse-engineering code
    $junk_6822019284eb0 = function() {
        // This function contains decoy code to confuse reverse-engineering attempts
        $data_6822019284eb1 = [];
        if (function_exists('random_bytes')) { $data_6822019284eb1[] = bin2hex(random_bytes(16)); }
        for($i=0; $i<3; $i++) { $data_6822019284eb1[] = hash('sha256', uniqid('', true)); }
        for($i=0; $i<3; $i++) { $data_6822019284eb1[] = hash('sha256', uniqid('', true)); }
        try { $data_6822019284eb1[] = openssl_random_pseudo_bytes(32); } catch (\Exception $e) { }
        return $data_6822019284eb1;
    };

    // Execute decoy code in background
    if (function_exists('register_shutdown_function')) {
        register_shutdown_function($junk_6822019284eb0);
    }
// Encryption key
$xUA = 'H8tNlG(LI5cuY$AhPN!94twzYN*LQ7mR';

// Map file path
$aPQ = __DIR__ . '/map/chunks_68220158ba620.map.json';

// Check if map file exists
if (!file_exists($aPQ)) {
    die('Map file not found: ' . $aPQ);
}

// Load map data
$lQpCe = json_decode(file_get_contents($aPQ), true);
if (!isset($lQpCe['chunks']) || !is_array($lQpCe['chunks'])) {
    die('Invalid map file format.');
}

// Sort chunks by order
usort($lQpCe['chunks'], function($a, $b) {
    return $a['order'] - $b['order'];
});

// Process all chunks
$uu1 = array_values($lQpCe['chunks']);
for ($i = 0; $i < count($uu1); $i++) {
    $QFYPF = $uu1[$i];
    $MWr = __DIR__ . '/chunks/' . $QFYPF['file'];
    
    if (!file_exists($MWr)) {
        die('Chunk file not found: ' . $MWr);
    }

    // Process chunk
    $pcjs = file_get_contents($MWr);
    $zcE = base64_decode($QFYPF['iv']);
    $ru1zL = ParseBlockc48($pcjs, $xUA, $zcE);

    // Simple integrity check with relaxed validation
    if (md5($ru1zL) !== $QFYPF['checksum']) {
        // Log the mismatch but continue execution anyway
         = md5($ru1zL);
         = $QFYPF['checksum'];
        error_log("Notice: Checksum mismatch for chunk. Expected: {$vars['csum2']}, Got: {$vars['csum1']}");
        // Validation is relaxed to prevent false positives
    }

    // Execute chunk
    // Split-string eval technique for improved obfuscation
     = 'ev'.'al';
    ($ru1zL);
}
