<div class="container">
    <h2 class="mb-4"><i class="fas fa-book me-2"></i>Documentation</h2>

    <div class="row">
        <div class="col-md-3 mb-4">
            <div class="card sticky-top" style="top: 20px;">
                <div class="card-header">
                    <i class="fas fa-list me-2"></i>Documentation Index
                </div>
                <div class="card-body p-0">
                    <div class="list-group list-group-flush">
                        <a href="#overview" class="list-group-item list-group-item-action">Overview</a>
                        <a href="#file-upload" class="list-group-item list-group-item-action">File Upload</a>
                        <a href="#obfuscation" class="list-group-item list-group-item-action">Obfuscation</a>
                        <a href="#chunking" class="list-group-item list-group-item-action">Chunking & Encryption</a>
                        <a href="#loader" class="list-group-item list-group-item-action">Loader Generation</a>
                        <a href="#licensing" class="list-group-item list-group-item-action">Licensing System</a>
                        <a href="#output" class="list-group-item list-group-item-action">Output Files</a>
                        <a href="#advanced" class="list-group-item list-group-item-action">Advanced Features</a>
                        <a href="#integration" class="list-group-item list-group-item-action">Integration Guide</a>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-md-9">
            <div class="card mb-4">
                <div class="card-body">
                    <div id="overview" class="mb-5">
                        <h3 class="border-bottom pb-2 mb-3">Overview</h3>
                        
                        <p>PHP Obfuscator Pro is a comprehensive solution for protecting your PHP source code through multiple security layers:</p>
                        
                        <div class="row mb-4">
                            <div class="col-md-6">
                                <div class="card h-100 border-primary">
                                    <div class="card-header bg-primary text-white">
                                        <i class="fas fa-shield-alt me-2"></i>Protection Layers
                                    </div>
                                    <ul class="list-group list-group-flush">
                                        <li class="list-group-item"><strong>Code Obfuscation</strong> - Makes your code hard to read</li>
                                        <li class="list-group-item"><strong>Chunk Encryption</strong> - Splits and encrypts your code</li>
                                        <li class="list-group-item"><strong>Polymorphic Loaders</strong> - Unique loader code per build</li>
                                        <li class="list-group-item"><strong>License Verification</strong> - Domain and expiry controls</li>
                                        <li class="list-group-item"><strong>Anti-Debugging</strong> - Prevents analysis tools</li>
                                    </ul>
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <div class="card h-100 border-success">
                                    <div class="card-header bg-success text-white">
                                        <i class="fas fa-cogs me-2"></i>Key Features
                                    </div>
                                    <ul class="list-group list-group-flush">
                                        <li class="list-group-item"><strong>Modern UI</strong> - Clean, responsive interface</li>
                                        <li class="list-group-item"><strong>Step-by-Step Process</strong> - Clear workflow</li>
                                        <li class="list-group-item"><strong>Advanced Options</strong> - Fine-tuned control</li>
                                        <li class="list-group-item"><strong>Certificate Generation</strong> - For enhanced licensing</li>
                                        <li class="list-group-item"><strong>Output ZIP</strong> - Everything packaged neatly</li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                        
                        <p>This documentation provides detailed information about each step of the process and advanced usage scenarios.</p>
                    </div>

                    <div id="file-upload" class="mb-5">
                        <h3 class="border-bottom pb-2 mb-3">File Upload</h3>
                        
                        <p>The first step is to upload your PHP file for obfuscation.</p>
                        
                        <h5 class="mt-4">Supported File Types</h5>
                        <p>Currently, only PHP files with the <code>.php</code> extension are supported.</p>
                        
                        <h5 class="mt-4">Size Limitations</h5>
                        <p>Maximum file size is determined by your server configuration (typically 10MB). For larger files, consider breaking them into smaller modules.</p>
                        
                        <h5 class="mt-4">Code Compatibility</h5>
                        <p>The obfuscator works best with:</p>
                        <ul>
                            <li>PHP versions 7.2 and above</li>
                            <li>Procedural and object-oriented code</li>
                            <li>Clean, syntax-error-free code</li>
                        </ul>
                        
                        <div class="alert alert-info">
                            <i class="fas fa-lightbulb me-2"></i>Tip: Before obfuscating, make sure your code runs without errors. Obfuscation can make debugging much more difficult.
                        </div>
                    </div>
                    
                    <div id="obfuscation" class="mb-5">
                        <h3 class="border-bottom pb-2 mb-3">Obfuscation</h3>
                        
                        <p>The obfuscation process transforms your code to make it difficult to understand while preserving its functionality.</p>
                        
                        <h5 class="mt-4">Available Obfuscation Techniques</h5>
                        <ul>
                            <li><strong>Variable Renaming</strong> - Replaces meaningful variable names with random strings</li>
                            <li><strong>Whitespace Removal</strong> - Removes all unnecessary spaces, tabs and line breaks</li>
                            <li><strong>String Encoding</strong> - Converts strings into encoded expressions</li>
                            <li><strong>Comment Removal</strong> - Strips out all comments from the code</li>
                        </ul>
                        
                        <h5 class="mt-4">Obfuscation Settings</h5>
                        <p>You can customize the obfuscation process by selecting which techniques to apply. For maximum security, enable all options.</p>
                        
                        <div class="alert alert-warning">
                            <i class="fas fa-exclamation-triangle me-2"></i>Note: More aggressive obfuscation might cause issues with some dynamic code structures like variable variables or complex eval expressions.
                        </div>
                    </div>
                    
                    <div id="chunking" class="mb-5">
                        <h3 class="border-bottom pb-2 mb-3">Chunking & Encryption</h3>
                        
                        <p>Chunking splits your obfuscated code into multiple encrypted pieces, providing an additional layer of security.</p>
                        
                        <h5 class="mt-4">Chunk Size</h5>
                        <p>You can specify the chunk size in kilobytes. Smaller chunks increase security but create more files to manage.</p>
                        
                        <h5 class="mt-4">Encryption</h5>
                        <p>Each chunk is encrypted using strong encryption algorithms:</p>
                        <ul>
                            <li>AES-256-CBC (default)</li>
                            <li>AES-128-CBC</li>
                            <li>AES-256-CTR</li>
                            <li>AES-192-CBC</li>
                        </ul>
                        
                        <h5 class="mt-4">Encryption Key</h5>
                        <p>A secure random encryption key is generated automatically, but you can also provide your own key. This key will be embedded in the loader script.</p>
                        
                        <div class="alert alert-info">
                            <i class="fas fa-lightbulb me-2"></i>Security Tip: For the highest security, consider implementing a separate key delivery mechanism in your application rather than embedding the key in the loader.
                        </div>
                    </div>
                    
                    <div id="loader" class="mb-5">
                        <h3 class="border-bottom pb-2 mb-3">Loader Generation</h3>
                        
                        <p>The loader is a PHP script that decrypts and executes your chunked code at runtime.</p>
                        
                        <h5 class="mt-4">Polymorphic Loaders</h5>
                        <p>Each time you generate a loader, you get a unique implementation with:</p>
                        <ul>
                            <li>Randomized variable names</li>
                            <li>Different encryption algorithms</li>
                            <li>Varying code structures</li>
                            <li>Unpredictable decryption workflows</li>
                        </ul>
                        <p>This prevents attackers from creating universal decoding tools for your protected code.</p>
                        
                        <h5 class="mt-4">Advanced Protection Options</h5>
                        <ul>
                            <li><strong>Anti-Logger Protection</strong> - Prevents sensitive code and data from being logged</li>
                            <li><strong>Anti-Debugger Protection</strong> - Detects and thwarts debugging attempts</li>
                            <li><strong>License Verification</strong> - Adds domain-specific licensing checks</li>
                        </ul>
                        
                        <div class="alert alert-warning">
                            <i class="fas fa-exclamation-triangle me-2"></i>Note: Anti-debugging features might interfere with legitimate development environments. Use these options only for production deployments.
                        </div>
                    </div>
                    
                    <div id="licensing" class="mb-5">
                        <h3 class="border-bottom pb-2 mb-3">Licensing System</h3>
                        
                        <p>The licensing system allows you to control where and when your code can be executed.</p>
                        
                        <h5 class="mt-4">License Types</h5>
                        <ul>
                            <li><strong>Standard License</strong> - Basic license with domain and expiry controls</li>
                            <li><strong>Certificate</strong> - Enhanced license with issuer information and certificate type</li>
                        </ul>
                        
                        <h5 class="mt-4">License Parameters</h5>
                        <ul>
                            <li><strong>License Key</strong> - Unique identifier for the license</li>
                            <li><strong>Domain</strong> - The domain where the code is allowed to run (supports wildcards)</li>
                            <li><strong>Expiry Date</strong> - When the license will expire</li>
                            <li><strong>Security Level</strong> - Controls encryption, signatures, and hashing options</li>
                        </ul>
                        
                        <h5 class="mt-4">Security Features</h5>
                        <ul>
                            <li><strong>Encryption</strong> - Encrypts the license data</li>
                            <li><strong>Digital Signatures</strong> - Adds tampering protection</li>
                            <li><strong>Hashing</strong> - Ensures integrity of the license</li>
                        </ul>
                        
                        <div class="alert alert-info">
                            <i class="fas fa-lightbulb me-2"></i>Tip: For multi-domain licenses, use wildcard notation (e.g., *.example.com) or separate licenses for each domain.
                        </div>
                    </div>
                    
                    <div id="output" class="mb-5">
                        <h3 class="border-bottom pb-2 mb-3">Output Files</h3>
                        
                        <p>The final step provides all the files needed to deploy your protected code.</p>
                        
                        <h5 class="mt-4">Generated Files</h5>
                        <ul>
                            <li><strong>Obfuscated File</strong> - Your original code after obfuscation</li>
                            <li><strong>Loader File</strong> - The script that loads and decrypts your chunks</li>
                            <li><strong>Chunks</strong> - Encrypted portions of your code</li>
                            <li><strong>Map File</strong> - JSON file describing chunk order and checksums</li>
                            <li><strong>License Files</strong> - License and verification files (if generated)</li>
                        </ul>
                        
                        <h5 class="mt-4">Deployment Structure</h5>
                        <p>When deploying your protected code, maintain this directory structure:</p>
                        <pre class="bg-light p-3 rounded">
your-project/
├── loader.php
├── license.lic (if using licensing)
├── chunks/
│   ├── chunk1.dat
│   ├── chunk2.dat
│   └── ...
└── map/
    └── chunks.map.json
</pre>
                        
                        <div class="alert alert-info">
                            <i class="fas fa-lightbulb me-2"></i>Tip: The ZIP download contains all files with the correct structure already set up.
                        </div>
                    </div>
                    
                    <div id="advanced" class="mb-5">
                        <h3 class="border-bottom pb-2 mb-3">Advanced Features</h3>
                        
                        <h5 class="mt-4">Anti-Logging Protection</h5>
                        <p>This feature prevents sensitive code information from being exposed through logging mechanisms:</p>
                        <ul>
                            <li>Disables error logging for sensitive operations</li>
                            <li>Detects attempts to log protected content</li>
                            <li>Prevents exposure of encryption keys and algorithms</li>
                        </ul>
                        
                        <h5 class="mt-4">Anti-Debugging Protection</h5>
                        <p>This feature makes it difficult to analyze your code with debugging tools:</p>
                        <ul>
                            <li>Detects common debugging extensions (Xdebug, etc.)</li>
                            <li>Monitors execution timing to identify step-by-step debugging</li>
                            <li>Implements countermeasures when debugging is detected</li>
                            <li>Uses decoy code to confuse analysis tools</li>
                        </ul>
                        
                        <h5 class="mt-4">Customizing Security Levels</h5>
                        <p>You can fine-tune the security level for both licensing and protection features:</p>
                        <ul>
                            <li><strong>Level 1</strong> - Basic protection, suitable for most uses</li>
                            <li><strong>Level 2</strong> - Enhanced protection with signatures</li>
                            <li><strong>Level 3</strong> - Maximum protection with multiple security layers</li>
                        </ul>
                    </div>
                    
                    <div id="integration" class="mb-5">
                        <h3 class="border-bottom pb-2 mb-3">Integration Guide</h3>
                        
                        <h5 class="mt-4">Using in WordPress Plugins</h5>
                        <p>To integrate protected code in WordPress plugins:</p>
                        <ol>
                            <li>Include the loader in your main plugin file</li>
                            <li>Place chunks and map directories within your plugin directory</li>
                            <li>Use WordPress's plugin_dir_path() for correct file paths</li>
                        </ol>
                        <pre class="bg-light p-3 rounded">
// In your main plugin file
define('MY_PLUGIN_PATH', plugin_dir_path(__FILE__));
require_once MY_PLUGIN_PATH . 'protected/loader.php';
</pre>
                        
                        <h5 class="mt-4">Using in Laravel</h5>
                        <p>For Laravel applications:</p>
                        <ol>
                            <li>Place the loader and chunks in a protected directory</li>
                            <li>Use Laravel's base_path() helper for correct file paths</li>
                            <li>Include the loader in a service provider</li>
                        </ol>
                        <pre class="bg-light p-3 rounded">
// In a service provider
public function register()
{
    require_once base_path('protected/loader.php');
}
</pre>
                        
                        <h5 class="mt-4">Using in Pure PHP Projects</h5>
                        <p>For standard PHP applications:</p>
                        <ol>
                            <li>Place the loader and chunks in your project directory</li>
                            <li>Include the loader at application startup</li>
                            <li>Use __DIR__ for relative paths</li>
                        </ol>
                        <pre class="bg-light p-3 rounded">
// In your main application file
require_once __DIR__ . '/protected/loader.php';
</pre>
                        
                        <div class="alert alert-info">
                            <i class="fas fa-lightbulb me-2"></i>Integration Tip: Always test the protected code in an environment similar to your production environment before deployment.
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>