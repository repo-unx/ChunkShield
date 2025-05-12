<div class="section-header mb-4">
    <h2>Obfuscate Code</h2>
    <p class="">Configure obfuscation settings for your PHP code.</p>
</div>

<?php
// Check if file is uploaded
if (!isset($_SESSION['uploaded_file'])) {
    echo '<div class="alert alert-danger">
            <i class="fas fa-exclamation-circle me-2"></i>
            No file has been uploaded. Please <a href="index.php?tab=upload">upload a file</a> first.
          </div>';
    exit;
}

// Display file info
$filename = $_SESSION['original_filename'] ?? 'unknown.php';
$filesize = strlen($_SESSION['uploaded_file']);
$filesize_formatted = formatFileSize($filesize);
?>

<div class="row">
    <div class="col-lg-8">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Obfuscation Settings</h5>
                <span class="badge bg-primary">
                    <i class="fas fa-file-code me-1"></i> <?php echo htmlspecialchars($filename); ?> (<?php echo $filesize_formatted; ?>)
                </span>
            </div>
            <div class="card-body">
                <form method="post" action="index.php">
                    <input type="hidden" name="action" value="obfuscate">
                    
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <div class="card h-100">
                                <div class="card-header">
                                    <h6 class="mb-0">Basic Options</h6>
                                </div>
                                <div class="card-body">
                                    <div class="form-check mb-3">
                                        <input class="form-check-input" type="checkbox" name="remove_comments" id="remove_comments" checked>
                                        <label class="form-check-label" for="remove_comments">
                                            Remove Comments
                                            <small class="d-block ">Strip all comments from the code</small>
                                        </label>
                                    </div>
                                    
                                    <div class="form-check mb-3">
                                        <input class="form-check-input" type="checkbox" name="remove_whitespace" id="remove_whitespace" checked>
                                        <label class="form-check-label" for="remove_whitespace">
                                            Minimize Whitespace
                                            <small class="d-block ">Remove unnecessary whitespace and line breaks</small>
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="card h-100">
                                <div class="card-header">
                                    <h6 class="mb-0">Advanced Options</h6>
                                </div>
                                <div class="card-body">
                                    <div class="form-check mb-3">
                                        <input class="form-check-input" type="checkbox" name="rename_variables" id="rename_variables" checked>
                                        <label class="form-check-label" for="rename_variables">
                                            Rename Variables & Functions
                                            <small class="d-block ">Replace variable and function names with random ones</small>
                                        </label>
                                    </div>
                                    
                                    <div class="form-check mb-3">
                                        <input class="form-check-input" type="checkbox" name="insert_junk" id="insert_junk" checked>
                                        <label class="form-check-label" for="insert_junk">
                                            Insert Junk Code
                                            <small class="d-block ">Add random code that does nothing</small>
                                        </label>
                                    </div>
                                    
                                    <div class="mb-3">
                                        <label for="junk_density" class="form-label">Junk Code Density (1-10)</label>
                                        <input type="range" class="form-range" name="junk_density" id="junk_density" min="1" max="10" value="3" oninput="this.nextElementSibling.value = this.value">
                                        <output>3</output>
                                        <small class=" d-block">Higher values add more junk code (may increase file size)</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="alert alert-info">
                        <i class="fas fa-info-circle me-2"></i>
                        These settings will affect how well your code is protected. More aggressive obfuscation makes the code harder to understand but might impact performance.
                    </div>
                    
                    <div class="d-flex justify-content-between">
                        <a href="index.php?tab=upload" class="btn btn-outline-secondary">
                            <i class="fas fa-arrow-left me-2"></i> Back to Upload
                        </a>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-code me-2"></i> Obfuscate & Continue
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    
    <div class="col-lg-4">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Code Preview</h5>
                <span class="badge bg-primary">
                    <i class="fas fa-terminal me-1"></i> Original Code
                </span>
            </div>
            <div class="card-body">
                <div class="row mb-3">
                    <div class="col-12">
                        <div id="originalCode" class="code-preview original-code p-2 rounded" style="background-color: #2c3e50; color: #ecf0f1; font-family: monospace; white-space: pre-wrap; height: 150px; overflow: auto; font-size: 0.8rem;"><?php echo htmlspecialchars($_SESSION['uploaded_file'] ?? '// No code uploaded'); ?></div>
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-12 d-flex justify-content-between align-items-center">
                        <span class="badge bg-secondary">
                            <i class="fas fa-file-alt me-1"></i> Lines: <?php echo substr_count($_SESSION['uploaded_file'], "\n") + 1; ?>
                        </span>
                        <span class="badge bg-secondary">
                            <i class="fas fa-file-code me-1"></i> Size: <?php echo $filesize_formatted; ?>
                        </span>
                    </div>
                </div>


                <div class="benefits mt-3">
                    <h6>Benefits of Obfuscation</h6>
                    <ul class="list-group list-group-flush">
                        <li class="list-group-item d-flex align-items-center py-2">
                            <i class="fas fa-shield-alt text-success me-2"></i>
                            <small>Makes reverse-engineering difficult</small>
                        </li>
                        <li class="list-group-item d-flex align-items-center py-2">
                            <i class="fas fa-eye-slash text-success me-2"></i>
                            <small>Hides business logic from prying eyes</small>
                        </li>
                        <li class="list-group-item d-flex align-items-center py-2">
                            <i class="fas fa-lock text-success me-2"></i>
                            <small>First layer of protection for your IP</small>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>