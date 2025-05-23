<?php
/**
 * ChunkShield License Functions
 * 
 * This file contains functions for handling license validation,
 * including loading license files, verifying licenses, and generating
 * license restrictions for runtime checks.
 */

// Include utilities if not already included
if (!function_exists('generate_random_string')) {
    require_once __DIR__ . '/utils.php';
}

/**
 * Loads a license key file
 * 
 * @param string $license_file Path to license file
 * @return array|bool License data or false on failure
 */
function load_license_file($license_file) {
    if (!file_exists($license_file)) {
        log_message("License file not found: $license_file", 'error');
        return false;
    }
    
    $license_content = file_get_contents($license_file);
    if ($license_content === false) {
        log_message("Failed to read license file: $license_file", 'error');
        return false;
    }
    
    // Check file extension
    $extension = strtolower(pathinfo($license_file, PATHINFO_EXTENSION));
    
    if ($extension === 'key' || $extension === 'json') {
        // Simple key file format (JSON)
        $license_data = json_decode($license_content, true);
        if ($license_data === null) {
            log_message("Invalid license key file format", 'error');
            return false;
        }
    } elseif ($extension === 'crt') {
        // Certificate file
        $cert_info = openssl_x509_parse($license_content);
        if ($cert_info === false) {
            log_message("Invalid certificate file", 'error');
            return false;
        }
        
        // Extract license information from certificate subject
        $license_data = [];
        
        if (isset($cert_info['subject'])) {
            if (isset($cert_info['subject']['CN'])) {
                $license_data['domain'] = explode(',', $cert_info['subject']['CN']);
            }
            
            if (isset($cert_info['subject']['OU'])) {
                $license_data['organization'] = $cert_info['subject']['OU'];
            }
        }
        
        // Get validity period
        if (isset($cert_info['validFrom_time_t']) && isset($cert_info['validTo_time_t'])) {
            $license_data['valid_from'] = $cert_info['validFrom_time_t'];
            $license_data['valid_to'] = $cert_info['validTo_time_t'];
        }
    } else {
        log_message("Unsupported license file type: $extension", 'error');
        return false;
    }
    
    return $license_data;
}

/**
 * Checks if a license is valid
 * 
 * @param array $license_data License data
 * @return bool True if license is valid, false otherwise
 */
function is_license_valid($license_data) {
    if (!is_array($license_data)) {
        return false;
    }
    
    // Check validity period
    if (isset($license_data['valid_from']) && isset($license_data['valid_to'])) {
        $current_time = time();
        
        if ($current_time < $license_data['valid_from']) {
            log_message("License not yet valid", 'error');
            return false;
        }
        
        if ($current_time > $license_data['valid_to']) {
            log_message("License has expired", 'error');
            return false;
        }
    }
    
    // Additional checks can be added here
    
    return true;
}

/**
 * Extracts domain restrictions from license data
 * 
 * @param array $license_data License data
 * @return array Domain restrictions
 */
function get_domain_restrictions($license_data) {
    $restrictions = [];
    
    if (isset($license_data['domain'])) {
        if (is_array($license_data['domain'])) {
            $restrictions['domain'] = $license_data['domain'];
        } else {
            $restrictions['domain'] = [$license_data['domain']];
        }
    }
    
    return $restrictions;
}

/**
 * Extracts IP restrictions from license data
 * 
 * @param array $license_data License data
 * @return array IP restrictions
 */
function get_ip_restrictions($license_data) {
    $restrictions = [];
    
    if (isset($license_data['ip'])) {
        if (is_array($license_data['ip'])) {
            $restrictions['ip'] = $license_data['ip'];
        } else {
            $restrictions['ip'] = [$license_data['ip']];
        }
    }
    
    return $restrictions;
}

/**
 * Extracts path restrictions from license data
 * 
 * @param array $license_data License data
 * @return array Path restrictions
 */
function get_path_restrictions($license_data) {
    $restrictions = [];
    
    if (isset($license_data['path'])) {
        if (is_array($license_data['path'])) {
            $restrictions['path'] = $license_data['path'];
        } else {
            $restrictions['path'] = [$license_data['path']];
        }
    }
    
    return $restrictions;
}

/**
 * Gets all restrictions from license data
 * 
 * @param array $license_data License data
 * @param array $check_options What to check (domain, ip, path)
 * @return array All restrictions
 */
function get_all_restrictions($license_data, $check_options = []) {
    // Default options
    $default_options = [
        'check_domain' => true,
        'check_ip' => true,
        'check_path' => true
    ];
    
    $options = array_merge($default_options, $check_options);
    $restrictions = [];
    
    if ($options['check_domain']) {
        $domain_restrictions = get_domain_restrictions($license_data);
        if (!empty($domain_restrictions)) {
            $restrictions = array_merge($restrictions, $domain_restrictions);
        }
    }
    
    if ($options['check_ip']) {
        $ip_restrictions = get_ip_restrictions($license_data);
        if (!empty($ip_restrictions)) {
            $restrictions = array_merge($restrictions, $ip_restrictions);
        }
    }
    
    if ($options['check_path']) {
        $path_restrictions = get_path_restrictions($license_data);
        if (!empty($path_restrictions)) {
            $restrictions = array_merge($restrictions, $path_restrictions);
        }
    }
    
    return $restrictions;
}

/**
 * Generates a license key file
 * 
 * @param array $license_data License data
 * @param string $output_file Output file path
 * @return bool True on success, false on failure
 */
function generate_license_key($license_data, $output_file) {
    // Ensure license data has at least basic fields
    if (!isset($license_data['valid_from'])) {
        $license_data['valid_from'] = time();
    }
    
    if (!isset($license_data['valid_to'])) {
        // Default to 1 year license
        $license_data['valid_to'] = strtotime('+1 year', $license_data['valid_from']);
    }
    
    // Add unique identifier if not present
    if (!isset($license_data['license_id'])) {
        $license_data['license_id'] = md5(uniqid(rand(), true));
    }
    
    // Add creation date
    $license_data['created_at'] = time();
    $license_data['created_date'] = date('Y-m-d H:i:s');
    
    // Convert timestamps to readable dates for display
    $license_data['valid_from_date'] = date('Y-m-d H:i:s', $license_data['valid_from']);
    $license_data['valid_to_date'] = date('Y-m-d H:i:s', $license_data['valid_to']);
    
    // Encode license data as JSON
    $license_content = json_encode($license_data, JSON_PRETTY_PRINT);
    
    // Create directory if it doesn't exist
    $dir = dirname($output_file);
    if (!file_exists($dir)) {
        if (!mkdir($dir, 0755, true)) {
            log_message("Failed to create directory: $dir", 'error');
            return false;
        }
    }
    
    // Write to file
    if (file_put_contents($output_file, $license_content) === false) {
        log_message("Failed to write license file: $output_file", 'error');
        return false;
    }
    
    log_message("License key file created: $output_file", 'info');
    return true;
}

/**
 * Creates a new license based on user inputs
 * 
 * @param array $license_data License data from user input
 * @param string $output_dir Directory to save license file
 * @return array Result with success status and message
 */
function create_license($license_data, $output_dir) {
    // Validate required fields
    if (!isset($license_data['customer_name']) || empty($license_data['customer_name'])) {
        return [
            'success' => false,
            'message' => 'Customer name is required'
        ];
    }
    
    // Generate a sanitized file name based on customer name
    $file_name = preg_replace('/[^a-zA-Z0-9_-]/', '_', $license_data['customer_name']);
    $file_name = strtolower($file_name) . '_' . date('Ymd') . '.key';
    $output_file = $output_dir . '/' . $file_name;
    
    // Set validity period
    if (!isset($license_data['valid_days']) || empty($license_data['valid_days'])) {
        $license_data['valid_days'] = 365; // Default to 1 year
    }
    
    $license_data['valid_from'] = time();
    $license_data['valid_to'] = strtotime('+' . intval($license_data['valid_days']) . ' days');
    
    // Generate license file
    if (generate_license_key($license_data, $output_file)) {
        return [
            'success' => true,
            'message' => 'License created successfully',
            'file' => $output_file,
            'license_data' => $license_data
        ];
    } else {
        return [
            'success' => false,
            'message' => 'Failed to create license file'
        ];
    }
}