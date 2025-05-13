<!-- Advanced Security Features Section -->
<div class="tab-pane fade" id="advanced" role="tabpanel" aria-labelledby="advanced-tab">
    <div class="doc-section">
        <h3>Advanced Security Features</h3>
        <p>ChunkShield provides advanced security mechanisms to ensure your code remains protected against sophisticated reverse engineering attempts, analysis tools, and unauthorized execution.</p>
        
        <div class="row mt-4">
            <div class="col-md-6">
                <div class="card mb-4">
                    <div class="card-header bg-primary text-white">
                        <h5 class="mb-0"><i class="fas fa-bug me-2"></i> Anti-Debugging Protection</h5>
                    </div>
                    <div class="card-body">
                        <p>Prevents code analysis by detecting and blocking debugging attempts.</p>
                        <ul class="list-group list-group-flush">
                            <li class="list-group-item">
                                <i class="fas fa-check-circle text-success me-2"></i>
                                Detects Xdebug, debug_backtrace(), and other debug tools
                            </li>
                            <li class="list-group-item">
                                <i class="fas fa-check-circle text-success me-2"></i>
                                Blocks execution when debugging is detected
                            </li>
                            <li class="list-group-item">
                                <i class="fas fa-check-circle text-success me-2"></i>
                                Monitors suspicious debug headers and environment variables
                            </li>
                            <li class="list-group-item">
                                <i class="fas fa-check-circle text-success me-2"></i>
                                Logs debugging attempts with IP, timestamp, and user agent
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
            
            <div class="col-md-6">
                <div class="card mb-4">
                    <div class="card-header bg-danger text-white">
                        <h5 class="mb-0"><i class="fas fa-bomb me-2"></i> Self-Destruct Mechanism</h5>
                    </div>
                    <div class="card-body">
                        <p>Automatically removes protected code when tampering is detected.</p>
                        <ul class="list-group list-group-flush">
                            <li class="list-group-item">
                                <i class="fas fa-check-circle text-success me-2"></i>
                                Deletes chunks and loader files when tampered with
                            </li>
                            <li class="list-group-item">
                                <i class="fas fa-check-circle text-success me-2"></i>
                                Activates on debugging attempts, license violations, or fingerprint failures
                            </li>
                            <li class="list-group-item">
                                <i class="fas fa-check-circle text-success me-2"></i>
                                Creates audit logs of self-destruct events
                            </li>
                            <li class="list-group-item">
                                <i class="fas fa-check-circle text-success me-2"></i>
                                Can be enabled/disabled via configuration
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="row">
            <div class="col-md-6">
                <div class="card mb-4">
                    <div class="card-header bg-success text-white">
                        <h5 class="mb-0"><i class="fas fa-lock me-2"></i> Encrypted Loader</h5>
                    </div>
                    <div class="card-body">
                        <p>Creates a self-decrypting loader that resists static analysis.</p>
                        <ul class="list-group list-group-flush">
                            <li class="list-group-item">
                                <i class="fas fa-check-circle text-success me-2"></i>
                                Uses AES-256-CBC encryption with a unique key
                            </li>
                            <li class="list-group-item">
                                <i class="fas fa-check-circle text-success me-2"></i>
                                Combines with base64 encoding and gzip compression
                            </li>
                            <li class="list-group-item">
                                <i class="fas fa-check-circle text-success me-2"></i>
                                Prevents static code analysis tools from reading the loader
                            </li>
                            <li class="list-group-item">
                                <i class="fas fa-check-circle text-success me-2"></i>
                                Self-decrypting at runtime with minimal overhead
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
            
            <div class="col-md-6">
                <div class="card mb-4">
                    <div class="card-header bg-info text-white">
                        <h5 class="mb-0"><i class="fas fa-shield-alt me-2"></i> Anti-Reverse Engineering</h5>
                    </div>
                    <div class="card-body">
                        <p>Implements techniques to frustrate reverse engineering attempts.</p>
                        <ul class="list-group list-group-flush">
                            <li class="list-group-item">
                                <i class="fas fa-check-circle text-success me-2"></i>
                                Adds junk code that confuses decompilers
                            </li>
                            <li class="list-group-item">
                                <i class="fas fa-check-circle text-success me-2"></i>
                                Implements random variable and function names
                            </li>
                            <li class="list-group-item">
                                <i class="fas fa-check-circle text-success me-2"></i>
                                Each generated loader has a unique structure
                            </li>
                            <li class="list-group-item">
                                <i class="fas fa-check-circle text-success me-2"></i>
                                Polymorphic code techniques resist signature-based detection
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="row mt-4">
            <div class="col-12">
                <div class="card mb-4">
                    <div class="card-header bg-dark text-white">
                        <h5 class="mb-0"><i class="fas fa-microchip me-2"></i> Semi-Compiler Technology</h5>
                    </div>
                    <div class="card-body">
                        <p>Our most advanced protection mechanism that transforms PHP code into a format extremely difficult to reverse engineer.</p>
                        
                        <div class="row">
                            <div class="col-md-6">
                                <h6>Protection Techniques:</h6>
                                <ul class="list-group list-group-flush">
                                    <li class="list-group-item">
                                        <i class="fas fa-check-circle text-success me-2"></i>
                                        Multiple encoding layers (hex, base64, binary operations)
                                    </li>
                                    <li class="list-group-item">
                                        <i class="fas fa-check-circle text-success me-2"></i>
                                        Encryption with AES and XOR algorithms
                                    </li>
                                    <li class="list-group-item">
                                        <i class="fas fa-check-circle text-success me-2"></i>
                                        Runtime code transformation and evaluation
                                    </li>
                                    <li class="list-group-item">
                                        <i class="fas fa-check-circle text-success me-2"></i>
                                        Advanced variable and function name mangling
                                    </li>
                                </ul>
                            </div>
                            
                            <div class="col-md-6">
                                <h6>Protection Levels:</h6>
                                <div class="table-responsive">
                                    <table class="table table-bordered">
                                        <thead class="table-light">
                                            <tr>
                                                <th>Level</th>
                                                <th>Description</th>
                                                <th>Use Case</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td>1</td>
                                                <td>Basic string and variable encoding</td>
                                                <td>Low-value code</td>
                                            </tr>
                                            <tr>
                                                <td>2</td>
                                                <td>+ Binary operations encoding</td>
                                                <td>General use</td>
                                            </tr>
                                            <tr>
                                                <td>3</td>
                                                <td>+ Eval wrapping and runtime protection</td>
                                                <td>Commercial code</td>
                                            </tr>
                                            <tr>
                                                <td>4</td>
                                                <td>+ Reflection-based execution</td>
                                                <td>High-value code</td>
                                            </tr>
                                            <tr>
                                                <td>5</td>
                                                <td>Maximum protection with all techniques</td>
                                                <td>Critical code</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="alert alert-warning mt-4">
            <div class="d-flex">
                <div class="me-3">
                    <i class="fas fa-exclamation-triangle fa-2x"></i>
                </div>
                <div>
                    <h5 class="alert-heading">Important Security Note</h5>
                    <p class="mb-0">While these advanced security features provide excellent protection, always keep your original source code in a secure backup. The self-destruct mechanism will permanently delete chunks and loaders when triggered.</p>
                </div>
            </div>
        </div>
    </div>
</div>