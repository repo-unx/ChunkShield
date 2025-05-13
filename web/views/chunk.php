<div class="section-header mb-4">
    <h2>Chunk &amp; Encrypt</h2>
    <p class="">Split your obfuscated code into encrypted chunks for maximum protection.</p>
</div>

<?php
// Check if code is obfuscated
if (!isset($_SESSION['obfuscated_file'])) {
    echo '<div class="alert alert-danger">
            <i class="fas fa-exclamation-circle me-2"></i>
            No obfuscated code available. Please <a href="index.php?tab=obfuscate">obfuscate your code</a> first.
          </div>';
    exit;
}

// Display file info
$filename = $_SESSION['original_filename'] ?? 'unknown.php';
$filesize = strlen($_SESSION['obfuscated_file']);
$filesize_formatted = formatFileSize($filesize);
?>

<div class="row">
    <div class="col-lg-8">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Chunking &amp; Encryption Settings</h5>
                <span class="badge bg-success">
                    <i class="fas fa-file-code me-1"></i> <?php echo htmlspecialchars($filename); ?> (<?php echo $filesize_formatted; ?>)
                </span>
            </div>
            <div class="card-body">
                <form method="post" action="index.php">
                    <input type="hidden" name="action" value="chunk">
                    
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <div class="card h-100">
                                <div class="card-header">
                                    <h6 class="mb-0">Chunking Options</h6>
                                </div>
                                <div class="card-body">
                                    <div class="mb-3">
                                        <label for="chunk_size" class="form-label">Chunk Size (bytes)</label>
                                        <input type="number" class="form-control" id="chunk_size" name="chunk_size" value="4096" min="1024" max="16384">
                                        <small class="">Size of each code chunk (in bytes)</small>
                                    </div>
                                    
                                    <div class="mb-3">
                                        <label for="min_chunks" class="form-label">Minimum Chunks</label>
                                        <input type="number" class="form-control" id="min_chunks" name="min_chunks" value="3" min="2" max="50">
                                        <small class="">Minimum number of chunks to generate</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="card h-100">
                                <div class="card-header">
                                    <h6 class="mb-0">Encryption Options</h6>
                                </div>
                                <div class="card-body">
                                    <div class="mb-3">
                                        <label class="form-label">Encryption Method</label>
                                        <select class="form-select" name="encryption_method" disabled>
                                            <option selected value="AES-256-CBC">AES-256-CBC</option>
                                        </select>
                                        <small class="">Strong encryption to secure your code</small>
                                    </div>
                                    
                                    <div class="form-check mb-3">
                                        <input class="form-check-input" type="checkbox" name="use_gzip" id="use_gzip" checked>
                                        <label class="form-check-label" for="use_gzip">
                                            GZIP Compression
                                            <small class="d-block ">Compress chunks before encryption</small>
                                        </label>
                                    </div>
                                    
                                    <div class="form-check mb-3">
                                        <input class="form-check-input" type="checkbox" name="use_base64" id="use_base64" checked>
                                        <label class="form-check-label" for="use_base64">
                                            Base64 Encoding
                                            <small class="d-block ">Make chunks safe for storage/transfer</small>
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="mb-4">
                        <div class="card">
                            <div class="card-header bg-primary text-white">
                                <h6 class="mb-0">Advanced Security Features</h6>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-check mb-3">
                                            <input class="form-check-input" type="checkbox" name="add_anti_debugging" id="add_anti_debugging" checked>
                                            <label class="form-check-label" for="add_anti_debugging">
                                                <strong>Anti-Debugging Protection</strong>
                                                <small class="d-block">Detects and blocks debugging attempts</small>
                                            </label>
                                        </div>
                                        
                                        <div class="form-check mb-3">
                                            <input class="form-check-input" type="checkbox" name="enable_self_destruct" id="enable_self_destruct">
                                            <label class="form-check-label" for="enable_self_destruct">
                                                <strong>Self-Destruct Mechanism</strong>
                                                <small class="d-block">Deletes files when tampering detected</small>
                                            </label>
                                        </div>
                                    </div>
                                    
                                    <div class="col-md-6">
                                        <div class="form-check mb-3">
                                            <input class="form-check-input" type="checkbox" name="encrypt_loader" id="encrypt_loader" checked>
                                            <label class="form-check-label" for="encrypt_loader">
                                                <strong>Encrypted Loader</strong>
                                                <small class="d-block">Creates AES-256 encrypted loader</small>
                                            </label>
                                        </div>
                                        
                                        <div class="form-check mb-3">
                                            <input class="form-check-input" type="checkbox" name="add_junk_eval" id="add_junk_eval" checked>
                                            <label class="form-check-label" for="add_junk_eval">
                                                <strong>Anti-Reverse Engineering</strong>
                                                <small class="d-block">Adds junk code and obfuscation techniques</small>
                                            </label>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="mt-2">
                                    <div class="alert alert-warning mb-0">
                                        <i class="fas fa-exclamation-triangle me-2"></i>
                                        <small>These advanced features provide optimal protection but may increase file size. The self-destruct mechanism will permanently delete files when tampering is detected.</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="mb-4">
                        <div class="card">
                            <div class="card-header">
                                <h6 class="mb-0">Encryption Key</h6>
                            </div>
                            <div class="card-body">
                                <div class="mb-3">
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="fas fa-key"></i></span>
                                        <input type="text" class="form-control" id="encryption_key" name="encryption_key" placeholder="Leave empty for auto-generated key" aria-label="Encryption Key">
                                        <button class="btn btn-outline-secondary" type="button" id="generateKeyBtn" onclick="document.getElementById('encryption_key').value = generateRandomKey(32);">Generate</button>
                                    </div>
                                    <small class="">Secure key used to encrypt code chunks. Auto-generated if left empty.</small>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="alert alert-info">
                        <i class="fas fa-info-circle me-2"></i>
                        Chunking splits your code into multiple encrypted files, making it harder to recover the full code. Each chunk is encrypted separately for additional security.
                    </div>
                    
                    <div class="d-flex justify-content-between">
                        <a href="index.php?tab=obfuscate" class="btn btn-outline-secondary">
                            <i class="fas fa-arrow-left me-2"></i> Back to Obfuscation
                        </a>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-puzzle-piece me-2"></i> Create Chunks & Continue
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    
    <div class="col-lg-4">
        <div class="card">
            <div class="card-header">
                <h5>Encryption Process</h5>
            </div>
            <div class="card-body">
                
                    <div class="process-diagram text-center mb-4">
                    <div class="row g-0 mb-3">
                        <div class="col">
                            <div class="process-step">
                                <i class="fas fa-code" style="font-size: 2rem; color: var(--primary-color);"></i>
                                <p class="mt-2 mb-0"><small>Obfuscated Code</small></p>
                            </div>
                        </div>
                        <div class="col-auto d-flex align-items-center">
                            <i class="fas fa-arrow-right mx-3"></i>
                        </div>
                        <div class="col">
                            <div class="process-step">
                                <i class="fas fa-puzzle-piece" style="font-size: 2rem; color: var(--primary-color);"></i>
                                <p class="mt-2 mb-0"><small>Chunking</small></p>
                            </div>
                        </div>
                    </div>
                    <div class="row g-0">
                        <div class="col">
                            <div class="process-step">
                                <i class="fas fa-download" style="font-size: 2rem; color: var(--primary-color);"></i>
                                <p class="mt-2 mb-0"><small>File Output</small></p>
                            </div>
                        </div>
                        <div class="col-auto d-flex align-items-center">
                            <i class="fas fa-arrow-left mx-3"></i>
                        </div>
                        <div class="col">
                            <div class="process-step">
                                <i class="fas fa-lock" style="font-size: 2rem; color: var(--primary-color);"></i>
                                <p class="mt-2 mb-0"><small>Encryption</small></p>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="benefits mt-3">
                    <h6>Benefits of Chunking</h6>
                    <ul class="list-group list-group-flush">
                        <li class="list-group-item d-flex align-items-center py-2">
                            <i class="fas fa-check-circle text-success me-2"></i>
                            <small>Prevents easy access to full source code</small>
                        </li>
                        <li class="list-group-item d-flex align-items-center py-2">
                            <i class="fas fa-check-circle text-success me-2"></i>
                            <small>Military-grade AES-256 encryption</small>
                        </li>
                        <li class="list-group-item d-flex align-items-center py-2">
                            <i class="fas fa-check-circle text-success me-2"></i>
                            <small>Integrity check for each chunk</small>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function generateRandomKey(length) {
    const chars = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';
    let result = '';
    for (let i = 0; i < length; i++) {
        result += chars.charAt(Math.floor(Math.random() * chars.length));
    }
    return result;
}
</script>