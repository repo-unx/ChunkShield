<h2 class="page-title"><i class="fas fa-key me-2"></i>Generate License & Certificate</h2>

<div class="row">
    <div class="col-lg-6">
        <div class="card mb-4">
            <div class="card-header">
                <i class="fas fa-cogs me-2"></i>License Configuration
            </div>
            <div class="card-body">
                <form action="index.php" method="post" id="licenseForm" class="license-form">
                    <input type="hidden" name="action" value="generate_license">
                    
                    <div class="mb-3">
                        <label for="license_type" class="form-label">License Type</label>
                        <select class="form-select" id="license_type" name="license_type">
                            <option value="license">License File (.lic)</option>
                            <option value="certificate">Certificate (.crt)</option>
                        </select>
                    </div>
                    
                    <div class="mb-3">
                        <label for="license_key" class="form-label">License Key</label>
                        <div class="input-group">
                            <input type="text" class="form-control" id="license_key" name="license_key" placeholder="Generate or enter a license key" required>
                            <button class="btn btn-outline-secondary" type="button" id="generateLicenseKey">
                                <i class="fas fa-random"></i> Generate
                            </button>
                        </div>
                        <div class="form-text">This is the key customers will use to activate your software.</div>
                    </div>
                    
                    <div class="mb-3">
                        <label for="license_domain" class="form-label">Target Domain</label>
                        <input type="text" class="form-control" id="license_domain" name="license_domain" placeholder="example.com" required>
                        <div class="form-text">The domain where this license can be used (e.g., example.com).</div>
                    </div>
                    
                    <div class="mb-3">
                        <label for="license_expiry" class="form-label">Expiry Date</label>
                        <input type="date" class="form-control" id="license_expiry" name="license_expiry" required>
                        <div class="form-text">When the license will expire.</div>
                    </div>
                    
                    <div id="certificate_options" class="d-none">
                        <hr>
                        <h5 class="mb-3">Certificate Options</h5>
                        
                        <div class="mb-3">
                            <label for="cert_issuer" class="form-label">Certificate Issuer</label>
                            <input type="text" class="form-control" id="cert_issuer" name="cert_issuer" placeholder="Your Company Name">
                            <div class="form-text">Organization issuing the certificate.</div>
                        </div>
                        
                        <div class="mb-3">
                            <label for="cert_type" class="form-label">Certificate Type</label>
                            <select class="form-select" id="cert_type" name="cert_type">
                                <option value="standard">Standard</option>
                                <option value="premium">Premium</option>
                                <option value="enterprise">Enterprise</option>
                            </select>
                        </div>
                    </div>
                    
                    <hr>
                    
                    <h5 class="mb-3">Security Options</h5>
                    
                    <div class="mb-3">
                        <label for="security_level" class="form-label">Security Level: <span id="security_value">2</span></label>
                        <input type="range" class="form-range security-level-slider" id="security_level" name="security_level" min="1" max="3" value="2">
                        <div class="d-flex justify-content-between">
                            <span class="badge bg-info">Basic</span>
                            <span class="badge bg-primary">Enhanced</span>
                            <span class="badge bg-secondary">Maximum</span>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="encrypt_license" name="encrypt_license" checked>
                            <label class="form-check-label" for="encrypt_license">
                                <i class="fas fa-lock me-2 text-primary"></i>Encrypt License Data
                            </label>
                            <div class="form-text">Encrypts the license data using AES-256-CBC.</div>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="add_signature" name="add_signature" checked>
                            <label class="form-check-label" for="add_signature">
                                <i class="fas fa-signature me-2 text-primary"></i>Add Digital Signature
                            </label>
                            <div class="form-text">Signs the license with a digital signature to prevent tampering.</div>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="add_hashing" name="add_hashing" checked>
                            <label class="form-check-label" for="add_hashing">
                                <i class="fas fa-fingerprint me-2 text-primary"></i>Add Hardware Binding
                            </label>
                            <div class="form-text">Creates a hardware fingerprint to bind the license to a specific server.</div>
                        </div>
                    </div>
                    
                    <div id="progressBar" class="progress mt-4 d-none">
                        <div class="progress-bar progress-bar-striped progress-bar-animated" role="progressbar" style="width: 0%;" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100">0%</div>
                    </div>
                    
                    <div class="d-grid gap-2 mt-4">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-file-export me-2"></i>Generate License
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    
    <div class="col-lg-6">
        <div class="card mb-4">
            <div class="card-header">
                <i class="fas fa-eye me-2"></i>License Preview
            </div>
            <div class="card-body">
                <div class="license-preview" id="license_preview">
                    <!-- Preview content will be loaded by JavaScript -->
                </div>
            </div>
        </div>
        
        <div class="card mb-4">
            <div class="card-header">
                <i class="fas fa-shield-alt me-2"></i>License Security Features
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-4">
                            <h5><i class="fas fa-lock text-primary me-2"></i>Encryption</h5>
                            <p class="text-muted small">Uses industry-standard AES-256-CBC encryption to secure your license data from unauthorized access.</p>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-4">
                            <h5><i class="fas fa-signature text-primary me-2"></i>Digital Signature</h5>
                            <p class="text-muted small">Cryptographically signs your license to ensure it hasn't been modified or tampered with.</p>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-4">
                            <h5><i class="fas fa-globe text-primary me-2"></i>Domain Binding</h5>
                            <p class="text-muted small">Restricts license usage to a specific domain to prevent unauthorized redistribution.</p>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-4">
                            <h5><i class="fas fa-calendar-alt text-primary me-2"></i>Time-Based Expiry</h5>
                            <p class="text-muted small">Automatically expires licenses after a specific date to enforce subscription models.</p>
                        </div>
                    </div>
                </div>
                
                <div class="alert alert-info mt-2">
                    <i class="fas fa-lightbulb me-2"></i>Tip: Use your license file with the loader to create a complete protection system.
                </div>
            </div>
        </div>
    </div>
</div>