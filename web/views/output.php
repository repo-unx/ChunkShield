<div class="section-header mb-4">
    <h2>Output Files</h2>
    <p class="">Your protected PHP code files are ready for download.</p>
</div>

<?php
// Check if loader is generated
if (!isset($_SESSION['loader_file'])) {
    echo '<div class="alert alert-danger">
            <i class="fas fa-exclamation-circle me-2"></i>
            No protected files available. Please <a href="index.php?tab=loader">generate a loader</a> first.
          </div>';
    exit;
}

// Get file information
$loader_file = $_SESSION['loader_file'] ?? '';
$chunks_dir = $_SESSION['chunks_dir'] ?? '';
$license_file = $_SESSION['license_file'] ?? '';
$chunk_count = count($_SESSION['chunks_info']['chunks'] ?? []);
$original_filename = $_SESSION['original_filename'] ?? 'unknown.php';

// Create zip archive for download
$output_basename = pathinfo($original_filename, PATHINFO_FILENAME);
$output_zip = dirname(dirname(__DIR__)) . '/output/' . $output_basename . '_protected.zip';

// Create new ZIP archive
$zip = new ZipArchive();
if ($zip->open($output_zip, ZipArchive::CREATE | ZipArchive::OVERWRITE) === TRUE) {
    // Add loader file
    if (file_exists($loader_file)) {
        $zip->addFile($loader_file, basename($loader_file));
    }

    // Add chunks directory
    if (is_dir($chunks_dir)) {
        $zip->addEmptyDir('chunks');
        $chunk_files = glob($chunks_dir . '/*.*');
        foreach ($chunk_files as $chunk_file) {
            $zip->addFile($chunk_file, 'chunks/' . basename($chunk_file));
        }
    }

    // Add license file if it exists
    if (!empty($license_file) && file_exists($license_file)) {
        $zip->addFile($license_file, basename($license_file));
    }

    $zip->close();
    $_SESSION['output_zip'] = $output_zip;
}

// Check if the zip exists and get its size
$zip_exists = file_exists($output_zip);
$zip_size = $zip_exists ? filesize($output_zip) : 0;
$zip_size_formatted = $zip_exists ? formatFileSize($zip_size) : 'N/A';

// Prepare file listing
$files = [
    [
        'name' => 'loader.php',
        'type' => 'loader',
        'size' => file_exists($loader_file) ? filesize($loader_file) : 0,
        'path' => $loader_file
    ]
];

// Add chunks to file listing
if (is_dir($chunks_dir)) {
    $chunk_files = glob($chunks_dir . '/*.chunk');
    foreach ($chunk_files as $chunk_file) {
        $files[] = [
            'name' => basename($chunk_file),
            'type' => 'chunk',
            'size' => filesize($chunk_file),
            'path' => $chunk_file
        ];
    }

    // Add metadata file
    $metadata_file = $chunks_dir . '/metadata.json';
    if (file_exists($metadata_file)) {
        $files[] = [
            'name' => 'metadata.json',
            'type' => 'metadata',
            'size' => filesize($metadata_file),
            'path' => $metadata_file
        ];
    }
}

// Add license file if it exists
if (!empty($license_file) && file_exists($license_file)) {
    $files[] = [
        'name' => basename($license_file),
        'type' => 'license',
        'size' => filesize($license_file),
        'path' => $license_file
    ];
}
?>

<div class="row">
    <div class="col-lg-8">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Protected Files</h5>
                <span class="badge bg-success">
                    <i class="fas fa-check-circle me-1"></i> Protection Complete
                </span>
            </div>
            <div class="card-body">
                <div class="alert alert-success mb-4">
                    <div class="d-flex">
                        <div class="me-3">
                            <i class="fas fa-shield-alt fa-2x"></i>
                        </div>
                        <div>
                            <h5 class="alert-heading">Protection Complete!</h5>
                            <p class="mb-0">Your PHP code has been successfully protected with obfuscation, chunking, encryption, and license restrictions.</p>
                        </div>
                    </div>
                </div>

                <div class="file-listing mb-4">
                    <h6>Generated Files</h6>
                    <ul class="file-list">
                        <?php foreach ($files as $file): ?>
                        <li class="file-list-item">
                            <div class="file-name">
                                <?php
                                $icon_class = 'fa-file-code';
                                if ($file['type'] === 'chunk') $icon_class = 'fa-puzzle-piece';
                                elseif ($file['type'] === 'license') $icon_class = 'fa-key';
                                elseif ($file['type'] === 'metadata') $icon_class = 'fa-file-alt';

                                $file_size = formatFileSize($file['size']);
                                ?>
                                <i class="fas <?php echo $icon_class; ?> file-icon"></i>
                                <?php echo htmlspecialchars($file['name']); ?>
                                <span class="ms-2 badge bg-secondary"><?php echo $file['type']; ?></span>
                            </div>
                            <div class="file-size">
                                <?php echo $file_size; ?>
                            </div>
                        </li>
                        <?php endforeach; ?>
                    </ul>
                </div>

                <div class="download-options">
                    <h6>Download Options</h6>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="card mb-3">
                                <div class="card-body text-center py-4">
                                    <i class="fas fa-file-archive mb-3" style="font-size: 3rem; color: var(--primary-color);"></i>
                                    <h5>Complete Package</h5>
                                    <p class=" mb-3">Includes loader, chunks, and license</p>
                                    <?php
                                    $output_zip = $_SESSION['output_zip'] ?? '';
                                    if (file_exists($output_zip)):
                                    ?>
                                    <a href="download.php?file=<?php echo urlencode(basename($output_zip)); ?>" 
                                       class="btn btn-primary" 
                                       download="<?php echo basename($output_zip); ?>">
                                        <i class="fas fa-download me-2"></i> Download ZIP (<?php echo $zip_size_formatted; ?>)
                                    </a>
                                    <?php else: ?>
                                    <button class="btn btn-secondary" disabled>
                                        <i class="fas fa-exclamation-circle me-2"></i> No Output Available
                                    </button>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="card mb-3">
                                <div class="card-body text-center py-4">
                                    <i class="fas fa-file-code mb-3" style="font-size: 3rem; color: var(--primary-color);"></i>
                                    <h5>Loader Only</h5>
                                    <p class=" mb-3">Just the loader.php file</p>
                                    <?php
                                    // Ensure we have a valid loader file path for download
                                    $download_loader_path = "";
                                    if (!empty($loader_file) && file_exists($loader_file)) {
                                        // Use download.php with correct parameter instead of direct path
                                        $download_loader_path = "download.php?file=" . urlencode(basename($loader_file)) . "&type=loader";
                                    }
                                    ?>
                                    <a href="<?php echo $download_loader_path; ?>" class="btn btn-outline-primary" download>
                                        <i class="fas fa-file-code me-2"></i> Download Loader
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="deployment-instructions mt-4">
                    <h6>Deployment Instructions</h6>
                    <ol class="deployment-steps">
                        <li class="mb-2">Extract the ZIP file on your web server</li>
                        <li class="mb-2">Make sure the <code>loader.php</code> and <code>chunks/</code> directory are in the same location</li>
                        <li class="mb-2">Access your protected code by running <code>loader.php</code></li>
                        <li class="mb-2">The loader will automatically decrypt and execute your code</li>
                    </ol>

                    <div class="alert alert-warning mt-3 mb-0">
                        <i class="fas fa-exclamation-triangle me-2"></i>
                        Keep your files secure! Never share the encryption key or chunks with unauthorized users.
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-4">
        <div class="card">
            <div class="card-header">
                <h5>Project Summary</h5>
            </div>
            <div class="card-body">
                <div class="summary-item d-flex justify-content-between mb-3 pb-3 border-bottom">
                    <div>
                        <i class="fas fa-file-code text-primary me-2"></i>
                        Original File
                    </div>
                    <div class="">
                        <?php echo htmlspecialchars($original_filename); ?>
                    </div>
                </div>

                <div class="summary-item d-flex justify-content-between mb-3 pb-3 border-bottom">
                    <div>
                        <i class="fas fa-puzzle-piece text-primary me-2"></i>
                        Chunks Created
                    </div>
                    <div class="">
                        <?php echo $chunk_count; ?> chunks
                    </div>
                </div>

                <div class="summary-item d-flex justify-content-between mb-3 pb-3 border-bottom">
                    <div>
                        <i class="fas fa-lock text-primary me-2"></i>
                        Encryption
                    </div>
                    <div class="">
                        AES-256-CBC
                    </div>
                </div>

                <div class="summary-item d-flex justify-content-between mb-3 pb-3 border-bottom">
                    <div>
                        <i class="fas fa-key text-primary me-2"></i>
                        License Status
                    </div>
                    <div class="text-success">
                        <?php echo !empty($license_file) ? 'Created' : 'Not Created'; ?>
                    </div>
                </div>

                <div class="summary-item d-flex justify-content-between mb-3 pb-3 border-bottom">
                    <div>
                        <i class="fas fa-calendar-alt text-primary me-2"></i>
                        Created On
                    </div>
                    <div class="">
                        <?php echo date('Y-m-d H:i'); ?>
                    </div>
                </div>

                <div class="actions mt-4">
                    <a href="index.php?tab=upload" class="btn btn-outline-primary d-block mb-3">
                        <i class="fas fa-redo me-2"></i> Start New Project
                    </a>
                    <a href="index.php?action=clear_all" class="btn btn-outline-danger d-block">
                        <i class="fas fa-trash me-2"></i> Clear All Data
                    </a>
                </div>
            </div>
        </div>

        <div class="card mt-4">
            <div class="card-body">
                <div class="text-center mb-3">
                    <i class="fas fa-star text-warning"></i>
                    <i class="fas fa-star text-warning"></i>
                    <i class="fas fa-star text-warning"></i>
                    <i class="fas fa-star text-warning"></i>
                    <i class="fas fa-star text-warning"></i>
                </div>
                <p class="text-center mb-0">
                    <strong>Thank you for using ChunkShield!</strong><br>
                    Your PHP code is now protected with advanced security measures.
                </p>
            </div>
        </div>
    </div>
</div>