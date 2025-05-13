<?php
/**
 * ChunkShield Code Validation Function
 * 
 * This file contains the function to validate obfuscated code
 * for syntax errors and other issues.
 */

// Include CodeValidator class if not already included
if (!class_exists('CodeValidator')) {
    $validator_path = dirname(__DIR__) . '/tests/CodeValidator.php';
    if (file_exists($validator_path)) {
        require_once $validator_path;
    }
}

/**
 * Validate obfuscated code for syntax errors
 *
 * @param string $code The PHP code to validate
 * @param int $level Validation level (1-4)
 * @return bool True if code is valid
 */
function validate_obfuscated_code($code, $level = 1) {
    // Check if CodeValidator class exists
    if (!class_exists('CodeValidator')) {
        // Log that validator is not available
        if (function_exists('log_message')) {
            log_message("CodeValidator class not found, skipping validation", 'warning');
        }
        return true; // Continue without validation
    }
    
    // Create validator instance
    $validator = new CodeValidator();
    
    // Validate code with specified level
    $isValid = $validator->validateCode($code, $level);
    
    // If not valid, log the errors
    if (!$isValid) {
        $results = $validator->getResults();
        $errors = $results['errors'];
        
        if (function_exists('log_message')) {
            log_message("Code validation failed: " . json_encode($errors), 'error');
        } else {
            error_log("Code validation failed: " . json_encode($errors));
        }
        
        // For automated testing, we should return false but not break production
        return false;
    }
    
    // Code is valid
    if (function_exists('log_message')) {
        log_message("Code validation passed", 'info');
    }
    
    return true;
}

/**
 * Run code validator on a file
 *
 * @param string $file Path to the file to validate
 * @param int $level Validation level (1-4)
 * @return array Validation results
 */
function validate_php_file($file, $level = 1) {
    if (!class_exists('CodeValidator')) {
        return [
            'valid' => false,
            'error' => 'CodeValidator class not found'
        ];
    }
    
    if (!file_exists($file)) {
        return [
            'valid' => false,
            'error' => 'File not found: ' . $file
        ];
    }
    
    $validator = new CodeValidator();
    $isValid = $validator->validateFile($file, $level);
    $results = $validator->getResults();
    
    return [
        'valid' => $isValid,
        'results' => $results
    ];
}