<h2 class="page-title"><i class="fas fa-upload me-2"></i>Upload PHP File</h2>

<div class="row">
    <div class="col-lg-5 mb-4">
        <div class="card h-100">
            <div class="card-header">
                <i class="fas fa-info-circle me-2"></i>Instructions
            </div>
            <div class="card-body">
                <div class="mb-4">
                    <div class="feature-icon mb-3">
                        <i class="fas fa-shield-alt"></i>
                    </div>
                    <h4 class="mb-3">Secure Your PHP Code</h4>
                    <p>PHP Obfuscator Pro provides industry-leading code protection with multiple layers of security:</p>
                </div>
                
                <div class="mb-4">
                    <h5><i class="fas fa-random text-primary me-2"></i>Code Obfuscation</h5>
                    <p class="text-muted small">Rename variables, remove whitespace, encode strings, and make your code unreadable to humans while keeping it fully functional.</p>
                </div>
                
                <div class="mb-4">
                    <h5><i class="fas fa-puzzle-piece text-primary me-2"></i>Chunk Encryption</h5>
                    <p class="text-muted small">Split your PHP file into multiple encrypted chunks using industry-standard AES-256-CBC encryption.</p>
                </div>
                
                <div class="mb-4">
                    <h5><i class="fas fa-file-code text-primary me-2"></i>Loader Generation</h5>
                    <p class="text-muted small">Create a specialized runtime loader that dynamically decrypts and executes your protected code.</p>
                </div>
                
                <div class="mb-3">
                    <h5><i class="fas fa-key text-primary me-2"></i>License Protection</h5>
                    <p class="text-muted small">Generate domain-locked license files and certificates to prevent unauthorized use of your software.</p>
                </div>
                
                <div class="alert alert-info">
                    <i class="fas fa-lightbulb me-2"></i>Tip: Make sure your PHP code is valid and doesn't contain syntax errors before uploading.
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-lg-7">
        <div class="card mb-4">
            <div class="card-header">
                <i class="fas fa-cloud-upload-alt me-2"></i>Upload Your PHP File
            </div>
            <div class="card-body">
                <form action="index.php" method="post" enctype="multipart/form-data">
                    <input type="hidden" name="action" value="upload">
                    
                    <div class="file-upload-wrapper">
                        <input type="file" id="phpFileUpload" name="phpFile" class="file-upload-input" accept=".php">
                        <div class="text-center">
                            <i class="fas fa-file-code fa-3x mb-3 text-primary"></i>
                            <h5>Drag and drop a PHP file or click to browse</h5>
                            <p class="text-muted">Selected: <span id="file-name">No file chosen</span></p>
                        </div>
                    </div>
                    
                    <div id="fileInfo" class="d-none"></div>
                    
                    <div id="progressBar" class="progress mt-4 d-none">
                        <div class="progress-bar progress-bar-striped progress-bar-animated" role="progressbar" style="width: 0%;" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100">0%</div>
                    </div>
                    
                    <div class="d-grid mt-4">
                        <button type="submit" id="uploadBtn" class="btn btn-primary" disabled>
                            <i class="fas fa-upload me-2"></i>Upload & Continue
                        </button>
                    </div>
                </form>
            </div>
        </div>
        
        <div class="card">
            <div class="card-header">
                <i class="fas fa-question-circle me-2"></i>Need Help?
            </div>
            <div class="card-body">
                <p class="mb-3">Our tool supports PHP files with the following characteristics:</p>
                <ul class="mb-3">
                    <li>Valid PHP syntax (PHP 7.x and 8.x compatible)</li>
                    <li>Files up to 10MB in size</li>
                    <li>Both procedural and object-oriented code</li>
                </ul>
                <p>For larger files or special requirements, consider splitting your code into multiple files before obfuscation.</p>
            </div>
        </div>
    </div>
</div>
