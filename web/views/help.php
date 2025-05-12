<div class="container">
    <h2 class="mb-4"><i class="fas fa-question-circle me-2"></i>Help Center</h2>

    <div class="row">
        <div class="col-md-3 mb-4">
            <div class="card sticky-top" style="top: 20px;">
                <div class="card-header">
                    <i class="fas fa-list me-2"></i>Table of Contents
                </div>
                <div class="card-body p-0">
                    <div class="list-group list-group-flush">
                        <a href="#getting-started" class="list-group-item list-group-item-action">Getting Started</a>
                        <a href="#common-errors" class="list-group-item list-group-item-action">Common Errors</a>
                        <a href="#troubleshooting" class="list-group-item list-group-item-action">Troubleshooting</a>
                        <a href="#faq" class="list-group-item list-group-item-action">Frequently Asked Questions</a>
                        <a href="#contact" class="list-group-item list-group-item-action">Contact Support</a>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-md-9">
            <div class="card mb-4">
                <div class="card-body">
                    <div id="getting-started" class="mb-5">
                        <h3 class="border-bottom pb-2 mb-3"><i class="fas fa-play me-2 text-primary"></i>Getting Started</h3>
                        
                        <h4 class="mt-4">How to Use This Tool</h4>
                        <p>The PHP Obfuscator Pro is a powerful tool designed to protect your PHP code through various security measures. Here's how to use it:</p>
                        
                        <div class="card mb-4 bg-light">
                            <div class="card-body">
                                <h5 class="mb-3">Step-by-Step Process:</h5>
                                <ol class="mb-0">
                                    <li class="mb-2"><strong>Upload</strong> - Upload your PHP source file.</li>
                                    <li class="mb-2"><strong>Obfuscate</strong> - Apply obfuscation techniques to make your code harder to understand.</li>
                                    <li class="mb-2"><strong>Chunk</strong> - Break your code into encrypted chunks for additional security.</li>
                                    <li class="mb-2"><strong>Loader</strong> - Generate a loader script that will decrypt and execute your chunks at runtime.</li>
                                    <li class="mb-2"><strong>License</strong> - (Optional) Create license files to control where your code can run.</li>
                                    <li class="mb-2"><strong>Output</strong> - Download all generated files in a convenient package.</li>
                                </ol>
                            </div>
                        </div>
                        
                        <h4 class="mt-4">Supported PHP Versions</h4>
                        <p>The obfuscator supports PHP versions 7.2 and above. For best results, we recommend PHP 8.0+.</p>
                    </div>
                    
                    <div id="common-errors" class="mb-5">
                        <h3 class="border-bottom pb-2 mb-3"><i class="fas fa-exclamation-triangle me-2 text-warning"></i>Common Errors</h3>
                        
                        <div class="accordion" id="errorsAccordion">
                            <div class="accordion-item">
                                <h2 class="accordion-header" id="headingOne">
                                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseOne" aria-expanded="false" aria-controls="collapseOne">
                                        File Upload Errors
                                    </button>
                                </h2>
                                <div id="collapseOne" class="accordion-collapse collapse" aria-labelledby="headingOne" data-bs-parent="#errorsAccordion">
                                    <div class="accordion-body">
                                        <ul>
                                            <li><strong>File too large</strong>: The maximum upload size is 10MB. If your file exceeds this limit, consider breaking it into smaller modules.</li>
                                            <li><strong>Invalid file type</strong>: Only PHP files (.php extension) are accepted.</li>
                                            <li><strong>Upload permission error</strong>: Ensure your browser has permission to upload files.</li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="accordion-item">
                                <h2 class="accordion-header" id="headingTwo">
                                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
                                        Obfuscation Errors
                                    </button>
                                </h2>
                                <div id="collapseTwo" class="accordion-collapse collapse" aria-labelledby="headingTwo" data-bs-parent="#errorsAccordion">
                                    <div class="accordion-body">
                                        <ul>
                                            <li><strong>Syntax errors</strong>: If your original code contains syntax errors, the obfuscation process may fail. Ensure your code has valid PHP syntax.</li>
                                            <li><strong>Timeout errors</strong>: Very large files may trigger timeout errors. Try obfuscating smaller sections of your code.</li>
                                            <li><strong>Memory limit exceeded</strong>: Complex code structures may require more memory than available. Simplify your code or increase server memory limits.</li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="accordion-item">
                                <h2 class="accordion-header" id="headingThree">
                                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseThree" aria-expanded="false" aria-controls="collapseThree">
                                        Loader Errors
                                    </button>
                                </h2>
                                <div id="collapseThree" class="accordion-collapse collapse" aria-labelledby="headingThree" data-bs-parent="#errorsAccordion">
                                    <div class="accordion-body">
                                        <ul>
                                            <li><strong>Chunk files not found</strong>: Ensure all chunk files are properly uploaded in their expected directory structure.</li>
                                            <li><strong>License verification failed</strong>: If you're using domain-locked licenses, ensure you're testing on the correct domain.</li>
                                            <li><strong>Encryption key errors</strong>: Make sure the key used in your loader matches the key used for encryption.</li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="accordion-item">
                                <h2 class="accordion-header" id="headingFour">
                                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseFour" aria-expanded="false" aria-controls="collapseFour">
                                        Headers Already Sent Error
                                    </button>
                                </h2>
                                <div id="collapseFour" class="accordion-collapse collapse" aria-labelledby="headingFour" data-bs-parent="#errorsAccordion">
                                    <div class="accordion-body">
                                        <p>The "Headers already sent" error typically occurs when there is output before using functions like <code>header()</code> or <code>session_start()</code>.</p>
                                        <p><strong>Common causes:</strong></p>
                                        <ul>
                                            <li>Whitespace or text output before the <code>&lt;?php</code> tag</li>
                                            <li>Echo statements or HTML content before header calls</li>
                                            <li>BOM (Byte Order Mark) in UTF-8 encoded files</li>
                                        </ul>
                                        <p><strong>Solution:</strong> Ensure no output is sent before header or session functions. When using the obfuscated code, include it at the very beginning of your script.</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div id="troubleshooting" class="mb-5">
                        <h3 class="border-bottom pb-2 mb-3"><i class="fas fa-tools me-2 text-info"></i>Troubleshooting</h3>
                        
                        <h4 class="mt-4">Debugging Obfuscated Code</h4>
                        <p>When your obfuscated code doesn't work as expected, follow these steps:</p>
                        
                        <div class="card mb-4 bg-light">
                            <div class="card-body">
                                <ol>
                                    <li>Test your original code first to ensure it works correctly before obfuscation.</li>
                                    <li>Start with minimal obfuscation options and gradually increase complexity.</li>
                                    <li>If using anti-debugging features, temporarily disable them during testing.</li>
                                    <li>Check for PHP error logs on your server to identify specific issues.</li>
                                    <li>Ensure all file paths in your loader script are correct.</li>
                                </ol>
                            </div>
                        </div>
                        
                        <h4 class="mt-4">License Verification Issues</h4>
                        <p>If your license verification is failing:</p>
                        <ul>
                            <li>Verify the domain in the license matches the domain where the code is running.</li>
                            <li>Check if the license has expired.</li>
                            <li>Ensure all license files (.lic and .key if applicable) are in the correct location.</li>
                            <li>Check for any errors in your license generation parameters.</li>
                        </ul>
                    </div>
                    
                    <div id="faq" class="mb-5">
                        <h3 class="border-bottom pb-2 mb-3"><i class="fas fa-question me-2 text-success"></i>Frequently Asked Questions</h3>
                        
                        <div class="accordion" id="faqAccordion">
                            <div class="accordion-item">
                                <h2 class="accordion-header">
                                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faqOne">
                                        Is my code secure after obfuscation?
                                    </button>
                                </h2>
                                <div id="faqOne" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                                    <div class="accordion-body">
                                        <p>Obfuscation significantly increases the difficulty of understanding your code, but it's not 100% unbreakable. Combined with chunking, encryption, and license verification, it provides a strong level of protection against casual inspection and automated deobfuscation tools.</p>
                                        <p>For maximum security, combine obfuscation with server-side validation and keep critical business logic on your server.</p>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="accordion-item">
                                <h2 class="accordion-header">
                                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faqTwo">
                                        Will obfuscation affect performance?
                                    </button>
                                </h2>
                                <div id="faqTwo" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                                    <div class="accordion-body">
                                        <p>There is a small performance overhead with obfuscated and encrypted code, primarily during the initial load when decryption occurs. For most applications, this difference is negligible.</p>
                                        <p>The anti-debugger and anti-logger features may add additional overhead, so use them judiciously for performance-critical applications.</p>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="accordion-item">
                                <h2 class="accordion-header">
                                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faqThree">
                                        How do I update obfuscated code?
                                    </button>
                                </h2>
                                <div id="faqThree" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                                    <div class="accordion-body">
                                        <p>When you need to update your obfuscated code:</p>
                                        <ol>
                                            <li>Start with your original (non-obfuscated) source code</li>
                                            <li>Make your changes and test thoroughly</li>
                                            <li>Run the obfuscation process again</li>
                                            <li>Replace all files (loader, chunks, etc.) on your server</li>
                                        </ol>
                                        <p>It's important to maintain your original source code in a secure location, as deobfuscating the protected code for updates is extremely difficult.</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div id="contact" class="mb-4">
                        <h3 class="border-bottom pb-2 mb-3"><i class="fas fa-envelope me-2 text-primary"></i>Contact Support</h3>
                        
                        <p>If you're experiencing issues that aren't covered in this help center, you can reach out to our support team.</p>
                        
                        <div class="card bg-light">
                            <div class="card-body">
                                <h5><i class="fas fa-paper-plane me-2"></i>Contact Options:</h5>
                                <ul class="mb-0">
                                    <li><strong>Email:</strong> <a href="mailto:support@phpobfuscator.pro">support@phpobfuscator.pro</a></li>
                                    <li><strong>GitHub Issues:</strong> <a href="https://github.com/phpobfuscator/issues" target="_blank">Report a Bug</a></li>
                                    <li><strong>Documentation:</strong> <a href="index.php?tab=docs">View Full Documentation</a></li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>