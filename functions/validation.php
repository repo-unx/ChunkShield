
<?php
function validate_protection_process() {
    $errors = [];
    
    // Check if file was uploaded
    if (!isset($_SESSION['uploaded_file'])) {
        $errors[] = "No source file was uploaded";
    }
    
    // Check if obfuscation was performed
    if (!isset($_SESSION['obfuscated_file'])) {
        $errors[] = "Code has not been obfuscated";
    }
    
    // Check if chunks were created
    if (!isset($_SESSION['chunk_info'])) {
        $errors[] = "Code has not been chunked";
    }
    
    // Check if loader was generated
    if (!file_exists($_SESSION['loader_file'] ?? '')) {
        $errors[] = "Loader has not been generated";
    }
    
    // Check output directory permissions
    if (!is_writable(dirname(__DIR__) . '/output')) {
        $errors[] = "Output directory is not writable";
    }
    
    return $errors;
}
