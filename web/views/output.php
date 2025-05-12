<?php
// Ensure we have a loader file
if (!isset($_SESSION['loader_file'])) {
    $_SESSION['error'] = 'No loader file found. Please generate a loader first.';
    header('Location: index.php?tab=loader');
    exit;
}

// Get paths to output files
$loaderPath = $_SESSION['loader_file'];
$obfuscatedPath = $_SESSION['obfuscated_file'];
$chunksInfo = $_SESSION['chunks_info'];
$mapFilePath = $chunksInfo['mapFile'];

// Function to get relative path for display
function getRelativePath($path) {
    $basePath = realpath(__DIR__ . '/../../');
    return str_replace($basePath, '', $path);
}

// Format relative paths for display
$relLoaderPath = getRelativePath($loaderPath);
$relObfuscatedPath = getRelativePath($obfuscatedPath);
$relMapFilePath = getRelativePath($mapFilePath);

// Create a zip file containing all the output
$zipFilename = 'obfuscated_' . time() . '.zip';
$zipPath = __DIR__ . '/../../output/' . $zipFilename;

$zip = new ZipArchive();
if ($zip->open($zipPath, ZipArchive::CREATE) === TRUE) {
    // Add obfuscated file
    $zip->addFile($obfuscatedPath, basename($obfuscatedPath));
    
    // Add loader file
    $zip->addFile($loaderPath, basename($loaderPath));
    
    // Add map file
    $zip->addFile($mapFilePath, 'map/' . basename($mapFilePath));
    
    // Add all chunk files
    foreach ($chunksInfo['chunks'] as $chunk) {
        $zip->addFile($chunk['file'], 'chunks/' . basename($chunk['file']));
    }
    
    // Add license files if they exist
    if (isset($_SESSION['license_result']) && isset($_SESSION['license_result']['license_file'])) {
        $licenseFile = $_SESSION['license_result']['license_file'];
        $verificationFile = $_SESSION['license_result']['verification_file'];
        
        if (file_exists($licenseFile)) {
            $zip->addFile($licenseFile, 'license/' . basename($licenseFile));
        }
        
        if (file_exists($verificationFile)) {
            $zip->addFile($verificationFile, 'license/' . basename($verificationFile));
        }
    }
    
    $zip->close();
}

$zipRelPath = getRelativePath($zipPath);
?>

<h2 class="page-title"><i class="fas fa-download me-2"></i>Output Files</h2>

<div class="alert alert-success">
    <i class="fas fa-check-circle me-2"></i>All processing completed successfully! Your obfuscated code and license files are ready for download.
</div>

<div class="row">
    <div class="col-lg-6">
        <div class="card mb-4">
            <div class="card-header">
                <i class="fas fa-file-archive me-2"></i>Download All Files
            </div>
            <div class="card-body text-center">
                <p>All generated files have been packed into a single ZIP archive for your convenience.</p>
                <a href="<?= htmlspecialchars($zipRelPath) ?>" class="btn btn-primary btn-lg">
                    <i class="fas fa-download me-2"></i>Download ZIP Archive
                </a>
                <p class="mt-3 text-muted">
                    <small>Size: <?= round(filesize($zipPath) / 1024, 2) ?> KB</small>
                </p>
            </div>
        </div>
    </div>
    
    <div class="col-lg-6">
        <div class="card mb-4">
            <div class="card-header">
                <i class="fas fa-list me-2"></i>Generated Files
            </div>
            <div class="card-body">
                <p>The following files have been generated:</p>
                
                <ul class="list-group">
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        <div>
                            <i class="fas fa-file-code me-2 text-primary"></i>Obfuscated File
                            <small class="d-block text-muted"><?= htmlspecialchars($relObfuscatedPath) ?></small>
                        </div>
                        <a href="<?= htmlspecialchars($relObfuscatedPath) ?>" class="btn btn-sm btn-outline-primary">
                            <i class="fas fa-download"></i>
                        </a>
                    </li>
                    
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        <div>
                            <i class="fas fa-file-code me-2 text-success"></i>Loader File
                            <small class="d-block text-muted"><?= htmlspecialchars($relLoaderPath) ?></small>
                        </div>
                        <a href="<?= htmlspecialchars($relLoaderPath) ?>" class="btn btn-sm btn-outline-success">
                            <i class="fas fa-download"></i>
                        </a>
                    </li>
                    
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        <div>
                            <i class="fas fa-file-alt me-2 text-info"></i>Chunks Map
                            <small class="d-block text-muted"><?= htmlspecialchars($relMapFilePath) ?></small>
                        </div>
                        <a href="<?= htmlspecialchars($relMapFilePath) ?>" class="btn btn-sm btn-outline-info">
                            <i class="fas fa-download"></i>
                        </a>
                    </li>
                    
                    <?php if (isset($_SESSION['license_result']) && isset($_SESSION['license_result']['license_file'])): ?>
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        <div>
                            <i class="fas fa-key me-2 text-danger"></i>License File
                            <small class="d-block text-muted"><?= htmlspecialchars(getRelativePath($_SESSION['license_result']['license_file'])) ?></small>
                        </div>
                        <a href="<?= htmlspecialchars(getRelativePath($_SESSION['license_result']['license_file'])) ?>" class="btn btn-sm btn-outline-danger">
                            <i class="fas fa-download"></i>
                        </a>
                    </li>
                    
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        <div>
                            <i class="fas fa-file-contract me-2 text-danger"></i>Verification File
                            <small class="d-block text-muted"><?= htmlspecialchars(getRelativePath($_SESSION['license_result']['verification_file'])) ?></small>
                        </div>
                        <a href="<?= htmlspecialchars(getRelativePath($_SESSION['license_result']['verification_file'])) ?>" class="btn btn-sm btn-outline-danger">
                            <i class="fas fa-download"></i>
                        </a>
                    </li>
                    
                    <?php if (isset($_SESSION['license_package'])): ?>
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        <div>
                            <i class="fas fa-file-archive me-2 text-danger"></i>License Package (ZIP)
                            <small class="d-block text-muted"><?= htmlspecialchars(getRelativePath($_SESSION['license_package'])) ?></small>
                        </div>
                        <a href="<?= htmlspecialchars(getRelativePath($_SESSION['license_package'])) ?>" class="btn btn-sm btn-outline-danger">
                            <i class="fas fa-download"></i>
                        </a>
                    </li>
                    <?php endif; ?>
                    <?php endif; ?>
                    
                    <li class="list-group-item">
                        <div class="mb-2">
                            <i class="fas fa-puzzle-piece me-2 text-warning"></i>Encrypted Chunks (<?= count($chunksInfo['chunks']) ?>)
                        </div>
                        <div class="accordion" id="chunksAccordion">
                            <div class="accordion-item">
                                <h2 class="accordion-header" id="chunksHeading">
                                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#chunksCollapse" aria-expanded="false" aria-controls="chunksCollapse">
                                        View All Chunks
                                    </button>
                                </h2>
                                <div id="chunksCollapse" class="accordion-collapse collapse" aria-labelledby="chunksHeading" data-bs-parent="#chunksAccordion">
                                    <div class="accordion-body p-0">
                                        <ul class="list-group list-group-flush">
                                            <?php foreach ($chunksInfo['chunks'] as $index => $chunk): ?>
                                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                                <div>
                                                    <small>Chunk <?= $index + 1 ?>: <?= basename($chunk['file']) ?></small>
                                                </div>
                                                <a href="<?= getRelativePath($chunk['file']) ?>" class="btn btn-sm btn-outline-warning">
                                                    <i class="fas fa-download"></i>
                                                </a>
                                            </li>
                                            <?php endforeach; ?>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</div>

<div class="card mb-4">
    <div class="card-header">
        <i class="fas fa-info-circle me-2"></i>Usage Instructions
    </div>
    <div class="card-body">
        <h5>How to Use the Generated Files</h5>
        
        <div class="row mt-4">
            <div class="col-md-4 mb-4">
                <h6><i class="fas fa-file-code me-2 text-primary"></i>Option 1: Basic Obfuscation</h6>
                <p>If you only need basic protection, use the obfuscated file:</p>
                <pre class="bg-light p-3 rounded"><code>&lt;?php
require_once 'path/to/your-file.obf.php';
?&gt;</code></pre>
            </div>
            
            <div class="col-md-4 mb-4">
                <h6><i class="fas fa-puzzle-piece me-2 text-primary"></i>Option 2: Chunk-Based Encryption</h6>
                <p>For enhanced security with chunk-based encryption:</p>
                <ol>
                    <li>Upload the loader.php file to your server</li>
                    <li>Upload the 'chunks' directory with all chunk files</li>
                    <li>Upload the 'map' directory with chunks.map.json</li>
                    <li>Include the loader in your project:</li>
                </ol>
                <pre class="bg-light p-3 rounded"><code>&lt;?php
require_once 'path/to/loader.php';
?&gt;</code></pre>
            </div>
            
            <?php if (isset($_SESSION['license_result'])): ?>
            <div class="col-md-4 mb-4">
                <h6><i class="fas fa-key me-2 text-danger"></i>Option 3: License Protection</h6>
                <p>For maximum security with license verification:</p>
                <ol>
                    <li>Upload the loader.php file (with license check)</li>
                    <li>Upload the chunks and map directories</li>
                    <li>Upload the license file to your server</li>
                    <li>Configure your application to verify the license:</li>
                </ol>
                <pre class="bg-light p-3 rounded"><code>&lt;?php
// License file should be in the same directory
$licenseFile = __DIR__ . '/license.lic';
require_once 'path/to/loader.php';
?&gt;</code></pre>
            </div>
            <?php endif; ?>
        </div>
        
        <div class="alert alert-info">
            <i class="fas fa-lightbulb me-2"></i>Tip: Keep your encryption key and license files secure. For production use, consider implementing a secure key storage mechanism and distributing licenses separately from your application code.
        </div>
        
        <?php if (isset($_SESSION['license_result'])): ?>
        <div class="mt-4">
            <h5><i class="fas fa-shield-alt me-2"></i>License Distribution</h5>
            <p>When distributing your software to clients:</p>
            <div class="row">
                <div class="col-md-6">
                    <ul class="list-group">
                        <li class="list-group-item">
                            <i class="fas fa-check text-success me-2"></i>Distribute the license file (.lic) to your client
                        </li>
                        <li class="list-group-item">
                            <i class="fas fa-check text-success me-2"></i>Include instructions to place the license file in the application root
                        </li>
                        <li class="list-group-item">
                            <i class="fas fa-check text-success me-2"></i>The loader will verify the license automatically before executing the code
                        </li>
                    </ul>
                </div>
                <div class="col-md-6">
                    <div class="alert alert-warning">
                        <i class="fas fa-exclamation-triangle me-2"></i>Remember that the license is domain-locked and will only work on the specified domain.
                    </div>
                </div>
            </div>
        </div>
        <?php endif; ?>
    </div>
</div>

<div class="d-grid gap-2 d-md-flex justify-content-md-end mt-4">
    <a href="index.php?tab=loader" class="btn btn-secondary me-md-2">
        <i class="fas fa-arrow-left me-2"></i>Back
    </a>
    <a href="index.php?tab=upload" class="btn btn-primary">
        <i class="fas fa-redo me-2"></i>Start New Project
    </a>
</div>
