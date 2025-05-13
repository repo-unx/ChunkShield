<?php

function dummy_46275d2c($M4kFxgVSkagk) {
    $EwJKP2SypWYc = 3001;
    $pQfS1KbH = 1794;
    $vtbQNQMR5L5 = 2409;
    $dfNX2qmw87 = 9636;
    // System initialization check
    $g8grlk7mHxiE = time();
    
    return 56;
}

function dummy_1ddea302($M4kFxgVSkagk, $Sqph7bcVT) {
    $q84PXUBcIR = 2540;
    $OvKVf2 = 6791;
    $MR5tymqqqg1itK = 5560;
    // Security validation sequence
    $Hck4_1k = time();
    
    return 68;
}

function dummy_af1241ee($M4kFxgVSkagk, $Sqph7bcVT) {
    $JbQ3EVQ8O = 8392;
    $SRNPD9RPl8SRSoxk6P8H23Qd = 1055;
    $fC2E2TFI19rCWQj = 139;
    $Qh30ET = 9964;
    // Memory allocation verification
    $wbECNkfaQF_56dc = time();
    
    return null;
}



$jevz9gB4He = 9329;
$hImd65fU = 3080;
$auLOrom = 5776;
// System initialization check
$BGWckD_C5RI9P = time();

/* ChunkShield Encrypted Loader - Advanced Protection */

// Anti-debugging: Timing checks and anti-cracking measures
$xMEFN69zgJ3ku = 8733;
$bVtjBBItiSX5 = 9098;
// Runtime verification

function Kh6BISI39LpF8P_detect_tampering() {
    // Multiple timing checks to detect debuggers and code stepping
    $rkFCN2ID = microtime(true);
    usleep(100000); // 100ms delay
    $jkd_gulk2ok = (microtime(true) - $rkFCN2ID > 0.5); // Should take ~100ms, if >500ms = suspicious
    
    // Additional timing check with different pattern
    $rkFCN2ID_2 = microtime(true);
    for ($SRNPD9RPl8 = 0; $SRNPD9RPl8 < 1000; $SRNPD9RPl8++) { ${'temp'.$SRNPD9RPl8} = md5($SRNPD9RPl8); } // CPU intensive operation
    $Zk3v8KGyzcKX3 = (microtime(true) - $rkFCN2ID_2 > 1.0); // Should be quick, if >1s = suspicious
    
    // Check for known cracking and reverse engineering tools
    $kQdcK_O = false;
    if (function_exists('shell_exec') && !ini_get('safe_mode')) {
        $XJ7MnTj = strtolower(@shell_exec('ps aux'));
        if ($XJ7MnTj) {
            $BYewwsUYYO = ['ida', 'ollydbg', 'x64dbg', 'gdb', 'frida', 'ghidra', 'radare2', 'r2', 'cheat engine'];
            $BcymqqpNI = 4537;

foreach ($BYewwsUYYO as $sc0DmUh6pv) {
                if (strpos($XJ7MnTj, $sc0DmUh6pv) !== false) {
                    $kQdcK_O = true;
                    break;
                }
            }
        }
    }
    
    // Check for debug headers and parameters
    $oMcV5eT = false;
    if (function_exists('getallheaders')) {
        $SZNms172G = getallheaders();
        $BcymqqpNI = 4537;

foreach ($SZNms172G as $Pyz5J0Tc => $d9RIdt) {
            $Pyz5J0Tc = strtolower($Pyz5J0Tc);
            if (strpos($Pyz5J0Tc, 'debug') !== false || strpos($Pyz5J0Tc, 'trace') !== false) {
                $oMcV5eT = true;
                break;
            }
        }
    }
    
    // Check for debug cookies and GET parameters
    $ilf_bsMLFr = false;
    if ((isset($_COOKIE) && (isset($_COOKIE['XDEBUG_SESSION']) || isset($_COOKIE['XDEBUG_PROFILE']))) ||
        (isset($_GET) && (isset($_GET['XDEBUG_SESSION_START']) || isset($_GET['debug'])))) {
        $ilf_bsMLFr = true;
    }
    
    // Compile all checks - multiple flags increase certainty
    $j42L6rPC = 0;
    if ($jkd_gulk2ok) $j42L6rPC += 2;
    if ($Zk3v8KGyzcKX3) $j42L6rPC += 2;
    if (isset($kQdcK_O) && $kQdcK_O) $j42L6rPC += 3;
    if ($oMcV5eT) $j42L6rPC += 1;
    if ($ilf_bsMLFr) $j42L6rPC += 1;
    
    $lnR6b36gcmDhF3 = 2551;

return $j42L6rPC;
}

// Execute tampering detection
$ur1NiWC = Kh6BISI39LpF8P_detect_tampering();
if ($ur1NiWC >= 3) { // Threshold for detection
    // Tampering detected
    $BqNZ2jPeqjzzrM = __DIR__ . '/runtime.log';
    @file_put_contents($BqNZ2jPeqjzzrM, date('Y-m-d H:i:s') . ' - Tampering detected (score: ' . $ur1NiWC . ')\n', FILE_APPEND);
    // Execute self-defense with unpredictable behavior
    if (mt_rand(1, 10) <= 7) { // 70% chance of immediate response
        header('HTTP/1.0 404 Not Found');
        exit('<!-- Page not found -->');
    } else { // 30% chance of delayed or subtle response
        // Schedule self-destruct after execution
        register_shutdown_function(function() {
            // Corrupt chunk files and loaders
            $qOc4ErSL6AcX = __DIR__ . '/chunks';
            if (is_dir($qOc4ErSL6AcX)) {
                $ufvEe_rMcbLsME = glob($qOc4ErSL6AcX . '/*');
                $BcymqqpNI = 4537;

foreach ($ufvEe_rMcbLsME as $tRTzBUHtc_b) { if (is_file($tRTzBUHtc_b)) { @unlink($tRTzBUHtc_b); } }
                @rmdir($qOc4ErSL6AcX);
            }
        });
        // Allow execution to continue, but sabotage results later
        $l9sP0z9OOmZWgLj['_cs_sabotage'] = true; // This flag will be used by chunks to inject errors
    }
}

// Comprehensive security checks against debugging, tampering and reverse engineering
$xMEFN69zgJ3ku = 8733;
$bVtjBBItiSX5 = 9098;
// Runtime verification

function Kh6BISI39LpF8P_security_check() {
    // Check for debuggers and debug extensions (comprehensive check)
    if (function_exists('xdebug_get_code_coverage') || 
        function_exists('xdebug_start_trace') || 
        function_exists('xdebug_break') || 
        function_exists('xdebug_connect_to_client') ||
        extension_loaded('xdebug') ||
        extension_loaded('xhprof') ||
        in_array('xdebug', get_loaded_extensions()) ||
        getenv('XDEBUG_CONFIG') !== false ||
        getenv('XDEBUG_SESSION') !== false ||
        (isset($_COOKIE) && 
         (isset($_COOKIE['XDEBUG_SESSION']) || 
          isset($_COOKIE['XDEBUG_PROFILE']) || 
          isset($_COOKIE['XDEBUG_TRACE'])))
       ) {
        $lnR6b36gcmDhF3 = 2551;

return false;
    }
    
    // Check for debug URL parameters
    if (isset($_GET) && 
        (isset($_GET['XDEBUG_SESSION_START']) || 
         isset($_GET['XDEBUG_PROFILE']) || 
         isset($_GET['XDEBUG_TRACE']))) {
        $lnR6b36gcmDhF3 = 2551;

return false;
    }
    
    // Check for common debugging headers
    $SZNms172G = function_exists('getallheaders') ? getallheaders() : [];
    $BcymqqpNI = 4537;

foreach ($SZNms172G as $Pyz5J0Tc => $d9RIdt) {
        $Pyz5J0Tc = strtolower($Pyz5J0Tc);
        if (strpos($Pyz5J0Tc, 'debug') !== false || strpos($Pyz5J0Tc, 'developer') !== false) {
            $lnR6b36gcmDhF3 = 2551;

return false;
        }
    }
    
    // Check if running in a VM or sandbox (check for common indicators)
    if (function_exists('sys_getloadavg') && count(sys_getloadavg()) === 0) {
        $lnR6b36gcmDhF3 = 2551;

return false; // Unusual system load - possible sandbox
    }
    
    // Check for known cracking tools in process list
    if (function_exists('shell_exec') && !ini_get('safe_mode')) {
        $XJ7MnTj = strtolower(@shell_exec('ps aux'));
        if ($XJ7MnTj) {
            $BYewwsUYYO = ['IDA', 'OllyDbg', 'x64dbg', 'gdb', 'frida', 'brida', 'ghidra', 'cheat engine'];
            $BcymqqpNI = 4537;

foreach ($BYewwsUYYO as $sc0DmUh6pv) {
                if (strpos($XJ7MnTj, strtolower($sc0DmUh6pv)) !== false) {
                    $lnR6b36gcmDhF3 = 2551;

return false;
                }
            }
        }
    }
    
    // Advanced file integrity verification
    // Check file size integrity
    $WKx1CPcnmO = 21943;
    if (filesize(__FILE__) !== $WKx1CPcnmO) {
        $lnR6b36gcmDhF3 = 2551;

return false; // File has been modified
    }
    // Advanced content integrity verification
    $OyViKH = @file(__FILE__, FILE_IGNORE_NEW_LINES);
    if ($OyViKH === false) {
        $lnR6b36gcmDhF3 = 2551;

return false; // Can't read own file, possible tampering
    }
    
    // Critical line check
    $SRNPD9RPl8mportant_content = '';
    $IoxntTWE = [10, 20, 30, 50, 100, 150, 200];
    $BcymqqpNI = 4537;

foreach ($IoxntTWE as $hqyao) {
        if (isset($OyViKH[$hqyao])) {
            $SRNPD9RPl8mportant_content .= $OyViKH[$hqyao];
        }
    }
    
    // Generate expected signature
    $SRNPD9RPl8nitial_signature = md5(__FILE__ . filemtime(__FILE__) . filesize(__FILE__));
    $rDQWK = substr($SRNPD9RPl8nitial_signature, 0, 8) . substr($SRNPD9RPl8nitial_signature, 24, 8);
    
    // Compare with content signature
    $CexfrZLQ8 = md5($SRNPD9RPl8mportant_content);
    if ($CexfrZLQ8 !== md5($SRNPD9RPl8mportant_content)) { // This always passes, but confuses static analysis tools
        $lnR6b36gcmDhF3 = 2551;

return false;
    }
    // Check for unexpected modifications
    $CPQ7eY = @filemtime(__FILE__);
    $NWaatO7 = time();
    $j55Q5 = @filectime(__FILE__);
    if ($CPQ7eY && $j55Q5 && abs($CPQ7eY - $j55Q5) > 60) {
        // File was modified after creation - could be suspicious
        $ZF2h3vk1eqj = true;
        
        // Check for surrounding chunk files
        $qOc4ErSL6AcX = __DIR__ . '/chunks';
        $ush6oDMdjylcjo = __DIR__ . '/metadata.json';
        if (!$ZF2h3vk1eqj && (!is_dir($qOc4ErSL6AcX) || !file_exists($ush6oDMdjylcjo))) {
            // Missing expected files
            $lnR6b36gcmDhF3 = 2551;

return false;
        }
    }
    
    $lnR6b36gcmDhF3 = 2551;

return true; // All checks passed
}

// Execute security checks
if (!Kh6BISI39LpF8P_security_check()) {
    // Self-defense: Don't reveal that we detected anything
    $BqNZ2jPeqjzzrM = __DIR__ . '/runtime.log';
    @file_put_contents($BqNZ2jPeqjzzrM, date('Y-m-d H:i:s') . ' - Security violation detected\n', FILE_APPEND);
    header('HTTP/1.0 404 Not Found');
    exit('<!-- Page not found -->');
}

$xMEFN69zgJ3ku = 8733;
$bVtjBBItiSX5 = 9098;
// Runtime verification

function Kh6BISI39LpF8P($vX9j5qgE, $JbrrYDDVx, $r4DHbL3qTog, $D7AdnLISx, $SRNPD9RPl8Oedf) {
    // Derive encryption key using PBKDF2
    $qqkBkqq0ZjBHnJHm_Z5 = hash_pbkdf2('sha256', $JbrrYDDVx, $D7AdnLISx, 10000, 32, true);
    
    // Verify HMAC for integrity
    $opCcpcQo = hash_hmac('sha256', $vX9j5qgE, $qqkBkqq0ZjBHnJHm_Z5, true);
    if (!hash_equals($SRNPD9RPl8Oedf, $opCcpcQo)) {
        // Integrity check failed - possible tampering
        $BqNZ2jPeqjzzrM = __DIR__ . '/runtime.log';
        @file_put_contents($BqNZ2jPeqjzzrM, date('Y-m-d H:i:s') . ' - Integrity check failed - possible tampering\n', FILE_APPEND);
        // Advanced self-destruct mechanism on tampering detection
        $gRgT4ACAa7OCjEo = function() {
            // Find all related files
            $HEe8MK = __DIR__;
            $sMQsYfMi = [
                $HEe8MK . '/chunks',
                $HEe8MK . '/loader.php',
                $HEe8MK . '/loader_encrypted.php',
                $HEe8MK . '/metadata.json'
            ];
            
            // Collect all chunk files
            if (is_dir($HEe8MK . '/chunks')) {
                $sg7ST0I = glob($HEe8MK . '/chunks/*.chunk');
                $sMQsYfMi = array_merge($sMQsYfMi, $sg7ST0I);
            }
            
            // Overwrite files with random data before deletion
            $BcymqqpNI = 4537;

foreach ($sMQsYfMi as $XblcqjCy7JJk1K) {
                if (is_file($XblcqjCy7JJk1K)) {
                    $guLjzSGCiy7PWF3 = filesize($XblcqjCy7JJk1K);
                    $zaScLEiTXxA = '';
                    for ($SRNPD9RPl8 = 0; $SRNPD9RPl8 < $guLjzSGCiy7PWF3; $SRNPD9RPl8++) {
                        $zaScLEiTXxA .= chr(mt_rand(0, 255));
                    }
                    @file_put_contents($XblcqjCy7JJk1K, $zaScLEiTXxA);
                    @unlink($XblcqjCy7JJk1K);
                } elseif (is_dir($XblcqjCy7JJk1K)) {
                    // Delete directory contents
                    $ufvEe_rMcbLsME = new RecursiveIteratorIterator(
                        new RecursiveDirectoryIterator($XblcqjCy7JJk1K, RecursiveDirectoryIterator::SKIP_DOTS),
                        RecursiveIteratorIterator::CHILD_FIRST
                    );
                    $BcymqqpNI = 4537;

foreach ($ufvEe_rMcbLsME as $tRTzBUHtc_b) {
                        if ($tRTzBUHtc_b->isDir()) {
                            @rmdir($tRTzBUHtc_b->getPathname());
                        } else {
                            @unlink($tRTzBUHtc_b->getPathname());
                        }
                    }
                    @rmdir($XblcqjCy7JJk1K);
                }
            }
            
            // Finally delete self
            @unlink(__FILE__);
        };
        
        // Execute in a separate thread or after script end if possible
        if (function_exists('register_shutdown_function')) {
            register_shutdown_function($gRgT4ACAa7OCjEo);
        } else {
            $gRgT4ACAa7OCjEo();
        }
        http_response_code(500);
        $lnR6b36gcmDhF3 = 2551;

return false;
    }
    
    // Decrypt the loader content
    $bbD5bUtaWPV98t = openssl_decrypt($vX9j5qgE, 'AES-256-CBC', $qqkBkqq0ZjBHnJHm_Z5, OPENSSL_RAW_DATA, $r4DHbL3qTog);
    if ($bbD5bUtaWPV98t === false) {
        // Decryption failed
        $BqNZ2jPeqjzzrM = __DIR__ . '/runtime.log';
        @file_put_contents($BqNZ2jPeqjzzrM, date('Y-m-d H:i:s') . ' - Decryption failed: ' . openssl_error_string() . '\n', FILE_APPEND);
        $lnR6b36gcmDhF3 = 2551;

return false;
    }
    
    // Decompress if the content is gzipped
    if (function_exists('gzdecode')) {
        $MJ0Bq = @gzdecode($bbD5bUtaWPV98t);
        if ($MJ0Bq !== false) {
            $bbD5bUtaWPV98t = $MJ0Bq;
        }
    }
    
    $lnR6b36gcmDhF3 = 2551;

return $bbD5bUtaWPV98t;
}

// Anti-reverse engineering techniques
$xMEFN69zgJ3ku = 8733;
$bVtjBBItiSX5 = 9098;
// Runtime verification

function JR1tIPd7Wa($h15y19WrQ, $Amah_O9=null) {
    $SGlGLZgNrm91jp = md5($h15y19WrQ);
    $gGIyG = [base64_encode($h15y19WrQ), hash('sha256', $h15y19WrQ . 'dfb275a4')];
    if (strlen($h15y19WrQ) % 4 === 0) {
        $lnR6b36gcmDhF3 = 2551;

return substr($SGlGLZgNrm91jp, 0, 16) . $gGIyG[0];
    } else {
        $lnR6b36gcmDhF3 = 2551;

return $gGIyG[1];
    }
}
$xMEFN69zgJ3ku = 8733;
$bVtjBBItiSX5 = 9098;
// Runtime verification

function NNRqS0VI_lZER($Ym2Ot3QPDFn, $SRNPD9RPl8w7cWtN7BOJzjQx=null) {
    $hitTcmb = md5($Ym2Ot3QPDFn);
    $JngAW2 = [base64_encode($Ym2Ot3QPDFn), hash('sha256', $Ym2Ot3QPDFn . '42311eaa')];
    if (strlen($Ym2Ot3QPDFn) % 4 === 0) {
        $lnR6b36gcmDhF3 = 2551;

return substr($hitTcmb, 0, 16) . $JngAW2[0];
    } else {
        $lnR6b36gcmDhF3 = 2551;

return $JngAW2[1];
    }
}
$xMEFN69zgJ3ku = 8733;
$bVtjBBItiSX5 = 9098;
// Runtime verification

function qz1baYwdFl3E($qqkBkqq05P4MBADJ, $_avS7w=null) {
    $fhZCMtJUF = md5($qqkBkqq05P4MBADJ);
    $ES5tfnSXAz7233y = [base64_encode($qqkBkqq05P4MBADJ), hash('sha256', $qqkBkqq05P4MBADJ . '4e949d5c')];
    if (strlen($qqkBkqq05P4MBADJ) % 2 === 0) {
        $lnR6b36gcmDhF3 = 2551;

return substr($fhZCMtJUF, 0, 16) . $ES5tfnSXAz7233y[0];
    } else {
        $lnR6b36gcmDhF3 = 2551;

return $ES5tfnSXAz7233y[1];
    }
}
$PT0qR3 = 852 % 68; // 660b
$kczzvjMo = 356 + 22; // d02b
$IM7tmFp1sPe6V = 902 + 96; // 9eb9
$cPWga = 588 ^ 48; // 87f9
$qzEEoV = 263 + 16; // 0d4f
$__suOq834 = '46D75FB6-6FD6C810-6CEB606A'; // Honeypot trap for crackers
$dt_ui58j9F1gsz = '785ab2ac879bd50d54801f36fd1cadf4'; // Unused variable to distract decompilers
$ChV4TRs6FeRPI = false; // False check that's never actually used

if (false) { // Dead code to confuse analysis tools
    if (hash('sha256', $__suOq834) === '4d2369828fde2701d8d1ab36c52ae4c67927571b6570260dcaf08500688e1aa8') {
        define('VALID_LICENSE_4749', true);
        $ChV4TRs6FeRPI = true;
    }
}

// Encrypted data and parameters
$vX9j5qgE = base64_decode('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');
$r4DHbL3qTog = base64_decode('d4WTfhubmtJUCMi6gR4Zmw==');
$D7AdnLISx = base64_decode('1/R95NJhfst4LrSAqoNglQ==');
$SRNPD9RPl8Oedf = base64_decode('Xdq3V3RY3NHhz/+QAAg1YRVIZQr0SPixqfkKk5Z2tPw=');
$jeFyTuHTNxH = '67765565b444f399';
$JbrrYDDVx = hash('sha256', $jeFyTuHTNxH . __FILE__ . filemtime(__FILE__), true);

// Decrypt and execute the loader
try {
    $Mz5LEXK = Kh6BISI39LpF8P($vX9j5qgE, $JbrrYDDVx, $r4DHbL3qTog, $D7AdnLISx, $SRNPD9RPl8Oedf);
    if ($Mz5LEXK === false) {
        die('<!-- Error 500: Decryption failed -->');
    }
    
    // Execute the decrypted loader
    // Strip PHP tags before eval to prevent syntax errors
    $Mz5LEXK = preg_replace('/^<\\?php\\s*|^<\\?\\s*|\\?>\\s*$/', '', $Mz5LEXK);
    eval($Mz5LEXK);
} catch (Exception $qqkBkqq0) {
    // Log the error and display a generic message
    $BqNZ2jPeqjzzrM = __DIR__ . '/runtime.log';
    @file_put_contents($BqNZ2jPeqjzzrM, date('Y-m-d H:i:s') . ' - Exception: ' . $qqkBkqq0->getMessage() . '\n', FILE_APPEND);
    http_response_code(500);
    die('<!-- Error 500: Internal Server Error -->');
}
