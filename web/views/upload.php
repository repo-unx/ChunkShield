<div class="section-header mb-4">
    <h2>Input PHP Code</h2>
    <p class="">Start by uploading a PHP file or paste your PHP code directly (without PHP tags) to protect it.</p>
</div>

<div class="row">
    <div class="col-lg-8">
        <div class="card">
            <div class="card-header">
                <ul class="nav nav-tabs card-header-tabs" id="inputTypeTabs" role="tablist">
                    <li class="nav-item" role="presentation">
                        <button class="nav-link active" id="upload-tab" data-bs-toggle="tab" data-bs-target="#upload-content" type="button" role="tab" aria-controls="upload-content" aria-selected="true">
                            <i class="fas fa-upload me-2"></i> Upload File
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="paste-tab" data-bs-toggle="tab" data-bs-target="#paste-content" type="button" role="tab" aria-controls="paste-content" aria-selected="false">
                            <i class="fas fa-code me-2"></i> Paste Code
                        </button>
                    </li>
                </ul>
            </div>
            <div class="card-body">
                <div class="tab-content" id="inputTypeTabContent">
                    <!-- File Upload Tab -->
                    <div class="tab-pane fade show active" id="upload-content" role="tabpanel" aria-labelledby="upload-tab">
                        <form method="post" enctype="multipart/form-data" action="index.php">
                            <input type="hidden" name="action" value="upload">
                            <input type="hidden" name="input_type" value="file">
                            
                            <div class="file-upload-wrapper">
                                <div class="file-upload-icon">
                                    <i class="fas fa-cloud-upload-alt"></i>
                                </div>
                                <h4>Drag & Drop Your PHP File Here</h4>
                                <p class="text">or click to browse</p>
                                <div class="file-name-display mt-3 alert alert-info d-none"></div>
                                <input type="file" name="phpFile" class="file-upload-input" accept=".php">
                            </div>
                            
                            <div class="d-grid mt-4">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-upload me-2"></i> Upload & Continue
                                </button>
                            </div>
                        </form>
                    </div>
                    
                    <!-- Paste Code Tab -->
                    <div class="tab-pane fade" id="paste-content" role="tabpanel" aria-labelledby="paste-tab">
                        <form method="post" action="index.php">
                            <input type="hidden" name="action" value="upload">
                            <input type="hidden" name="input_type" value="paste">
                            
                            <div class="mb-3">
                                <label for="phpCode" class="form-label">PHP Code</label>
                                <textarea name="phpCode" id="phpCode" class="form-control code-editor" rows="15" placeholder="<?php echo htmlspecialchars('<?php
// Paste your PHP code here
function example_function($param) {
    return "Hello, " . $param;
}

$result = example_function("World");
echo $result;
?>'); ?>"><?php
// Example PHP code
function calculate_factorial($n) {
    if ($n <= 1) {
        return 1;
    }
    return $n * calculate_factorial($n - 1);
}

function calculate_fibonacci($n) {
    if ($n <= 1) {
        return $n;
    }
    return calculate_fibonacci($n - 1) + calculate_fibonacci($n - 2);
}

// Usage example
$number = 5;
echo "Factorial of {$number} is: " . calculate_factorial($number) . "\n";
echo "Fibonacci number at position {$number} is: " . calculate_fibonacci($number);
?></textarea>
                            </div>
                            
                            <div class="d-grid">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-code me-2"></i> Process Code & Continue
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-lg-4">
        <div class="card">
            <div class="card-header">
                <h5>Protection Features</h5>
            </div>
            <div class="card-body">
                <ul class="list-group list-group-flush">
                    <li class="list-group-item d-flex align-items-center">
                        <i class="fas fa-check-circle text-success me-2"></i>
                        Code Obfuscation
                    </li>
                    <li class="list-group-item d-flex align-items-center">
                        <i class="fas fa-check-circle text-success me-2"></i>
                        Variable Renaming
                    </li>
                    <li class="list-group-item d-flex align-items-center">
                        <i class="fas fa-check-circle text-success me-2"></i>
                        Junk Code Insertion
                    </li>
                    <li class="list-group-item d-flex align-items-center">
                        <i class="fas fa-check-circle text-success me-2"></i>
                        Chunk & Encrypt
                    </li>
                    <li class="list-group-item d-flex align-items-center">
                        <i class="fas fa-check-circle text-success me-2"></i>
                        Dynamic Loader Generator
                    </li>
                    <li class="list-group-item d-flex align-items-center">
                        <i class="fas fa-check-circle text-success me-2"></i>
                        License Protection
                    </li>
                </ul>
            </div>
        </div>
        
        <div class="card mt-4">
            <div class="card-header">
                <h5>Supported File Types</h5>
            </div>
            <div class="card-body">
                <div class="d-flex align-items-center mb-3">
                    <i class="fas fa-file-code text-primary me-3" style="font-size: 2rem;"></i>
                    <div>
                        <h6 class="mb-0">.PHP Files</h6>
                        <p class="mb-0 small">PHP 5.6 and above</p>
                    </div>
                </div>
                <p class="alert alert-warning small mb-0">
                    <i class="fas fa-exclamation-triangle me-2"></i>
                    Make sure your PHP file is syntactically correct before uploading.
                </p>
            </div>
        </div>
    </div>
</div>
