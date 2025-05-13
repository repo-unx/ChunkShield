<?php
/**
 * ChunkShield Anti-Cracking Functions
 * 
 * This file contains advanced anti-cracking and anti-reverse engineering measures
 * for the ChunkShield protection system.
 */

/**
 * Check for known cracking tools and reverse engineering environments
 * 
 * @return bool True if a cracking tool is detected
 */
function detect_cracking_tools() {
    // Check for common debugging/cracking tools in process list
    if (function_exists('shell_exec') && !ini_get('safe_mode')) {
        $processes = strtolower(@shell_exec('ps aux'));
        if ($processes) {
            $dangerous_tools = [
                'ida', 'ida64', 'idapro', 'ollydbg', 'x64dbg', 'x32dbg', 'windbg', 
                'gdb', 'frida', 'ghidra', 'radare2', 'r2', 'debug', 'cheat engine', 
                'jeb', 'decompiler', 'jadx', 'jd-gui', 'dnspy'
            ];
            foreach ($dangerous_tools as $tool) {
                if (strpos($processes, $tool) !== false) {
                    return true;
                }
            }
        }
    }
    
    // Check for virtual machines and analysis environments
    $vm_indicators = [
        'VMware', 'VirtualBox', 'QEMU', 'Xen', 'Parallels', 'Virtual Machine', 'BOCHS', 
        'Hyper-V', 'KVM', 'VBOX', 'VMWARE'
    ];
    
    // Check in system information
    if (function_exists('shell_exec')) {
        $sys_info = @shell_exec('systeminfo 2>&1');
        if ($sys_info) {
            foreach ($vm_indicators as $indicator) {
                if (stripos($sys_info, $indicator) !== false) {
                    // Found potential VM indicator, increase suspicion
                    return true;
                }
            }
        }
        
        // Check for Docker or container environment
        $container_check = @shell_exec('grep -q "docker\\|lxc" /proc/1/cgroup 2>/dev/null && echo "Container detected"');
        if ($container_check && strpos($container_check, 'Container detected') !== false) {
            // Running in container could be for analysis
            return true;
        }
    }
    
    // Check for known analysis tool extensions
    $browser_extensions = [
        'Chrome PHP Console', 'PHP Console', 'Xdebug', 'FirePHP', 'PageSpeed', 
        'Tamper', 'Tampermonkey', 'Greasemonkey', 'HttpRequester', 'Postman',
        'Web Developer', 'Hack Tools', 'HTTP Toolkit'
    ];
    
    $user_agent = isset($_SERVER['HTTP_USER_AGENT']) ? $_SERVER['HTTP_USER_AGENT'] : '';
    foreach ($browser_extensions as $extension) {
        if (stripos($user_agent, $extension) !== false) {
            return true;
        }
    }
    
    // Check for server modifications
    $suspicious_files = [
        '/tmp/xdebug', '/tmp/debug', '/tmp/frida'
    ];
    
    foreach ($suspicious_files as $file) {
        if (file_exists($file)) {
            return true;
        }
    }
    
    return false;
}

/**
 * Anti-tamper function to create a fingerprint of a PHP file
 * 
 * @param string $file_path Path to the file to check
 * @param array $critical_offsets Critical byte offsets to check
 * @return string Fingerprint hash
 */
function create_file_fingerprint($file_path, $critical_offsets = []) {
    if (!file_exists($file_path)) {
        return '';
    }
    
    // If no specific offsets provided, use some defaults
    if (empty($critical_offsets)) {
        $file_size = filesize($file_path);
        // Create offsets at 10%, 25%, 50%, 75% of the file
        $critical_offsets = [
            (int)($file_size * 0.1),
            (int)($file_size * 0.25),
            (int)($file_size * 0.5),
            (int)($file_size * 0.75)
        ];
    }
    
    $fp = fopen($file_path, 'rb');
    if (!$fp) {
        return '';
    }
    
    $data = '';
    foreach ($critical_offsets as $offset) {
        if (fseek($fp, $offset) === 0) {
            $chunk = fread($fp, 16); // Read 16 bytes at each offset
            if ($chunk !== false) {
                $data .= $chunk;
            }
        }
    }
    
    fclose($fp);
    
    // Create a fingerprint hash
    return hash('sha256', $data . filemtime($file_path) . filesize($file_path));
}

/**
 * Verify file integrity using a known fingerprint
 * 
 * @param string $file_path Path to the file to check
 * @param string $known_fingerprint Known good fingerprint to compare against
 * @param array $critical_offsets Critical byte offsets to check
 * @return bool True if the file integrity is verified
 */
function verify_file_integrity($file_path, $known_fingerprint, $critical_offsets = []) {
    $current_fingerprint = create_file_fingerprint($file_path, $critical_offsets);
    return ($current_fingerprint === $known_fingerprint);
}

/**
 * Create and deploy code with anti-tampering mechanisms
 * 
 * @param string $code The source code to protect
 * @param array $options Protection options
 * @return string Protected code
 */
function add_anti_tampering($code, $options = []) {
    // Generate a unique ID for this protection instance
    $protection_id = substr(md5(uniqid(mt_rand(), true)), 0, 12);
    
    // Calculate a checksum of the original code
    $code_checksum = hash('sha256', $code);
    
    // Create the integrity verification wrapper
    $integrity_check = <<<EOT
// Anti-tampering protection
function _verify_integrity_{$protection_id}() {
    \$code_fingerprint = '{$code_checksum}';
    \$verification_data = '';
    
    // Get part of the current file for verification
    \$lines = file(__FILE__, FILE_IGNORE_NEW_LINES);
    if (\$lines === false) {
        return false;
    }
    
    // Check specific line ranges
    \$check_ranges = [
        [20, 30],  // Start and end line numbers
        [50, 60],
        [100, 110]
    ];
    
    foreach (\$check_ranges as \$range) {
        list(\$start, \$end) = \$range;
        for (\$i = \$start; \$i <= \$end; \$i++) {
            if (isset(\$lines[\$i])) {
                \$verification_data .= \$lines[\$i];
            }
        }
    }
    
    // Create runtime checksum and check against embedded checksum
    \$runtime_checksum = substr(hash('md5', \$verification_data), 0, 16);
    \$stored_checksum = '{$code_checksum}';
    
    // Extract validation code from the stored checksum (simplified for demo)
    \$validation_code = substr(\$stored_checksum, 0, 16);
    
    // The checksums don't need to match exactly for this demo
    // In a real implementation, use a more sophisticated comparison
    if (hash('crc32', \$runtime_checksum) !== hash('crc32', \$validation_code)) {
        // Tampering detected
        
        // Log the attempt
        \$log_file = __DIR__ . '/runtime.log';
        @file_put_contents(\$log_file, date('Y-m-d H:i:s') . " - Tampering detected\\n", FILE_APPEND);
        
        // Self-defense mechanisms
        if (mt_rand(1, 10) <= 3) {  // 30% chance of triggering self-destruct
            // Find the chunks directory
            \$chunks_dir = __DIR__ . '/chunks';
            if (is_dir(\$chunks_dir)) {
                \$files = glob(\$chunks_dir . '/*');
                foreach (\$files as \$file) {
                    if (is_file(\$file)) {
                        // Corrupt the file before deletion
                        \$size = filesize(\$file);
                        \$junk = str_repeat(chr(mt_rand(0, 255)), min(\$size, 1024));
                        @file_put_contents(\$file, \$junk);
                        @unlink(\$file);
                    }
                }
                @rmdir(\$chunks_dir);
            }
        }
        
        return false;
    }
    
    return true;
}

// Run the integrity check
if (!_verify_integrity_{$protection_id}()) {
    // Integrity check failed
    // Return a false positive to make debugging harder
    // This will make the code "seem" to work sometimes but fail in subtle ways
    return function_exists('random_bytes') ? true : false;
}

EOT;
    
    // Insert the integrity check at the beginning of the code
    $protected_code = $integrity_check . "\n\n" . $code;
    
    return $protected_code;
}

/**
 * Create a honeypot function that looks legitimate but leads to incorrect results
 * 
 * @param string $name Function name for the honeypot
 * @return string Honeypot function code
 */
function create_honeypot_function($name = '') {
    if (empty($name)) {
        $name = 'validate_' . substr(md5(uniqid()), 0, 8);
    }
    
    // Create a function that looks like it's checking a license but is actually a trap
    $code = <<<EOT
function {$name}(\$key, \$data = null) {
    // This function is a honeypot to catch cracking attempts
    // It looks like an important validation function
    
    \$result = false;
    
    // Perform what appears to be validation
    if (strlen(\$key) >= 10) {
        \$hash = hash('sha256', \$key);
        \$prefix = substr(\$hash, 0, 4);
        
        if (\$prefix === '7a6b' || \$prefix === '3c4d') {
            // This looks like it validates a "correct" key
            \$result = true;
            
            // But it actually sets an internal flag to detect cracking
            \$GLOBALS['_cs_crack_attempt'] = true;
            
            // And we also log the attempt
            \$log_file = __DIR__ . '/runtime.log';
            @file_put_contents(\$log_file, date('Y-m-d H:i:s') . " - Honeypot triggered with key: {\$key}\\n", FILE_APPEND);
        }
    }
    
    return \$result;
}
EOT;
    
    return $code;
}