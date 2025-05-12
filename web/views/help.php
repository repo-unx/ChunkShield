<div class="section-header mb-4">
    <h2>Help Center</h2>
    <p class="">Get assistance with using ChunkShield</p>
</div>

<div class="row">
    <div class="col-lg-8">
        <div class="card mb-4">
            <div class="card-header">
                <h5>Quick Start Guide</h5>
            </div>
            <div class="card-body">
                <div class="steps-container">
                    <div class="d-flex mb-4">
                        <div class="step-content ms-3">
                            <h5>Upload Your PHP File</h5>
                            <p>Start by uploading the PHP file you want to protect. The file should be a valid PHP script with no syntax errors.</p>
                            <a href="index.php?tab=upload" class="btn btn-sm btn-outline-primary">Go to Upload</a>
                        </div>
                    </div>
                    
                    <div class="d-flex mb-4">
                        <div class="step-content ms-3">
                            <h5>Configure Obfuscation</h5>
                            <p>Select which obfuscation techniques to apply to your code. You can choose to remove comments, minimize whitespace, rename variables, and add junk code.</p>
                            <a href="index.php?tab=obfuscate" class="btn btn-sm btn-outline-primary">Go to Obfuscate</a>
                        </div>
                    </div>
                    
                    <div class="d-flex mb-4">
                        <div class="step-content ms-3">
                            <h5>Set Up Chunking & Encryption</h5>
                            <p>Configure how your code will be split into chunks and encrypted. You can specify chunk size, encryption method, and other parameters.</p>
                            <a href="index.php?tab=chunk" class="btn btn-sm btn-outline-primary">Go to Chunking</a>
                        </div>
                    </div>
                    
                    <div class="d-flex mb-4">
                        <div class="step-content ms-3">
                            <h5>Generate a Loader</h5>
                            <p>Create a polymorphic loader script that will decrypt and execute your protected code at runtime. You can add junk eval blocks and environment fingerprinting.</p>
                            <a href="index.php?tab=loader" class="btn btn-sm btn-outline-primary">Go to Loader</a>
                        </div>
                    </div>
                    
                    <div class="d-flex mb-4">
                        <div class="step-content ms-3">
                            <h5>Add License Protection (Optional)</h5>
                            <p>Create license restrictions to ensure your code only runs in authorized environments. You can restrict by domain, IP, and path.</p>
                            <a href="index.php?tab=license" class="btn btn-sm btn-outline-primary">Go to License</a>
                        </div>
                    </div>
                    
                    <div class="d-flex">
                        <div class="step-content ms-3">
                            <h5>Download Protected Files</h5>
                            <p>Download the final protected package, including the loader and encrypted chunks. Deploy these files to your server.</p>
                            <a href="index.php?tab=output" class="btn btn-sm btn-outline-primary">Go to Output</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="card mb-4">
            <div class="card-header">
                <h5>Common Issues</h5>
            </div>
            <div class="card-body">
                <div class="accordion" id="issuesAccordion">
                    <div class="accordion-item">
                        <h2 class="accordion-header" id="issueOne">
                            <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#issueCollapseOne" aria-expanded="true" aria-controls="issueCollapseOne">
                                Protected code doesn't execute properly
                            </button>
                        </h2>
                        <div id="issueCollapseOne" class="accordion-collapse collapse show" aria-labelledby="issueOne" data-bs-parent="#issuesAccordion">
                            <div class="accordion-body">
                                <p>If your protected code isn't working correctly after deployment, check the following:</p>
                                <ul>
                                    <li>Ensure the loader.php file and chunks/ directory are in the same relative location</li>
                                    <li>Check that all chunk files were uploaded correctly</li>
                                    <li>Verify that your server has the required PHP extensions (openssl, zlib)</li>
                                    <li>Check server error logs for specific error messages</li>
                                    <li>If using license protection, verify that the execution environment meets the license restrictions</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    <div class="accordion-item">
                        <h2 class="accordion-header" id="issueTwo">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#issueCollapseTwo" aria-expanded="false" aria-controls="issueCollapseTwo">
                                "Failed to decrypt chunk" error
                            </button>
                        </h2>
                        <div id="issueCollapseTwo" class="accordion-collapse collapse" aria-labelledby="issueTwo" data-bs-parent="#issuesAccordion">
                            <div class="accordion-body">
                                <p>This error typically occurs when:</p>
                                <ul>
                                    <li>A chunk file is corrupted or incomplete</li>
                                    <li>The encryption key in the loader doesn't match the one used to encrypt the chunks</li>
                                    <li>The OpenSSL extension is missing or misconfigured</li>
                                </ul>
                                <p><strong>Solution:</strong> Re-generate the protected package and upload all files again. Ensure all files are transferred completely.</p>
                            </div>
                        </div>
                    </div>
                    <div class="accordion-item">
                        <h2 class="accordion-header" id="issueThree">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#issueCollapseThree" aria-expanded="false" aria-controls="issueCollapseThree">
                                "License verification failed" error
                            </button>
                        </h2>
                        <div id="issueCollapseThree" class="accordion-collapse collapse" aria-labelledby="issueThree" data-bs-parent="#issuesAccordion">
                            <div class="accordion-body">
                                <p>The loader is detecting that the execution environment doesn't match the license restrictions. Check:</p>
                                <ul>
                                    <li>Domain: Ensure the website is running on one of the allowed domains</li>
                                    <li>IP Address: Verify the server's IP matches the allowed IP(s)</li>
                                    <li>Path: Check that the script is being executed from an allowed directory path</li>
                                    <li>Expiration: Verify the license hasn't expired</li>
                                </ul>
                                <p><strong>Solution:</strong> Update the license file with the correct restrictions or generate a new protected package with updated license settings.</p>
                            </div>
                        </div>
                    </div>
                    <div class="accordion-item">
                        <h2 class="accordion-header" id="issueFour">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#issueCollapseFour" aria-expanded="false" aria-controls="issueCollapseFour">
                                Obfuscation breaks functionality
                            </button>
                        </h2>
                        <div id="issueCollapseFour" class="accordion-collapse collapse" aria-labelledby="issueFour" data-bs-parent="#issuesAccordion">
                            <div class="accordion-body">
                                <p>In some cases, aggressive obfuscation might break code functionality, especially for complex PHP applications. If this happens:</p>
                                <ul>
                                    <li>Disable variable renaming, which is the most likely cause of issues</li>
                                    <li>Reduce junk code density or disable junk code insertion completely</li>
                                    <li>Keep whitespace removal and comment removal, which are generally safe</li>
                                </ul>
                                <p><strong>Solution:</strong> Start with minimal obfuscation and gradually add more aggressive techniques while testing functionality at each step.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-lg-4">
        <div class="card mb-4">
            <div class="card-header">
                <h5>Resources</h5>
            </div>
            <div class="card-body">
                <div class="list-group">
                    <a href="index.php?tab=docs" class="list-group-item list-group-item-action d-flex align-items-center">
                        <i class="fas fa-book text-primary me-3"></i>
                        <div>
                            <strong>Documentation</strong>
                            <p class="mb-0 small ">Detailed guides on using ChunkShield</p>
                        </div>
                    </a>
                    <a href="https://github.com/repo-unx/ChunkShield" class="list-group-item list-group-item-action d-flex align-items-center" target="_blank">
                        <i class="fab fa-github text-primary me-3"></i>
                        <div>
                            <strong>GitHub Repository</strong>
                            <p class="mb-0 small ">Source code and issue tracking</p>
                        </div>
                    </a>
                    <div class="list-group-item d-flex align-items-center">
                        <i class="fas fa-envelope text-primary me-3"></i>
                        <div>
                            <strong>Support Email</strong>
                            <p class="mb-0 small ">support@chunkshield.com</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="card">
            <div class="card-header">
                <h5>System Requirements</h5>
            </div>
            <div class="card-body">
                <ul class="list-group list-group-flush">
                    <li class="list-group-item d-flex align-items-center">
                        <i class="fab fa-php text-primary me-3"></i>
                        <div>
                            <strong>PHP Version</strong>
                            <p class="mb-0 small ">PHP 5.6 or higher (PHP 7.x/8.x recommended)</p>
                        </div>
                    </li>
                    <li class="list-group-item d-flex align-items-center">
                        <i class="fas fa-lock text-primary me-3"></i>
                        <div>
                            <strong>Required Extensions</strong>
                            <p class="mb-0 small ">openssl, zlib</p>
                        </div>
                    </li>
                    <li class="list-group-item d-flex align-items-center">
                        <i class="fas fa-server text-primary me-3"></i>
                        <div>
                            <strong>Server</strong>
                            <p class="mb-0 small ">Any web server supporting PHP (Apache, Nginx, etc.)</p>
                        </div>
                    </li>
                    <li class="list-group-item d-flex align-items-center">
                        <i class="fas fa-file-code text-primary me-3"></i>
                        <div>
                            <strong>Input Files</strong>
                            <p class="mb-0 small ">Valid PHP files with no syntax errors</p>
                        </div>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</div>
