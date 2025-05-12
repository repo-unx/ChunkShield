/**
 * Main JavaScript for PHP Obfuscation Tool - Modern UI Version
 */

document.addEventListener('DOMContentLoaded', function() {
    // File Upload Preview
    const fileInput = document.getElementById('phpFileUpload');
    if (fileInput) {
        fileInput.addEventListener('change', function(e) {
            const fileName = e.target.files[0]?.name || 'No file chosen';
            document.getElementById('file-name').textContent = fileName;
            
            // Enable the upload button if a file is selected
            const uploadBtn = document.getElementById('uploadBtn');
            if (uploadBtn) {
                uploadBtn.disabled = !fileName || fileName === 'No file chosen';
            }
            
            // Show file info if a file is selected
            const fileInfoDiv = document.getElementById('fileInfo');
            if (fileInfoDiv && e.target.files[0]) {
                const file = e.target.files[0];
                const fileSize = (file.size / 1024).toFixed(2) + ' KB';
                fileInfoDiv.innerHTML = `
                    <div class="alert alert-info mt-3">
                        <strong>File: </strong>${file.name}<br>
                        <strong>Size: </strong>${fileSize}<br>
                        <strong>Type: </strong>${file.type}
                    </div>
                `;
                fileInfoDiv.classList.remove('d-none');
            }
        });
    }
    
    // Form submission loading state
    const forms = document.querySelectorAll('form');
    forms.forEach(form => {
        form.addEventListener('submit', function() {
            const submitButton = this.querySelector('button[type="submit"]');
            if (submitButton) {
                submitButton.disabled = true;
                submitButton.innerHTML = '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Processing...';
            }
            
            // Show progress bar if it exists
            const progressBar = document.getElementById('progressBar');
            if (progressBar) {
                progressBar.classList.remove('d-none');
                simulateProgress();
            }
        });
    });
    
    // Simulate progress for progress bars
    function simulateProgress() {
        const progressBar = document.querySelector('.progress-bar');
        if (!progressBar) return;
        
        let width = 0;
        const interval = setInterval(function() {
            if (width >= 90) {
                clearInterval(interval);
            } else {
                width += Math.floor(Math.random() * 10) + 1;
                if (width > 90) width = 90;
                progressBar.style.width = width + '%';
                progressBar.setAttribute('aria-valuenow', width);
                progressBar.textContent = width + '%';
            }
        }, 300);
    }
    
    // Copy to clipboard functionality
    const copyButtons = document.querySelectorAll('.btn-copy');
    copyButtons.forEach(button => {
        button.addEventListener('click', function() {
            const targetId = this.getAttribute('data-target');
            const targetElement = document.getElementById(targetId);
            
            if (!targetElement) return;
            
            let textToCopy = targetElement.textContent;
            // Special handling for input elements
            if (targetElement.tagName === 'INPUT') {
                textToCopy = targetElement.value;
            }
            
            navigator.clipboard.writeText(textToCopy).then(() => {
                // Update button text temporarily
                const originalText = this.innerHTML;
                this.innerHTML = '<i class="fas fa-check"></i> Copied!';
                setTimeout(() => {
                    this.innerHTML = originalText;
                }, 2000);
            }).catch(err => {
                console.error('Failed to copy text: ', err);
            });
        });
    });
    
    // Tooltips initialization
    const tooltipTriggerList = document.querySelectorAll('[data-bs-toggle="tooltip"]');
    if (tooltipTriggerList.length > 0) {
        const tooltipList = [...tooltipTriggerList].map(tooltipTriggerEl => new bootstrap.Tooltip(tooltipTriggerEl));
    }
    
    // Auto-dismiss alerts after 5 seconds
    const alerts = document.querySelectorAll('.alert-dismissible');
    alerts.forEach(alert => {
        setTimeout(() => {
            const closeButton = alert.querySelector('.btn-close');
            if (closeButton) {
                closeButton.click();
            }
        }, 5000);
    });
    
    // Toggle options sections
    const toggleButtons = document.querySelectorAll('.toggle-options');
    toggleButtons.forEach(button => {
        button.addEventListener('click', function() {
            const targetId = this.getAttribute('data-target');
            const target = document.getElementById(targetId);
            
            if (target) {
                if (target.classList.contains('d-none')) {
                    target.classList.remove('d-none');
                    this.innerHTML = this.innerHTML.replace('Show', 'Hide');
                } else {
                    target.classList.add('d-none');
                    this.innerHTML = this.innerHTML.replace('Hide', 'Show');
                }
            }
        });
    });
    
    // Generate random encryption key
    const generateKeyButton = document.getElementById('generateKey');
    if (generateKeyButton) {
        generateKeyButton.addEventListener('click', function() {
            const keyInput = document.getElementById('encryption_key');
            if (keyInput) {
                const randomKey = generateRandomString(32);
                keyInput.value = randomKey;
            }
        });
    }
    
    // Generate random license key
    const generateLicenseKeyButton = document.getElementById('generateLicenseKey');
    if (generateLicenseKeyButton) {
        generateLicenseKeyButton.addEventListener('click', function() {
            const licenseKeyInput = document.getElementById('license_key');
            if (licenseKeyInput) {
                const licenseKey = generateLicenseKey();
                licenseKeyInput.value = licenseKey;
            }
        });
    }

    // Domain input validation for license generation
    const domainInput = document.getElementById('license_domain');
    if (domainInput) {
        domainInput.addEventListener('input', function() {
            validateDomainInput(this);
        });
    }

    // Expiration date picker - default to 1 year from now
    const expiryInput = document.getElementById('license_expiry');
    if (expiryInput) {
        const oneYearFromNow = new Date();
        oneYearFromNow.setFullYear(oneYearFromNow.getFullYear() + 1);
        const formattedDate = oneYearFromNow.toISOString().split('T')[0];
        expiryInput.value = formattedDate;
        expiryInput.setAttribute('min', new Date().toISOString().split('T')[0]);
    }

    // License preview update
    const licenseFormInputs = document.querySelectorAll('.license-form input, .license-form select');
    if (licenseFormInputs.length > 0) {
        licenseFormInputs.forEach(input => {
            input.addEventListener('input', updateLicensePreview);
            input.addEventListener('change', updateLicensePreview);
        });
        // Initial update
        updateLicensePreview();
    }

    // Security level slider
    const securitySlider = document.getElementById('security_level');
    const securityValue = document.getElementById('security_value');
    if (securitySlider && securityValue) {
        securitySlider.addEventListener('input', function() {
            securityValue.textContent = this.value;
            updateSecurityOptions(parseInt(this.value));
        });
        // Initial update
        if (securityValue.textContent === '') {
            securityValue.textContent = securitySlider.value;
            updateSecurityOptions(parseInt(securitySlider.value));
        }
    }

    // Switch license type
    const licenseTypeSelect = document.getElementById('license_type');
    const certOptionsDiv = document.getElementById('certificate_options');
    if (licenseTypeSelect && certOptionsDiv) {
        licenseTypeSelect.addEventListener('change', function() {
            if (this.value === 'certificate') {
                certOptionsDiv.classList.remove('d-none');
            } else {
                certOptionsDiv.classList.add('d-none');
            }
            updateLicensePreview();
        });
    }

    // Tab navigation handler to maintain state
    const tabLinks = document.querySelectorAll('.nav-tabs .nav-link:not(.disabled)');
    tabLinks.forEach(tabLink => {
        tabLink.addEventListener('click', function(e) {
            if (this.classList.contains('disabled')) {
                e.preventDefault();
                return false;
            }
        });
    });
    
    // Function to generate random string
    function generateRandomString(length) {
        const chars = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789!@#$%^&*()_+';
        let result = '';
        for (let i = 0; i < length; i++) {
            result += chars.charAt(Math.floor(Math.random() * chars.length));
        }
        return result;
    }

    // Function to generate license key
    function generateLicenseKey() {
        const segments = 4;
        const segmentLength = 5;
        let license = '';
        
        for (let i = 0; i < segments; i++) {
            for (let j = 0; j < segmentLength; j++) {
                // Use uppercase letters and numbers for readability
                const charset = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
                license += charset.charAt(Math.floor(Math.random() * charset.length));
            }
            if (i < segments - 1) license += '-';
        }
        
        return license;
    }

    // Function to validate domain input
    function validateDomainInput(input) {
        const value = input.value.trim();
        const domainRegex = /^([a-zA-Z0-9]([a-zA-Z0-9\-]{0,61}[a-zA-Z0-9])?\.)+[a-zA-Z]{2,}$/;
        
        if (value === '') {
            input.classList.remove('is-invalid', 'is-valid');
        } else if (domainRegex.test(value)) {
            input.classList.remove('is-invalid');
            input.classList.add('is-valid');
        } else {
            input.classList.remove('is-valid');
            input.classList.add('is-invalid');
        }
    }

    // Function to update license preview
    function updateLicensePreview() {
        const previewElement = document.getElementById('license_preview');
        if (!previewElement) return;

        const licenseKey = document.getElementById('license_key')?.value || 'XXXXX-XXXXX-XXXXX-XXXXX';
        const domain = document.getElementById('license_domain')?.value || 'example.com';
        const expiryInput = document.getElementById('license_expiry');
        const licenseType = document.getElementById('license_type')?.value || 'license';
        
        let expiryDate = 'Unknown';
        if (expiryInput && expiryInput.value) {
            const date = new Date(expiryInput.value);
            expiryDate = date.toLocaleDateString('en-US', {
                year: 'numeric',
                month: 'long',
                day: 'numeric'
            });
        }

        const licenseTypeText = licenseType === 'certificate' ? 'Certificate' : 'License';
        
        let previewHtml = `
            <div class="mb-3">
                <strong>${licenseTypeText} Key:</strong> <span class="text-primary">${licenseKey}</span>
            </div>
            <div class="mb-3">
                <strong>Domain:</strong> ${domain}
            </div>
            <div class="mb-3">
                <strong>Expiry Date:</strong> ${expiryDate}
            </div>
        `;

        if (licenseType === 'certificate') {
            const issuerName = document.getElementById('cert_issuer')?.value || 'Your Company';
            const certType = document.getElementById('cert_type')?.value || 'Standard';
            
            previewHtml += `
                <div class="mb-3">
                    <strong>Issuer:</strong> ${issuerName}
                </div>
                <div class="mb-3">
                    <strong>Certificate Type:</strong> ${certType}
                </div>
                <div class="mt-3 text-center">
                    <div class="p-2 border border-primary rounded d-inline-block">
                        <i class="fas fa-certificate text-primary"></i> Digitally Signed
                    </div>
                </div>
            `;
        }

        previewElement.innerHTML = previewHtml;
    }

    // Function to update security options based on slider
    function updateSecurityOptions(level) {
        const encryptCheckbox = document.getElementById('encrypt_license');
        const signatureCheckbox = document.getElementById('add_signature');
        const hashingCheckbox = document.getElementById('add_hashing');
        
        if (!encryptCheckbox || !signatureCheckbox || !hashingCheckbox) return;
        
        if (level >= 1) {
            encryptCheckbox.checked = true;
        }
        
        if (level >= 2) {
            signatureCheckbox.checked = true;
        }
        
        if (level >= 3) {
            hashingCheckbox.checked = true;
        }
    }

    // Dark mode toggle with improved functionality
    const darkModeToggle = document.getElementById('darkModeToggle');
    if (darkModeToggle) {
        darkModeToggle.addEventListener('click', function(e) {
            e.preventDefault();
            document.body.classList.toggle('dark-mode');
            const isDark = document.body.classList.contains('dark-mode');
            localStorage.setItem('darkMode', isDark ? 'true' : 'false');
            
            // Update active theme indication in UI
            document.querySelectorAll('.theme-indicator').forEach(indicator => {
                indicator.textContent = isDark ? 'Dark Mode' : 'Light Mode';
            });
        });
        
        // Check saved preference and apply on load
        if (localStorage.getItem('darkMode') === 'true') {
            document.body.classList.add('dark-mode');
            // Update any theme indicators
            document.querySelectorAll('.theme-indicator').forEach(indicator => {
                indicator.textContent = 'Dark Mode';
            });
        }
    }
    
    // Clear all confirmation
    const clearAllButton = document.querySelector('a[href*="clear_all"]');
    if (clearAllButton) {
        clearAllButton.addEventListener('click', function(e) {
            e.preventDefault();
            
            if (confirm('Are you sure you want to clear all progress? This action cannot be undone.')) {
                window.location.href = this.getAttribute('href');
            }
        });
    }
});