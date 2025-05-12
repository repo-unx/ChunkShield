<div class="section-header mb-4">
    <h2>Documentation</h2>
    <p class="">Learn how to use ChunkShield to protect your PHP code.</p>
</div>

<div class="row">
    <div class="col-lg-3">
        <div class="card mb-4">
            <div class="card-body">
                <nav class="docs-nav" id="docs-nav">
                    <div class="nav flex-column nav-pills" role="tablist" aria-orientation="vertical">
                        <a class="nav-link active" id="overview-tab" data-bs-toggle="pill" href="#overview" role="tab" aria-controls="overview" aria-selected="true">Overview</a>
                        <a class="nav-link" id="obfuscation-tab" data-bs-toggle="pill" href="#obfuscation" role="tab" aria-controls="obfuscation" aria-selected="false">Obfuscation</a>
                        <a class="nav-link" id="chunking-tab" data-bs-toggle="pill" href="#chunking" role="tab" aria-controls="chunking" aria-selected="false">Chunking</a>
                        <a class="nav-link" id="loader-tab" data-bs-toggle="pill" href="#loader" role="tab" aria-controls="loader" aria-selected="false">Loader</a>
                        <a class="nav-link" id="license-tab" data-bs-toggle="pill" href="#license" role="tab" aria-controls="license" aria-selected="false">License</a>
                        <a class="nav-link" id="deployment-tab" data-bs-toggle="pill" href="#deployment" role="tab" aria-controls="deployment" aria-selected="false">Deployment</a>
                        <a class="nav-link" id="faq-tab" data-bs-toggle="pill" href="#faq" role="tab" aria-controls="faq" aria-selected="false">FAQ</a>
                    </div>
                </nav>
            </div>
        </div>
    </div>
    
    <div class="col-lg-9">
        <div class="card">
            <div class="card-body">
                <div class="tab-content" id="docs-tab-content">
                    <!-- Overview Section -->
                    <div class="tab-pane fade show active" id="overview" role="tabpanel" aria-labelledby="overview-tab">
                        <div class="doc-section">
                            <h3>ChunkShield: PHP Code Protection System</h3>
                            <p>ChunkShield is a comprehensive PHP code protection system that combines multiple security techniques to protect your valuable PHP code from unauthorized access, analysis, and theft.</p>
                            
                            <h4 class="mt-4">Protection Layers</h4>
                            <div class="row mt-3">
                                <div class="col-md-6">
                                    <div class="card mb-3">
                                        <div class="card-body">
                                            <h5 class="card-title"><i class="fas fa-code text-primary me-2"></i> Obfuscation</h5>
                                            <p class="card-text">Transforms your readable PHP code into a complex, difficult-to-understand format while maintaining functionality.</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="card mb-3">
                                        <div class="card-body">
                                            <h5 class="card-title"><i class="fas fa-puzzle-piece text-primary me-2"></i> Chunking</h5>
                                            <p class="card-text">Splits your obfuscated code into multiple encrypted chunks, making it harder to recover the full source code.</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="card mb-3">
                                        <div class="card-body">
                                            <h5 class="card-title"><i class="fas fa-lock text-primary me-2"></i> Encryption</h5>
                                            <p class="card-text">Secures each chunk with AES-256-CBC encryption, unreadable without the proper decryption key.</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="card mb-3">
                                        <div class="card-body">
                                            <h5 class="card-title"><i class="fas fa-file-code text-primary me-2"></i> Polymorphic Loader</h5>
                                            <p class="card-text">Generates a unique loader script that decrypts and executes your protected code at runtime.</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="card mb-3">
                                        <div class="card-body">
                                            <h5 class="card-title"><i class="fas fa-key text-primary me-2"></i> License Protection</h5>
                                            <p class="card-text">Adds runtime validation to ensure your code only runs in authorized environments.</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <h4 class="mt-4">How It Works</h4>
                            <ol>
                                <li>Upload your PHP file through the web interface</li>
                                <li>Configure obfuscation options to hide your code logic</li>
                                <li>Set up chunking and encryption parameters</li>
                                <li>Generate a polymorphic loader that reassembles your code</li>
                                <li>Create license restrictions if needed</li>
                                <li>Download and deploy the protected package</li>
                            </ol>
                        </div>
                    </div>
                    
                    <!-- Obfuscation Section -->
                    <div class="tab-pane fade" id="obfuscation" role="tabpanel" aria-labelledby="obfuscation-tab">
                        <div class="doc-section">
                            <h3>Obfuscation</h3>
                            <p>Obfuscation is the first layer of protection that makes your PHP code difficult to understand while preserving its functionality.</p>
                            
                            <h4 class="mt-4">Obfuscation Techniques</h4>
                            <div class="table-responsive">
                                <table class="table">
                                    <thead>
                                        <tr>
                                            <th>Technique</th>
                                            <th>Description</th>
                                            <th>Effect</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td>Comment Removal</td>
                                            <td>Strips all comments from your code</td>
                                            <td>Removes explanations and documentation</td>
                                        </tr>
                                        <tr>
                                            <td>Whitespace Removal</td>
                                            <td>Eliminates unnecessary spaces, tabs, and line breaks</td>
                                            <td>Makes code harder to read and format</td>
                                        </tr>
                                        <tr>
                                            <td>Variable Renaming</td>
                                            <td>Replaces meaningful variable names with random strings</td>
                                            <td>Hides the purpose and context of variables</td>
                                        </tr>
                                        <tr>
                                            <td>Function Renaming</td>
                                            <td>Replaces function names with random identifiers</td>
                                            <td>Obscures code structure and flow</td>
                                        </tr>
                                        <tr>
                                            <td>Junk Code Insertion</td>
                                            <td>Adds meaningless code snippets throughout your code</td>
                                            <td>Distracts analysts and breaks decompilers</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                            
                            <h4 class="mt-4">Code Example</h4>
                            <div class="row">
                                <div class="col-md-6">
                                    <h5>Original Code</h5>
                                    <pre class="bg-light p-3 rounded"><code>function getUserInfo($userId) {
    // Fetch user data from database
    $userData = fetchUserData($userId);
    
    // Format the user information
    $userInfo = [
        'name' => $userData['name'],
        'email' => $userData['email'],
        'role' => $userData['role']
    ];
    
    return $userInfo;
}</code></pre>
                                </div>
                                <div class="col-md-6">
                                    <h5>Obfuscated Code</h5>
                                    <pre class="bg-dark text-light p-3 rounded"><code>function xKw_92js($a){$QxR=fetchUserData($a);$xRt5=array('name'=>$QxR['name'],'email'=>$QxR['email'],'role'=>$QxR['role']);$wer=rand(0,99);if($wer>50){$wer=$wer+5;}$ZxT='6'.rand(12,99);return $xRt5;}</code></pre>
                                </div>
                            </div>
                            
                            <div class="alert alert-info mt-4">
                                <i class="fas fa-info-circle me-2"></i>
                                <strong>Note:</strong> While obfuscation makes your code difficult to understand, it's not impossible to reverse-engineer. That's why ChunkShield combines obfuscation with encryption and chunking for maximum protection.
                            </div>
                        </div>
                    </div>
                    
                    <!-- Chunking Section -->
                    <div class="tab-pane fade" id="chunking" role="tabpanel" aria-labelledby="chunking-tab">
                        <div class="doc-section">
                            <h3>Chunking & Encryption</h3>
                            <p>After obfuscation, your code is split into multiple chunks and each chunk is encrypted separately. This prevents anyone from accessing your complete source code even if they obtain some of the chunks.</p>
                            
                            <h4 class="mt-4">Chunking Process</h4>
                            <ol>
                                <li>Your obfuscated code is divided into multiple segments (chunks)</li>
                                <li>Each chunk is compressed using GZIP for smaller size</li>
                                <li>Chunks are encrypted using AES-256-CBC encryption</li>
                                <li>Encrypted chunks are encoded with Base64 for safe storage</li>
                                <li>Each chunk is saved as a separate file with a random name</li>
                                <li>A metadata file is created to track chunk order and relationships</li>
                            </ol>
                            
                            <h4 class="mt-4">Encryption Details</h4>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="card mb-3">
                                        <div class="card-body">
                                            <h5 class="card-title">Algorithm</h5>
                                            <p class="card-text">AES-256-CBC (Advanced Encryption Standard)</p>
                                            <ul class="list-unstyled">
                                                <li><i class="fas fa-check-circle text-success me-2"></i> 256-bit key length</li>
                                                <li><i class="fas fa-check-circle text-success me-2"></i> Cipher Block Chaining mode</li>
                                                <li><i class="fas fa-check-circle text-success me-2"></i> Random Initialization Vector (IV)</li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="card mb-3">
                                        <div class="card-body">
                                            <h5 class="card-title">Security Features</h5>
                                            <ul class="list-unstyled">
                                                <li><i class="fas fa-check-circle text-success me-2"></i> Unique encryption key for each project</li>
                                                <li><i class="fas fa-check-circle text-success me-2"></i> Hash verification for integrity checks</li>
                                                <li><i class="fas fa-check-circle text-success me-2"></i> Metadata protection</li>
                                                <li><i class="fas fa-check-circle text-success me-2"></i> Secure key management</li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <h4 class="mt-4">File Structure</h4>
                            <pre class="bg-light p-3 rounded"><code>protected_project/
├── loader.php              # Main loader file
└── chunks/                 # Directory containing encrypted chunks
    ├── metadata.json       # Encrypted metadata about chunks
    ├── a8f2c1d3.chunk      # Encrypted chunk 1
    ├── b7e4d2c5.chunk      # Encrypted chunk 2
    └── f9a3b2e1.chunk      # Encrypted chunk 3</code></pre>
                            
                            <div class="alert alert-warning mt-4">
                                <i class="fas fa-exclamation-triangle me-2"></i>
                                <strong>Important:</strong> Always keep your encryption key secure. If an attacker obtains both your chunks and the encryption key, they could potentially decrypt your code.
                            </div>
                        </div>
                    </div>
                    
                    <!-- Loader Section -->
                    <div class="tab-pane fade" id="loader" role="tabpanel" aria-labelledby="loader-tab">
                        <div class="doc-section">
                            <h3>Polymorphic Loader</h3>
                            <p>The loader is a PHP script that decrypts your chunked code at runtime and executes it. Each loader is uniquely generated with different internal structure to prevent signature detection.</p>
                            
                            <h4 class="mt-4">Loader Features</h4>
                            <div class="row">
                                <div class="col-md-6">
                                    <ul class="list-group mb-4">
                                        <li class="list-group-item d-flex align-items-center">
                                            <i class="fas fa-random text-primary me-3"></i>
                                            <div>
                                                <strong>Polymorphic Generation</strong>
                                                <p class="mb-0 small ">Each loader has a unique structure</p>
                                            </div>
                                        </li>
                                        <li class="list-group-item d-flex align-items-center">
                                            <i class="fas fa-puzzle-piece text-primary me-3"></i>
                                            <div>
                                                <strong>Chunk Management</strong>
                                                <p class="mb-0 small ">Loads and assembles chunks in correct order</p>
                                            </div>
                                        </li>
                                        <li class="list-group-item d-flex align-items-center">
                                            <i class="fas fa-shield-alt text-primary me-3"></i>
                                            <div>
                                                <strong>Integrity Verification</strong>
                                                <p class="mb-0 small ">Ensures chunks haven't been tampered with</p>
                                            </div>
                                        </li>
                                    </ul>
                                </div>
                                <div class="col-md-6">
                                    <ul class="list-group mb-4">
                                        <li class="list-group-item d-flex align-items-center">
                                            <i class="fas fa-fingerprint text-primary me-3"></i>
                                            <div>
                                                <strong>Environment Fingerprinting</strong>
                                                <p class="mb-0 small ">Validates execution environment</p>
                                            </div>
                                        </li>
                                        <li class="list-group-item d-flex align-items-center">
                                            <i class="fas fa-code text-primary me-3"></i>
                                            <div>
                                                <strong>Junk Eval Blocks</strong>
                                                <p class="mb-0 small ">Adds confusion to the loader code</p>
                                            </div>
                                        </li>
                                        <li class="list-group-item d-flex align-items-center">
                                            <i class="fas fa-clock text-primary me-3"></i>
                                            <div>
                                                <strong>Runtime Execution</strong>
                                                <p class="mb-0 small ">Decrypts and runs code on demand</p>
                                            </div>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                            
                            <h4 class="mt-4">How the Loader Works</h4>
                            <ol>
                                <li>When accessed, the loader first performs environment checks (if license protection is enabled)</li>
                                <li>It then loads the list of chunks from metadata or from hardcoded information</li>
                                <li>Each chunk is loaded from the <code>chunks/</code> directory</li>
                                <li>Chunks are decrypted using the embedded encryption key</li>
                                <li>The integrity of each chunk is verified using hash checks</li>
                                <li>Decrypted chunks are combined in the correct order</li>
                                <li>The assembled code is executed using PHP's <code>eval()</code> function</li>
                            </ol>
                            
                            <div class="alert alert-info mt-4">
                                <i class="fas fa-info-circle me-2"></i>
                                <strong>Note:</strong> The loader.php file and chunks/ directory must be kept together in the same relative location for the loader to work properly.
                            </div>
                        </div>
                    </div>
                    
                    <!-- License Section -->
                    <div class="tab-pane fade" id="license" role="tabpanel" aria-labelledby="license-tab">
                        <div class="doc-section">
                            <h3>License Protection</h3>
                            <p>License protection adds runtime verification to ensure your code only runs in authorized environments. This is optional but recommended for commercial applications.</p>
                            
                            <h4 class="mt-4">License Types</h4>
                            <div class="table-responsive">
                                <table class="table">
                                    <thead>
                                        <tr>
                                            <th>Restriction Type</th>
                                            <th>Description</th>
                                            <th>Example</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td>Domain</td>
                                            <td>Restricts execution to specific domain names</td>
                                            <td><code>example.com</code>, <code>client.org</code></td>
                                        </tr>
                                        <tr>
                                            <td>IP Address</td>
                                            <td>Limits execution to specific IP addresses or ranges</td>
                                            <td><code>192.168.1.10</code>, <code>10.0.0.*</code></td>
                                        </tr>
                                        <tr>
                                            <td>Server Path</td>
                                            <td>Allows execution only in specific directories</td>
                                            <td><code>/var/www/client</code>, <code>/home/user/apps/*</code></td>
                                        </tr>
                                        <tr>
                                            <td>Time-Based</td>
                                            <td>Sets an expiration date for the license</td>
                                            <td>Valid from 2023-01-01 to 2024-01-01</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                            
                            <h4 class="mt-4">License File Format</h4>
                            <p>Licenses are stored as JSON files with the following structure:</p>
                            <pre class="bg-light p-3 rounded"><code>{
  "license_id": "cs_a7b8c9d0",
  "customer_name": "Example Company",
  "customer_email": "customer@example.com",
  "valid_from": 1683905400,
  "valid_to": 1715441400,
  "domain": ["example.com", "client-site.com"],
  "ip": ["192.168.1.*"],
  "path": ["/var/www/html/*"],
  "check_domain": true,
  "check_ip": true,
  "check_path": false,
  "created_at": 1683905400
}</code></pre>
                            
                            <h4 class="mt-4">Validation Process</h4>
                            <p>When license validation is enabled, the loader performs these checks before executing your code:</p>
                            <ol>
                                <li>Verifies the current date is within the license validity period</li>
                                <li>If domain restriction is enabled, checks if the current domain is authorized</li>
                                <li>If IP restriction is enabled, validates the client's IP address</li>
                                <li>If path restriction is enabled, ensures the script is executed from an allowed path</li>
                            </ol>
                            <p>If any check fails, the loader will show an error message and terminate execution.</p>
                            
                            <div class="alert alert-warning mt-4">
                                <i class="fas fa-exclamation-triangle me-2"></i>
                                <strong>Important:</strong> While license protection adds a layer of security, determined attackers might attempt to modify the loader to bypass these checks. Always combine license protection with other security measures.
                            </div>
                        </div>
                    </div>
                    
                    <!-- Deployment Section -->
                    <div class="tab-pane fade" id="deployment" role="tabpanel" aria-labelledby="deployment-tab">
                        <div class="doc-section">
                            <h3>Deployment Guide</h3>
                            <p>Follow these steps to deploy your protected PHP application on a web server.</p>
                            
                            <h4 class="mt-4">Deployment Steps</h4>
                            <div class="accordion" id="deploymentAccordion">
                                <div class="accordion-item">
                                    <h2 class="accordion-header" id="headingOne">
                                        <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                                            Step 1: Download the Protected Files
                                        </button>
                                    </h2>
                                    <div id="collapseOne" class="accordion-collapse collapse show" aria-labelledby="headingOne" data-bs-parent="#deploymentAccordion">
                                        <div class="accordion-body">
                                            <p>After completing the protection process, download the ZIP file containing all protected files from the Output tab.</p>
                                            <p>The ZIP file contains:</p>
                                            <ul>
                                                <li><code>loader.php</code> - The main entry point for your application</li>
                                                <li><code>chunks/</code> directory - Contains all encrypted code chunks</li>
                                                <li><code>chunks/metadata.json</code> - Information about chunk structure (encrypted)</li>
                                                <li>License file (if created) - Contains license restrictions</li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                                <div class="accordion-item">
                                    <h2 class="accordion-header" id="headingTwo">
                                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
                                            Step 2: Upload to Your Web Server
                                        </button>
                                    </h2>
                                    <div id="collapseTwo" class="accordion-collapse collapse" aria-labelledby="headingTwo" data-bs-parent="#deploymentAccordion">
                                        <div class="accordion-body">
                                            <p>Upload the files to your web server using FTP, SFTP, or any file transfer method.</p>
                                            <ul>
                                                <li>Extract the ZIP file if you haven't already</li>
                                                <li>Upload all files to the desired directory on your web server</li>
                                                <li>Maintain the original directory structure</li>
                                                <li>Ensure the <code>loader.php</code> file and <code>chunks/</code> directory remain in the same relative location</li>
                                            </ul>
                                            <div class="alert alert-info">
                                                <i class="fas fa-info-circle me-2"></i>
                                                You can rename the <code>loader.php</code> file to match your original file name if desired (e.g., <code>index.php</code>).
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="accordion-item">
                                    <h2 class="accordion-header" id="headingThree">
                                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseThree" aria-expanded="false" aria-controls="collapseThree">
                                            Step 3: Set File Permissions
                                        </button>
                                    </h2>
                                    <div id="collapseThree" class="accordion-collapse collapse" aria-labelledby="headingThree" data-bs-parent="#deploymentAccordion">
                                        <div class="accordion-body">
                                            <p>Set the appropriate file permissions to ensure security while allowing your web server to read the files.</p>
                                            <div class="table-responsive">
                                                <table class="table">
                                                    <thead>
                                                        <tr>
                                                            <th>File/Directory</th>
                                                            <th>Recommended Permission</th>
                                                            <th>Command (Linux/Unix)</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <tr>
                                                            <td><code>loader.php</code></td>
                                                            <td>644 (rw-r--r--)</td>
                                                            <td><code>chmod 644 loader.php</code></td>
                                                        </tr>
                                                        <tr>
                                                            <td><code>chunks/</code> directory</td>
                                                            <td>755 (rwxr-xr-x)</td>
                                                            <td><code>chmod 755 chunks</code></td>
                                                        </tr>
                                                        <tr>
                                                            <td>Chunk files</td>
                                                            <td>644 (rw-r--r--)</td>
                                                            <td><code>chmod 644 chunks/*</code></td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="accordion-item">
                                    <h2 class="accordion-header" id="headingFour">
                                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseFour" aria-expanded="false" aria-controls="collapseFour">
                                            Step 4: Test Your Protected Application
                                        </button>
                                    </h2>
                                    <div id="collapseFour" class="accordion-collapse collapse" aria-labelledby="headingFour" data-bs-parent="#deploymentAccordion">
                                        <div class="accordion-body">
                                            <p>Access your protected application through the loader file:</p>
                                            <ol>
                                                <li>Navigate to the URL of your loader file (e.g., <code>https://example.com/loader.php</code>)</li>
                                                <li>Verify that your application works correctly</li>
                                                <li>If you've enabled license restrictions, ensure they're working as expected</li>
                                            </ol>
                                            <div class="alert alert-warning">
                                                <i class="fas fa-exclamation-triangle me-2"></i>
                                                If you encounter any issues, check the server error logs for details about what might be going wrong.
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <h4 class="mt-4">Integration with Existing Applications</h4>
                            <p>To integrate protected code into a larger application:</p>
                            <ol>
                                <li>Place the loader.php and chunks/ directory in your application</li>
                                <li>Include the loader file where you need the protected functionality:
                                    <pre class="bg-light p-3 rounded mt-2"><code>&lt;?php
// Include the protected code
include('path/to/loader.php');

// Now you can use functions from the protected code
$result = protected_function($param);
?&gt;</code></pre>
                                </li>
                            </ol>
                            
                            <div class="alert alert-info mt-4">
                                <i class="fas fa-info-circle me-2"></i>
                                <strong>Note:</strong> For optimal protection, consider protecting your entire application rather than just portions of it. This provides a more comprehensive security solution.
                            </div>
                        </div>
                    </div>
                    
                    <!-- FAQ Section -->
                    <div class="tab-pane fade" id="faq" role="tabpanel" aria-labelledby="faq-tab">
                        <div class="doc-section">
                            <h3>Frequently Asked Questions</h3>
                            
                            <div class="accordion" id="faqAccordion">
                                <div class="accordion-item">
                                    <h2 class="accordion-header" id="faqOne">
                                        <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#faqCollapseOne" aria-expanded="true" aria-controls="faqCollapseOne">
                                            Is ChunkShield 100% uncrackable?
                                        </button>
                                    </h2>
                                    <div id="faqCollapseOne" class="accordion-collapse collapse show" aria-labelledby="faqOne" data-bs-parent="#faqAccordion">
                                        <div class="accordion-body">
                                            <p>No code protection system can guarantee 100% security against determined attackers with unlimited time and resources. However, ChunkShield combines multiple layers of protection (obfuscation, encryption, chunking, and license validation) to make reverse-engineering extremely difficult and time-consuming.</p>
                                            <p>The goal is to make recovering your original code so challenging that it's not worth the effort for most potential attackers.</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="accordion-item">
                                    <h2 class="accordion-header" id="faqTwo">
                                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faqCollapseTwo" aria-expanded="false" aria-controls="faqCollapseTwo">
                                            Will protected code run slower?
                                        </button>
                                    </h2>
                                    <div id="faqCollapseTwo" class="accordion-collapse collapse" aria-labelledby="faqTwo" data-bs-parent="#faqAccordion">
                                        <div class="accordion-body">
                                            <p>There is some performance overhead in the initial loading of the protected code, as the loader needs to decrypt and assemble the chunks before execution. However, once the code is loaded and running, the performance impact is minimal.</p>
                                            <p>For most applications, this initial loading time is negligible (usually a fraction of a second). The actual running code maintains nearly the same performance as the original code.</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="accordion-item">
                                    <h2 class="accordion-header" id="faqThree">
                                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faqCollapseThree" aria-expanded="false" aria-controls="faqCollapseThree">
                                            What PHP versions are supported?
                                        </button>
                                    </h2>
                                    <div id="faqCollapseThree" class="accordion-collapse collapse" aria-labelledby="faqThree" data-bs-parent="#faqAccordion">
                                        <div class="accordion-body">
                                            <p>ChunkShield is compatible with PHP 5.6 and above, including PHP 7.x and PHP 8.x. The system is designed to work with modern PHP codebases while maintaining backward compatibility with slightly older versions.</p>
                                            <p>For optimal security and performance, we recommend using PHP 7.4 or newer.</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="accordion-item">
                                    <h2 class="accordion-header" id="faqFour">
                                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faqCollapseFour" aria-expanded="false" aria-controls="faqCollapseFour">
                                            Can I protect WordPress plugins or themes?
                                        </button>
                                    </h2>
                                    <div id="faqCollapseFour" class="accordion-collapse collapse" aria-labelledby="faqFour" data-bs-parent="#faqAccordion">
                                        <div class="accordion-body">
                                            <p>Yes, you can protect WordPress plugins and themes with ChunkShield. However, there are some considerations:</p>
                                            <ul>
                                                <li>It's best to protect the entire plugin/theme rather than individual files</li>
                                                <li>You'll need to modify the main plugin file to use the loader</li>
                                                <li>Some WordPress hooks and filters might require special handling</li>
                                            </ul>
                                            <p>For complex WordPress plugins with many files, you may need to protect each functional component separately and then create a main loader that assembles everything.</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="accordion-item">
                                    <h2 class="accordion-header" id="faqFive">
                                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faqCollapseFive" aria-expanded="false" aria-controls="faqCollapseFive">
                                            What happens if the license expires?
                                        </button>
                                    </h2>
                                    <div id="faqCollapseFive" class="accordion-collapse collapse" aria-labelledby="faqFive" data-bs-parent="#faqAccordion">
                                        <div class="accordion-body">
                                            <p>If you've set an expiration date for the license and that date passes, the loader will display an error message like "License has expired" and will not execute the protected code.</p>
                                            <p>To extend the license, you can either:</p>
                                            <ol>
                                                <li>Create a new license file with an extended expiration date</li>
                                                <li>Modify the existing license file to update the expiration date</li>
                                                <li>Generate a new protected package with updated license settings</li>
                                            </ol>
                                        </div>
                                    </div>
                                </div>
                                <div class="accordion-item">
                                    <h2 class="accordion-header" id="faqSix">
                                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faqCollapseSix" aria-expanded="false" aria-controls="faqCollapseSix">
                                            Can I update protected code after deployment?
                                        </button>
                                    </h2>
                                    <div id="faqCollapseSix" class="accordion-collapse collapse" aria-labelledby="faqSix" data-bs-parent="#faqAccordion">
                                        <div class="accordion-body">
                                            <p>Yes, you can update protected code by following these steps:</p>
                                            <ol>
                                                <li>Make changes to your original source code</li>
                                                <li>Run the code through ChunkShield to create a new protected package</li>
                                                <li>Replace the old protected files on your server with the new ones</li>
                                            </ol>
                                            <p>Each protection process creates a completely new set of files, so you'll need to replace all components (loader and chunks) when updating.</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>