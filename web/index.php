<?php
/**
 * ChunkShield - PHP Code Protection System
 * Main entry point for the web interface
 */

// Maximum error reporting for debugging
ini_set('display_errors', 1); 
error_reporting(E_ALL); 

// Initialize session
session_start();

// Include necessary files and functions
require_once dirname(__DIR__) . '/tools/utils.php';
require_once dirname(__DIR__) . '/tools/obfuscator.php';
require_once dirname(__DIR__) . '/tools/chunker.php';
require_once dirname(__DIR__) . '/tools/license.php';

// Create necessary directories if they don't exist
$directories = [
    dirname(__DIR__) . '/output/obfuscated',
    dirname(__DIR__) . '/output/chunks',
    dirname(__DIR__) . '/output/licenses',
    dirname(__DIR__) . '/logs'
];

foreach ($directories as $dir) {
    if (!file_exists($dir)) {
        mkdir($dir, 0755, true);
    }
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
            $input_type = isset($_POST['input_type']) ? $_POST['input_type'] : 'file';
            
            if ($input_type === 'file') {
                // Handle file upload
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
            } else if ($input_type === 'paste') {
                // Handle pasted code
                if (isset($_POST['phpCode']) && !empty($_POST['phpCode'])) {
                    $code = trim($_POST['phpCode']);
                    
                    // Remove any existing PHP tags
                    $code = preg_replace('/<\?(?:php)?|\?>/', '', $code);
                    $code = trim($code);
                    
                    // Add PHP tags in a standardized way
                    $code = "<?php\n" . $code . "\n?>";
                    
                    // Generate a filename for the pasted code
                    $timestamp = date('YmdHis');
                    $filename = "pasted_code_{$timestamp}.php";
                    
                    // Save to session for processing
                    $_SESSION['uploaded_file'] = $code;
                    $_SESSION['original_filename'] = $filename;
                    $_SESSION['success'] = 'Code received successfully.';
                    
                    // Redirect to obfuscate tab
                    header('Location: index.php?tab=obfuscate');
                    exit;
                } else {
                    $_SESSION['error'] = 'No code was provided. Please paste your PHP code.';
                    header('Location: index.php?tab=upload');
                    exit;
                }
            } else {
                $_SESSION['error'] = 'Invalid input type.';
                header('Location: index.php?tab=upload');
                exit;
            }
            break;

        case 'obfuscate':
            // Process obfuscation
            if (!isset($_SESSION['uploaded_file'])) {
                $_SESSION['error'] = 'No file has been uploaded.';
                header('Location: index.php?tab=upload');
                exit;
            }

            $code = $_SESSION['uploaded_file'];
            $options = [
                'remove_comments' => isset($_POST['remove_comments']),
                'remove_whitespace' => isset($_POST['remove_whitespace']),
                'rename_variables' => isset($_POST['rename_variables']),
                'rename_functions' => isset($_POST['rename_variables']),  // Use same setting for functions
                'insert_junk' => isset($_POST['insert_junk']),
                'junk_density' => isset($_POST['junk_density']) ? intval($_POST['junk_density']) : 3
            ];

            $result = obfuscate_code($code, $options);

            if ($result !== false) {
                // Save obfuscated code to session and file
                $_SESSION['obfuscated_file'] = $result['code'];
                $_SESSION['obfuscation_metadata'] = $result['metadata'];

                $obfuscated_file = dirname(__DIR__) . '/output/obfuscated/' . pathinfo($_SESSION['original_filename'], PATHINFO_FILENAME) . '_obfuscated.php';
                file_put_contents($obfuscated_file, $result['code']);

                $_SESSION['success'] = 'Code obfuscated successfully.';
                header('Location: index.php?tab=chunk');
            } else {
                $_SESSION['error'] = 'Obfuscation failed.';
                header('Location: index.php?tab=obfuscate');
            }
            exit;
            break;

        case 'chunk':
            // Process chunking and encryption
            if (!isset($_SESSION['obfuscated_file'])) {
                $_SESSION['error'] = 'No obfuscated code available.';
                header('Location: index.php?tab=obfuscate');
                exit;
            }

            $obfuscated_code = $_SESSION['obfuscated_file'];
            $chunks_dir = dirname(__DIR__) . '/output/chunks/' . uniqid('chunks_');

            if (!file_exists($chunks_dir)) {
                mkdir($chunks_dir, 0755, true);
            }

            $options = [
                'chunk_size' => isset($_POST['chunk_size']) ? intval($_POST['chunk_size']) : 4096,
                'min_chunks' => isset($_POST['min_chunks']) ? intval($_POST['min_chunks']) : 3,
                'use_gzip' => isset($_POST['use_gzip']),
                'use_base64' => isset($_POST['use_base64'])
            ];

            $encryption_key = !empty($_POST['encryption_key']) ? $_POST['encryption_key'] : generate_encryption_key();

            $chunk_info = create_chunks($obfuscated_code, $chunks_dir, $encryption_key, $options);

            if ($chunk_info !== false) {
                // Save metadata
                $metadata_file = create_chunk_metadata($chunk_info, $chunks_dir);

                if ($metadata_file !== false) {
                    $_SESSION['chunks_info'] = $chunk_info;
                    $_SESSION['chunks_dir'] = $chunks_dir;
                    $_SESSION['success'] = 'Code successfully chunked and encrypted.';
                    header('Location: index.php?tab=loader');
                } else {
                    $_SESSION['error'] = 'Failed to create metadata.';
                    header('Location: index.php?tab=chunk');
                }
            } else {
                $_SESSION['error'] = 'Chunking failed.';
                header('Location: index.php?tab=chunk');
            }
            exit;
            break;

        case 'loader':
            // Generate loader
            if (!isset($_SESSION['chunks_info'])) {
                $_SESSION['error'] = 'No chunks available.';
                header('Location: index.php?tab=chunk');
                exit;
            }

            $chunks_info = $_SESSION['chunks_info'];
            $chunks_dir = $_SESSION['chunks_dir'];
            $output_dir = dirname($chunks_dir);

            $options = [
                'add_junk_eval' => isset($_POST['add_junk_eval']),
                'junk_count' => isset($_POST['junk_count']) ? intval($_POST['junk_count']) : 5,
                'add_fingerprinting' => isset($_POST['add_fingerprinting'])
            ];

            $loader_name = !empty($_POST['loader_name']) ? $_POST['loader_name'] : 'loader.php';
            $loader_file = $output_dir . '/' . $loader_name;

            $license_info = []; // Will be filled in the license step

            $loader_result = generate_loader($chunks_info, $output_dir, $license_info, $options);

            if ($loader_result !== false) {
                $_SESSION['loader_file'] = $loader_file;
                $_SESSION['loader_options'] = $options;
                $_SESSION['success'] = 'Loader generated successfully.';
                header('Location: index.php?tab=license');
            } else {
                $_SESSION['error'] = 'Failed to generate loader.';
                header('Location: index.php?tab=loader');
            }
            exit;
            break;

        case 'license':
            // Check if license is enabled
            if (!isset($_POST['enable_license'])) {
                $_SESSION['success'] = 'Skipped license generation.';
                header('Location: index.php?tab=output');
                exit;
            }

            // Create license
            $license_data = [
                'customer_name' => $_POST['customer_name'] ?? '',
                'customer_email' => $_POST['customer_email'] ?? '',
                'valid_days' => isset($_POST['valid_days']) ? intval($_POST['valid_days']) : 365
            ];

            // Add domain restriction if enabled
            if (isset($_POST['check_domain']) && !empty($_POST['domain'])) {
                $license_data['check_domain'] = true;
                $license_data['domain'] = array_map('trim', explode(',', $_POST['domain']));
            }

            // Add IP restriction if enabled
            if (isset($_POST['check_ip']) && !empty($_POST['ip'])) {
                $license_data['check_ip'] = true;
                $license_data['ip'] = array_map('trim', explode(',', $_POST['ip']));
            }

            // Add path restriction if enabled
            if (isset($_POST['check_path']) && !empty($_POST['path'])) {
                $license_data['check_path'] = true;
                $license_data['path'] = array_map('trim', explode(',', $_POST['path']));
            }

            $licenses_dir = dirname(__DIR__) . '/output/licenses';
            $license_file = $licenses_dir . '/' . strtolower(preg_replace('/[^a-zA-Z0-9_-]/', '_', $license_data['customer_name'])) . '_license.key';

            $result = create_license($license_data, $licenses_dir);

            if ($result['success']) {
                $_SESSION['license_file'] = $result['file'];
                $_SESSION['license_data'] = $result['license_data'];

                // Update loader with license restrictions if fingerprinting is enabled
                if (isset($_SESSION['loader_options']['add_fingerprinting']) && $_SESSION['loader_options']['add_fingerprinting']) {
                    $restrictions = [];

                    if (isset($license_data['check_domain']) && $license_data['check_domain']) {
                        $restrictions['domain'] = $license_data['domain'];
                    }

                    if (isset($license_data['check_ip']) && $license_data['check_ip']) {
                        $restrictions['ip'] = $license_data['ip'];
                    }

                    if (isset($license_data['check_path']) && $license_data['check_path']) {
                        $restrictions['path'] = $license_data['path'];
                    }

                    // Regenerate loader with license restrictions
                    if (!empty($restrictions) && isset($_SESSION['chunks_info']) && isset($_SESSION['loader_file'])) {
                        $loader_file = $_SESSION['loader_file'];
                        $output_dir = dirname($loader_file);

                        generate_loader(
                            $_SESSION['chunks_info'],
                            $output_dir,
                            $restrictions,
                            $_SESSION['loader_options']
                        );
                    }
                }

                // Create zip archive with loader and chunks
                if (isset($_SESSION['loader_file']) && isset($_SESSION['chunks_dir'])) {
                    $output_basename = pathinfo($_SESSION['original_filename'], PATHINFO_FILENAME);
                    $output_zip = dirname(__DIR__) . '/output/' . $output_basename . '_protected.zip';

                    $zip = new ZipArchive();
                    if ($zip->open($output_zip, ZipArchive::CREATE | ZipArchive::OVERWRITE) === true) {
                        // Add loader
                        $loader_file = $_SESSION['loader_file'];
                        $zip->addFile($loader_file, basename($loader_file));

                        // Add chunks directory
                        $chunks_dir = $_SESSION['chunks_dir'];
                        $zip->addEmptyDir('chunks');

                        // Add chunk files
                        $chunk_files = glob($chunks_dir . '/*.*');
                        foreach ($chunk_files as $chunk_file) {
                            $zip->addFile($chunk_file, 'chunks/' . basename($chunk_file));
                        }

                        // Add license file if available
                        if (isset($_SESSION['license_file'])) {
                            $license_file = $_SESSION['license_file'];
                            $zip->addFile($license_file, basename($license_file));
                        }

                        $zip->close();
                        $_SESSION['output_zip'] = $output_zip;
                    }
                }

                $_SESSION['success'] = 'License created successfully.';
                header('Location: index.php?tab=output');
            } else {
                $_SESSION['error'] = 'Error generating license: ' . $result['message'];
                header('Location: index.php?tab=license');
            }
            exit;
            break;
        case 'preview_obfuscate':
            if (!isset($_SESSION['uploaded_file'])) {
                echo json_encode(['success' => false, 'error' => 'No file uploaded']);
                exit;
            }

            $code = $_SESSION['uploaded_file'];
            $options = [
                'remove_comments' => isset($_POST['remove_comments']),
                'remove_whitespace' => isset($_POST['remove_whitespace']),
                'rename_variables' => isset($_POST['rename_variables']),
                'insert_junk' => isset($_POST['insert_junk']),
                'junk_density' => isset($_POST['junk_density']) ? intval($_POST['junk_density']) : 3
            ];

            $result = obfuscate_code($code, $options);
            echo json_encode(['success' => true, 'preview' => $result['code']]);
            exit;
            break;

        case 'preview_chunk':
            if (!isset($_SESSION['obfuscated_file'])) {
                echo json_encode(['success' => false, 'error' => 'No obfuscated code']);
                exit;
            }

            $code = $_SESSION['obfuscated_file'];
            $chunks = split_into_chunks($code, isset($_POST['chunk_size']) ? intval($_POST['chunk_size']) : 4096);
            $preview = "// Total chunks: " . count($chunks) . "\n\n";
            $preview .= "// First chunk preview:\n" . substr($chunks[0], 0, 100) . "...\n";

            echo json_encode(['success' => true, 'preview' => $preview]);
            exit;
            break;

        case 'preview_loader':
            if (!isset($_SESSION['chunks_info'])) {
                echo json_encode(['success' => false, 'error' => 'No chunks available']);
                exit;
            }

            $options = [
                'add_junk_eval' => isset($_POST['add_junk_eval']),
                'junk_count' => isset($_POST['junk_count']) ? intval($_POST['junk_count']) : 5,
                'add_fingerprinting' => isset($_POST['add_fingerprinting'])
            ];

            $loader_preview = generate_loader_code($_SESSION['chunks_info'], [], $options['add_junk_eval'], $options['add_fingerprinting']);
            $preview = highlight_string($loader_preview, true);

            echo json_encode(['success' => true, 'preview' => $preview]);
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
