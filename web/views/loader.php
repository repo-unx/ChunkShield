<?php
// Ensure we have chunks info
if (!isset($_SESSION['chunks_info'])) {
    $_SESSION['error'] = 'No chunks found. Please create chunks first.';
    header('Location: index.php?tab=chunk');
    exit;
}

// Get chunks info and encryption key
$chunksInfo = $_SESSION['chunks_info'];
$encryptionKey = $_SESSION['encryption_key'];
?>

<h2 class="mb-4"><i class="fas fa-file-code me-2"></i>Generate Loader</h2>

<div class="row">
    <div class="col-md-6">
        <div class="card mb-4">
            <div class="card-header">
                <i class="fas fa-puzzle-piece me-2"></i>Chunks Information
            </div>
            <div class="card-body">
                <p><strong>Number of Chunks:</strong> <?= count($chunksInfo['chunks']) ?></p>
                <p><strong>Map File:</strong> <?= htmlspecialchars($chunksInfo['mapFile']) ?></p>
                
                <div class="accordion" id="chunksAccordion">
                    <div class="accordion-item">
                        <h2 class="accordion-header" id="chunksHeading">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#chunksCollapse" aria-expanded="false" aria-controls="chunksCollapse">
                                View Chunks Details
                            </button>
                        </h2>
                        <div id="chunksCollapse" class="accordion-collapse collapse" aria-labelledby="chunksHeading" data-bs-parent="#chunksAccordion">
                            <div class="accordion-body">
                                <ul class="list-group">
                                    <?php foreach ($chunksInfo['chunks'] as $index => $chunk): ?>
                                    <li class="list-group-item">
                                        <strong>Chunk <?= $index + 1 ?>:</strong> <?= basename($chunk['file']) ?><br>
                                        <small class="text-muted">Size: <?= round(filesize($chunk['file']) / 1024, 2) ?> KB</small>
                                    </li>
                                    <?php endforeach; ?>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-md-6">
        <div class="card mb-4">
            <div class="card-header">
                <i class="fas fa-cogs me-2"></i>Loader Options
            </div>
            <div class="card-body">
                <form action="index.php" method="post">
                    <input type="hidden" name="action" value="generate_loader">
                    
                    <div class="form-check mb-3">
                        <input class="form-check-input" type="checkbox" id="license_check" name="license_check">
                        <label class="form-check-label" for="license_check">
                            <i class="fas fa-id-card me-2 text-primary"></i>Add License Verification
                        </label>
                        <small class="form-text text-muted d-block">
                            Adds code to verify a license file before loading the chunks.
                        </small>
                    </div>
                    
                    <hr class="my-3">
                    <div class="security-options-section">
                        <h5 class="mb-3"><i class="fas fa-shield-alt me-2 text-danger"></i>Advanced Protection</h5>
                        
                        <div class="junk-code-option mb-3">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="anti_logger" name="anti_logger">
                                <label class="form-check-label" for="anti_logger">
                                    <i class="fas fa-eye-slash me-2 text-warning"></i><strong>Anti-Logger Protection</strong>
                                </label>
                                <small class="form-text text-muted d-block mt-1">
                                    Prevents logging of sensitive data and detects runtime logging attempts.
                                    This helps protect your code against information leakage and debug probing.
                                </small>
                            </div>
                        </div>
                        
                        <div class="junk-code-option mb-3">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="anti_debugger" name="anti_debugger">
                                <label class="form-check-label" for="anti_debugger">
                                    <i class="fas fa-bug-slash me-2 text-danger"></i><strong>Anti-Debugger Protection</strong>
                                </label>
                                <small class="form-text text-muted d-block mt-1">
                                    Adds code to detect and prevent debugging and analysis tools.
                                    Monitors execution timing and prevents step-by-step code analysis.
                                </small>
                            </div>
                        </div>
                        <div class="junk-code-option mb-3">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="junk_code" name="junk_code" checked>
                                <label class="form-check-label" for="junk_code">
                                    <i class="fas fa-random me-2 text-success"></i><strong>Junk Code Injection</strong>
                                </label>
                                <small class="form-text text-muted d-block mt-1">
                                    Adds decoy functions and misleading code to make reverse-engineering more difficult.
                                    This creates fake cryptographic operations and code paths to confuse analysis tools.
                                </small>
                            </div>
                        </div>
                    </div>
                    
                    <hr class="my-3">
                    <div class="fingerprinting-section">
                        <h5 class="mb-3"><i class="fas fa-fingerprint me-2 fingerprinting-icon"></i>Runtime Fingerprinting</h5>
                        
                        <div class="fingerprinting-item mb-3">
                            <label for="allowed_domains" class="form-label">
                                <i class="fas fa-globe me-2 text-primary"></i>Allowed Domains
                            </label>
                            <input type="text" class="form-control" id="allowed_domains" name="allowed_domains" placeholder="example.com, *.subdomain.com">
                            <small class="form-text text-muted">
                                Comma-separated domains where the code is allowed to run. Use * as wildcard for subdomains.
                            </small>
                        </div>
                        
                        <div class="fingerprinting-item mb-3">
                            <label for="allowed_ips" class="form-label">
                                <i class="fas fa-network-wired me-2 text-primary"></i>Allowed IP Addresses
                            </label>
                            <input type="text" class="form-control" id="allowed_ips" name="allowed_ips" placeholder="192.168.1.1, 10.0.0.1, 127.0.0.1">
                            <small class="form-text text-muted">
                                Comma-separated IP addresses where the code is allowed to run. Add 127.0.0.1 for local testing.
                            </small>
                        </div>
                        
                        <div class="fingerprinting-item mb-3">
                            <label for="allowed_paths" class="form-label">
                                <i class="fas fa-folder me-2 text-primary"></i>Allowed Installation Paths
                            </label>
                            <input type="text" class="form-control" id="allowed_paths" name="allowed_paths" placeholder="/var/www, /home/user/public_html">
                            <small class="form-text text-muted">
                                Comma-separated paths where the code is allowed to run. Use phpinfo() to find your server path.
                            </small>
                        </div>
                        <div class="alert alert-info">
                            <i class="fas fa-info-circle me-2"></i>
                            Runtime fingerprinting adds environment validation to prevent unauthorized use. Clear fields to disable specific validations.
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label for="encryption_key_display" class="form-label">
                            <i class="fas fa-key me-2 text-primary"></i>Encryption Key (for reference)
                        </label>
                        <div class="input-group">
                            <input type="text" class="form-control" id="encryption_key_display" value="<?= htmlspecialchars($encryptionKey) ?>" readonly>
                            <button class="btn btn-outline-secondary btn-copy" type="button" data-target="encryption_key_display">
                                <i class="fas fa-copy"></i>
                            </button>
                        </div>
                        <small class="form-text text-muted">
                            This key will be embedded in the loader to decrypt chunks at runtime.
                        </small>
                    </div>
                    
                    <div class="alert alert-warning">
                        <i class="fas fa-exclamation-triangle me-2"></i>Warning: The loader will include the encryption key. 
                        If you need higher security, consider implementing a separate key delivery mechanism.
                    </div>
                    
                    <div id="progressBar" class="progress mt-4 d-none">
                        <div class="progress-bar progress-bar-striped progress-bar-animated" role="progressbar" style="width: 0%;" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100">0%</div>
                    </div>
                    
                    <div class="d-grid gap-2 d-md-flex justify-content-md-end mt-4">
                        <a href="index.php?tab=chunk" class="btn btn-secondary me-md-2">
                            <i class="fas fa-arrow-left me-2"></i>Back
                        </a>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-file-code me-2"></i>Generate Loader & Continue
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
