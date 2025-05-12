<div class="section-header mb-4">
    <h2>Create License</h2>
    <p class="">Generate license restrictions for your protected PHP code.</p>
</div>

<div class="row">
    <div class="col-lg-8">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">License Settings</h5>
            </div>
            <div class="card-body">
                <form method="post" action="index.php">
                    <input type="hidden" name="action" value="license">
                    
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <div class="card h-100">
                                <div class="card-header">
                                    <h6 class="mb-0">Customer Information</h6>
                                </div>
                                <div class="card-body">
                                    <div class="mb-3">
                                        <label for="customer_name" class="form-label">Customer/Company Name</label>
                                        <input type="text" class="form-control" id="customer_name" name="customer_name" placeholder="Enter customer name">
                                    </div>
                                    
                                    <div class="mb-3">
                                        <label for="customer_email" class="form-label">Customer Email</label>
                                        <input type="email" class="form-control" id="customer_email" name="customer_email" placeholder="customer@example.com">
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="card h-100">
                                <div class="card-header">
                                    <h6 class="mb-0">License Validity</h6>
                                </div>
                                <div class="card-body">
                                    <div class="mb-3">
                                        <label for="valid_days" class="form-label">Valid For (days)</label>
                                        <input type="number" class="form-control" id="valid_days" name="valid_days" value="365" min="1" max="3650">
                                        <small class="">Number of days the license will be valid</small>
                                    </div>
                                    
                                    <div class="valid-dates mt-3 small ">
                                        <div>Valid From: <span id="valid_from"><?php echo date('Y-m-d'); ?></span></div>
                                        <div>Valid Until: <span id="valid_to"><?php echo date('Y-m-d', strtotime('+365 days')); ?></span></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="row mb-4">
                        <div class="col-md-12">
                            <div class="card">
                                <div class="card-header">
                                    <h6 class="mb-0">License Restrictions</h6>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-4">
                                            <div class="form-check mb-3">
                                                <input class="form-check-input" type="checkbox" name="check_domain" id="check_domain" checked>
                                                <label class="form-check-label" for="check_domain">
                                                    Domain Restriction
                                                </label>
                                            </div>
                                            
                                            <div class="mb-3 domain-input">
                                                <input type="text" class="form-control" id="domain" name="domain" placeholder="example.com">
                                                <small class="">Comma-separated domains</small>
                                            </div>
                                        </div>
                                        
                                        <div class="col-md-4">
                                            <div class="form-check mb-3">
                                                <input class="form-check-input" type="checkbox" name="check_ip" id="check_ip">
                                                <label class="form-check-label" for="check_ip">
                                                    IP Restriction
                                                </label>
                                            </div>
                                            
                                            <div class="mb-3 ip-input">
                                                <input type="text" class="form-control" id="ip" name="ip" placeholder="192.168.1.* or specific IP" disabled>
                                                <small class="">Comma-separated IPs/ranges</small>
                                            </div>
                                        </div>
                                        
                                        <div class="col-md-4">
                                            <div class="form-check mb-3">
                                                <input class="form-check-input" type="checkbox" name="check_path" id="check_path">
                                                <label class="form-check-label" for="check_path">
                                                    Path Restriction
                                                </label>
                                            </div>
                                            
                                            <div class="mb-3 path-input">
                                                <input type="text" class="form-control" id="path" name="path" placeholder="/var/www/*" disabled>
                                                <small class="">Allowed execution path</small>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="alert alert-info">
                        <i class="fas fa-info-circle me-2"></i>
                        License restrictions prevent your code from running on unauthorized domains, IPs, or server paths. The loader will check these restrictions at runtime.
                    </div>
                    
                    <div class="d-flex justify-content-between">
                        <a href="index.php?tab=loader" class="btn btn-outline-secondary">
                            <i class="fas fa-arrow-left me-2"></i> Back to Loader
                        </a>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-key me-2"></i> Create License & Continue
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    
    <div class="col-lg-4">
        <div class="card">
            <div class="card-header">
                <h5>License Preview</h5>
            </div>
            <div class="card-body">
                <div class="license-preview p-3 rounded mb-4" style="background-color: #f8f9fa; font-family: monospace; font-size: 0.8rem;">
<pre id="licensePreview">{
  "license_id": "cs_a7b8c9d0",
  "customer_name": "Example Company",
  "customer_email": "customer@example.com",
  "valid_from": 1683905400,
  "valid_to": 1715441400,
  "domain": ["example.com"],
  "check_domain": true,
  "check_ip": false,
  "check_path": false,
  "created_at": 1683905400
}</pre>
                </div>
                
                <div class="license-features mt-3">
                    <h6>License Features</h6>
                    <ul class="list-group list-group-flush">
                        <li class="list-group-item d-flex align-items-center py-2">
                            <i class="fas fa-fingerprint text-success me-2"></i>
                            <small>Unique license identifier</small>
                        </li>
                        <li class="list-group-item d-flex align-items-center py-2">
                            <i class="fas fa-clock text-success me-2"></i>
                            <small>Time-based expiration</small>
                        </li>
                        <li class="list-group-item d-flex align-items-center py-2">
                            <i class="fas fa-globe text-success me-2"></i>
                            <small>Environment restrictions</small>
                        </li>
                        <li class="list-group-item d-flex align-items-center py-2">
                            <i class="fas fa-file-alt text-success me-2"></i>
                            <small>JSON license file format</small>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
// Enable/disable IP input based on checkbox
document.getElementById('check_ip').addEventListener('change', function() {
    document.getElementById('ip').disabled = !this.checked;
});

// Enable/disable path input based on checkbox
document.getElementById('check_path').addEventListener('change', function() {
    document.getElementById('path').disabled = !this.checked;
});

// Enable/disable domain input based on checkbox
document.getElementById('check_domain').addEventListener('change', function() {
    document.getElementById('domain').disabled = !this.checked;
});

// Update valid dates based on valid_days input
document.getElementById('valid_days').addEventListener('input', function() {
    const days = parseInt(this.value) || 365;
    const validFrom = new Date();
    const validTo = new Date();
    validTo.setDate(validTo.getDate() + days);
    
    document.getElementById('valid_from').textContent = validFrom.toISOString().split('T')[0];
    document.getElementById('valid_to').textContent = validTo.toISOString().split('T')[0];
});

// Update license preview
function updateLicensePreview() {
    const customerName = document.getElementById('customer_name').value || 'Example Company';
    const customerEmail = document.getElementById('customer_email').value || 'customer@example.com';
    const validDays = parseInt(document.getElementById('valid_days').value) || 365;
    const domain = document.getElementById('domain').value || 'example.com';
    const checkDomain = document.getElementById('check_domain').checked;
    const checkIp = document.getElementById('check_ip').checked;
    const checkPath = document.getElementById('check_path').checked;
    
    const now = Math.floor(Date.now() / 1000);
    const validTo = now + (validDays * 24 * 60 * 60);
    
    const license = {
        "license_id": "cs_" + Math.random().toString(16).substring(2, 10),
        "customer_name": customerName,
        "customer_email": customerEmail,
        "valid_from": now,
        "valid_to": validTo,
        "domain": domain ? domain.split(',').map(d => d.trim()) : [],
        "check_domain": checkDomain,
        "check_ip": checkIp,
        "check_path": checkPath,
        "created_at": now
    };
    
    document.getElementById('licensePreview').textContent = JSON.stringify(license, null, 2);
}

// Add event listeners for all form fields
const formFields = document.querySelectorAll('input, select');
formFields.forEach(field => {
    field.addEventListener('input', updateLicensePreview);
    field.addEventListener('change', updateLicensePreview);
});

// Initial update
updateLicensePreview();
</script>