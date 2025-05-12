<?php
// Ensure we have an uploaded file
if (!isset($_SESSION['uploaded_file'])) {
    $_SESSION['error'] = 'No file uploaded. Please upload a PHP file first.';
    header('Location: index.php?tab=upload');
    exit;
}

// Get code preview (first 20 lines)
$code = $_SESSION['uploaded_file'];
$codeLines = explode("\n", $code);
$preview = implode("\n", array_slice($codeLines, 0, min(20, count($codeLines))));
if (count($codeLines) > 20) {
    $preview .= "\n// ... (more lines) ...";
}
?>

<h2 class="mb-4"><i class="fas fa-random me-2"></i>Obfuscate Code</h2>

<div class="row">
    <div class="col-md-6">
        <div class="card mb-4">
            <div class="card-header">
                <i class="fas fa-file-code me-2"></i>Original PHP Code Preview
            </div>
            <div class="card-body">
                <pre class="bg-light p-3 rounded"><code><?= htmlspecialchars($preview) ?></code></pre>
                <p class="text-muted">Filename: <?= htmlspecialchars($_SESSION['original_filename']) ?></p>
            </div>
        </div>
    </div>
    
    <div class="col-md-6">
        <div class="card mb-4">
            <div class="card-header">
                <i class="fas fa-cogs me-2"></i>Obfuscation Options
            </div>
            <div class="card-body">
                <form action="index.php" method="post">
                    <input type="hidden" name="action" value="obfuscate">
                    
                    <div class="form-check mb-3">
                        <input class="form-check-input" type="checkbox" id="rename_variables" name="rename_variables" checked>
                        <label class="form-check-label" for="rename_variables">
                            <i class="fas fa-random me-2 text-primary"></i>Rename Variables & Functions
                        </label>
                        <small class="form-text text-muted d-block">
                            Replaces all variable, function, and class names with random strings.
                        </small>
                    </div>
                    
                    <div class="form-check mb-3">
                        <input class="form-check-input" type="checkbox" id="remove_whitespace" name="remove_whitespace" checked>
                        <label class="form-check-label" for="remove_whitespace">
                            <i class="fas fa-compress-alt me-2 text-primary"></i>Remove Comments & Whitespace
                        </label>
                        <small class="form-text text-muted d-block">
                            Removes all comments and unnecessary whitespace from the code.
                        </small>
                    </div>
                    
                    <div class="form-check mb-3">
                        <input class="form-check-input" type="checkbox" id="encode_strings" name="encode_strings" checked>
                        <label class="form-check-label" for="encode_strings">
                            <i class="fas fa-lock me-2 text-primary"></i>Encode String Literals
                        </label>
                        <small class="form-text text-muted d-block">
                            Encodes string literals with base64 and adds decode statements at runtime.
                        </small>
                    </div>
                    
                    <div class="alert alert-warning">
                        <i class="fas fa-exclamation-triangle me-2"></i>Warning: Obfuscation may affect code that uses reflection or evaluates dynamic variable names.
                    </div>
                    
                    <div id="progressBar" class="progress mt-4 d-none">
                        <div class="progress-bar progress-bar-striped progress-bar-animated" role="progressbar" style="width: 0%;" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100">0%</div>
                    </div>
                    
                    <div class="d-grid gap-2 d-md-flex justify-content-md-end mt-4">
                        <a href="index.php?tab=upload" class="btn btn-secondary me-md-2">
                            <i class="fas fa-arrow-left me-2"></i>Back
                        </a>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-code me-2"></i>Obfuscate & Continue
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
