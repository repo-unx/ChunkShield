<?php
/**
 * Main entry point for the PHP Code Obfuscation & Licensing Tool
 */

// Ensure no whitespace or output before this point to prevent "headers already sent" errors
// Disable error reporting during session start and header operations
error_reporting(0);

// Initialize session
session_start();

// Restore error reporting
error_reporting(E_ALL);

// Include necessary files
require_once __DIR__ . '/../tools/utils.php';

// Create necessary directories if they don't exist
$directories = [
    __DIR__ . '/../output/obfuscated',
    __DIR__ . '/../output/chunks',
    __DIR__ . '/../output/map',
    __DIR__ . '/../output/licenses',
    __DIR__ . '/../logs'
];

foreach ($directories as $dir) {
    if (!file_exists($dir)) {
        mkdir($dir, 0755, true);
    }
}

// Create assets directory for CSS and JS
$assetsDirectories = [
    __DIR__ . '/assets',
    __DIR__ . '/assets/css',
    __DIR__ . '/assets/js',
    __DIR__ . '/assets/img'
];

foreach ($assetsDirectories as $dir) {
    if (!file_exists($dir)) {
        mkdir($dir, 0755, true);
    }
}

// Create symlinks for assets if they don't exist
if (!file_exists(__DIR__ . '/assets/css/custom.css')) {
    symlink(__DIR__ . '/../web/assets/css/custom.css', __DIR__ . '/assets/css/custom.css');
}

if (!file_exists(__DIR__ . '/assets/js/main.js')) {
    symlink(__DIR__ . '/../web/assets/js/main.js', __DIR__ . '/assets/js/main.js');
}

// Handle special actions - Keep all redirects early in the script
$redirect = false;
$redirect_url = '';

if (isset($_GET['action'])) {
    switch ($_GET['action']) {
        case 'clear_all':
            // Clear all session data
            session_unset();
            $_SESSION['success'] = 'All data has been cleared. You can start a new project.';
            $redirect = true;
            $redirect_url = 'index.php?tab=upload';
            break;
    }
}

// Handle redirects early before any output
if ($redirect) {
    header('Location: ' . $redirect_url);
    exit;
}

// Default tab is upload
$current_tab = isset($_GET['tab']) ? $_GET['tab'] : 'upload';

// Handle form submissions - Processing early before any output
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {
    // Buffer any potential output during processing
    ob_start();
    
    switch ($_POST['action']) {
        case 'upload':
            if (isset($_FILES['phpFile']) && $_FILES['phpFile']['error'] === UPLOAD_ERR_OK) {
                $uploadedFile = $_FILES['phpFile']['tmp_name'];
                $originalName = $_FILES['phpFile']['name'];
                
                // Validate file is PHP
                $fileInfo = pathinfo($originalName);
                if (strtolower($fileInfo['extension']) !== 'php') {
                    $_SESSION['error'] = 'Only PHP files are allowed.';
                    header('Location: index.php?tab=upload');
                    exit;
                }
                
                // Save to session for processing
                $_SESSION['uploaded_file'] = file_get_contents($uploadedFile);
                $_SESSION['original_filename'] = $originalName;
                $_SESSION['success'] = 'File uploaded successfully.';
                
                // Redirect to obfuscate tab
                header('Location: index.php?tab=obfuscate');
                exit;
            } else {
                $_SESSION['error'] = 'Error uploading file. ' . getUploadErrorMessage($_FILES['phpFile']['error']);
                header('Location: index.php?tab=upload');
                exit;
            }
            break;
            
        case 'obfuscate':
            // Process obfuscation
            require_once __DIR__ . '/../tools/obfuscator.php';
            
            $code = $_SESSION['uploaded_file'];
            $filename = $_SESSION['original_filename'];
            
            $options = [
                'rename_variables' => isset($_POST['rename_variables']),
                'remove_whitespace' => isset($_POST['remove_whitespace']),
                'encode_strings' => isset($_POST['encode_strings'])
            ];
            
            $obfuscated = obfuscateCode($code, $options);
            
            // Save obfuscated file
            $outputPath = __DIR__ . '/../output/obfuscated/' . pathinfo($filename, PATHINFO_FILENAME) . '.obf.php';
            file_put_contents($outputPath, $obfuscated);
            
            $_SESSION['obfuscated_file'] = $outputPath;
            $_SESSION['success'] = 'Code obfuscated successfully.';
            
            header('Location: index.php?tab=chunk');
            exit;
            break;
            
        case 'chunk':
            // Process chunking
            require_once __DIR__ . '/../tools/chunker.php';
            
            $obfuscatedPath = $_SESSION['obfuscated_file'];
            $code = file_get_contents($obfuscatedPath);
            $filename = basename($obfuscatedPath);
            
            $chunkSize = isset($_POST['chunk_size']) ? (int)$_POST['chunk_size'] : 5; // Default 5KB
            $encryptChunks = isset($_POST['encrypt_chunks']);
            $encryptionKey = $_POST['encryption_key'] ?? generateRandomString(32);
            
            $chunksInfo = createChunks($code, $chunkSize, $encryptChunks, $encryptionKey);
            
            $_SESSION['chunks_info'] = $chunksInfo;
            $_SESSION['encryption_key'] = $encryptionKey;
            $_SESSION['success'] = 'Code chunked and encrypted successfully.';
            
            header('Location: index.php?tab=loader');
            exit;
            break;
            
        case 'generate_loader':
            // Generate loader
            require_once __DIR__ . '/../tools/loader_generator.php';
            
            $chunksInfo = $_SESSION['chunks_info'];
            $encryptionKey = $_SESSION['encryption_key'];
            
            // Get all protection options
            $options = [
                'license_check' => isset($_POST['license_check']),
                'anti_logger' => isset($_POST['anti_logger']),
                'anti_debugger' => isset($_POST['anti_debugger'])
            ];
            
            $loader = generateLoader($chunksInfo, $encryptionKey, $options);
            
            // Save loader file
            $loaderPath = __DIR__ . '/../output/loader.php';
            file_put_contents($loaderPath, $loader);
            
            $_SESSION['loader_file'] = $loaderPath;
            $_SESSION['success'] = 'Loader generated successfully.';
            
            // If license check was selected, redirect to license tab
            if ($licenseCheck) {
                header('Location: index.php?tab=license');
            } else {
                header('Location: index.php?tab=output');
            }
            exit;
            break;
            
        case 'generate_license':
            // Generate license or certificate
            require_once __DIR__ . '/../tools/license_generator.php';
            
            // Collect license data from the form
            $licenseData = [
                'license_type' => $_POST['license_type'] ?? 'license',
                'license_key' => $_POST['license_key'] ?? generateRandomString(20),
                'license_domain' => $_POST['license_domain'] ?? 'example.com',
                'license_expiry' => $_POST['license_expiry'] ?? date('Y-m-d', strtotime('+1 year'))
            ];
            
            // Add certificate-specific fields if applicable
            if ($licenseData['license_type'] === 'certificate') {
                $licenseData['cert_issuer'] = $_POST['cert_issuer'] ?? '';
                $licenseData['cert_type'] = $_POST['cert_type'] ?? 'standard';
            }
            
            // Security options
            $securityOptions = [
                'encrypt' => isset($_POST['encrypt_license']),
                'add_signature' => isset($_POST['add_signature']),
                'add_hashing' => isset($_POST['add_hashing']),
                'security_level' => (int)($_POST['security_level'] ?? 2)
            ];
            
            // Generate the license
            $result = generateLicense($licenseData, $securityOptions);
            
            if ($result['success']) {
                $_SESSION['license_result'] = $result;
                $_SESSION['success'] = 'License generated successfully.';
                
                // Create a zip package with license and verification files
                $zipFile = createLicensePackage([
                    $result['license_file'],
                    $result['verification_file']
                ], $result['license_id']);
                
                if ($zipFile) {
                    $_SESSION['license_package'] = $zipFile;
                }
                
                header('Location: index.php?tab=output');
            } else {
                $_SESSION['error'] = 'Error generating license: ' . $result['message'];
                header('Location: index.php?tab=license');
            }
            // Clear buffer and exit
            ob_end_clean();
            exit;
            break;
    }
    
    // End buffer if we didn't redirect
    ob_end_clean();
}

// Include header and current tab content
include __DIR__ . '/views/header.php';

switch ($current_tab) {
    case 'upload':
        include __DIR__ . '/views/upload.php';
        break;
    case 'obfuscate':
        include __DIR__ . '/views/obfuscate.php';
        break;
    case 'chunk':
        include __DIR__ . '/views/chunk.php';
        break;
    case 'loader':
        include __DIR__ . '/views/loader.php';
        break;
    case 'license':
        include __DIR__ . '/views/license.php';
        break;
    case 'output':
        include __DIR__ . '/views/output.php';
        break;
    case 'help':
        include __DIR__ . '/views/help.php';
        break;
    case 'docs':
        include __DIR__ . '/views/docs.php';
        break;
    default:
        include __DIR__ . '/views/upload.php';
}

include __DIR__ . '/views/footer.php';
?>
