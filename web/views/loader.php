<div class="section-header mb-4">
    <h2>Generate Loader</h2>
    <p class="">Create a polymorphic loader script that will decrypt and execute your protected code.</p>
</div>

<?php
// Check if chunks are created
if (!isset($_SESSION['chunks_info'])) {
    echo '<div class="alert alert-danger">
            <i class="fas fa-exclamation-circle me-2"></i>
            No chunks available. Please <a href="index.php?tab=chunk">create chunks</a> first.
          </div>';
    exit;
}

// Display file info
$filename = $_SESSION['original_filename'] ?? 'unknown.php';
$chunk_count = count($_SESSION['chunks_info']['chunks'] ?? []);
?>

<div class="row">
    <div class="col-lg-8">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Loader Generator Settings</h5>
                <span class="badge bg-info">
                    <i class="fas fa-puzzle-piece me-1"></i> <?php echo $chunk_count; ?> Chunks Created
                </span>
            </div>
            <div class="card-body">
                <form method="post" action="index.php">
                    <input type="hidden" name="action" value="loader">

                    <div class="row mb-4">
                        <div class="col-md-6">
                            <div class="card h-100">
                                <div class="card-header">
                                    <h6 class="mb-0">Loader Options</h6>
                                </div>
                                <div class="card-body">
                                    <div class="form-check mb-3">
                                        <input class="form-check-input" type="checkbox" name="add_junk_eval" id="add_junk_eval" checked>
                                        <label class="form-check-label" for="add_junk_eval">
                                            Add Junk Eval Blocks
                                            <small class="d-block ">Insert random eval() code to confuse decompilers</small>
                                        </label>
                                    </div>

                                    <div class="mb-3">
                                        <label for="junk_count" class="form-label">Junk Eval Count</label>
                                        <input type="number" class="form-control" id="junk_count" name="junk_count" value="5" min="1" max="20">
                                        <small class="">Number of junk eval blocks to insert</small>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="card h-100">
                                <div class="card-header">
                                    <h5 class="mb-0">License Settings</h5>
                                    <div class="form-check form-switch">
                                        <input class="form-check-input" type="checkbox" id="enableLicense" name="enable_license" checked>
                                        <label class="form-check-label" for="enableLicense">Enable License Protection</label>
                                    </div>
                                </div>
                                <div class="card-body">
                                    <div class="form-check mb-3">
                                        <input class="form-check-input" type="checkbox" name="add_fingerprinting" id="add_fingerprinting" checked>
                                        <label class="form-check-label" for="add_fingerprinting">
                                            Add Environment Fingerprinting
                                            <small class="d-block ">Check domain, IP, and path at runtime</small>
                                        </label>
                                    </div>

                                    <div class="alert alert-warning p-2 small">
                                        <i class="fas fa-info-circle me-1"></i>
                                        Fingerprinting will be set in the License step. You can enable/disable it here.
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="mb-4">
                        <div class="card">
                            <div class="card-header">
                                <h6 class="mb-0">Loader Output</h6>
                            </div>
                            <div class="card-body">
                                <div class="mb-3">
                                    <label for="loader_name" class="form-label">Loader Filename</label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="fas fa-file-code"></i></span>
                                        <input type="text" class="form-control" id="loader_name" name="loader_name" value="loader.php">
                                    </div>
                                    <small class="">Name of the generated loader file</small>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="alert alert-info">
                        <i class="fas fa-info-circle me-2"></i>
                        The loader is a standalone PHP file that will fetch, decrypt, and execute your chunked code. Each time you generate a loader, it will have a unique structure to prevent signature-based detection.
                    </div>

                    <div class="d-flex justify-content-between">
                        <a href="index.php?tab=chunk" class="btn btn-outline-secondary">
                            <i class="fas fa-arrow-left me-2"></i> Back to Chunking
                        </a>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-file-code me-2"></i> Generate Loader & Continue
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="col-lg-4">
        <div class="card">
            <div class="card-header">
                <h5>Loader Structure</h5>
            </div>
            <div class="card-body">
                <div class="loader-diagram mb-4">
                        <div class="code-diagram p-3 rounded" style="background-color: #2c3e50; color: #ecf0f1; font-family: monospace; font-size: 0.8rem; max-height: 400px; overflow: auto;">
<span style="color: #9b59b6;">&lt;?php</span>
<span style="color: #95a5a6;">/* Loader Header */</span>

<span style="color: #3498db;">// Random junk eval blocks</span>
<span style="color: #e74c3c;">eval</span>(<span style="color: #f1c40f;">"&#36;x=rand(1,100);"</span>);

<span style="color: #3498db;">// Fingerprinting code</span>
<span style="color: #2ecc71;">if</span>(<span style="color: #e74c3c;">&#36;_SERVER</span>[<span style="color: #f1c40f;">'HTTP_HOST'</span>]!==<span style="color: #f1c40f;">'example.com'</span>){
    <span style="color: #e74c3c;">die</span>(<span style="color: #f1c40f;">'Invalid domain'</span>);
}

<span style="color: #3498db;">// Decrypt function</span>
<span style="color: #2ecc71;">function</span> <span style="color: #3498db;">decrypt_aVzt9</span>(<span style="color: #e74c3c;">&#36;data</span>) {
    <span style="color: #3498db;">// Decryption logic</span>
}

<span style="color: #3498db;">// Load chunks</span>
<span style="color: #e74c3c;">&#36;chunks</span> = [
    [<span style="color: #f1c40f;">'id'</span> => <span style="color: #f1c40f;">'abc123'</span>, ...],
    [<span style="color: #f1c40f;">'id'</span> => <span style="color: #f1c40f;">'def456'</span>, ...],
];

<span style="color: #3498db;">// Process chunks</span>
<span style="color: #2ecc71;">foreach</span>(<span style="color: #e74c3c;">&#36;chunks</span> <span style="color: #2ecc71;">as</span> <span style="color: #e74c3c;">&#36;chunk</span>) {
    <span style="color: #3498db;">// Load and decrypt...</span>
}

<span style="color: #3498db;">// Execute code</span>
<span style="color: #e74c3c;">eval</span>(<span style="color: #e74c3c;">&#36;decrypted_code</span>);
<span style="color: #9b59b6;">?&gt;</span>
                    </div>
                </div>

                <div class="loader-features mt-3">
                    <h6>Polymorphic Loader Features</h6>
                    <ul class="list-group list-group-flush">
                        <li class="list-group-item d-flex align-items-center py-2">
                            <i class="fas fa-random text-success me-2"></i>
                            <small>Each loader is uniquely generated</small>
                        </li>
                        <li class="list-group-item d-flex align-items-center py-2">
                            <i class="fas fa-shield-alt text-success me-2"></i>
                            <small>Runtime environment validation</small>
                        </li>
                        <li class="list-group-item d-flex align-items-center py-2">
                            <i class="fas fa-puzzle-piece text-success me-2"></i>
                            <small>Chunk integrity verification</small>
                        </li>
                        <li class="list-group-item d-flex align-items-center py-2">
                            <i class="fas fa-magic text-success me-2"></i>
                            <small>Obfuscated decryption routines</small>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>