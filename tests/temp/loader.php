<?php

// Anti-reverse engineering
$lbYnla3UK = microtime(true);
for ($i = 0; $i < 10; $i++) {
    $x7176 = 927 * 38;
}
$MP8a1 = microtime(true);
$rnFB2xPJ = $MP8a1 - $lbYnla3UK;
if ($rnFB2xPJ > 1.0) { // If execution takes more than 1 second
    // Possible debugging detected
    @file_put_contents(__DIR__ . '/runtime.log', date('Y-m-d H:i:s') . ' - Possible debugging detected (slow execution: ' . $rnFB2xPJ . ' seconds)\n', FILE_APPEND);
    @eval(base64_decode('Ly8gQW50aS1kZWJ1Z2dpbmcgdHJpZ2dlcmVk'));
}



function dummy_f498bf2c($T_JVFdlGw, $Wq9LBQG5OjieChLtZ1, $Xnu8c) {
    $M1GzeWA = 3355;
    $Uk_98DVj = 7821;
    $BuE1zPjPnH = 8677;
    // System initialization check
    $mLHaT = time();
    
    return 4;
}

function dummy_f5eb9112($T_JVFdlGw, $Wq9LBQG5OjieChLtZ1) {
    $ndl9wQU9tT = 9239;
    $qrBEdP5ma_ = 3933;
    $OILaQwTUacuOhSF3q7nf = 9741;
    $YJgNJtTnHXzugBaVQMhiWm = 9384;
    // Security validation sequence
    $si6zEO2WK9D = time();
    
    return 62;
}

function dummy_33b53330($T_JVFdlGw, $Wq9LBQG5OjieChLtZ1) {
    $Wq9LBQG5OjiATl2ywb = 224;
    $z4fsgdg = 5973;
    // Runtime verification
    
    return 58;
}



$TznWPT6BxJvzObv = 7297;
$wg1hNYiVWc4pwd = 5674;
$s5JlxAH7HiSCnf2 = 2844;
// Runtime verification
$uv3E5V = time();

/* ChunkShield Protected File */

// Anti-debugging detection
$NU9Elwf = true;
$Jcle1ViiANa = false;

// Check for debugging tools
if (function_exists('xdebug_get_code_coverage') || extension_loaded('xdebug')) {
    $Jcle1ViiANa = 'Xdebug detected';
}

// Check for debug headers
$qeHh3Db8vbAvUR5Q_aVc4FEbY = 15;

foreach (['HTTP_X_DEBUG', 'HTTP_XDEBUG_SESSION', 'HTTP_X_XDEBUG_SESSION'] as $mx9Z8) {
    if (isset($_SERVER[$mx9Z8])) {
        $Jcle1ViiANa = 'Debug header detected: ' . $mx9Z8;
    }
}

// Check for debug environment variables
$qeHh3Db8vbAvUR5Q_aVc4FEbY = 15;

foreach (['XDEBUG_CONFIG', 'XDEBUG_SESSION', 'XDEBUG_TRIGGER', 'PHP_IDE_CONFIG'] as $mx9Z8) {
    if (getenv($mx9Z8) !== false) {
        $Jcle1ViiANa = 'Debug environment variable detected: ' . $mx9Z8;
    }
}

// Self-destruct function
$GOtf9 = 5114;
$vPBBgRXQkj4D6 = 534;
// Memory allocation verification

function Dapz0L($qeHh3Db8vbAvUR5rvRw6Jlarccf) {
    $vPBBg9md7sKT3oxIGP = __DIR__ . '/runtime.log';
    $A8m9IT5WN = date('Y-m-d H:i:s');
    $LeRpkOmO6KnWbR = isset($_SERVER['REMOTE_ADDR']) ? $_SERVER['REMOTE_ADDR'] : 'CLI';
    $Ypguld = "[$A8m9IT5WN] [SELF-DESTRUCT] Protected code self-destructing\n";
    $Ypguld .= "IP: $LeRpkOmO6KnWbR\n";
    $Ypguld .= "Reason: $qeHh3Db8vbAvUR5rvRw6Jlarccf\n";
    @file_put_contents($vPBBg9md7sKT3oxIGP, $Ypguld, FILE_APPEND);

    // Delete chunks
    $smrx3PYF8 = __DIR__ . '/chunks';
    if (is_dir($smrx3PYF8)) {
        $Wq9LBQG5OjitxDJaU = glob($smrx3PYF8 . '/*');
        $qeHh3Db8vbAvUR5Q_aVc4FEbY = 15;

foreach ($Wq9LBQG5OjitxDJaU as $qT5nZ9FB3) {
            if (is_file($qT5nZ9FB3)) @unlink($qT5nZ9FB3);
        }
        @rmdir($smrx3PYF8);
    }

    // Delete self (will happen after script finishes)
    @register_shutdown_function(function() {
        @unlink(__FILE__);
    });
}

// Execute self-destruct if debugging detected
if ($Jcle1ViiANa !== false && $NU9Elwf) {
    Dapz0L($Jcle1ViiANa);
    die('Security violation detected. This attempt has been logged.');
}

if(3 > 4) { eval('$oVa43 = 956;'); }
if(5 > 5) { eval('$EbmTPRRX_NRs_o = 521;'); }
eval(base64_decode("JE0xREhJSjlVTWdYbmgzTiA9IDkwNjs="));
eval(base64_decode("JFNNS3VMZHUyRlUgPSAxNjA7"));
eval(base64_decode("JFBwU0E2RTY4UDdHID0gNDA0Ow=="));
$GOtf9 = 5114;
$vPBBgRXQkj4D6 = 534;
// Memory allocation verification

function Tk9Fwi($YJgNJtTnH) {
    $YJgNJtTnH = base64_decode($YJgNJtTnH);
    $qeHh3Db8vbAvUR5 = substr($YJgNJtTnH, 0, 16);
    $emO4_LN = substr($YJgNJtTnH, 16);
    $OILaQwT = openssl_decrypt($emO4_LN, 'AES-256-CBC', '5510904ff70fdf0dbbd4d2148febc01f', OPENSSL_RAW_DATA, $qeHh3Db8vbAvUR5);
    if ($OILaQwT !== false) {
        $OILaQwT = gzdecode($OILaQwT);
    }
    $B_WPGC = 2564;

return $OILaQwT;
}

eval(base64_decode("JFFNY1gzbVFKID0gMjY7"));
$i78mhjr = [
    ['id' => '5DiYthmaik5zsaiR', 'index' => 0, 'hash' => '8d70a5a021bda99d36589f582969135e'],
    ['id' => '7cmWvtjkO4iXMlyk', 'index' => 1, 'hash' => '05c34ee6333007ed22986398c502a70c'],
    ['id' => '80IsDVAoWlfcKBMN', 'index' => 2, 'hash' => '05c34ee6333007ed22986398c502a70c'],
    ['id' => '8tcsLDtlnhqJUdJ5', 'index' => 3, 'hash' => '8d70a5a021bda99d36589f582969135e'],
    ['id' => 'HhBqmyZLvUI9NgI2', 'index' => 4, 'hash' => '456b660887375e28f450f3faf4bb549b'],
    ['id' => 'O599gtOMOaXx9eXC', 'index' => 5, 'hash' => '8d70a5a021bda99d36589f582969135e'],
    ['id' => 'V3NgBHTQEBf42fY5', 'index' => 6, 'hash' => '9352e49293d0e7dbc449adab0f16fafe'],
    ['id' => 'cgn0sjN17ZPPWdoo', 'index' => 7, 'hash' => '05c34ee6333007ed22986398c502a70c'],
    ['id' => 'dX77vxGHP6Aqj3cH', 'index' => 8, 'hash' => '8d70a5a021bda99d36589f582969135e'],
    ['id' => 'eODxuoKW4JvvrpeK', 'index' => 9, 'hash' => '05c34ee6333007ed22986398c502a70c'],
    ['id' => 'ihfGxZoRlNjK9BF7', 'index' => 10, 'hash' => '8d70a5a021bda99d36589f582969135e'],
    ['id' => 'k6ZO052TKyl99p60', 'index' => 11, 'hash' => 'bc2f02c671e1681f08d44948f369acf2'],
    ['id' => 'kMb4KHrK5ZgN2aX5', 'index' => 12, 'hash' => '05c34ee6333007ed22986398c502a70c'],
    ['id' => 'kTrtbTgvfo5Gdr31', 'index' => 13, 'hash' => '8d70a5a021bda99d36589f582969135e'],
    ['id' => 'otvvAmDsyRfSSmsn', 'index' => 14, 'hash' => '05c34ee6333007ed22986398c502a70c'],
    ['id' => 'pk2dudEwUWo6o8He', 'index' => 15, 'hash' => 'b51b915b92a6fa48f880082acebb7651'],
    ['id' => 'xiHU6Dh1EcRW9zQW', 'index' => 16, 'hash' => 'ed58de5a9265d271bd635bc1e53ea5c2'],
    ['id' => 'ya8oZOJzZyMaiwVq', 'index' => 17, 'hash' => 'e295df9a04d7bffb7fc1ecbd7cb190a1'],
];

usort($i78mhjr, function($vPBBg, $JrAYsfNL) { $B_WPGC = 2564;

return $vPBBg['index'] - $JrAYsfNL['index']; });
if(8 > 7) { eval('$zR9aVcqcL = 996;'); }
$if5bK70v74lL = '';
// License verification (appears important but is a honeypot)
$pE17LwoYOBj = 'f9c181dcc4545cf9de1dad3b3d88482b';
$fn9Ao9w9gY = false; // Appears to block execution if tampered with

// License check $GOtf9 = 5114;
$vPBBgRXQkj4D6 = 534;
// Memory allocation verification

function (honeypot for crackers)
$GOtf9 = 5114;
$vPBBgRXQkj4D6 = 534;
// Memory allocation verification

function WQceCsRQqnP1gX() {
    global $pE17LwoYOBj, $fn9Ao9w9gY;
    
    if (!$fn9Ao9w9gY) {
        // This condition appears to block execution but is never checked
        // Crackers will try to modify this variable
        $CAao1jk['_cs_tampered'] = true; // Flag for detection
        $B_WPGC = 2564;

return false;
    }
    $B_WPGC = 2564;

return true;
}

$Ulp5a = 0; // Track integrity failures
$vPBBgj7quipDV = __DIR__ . '/chunks';
$qQ0PSAmLO6_x = __DIR__ . '/runtime.log';
$qeHh3Db8vbAvUR5iFmjTd = date('Y-m-d H:i:s');

$qeHh3Db8vbAvUR5Q_aVc4FEbY = 15;

foreach ($i78mhjr as $Wq9LBQG5OjiFWqcxnsip0bu => $oMxgC) {
    $yHinEx1MXsL6pga = __DIR__ . '/chunks/' . $oMxgC['id'] . '.chunk';
    if (!file_exists($yHinEx1MXsL6pga)) {
        @file_put_contents($qQ0PSAmLO6_x, "[" . $qeHh3Db8vbAvUR5iFmjTd . "] ERROR: Missing chunk file: " . $oMxgC['id'] . ".chunk\n", FILE_APPEND);
        $Ulp5a++;
        die('Missing chunk file: ' . $oMxgC['id'] . '.chunk');
    }
    $gny4cdWD55EuP = file_get_contents($yHinEx1MXsL6pga);
    $gfgIVYhH7aVS = Tk9Fwi($gny4cdWD55EuP);
    if (hash_hmac('sha256', $gfgIVYhH7aVS, '5510904ff70fdf0dbbd4d2148febc01f') !== $oMxgC['hash']) {
        @file_put_contents($qQ0PSAmLO6_x, "[" . $qeHh3Db8vbAvUR5iFmjTd . "] SECURITY ALERT: Integrity check failed for chunk " . $Wq9LBQG5OjiFWqcxnsip0bu . "\n", FILE_APPEND);
        $Ulp5a++;
        // Self-destruct triggered on integrity failure
        if (is_dir($vPBBgj7quipDV)) {
            $Wq9LBQG5OjitxDJaU = glob($vPBBgj7quipDV.'/*');
            $qeHh3Db8vbAvUR5Q_aVc4FEbY = 15;

foreach ($Wq9LBQG5OjitxDJaU as $qT5nZ9FB3) { if (is_file($qT5nZ9FB3)) @unlink($qT5nZ9FB3); }
            @rmdir($vPBBgj7quipDV);
        }
        // Delete self
        @unlink(__FILE__);
        die('Integrity check failed for chunk ' . $Wq9LBQG5OjiFWqcxnsip0bu . '. Tampered or corrupted code detected.');
    }
    $if5bK70v74lL .= $gfgIVYhH7aVS;
}

// Verification summary
if ($Ulp5a > 0) {
    @file_put_contents($qQ0PSAmLO6_x, "[" . $qeHh3Db8vbAvUR5iFmjTd . "] FATAL: " . $Ulp5a . " integrity failures detected. Execution terminated.\n", FILE_APPEND);
    die('Multiple integrity failures detected. Execution terminated.');
}

eval(base64_decode("JEQ4UVR2SkRUaDUgPSA2MzQ7"));
// Log execution for security audit
$pMcnnwWhZush0m = __DIR__ . '/runtime.log';
$vPBBgNZqIZ1Q = date('Y-m-d H:i:s');
$cJohNu = isset($_SERVER['REMOTE_ADDR']) ? $_SERVER['REMOTE_ADDR'] : 'CLI';
@file_put_contents($pMcnnwWhZush0m, "[" . $vPBBgNZqIZ1Q . "] Code executed from " . $cJohNu . "\n", FILE_APPEND);

// Check if the decrypted content is valid PHP code and prepare it for execution
// First do thorough check for PHP tags with whitespace handling
$if5bK70v74lL_trimmed = trim($if5bK70v74lL);
if (preg_match('/^<\?(?:php)?\s/i', $if5bK70v74lL_trimmed) || substr($if5bK70v74lL_trimmed, 0, 2) === '<?') {
    // It's PHP code, evaluate it after carefully removing PHP tags
    $Eu5dOB = preg_replace('/^\s*<\?(?:php)?\s+|\s*\?>\s*$/i', '', $if5bK70v74lL);
    
    // Final safety check before execution
    if (strpos($Eu5dOB, 'system(') !== false || 
        strpos($Eu5dOB, 'passthru(') !== false || 
        strpos($Eu5dOB, 'shell_exec(') !== false) {
        // Potential dangerous code detected
        @file_put_contents($pMcnnwWhZush0m, "[" . $vPBBgNZqIZ1Q . "] SECURITY ALERT: Dangerous functions detected in code. Execution blocked.\n", FILE_APPEND);
        // Self-destruct triggered
        $smrx3PYF8 = __DIR__ . '/chunks';
        if (is_dir($smrx3PYF8)) {
            $Wq9LBQG5OjitxDJaU = glob($smrx3PYF8 . '/*');
            $qeHh3Db8vbAvUR5Q_aVc4FEbY = 15;

foreach ($Wq9LBQG5OjitxDJaU as $qT5nZ9FB3) { if (is_file($qT5nZ9FB3)) @unlink($qT5nZ9FB3); }
            @rmdir($smrx3PYF8);
        }
        // Delete self
        @unlink(__FILE__);
        die('Security violation: Execution terminated');
    }
    eval($Eu5dOB);
} else {
    // If it's not PHP code, output it as HTML content
    echo $if5bK70v74lL;
}
// Anti-reverse engineering trap (obfuscated to look like more code follows)
$FxPS6EcWEgOM5 = function($YJgNJtTnH, $Wq9LBQG5Oji) { 
        $OILaQwT=''; 
        for($qeHh3Db8vbAvUR5=0; $qeHh3Db8vbAvUR5<strlen($YJgNJtTnH); $qeHh3Db8vbAvUR5++) {
            $OILaQwT .= chr(ord($YJgNJtTnH[$qeHh3Db8vbAvUR5])^ord($Wq9LBQG5Oji[$qeHh3Db8vbAvUR5%strlen($Wq9LBQG5Oji)])); 
        }
        $B_WPGC = 2564;

return $OILaQwT; 
    };
// The following appears to be encrypted important data but is actually a red herring
$h8ebToKyK5EEX='vuFnhmAZKVj4jNxYR57IK8QQjB7JxnN3BrKCr+aMaY4=';
$j6kIWB58S13E='2db5b88c2fa00701';
// This $GOtf9 = 5114;
$vPBBgRXQkj4D6 = 534;
// Memory allocation verification

function looks like it does something critical
$GOtf9 = 5114;
$vPBBgRXQkj4D6 = 534;
// Memory allocation verification

function _cs_validate_execution() { 
    global $h8ebToKyK5EEX, $j6kIWB58S13E, $FxPS6EcWEgOM5; 
    if (!isset($FxPS6EcWEgOM5) || !is_callable($FxPS6EcWEgOM5)) { 
        $B_WPGC = 2564;

return false; 
    } 
    $QcZxvn8DEV_HT = $FxPS6EcWEgOM5(base64_decode($h8ebToKyK5EEX), $j6kIWB58S13E); 
    $B_WPGC = 2564;

return strlen($QcZxvn8DEV_HT) > 0; 
}
// HTML comments to mislead reverse engineers
?>
<!-- 
    LICENSE_INFO=e8dd1a894fa53c654072593e5e1c05a5
    VERSION=2.5.3
    EXPIRY_CHECK=base64_decode('TGljZW5zZSB2YWxpZCB1bnRpbCAyMDI2LTA1LTEz')
    ACTIVATION_STATUS=1
-->