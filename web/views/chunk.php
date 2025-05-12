<?php
// Ensure we have an obfuscated file
if (!isset($_SESSION['obfuscated_file'])) {
    $_SESSION['error'] = 'No obfuscated file found. Please obfuscate a file first.';
    header('Location: index.php?tab=obfuscate');
    exit;
}

// Get obfuscated file path
$obfuscatedPath = $_SESSION['obfuscated_file'];
?>

<h2 class="mb-4"><i class="fas fa-puzzle-piece me-2"></i>Chunk-Based Obfuscation</h2>

<div class="row">
    <div class="col-md-6">
        <div class="card mb-4">
            <div class="card-header">
                <i class="fas fa-file-code me-2"></i>Obfuscated File
            </div>
            <div class="card-body">
                <p>Your PHP file has been obfuscated and saved to:</p>
                <div class="input-group">
                    <input type="text" class="form-control" value="<?= htmlspecialchars($obfuscatedPath) ?>" readonly>
                    <button class="btn btn-outline-secondary btn-copy" type="button" data-target="obfuscatedPath">
                        <i class="fas fa-copy"></i>
                    </button>
                </div>
                <div class="mt-3">
                    <strong>File Size:</strong> <?= round(filesize($obfuscatedPath) / 1024, 2) ?> KB
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-md-6">
        <div class="card mb-4">
            <div class="card-header">
                <i class="fas fa-cogs me-2"></i>Chunking Options
            </div>
            <div class="card-body">
                <form action="index.php" method="post">
                    <input type="hidden" name="action" value="chunk">
                    
                    <div class="fingerprinting-item mb-3">
                        <label for="chunk_size" class="form-label">
                            <i class="fas fa-cut me-2 text-primary"></i><strong>Maximum Chunk Size (KB)</strong>
                        </label>
                        <input type="number" class="form-control" id="chunk_size" name="chunk_size" value="5" min="1" max="50">
                        <small class="form-text text-muted">
                            The maximum size of each code chunk in KB. Smaller chunks mean more files and better security.
                            Recommended: 2-5KB for optimal protection.
                        </small>
                    </div>
                    
                    <div class="junk-code-option mb-3">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="encrypt_chunks" name="encrypt_chunks" checked>
                            <label class="form-check-label" for="encrypt_chunks">
                                <i class="fas fa-lock me-2 text-danger"></i><strong>Encrypt Chunks</strong>
                            </label>
                            <small class="form-text text-muted d-block mt-1">
                                Encrypts each chunk using AES-256-CBC encryption.
                                This adds military-grade encryption to each code fragment.
                            </small>
                        </div>
                    </div>
                    
                    <div class="fingerprinting-item mb-3">
                        <label for="encryption_key" class="form-label">
                            <i class="fas fa-key me-2 text-warning"></i><strong>Encryption Key</strong>
                        </label>
                        <div class="input-group">
                            <input type="text" class="form-control" id="encryption_key" name="encryption_key" 
                                   value="<?= isset($_SESSION['encryption_key']) ? htmlspecialchars($_SESSION['encryption_key']) : '' ?>" 
                                   required>
                            <button class="btn btn-outline-primary" type="button" id="generateKey">
                                <i class="fas fa-random"></i> Generate Strong Key
                            </button>
                        </div>
                        <small class="form-text text-muted mt-1">
                            This cryptographic key encrypts your code chunks and will be embedded in the loader.
                            For maximum security, use a strong random key (32+ characters).
                        </small>
                    </div>
                    
                    <div class="alert alert-info">
                        <i class="fas fa-info-circle me-2"></i>The code will be split into multiple encrypted chunks. 
                        A map file will be generated to track the chunks, which will be used by the loader.
                    </div>
                    
                    <div id="progressBar" class="progress mt-4 d-none">
                        <div class="progress-bar progress-bar-striped progress-bar-animated" role="progressbar" style="width: 0%;" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100">0%</div>
                    </div>
                    
                    <div class="d-grid gap-2 d-md-flex justify-content-md-end mt-4">
                        <a href="index.php?tab=obfuscate" class="btn btn-secondary me-md-2">
                            <i class="fas fa-arrow-left me-2"></i>Back
                        </a>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-puzzle-piece me-2"></i>Create Chunks & Continue
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
