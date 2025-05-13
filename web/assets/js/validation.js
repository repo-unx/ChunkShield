/**
 * ChunkShield Code Validation JavaScript
 * 
 * This script manages interactive code validation in the UI
 */

document.addEventListener('DOMContentLoaded', function() {
    // Check if validation checkbox is present
    const validateSyntaxCheckbox = document.getElementById('validate_syntax');
    
    if (validateSyntaxCheckbox) {
        // Add event listener for validation toggle
        validateSyntaxCheckbox.addEventListener('change', function() {
            toggleValidationWarning(this.checked);
        });
        
        // Initial state
        toggleValidationWarning(validateSyntaxCheckbox.checked);
    }
    
    // Check if validation results container exists
    const validationResultsContainer = document.getElementById('validationResults');
    if (validationResultsContainer) {
        // Initialize the validation results UI
        initValidationResultsUI();
    }
});

/**
 * Toggle validation warning message
 * 
 * @param {boolean} isValidationEnabled Whether validation is enabled
 */
function toggleValidationWarning(isValidationEnabled) {
    let warningContainer = document.getElementById('validation_warning_container');
    
    // Create warning container if it doesn't exist
    if (!warningContainer) {
        const validateSyntaxCheckbox = document.getElementById('validate_syntax');
        if (!validateSyntaxCheckbox) return;
        
        warningContainer = document.createElement('div');
        warningContainer.id = 'validation_warning_container';
        warningContainer.className = 'mt-2';
        validateSyntaxCheckbox.parentElement.appendChild(warningContainer);
    }
    
    if (!isValidationEnabled) {
        // Show warning if validation is disabled
        warningContainer.innerHTML = `
            <div class="alert alert-warning small py-2">
                <i class="fas fa-exclamation-triangle me-1"></i>
                Disabling validation may result in PHP syntax errors in generated code.
            </div>
        `;
    } else {
        // Clear warning if validation is enabled
        warningContainer.innerHTML = '';
    }
}

/**
 * Initialize validation results UI
 */
function initValidationResultsUI() {
    const validationResultsContainer = document.getElementById('validationResults');
    if (!validationResultsContainer) return;
    
    // Add UI elements for validation results
    validationResultsContainer.innerHTML = `
        <div class="validation-status mb-3 d-none">
            <div class="alert validation-alert">
                <div class="d-flex align-items-center">
                    <span class="status-icon me-2"></span>
                    <span class="status-message"></span>
                </div>
            </div>
        </div>
        <div class="validation-details d-none">
            <h6>Validation Details</h6>
            <div class="code-issues"></div>
        </div>
    `;
}

/**
 * Display validation results in the UI
 * 
 * @param {Object} results Validation results object
 */
function displayValidationResults(results) {
    const container = document.getElementById('validationResults');
    if (!container) return;
    
    const statusContainer = container.querySelector('.validation-status');
    const statusAlert = container.querySelector('.validation-alert');
    const statusIcon = container.querySelector('.status-icon');
    const statusMessage = container.querySelector('.status-message');
    const detailsContainer = container.querySelector('.validation-details');
    const issuesContainer = container.querySelector('.code-issues');
    
    // Display status
    statusContainer.classList.remove('d-none');
    
    if (results.passed) {
        statusAlert.className = 'alert alert-success';
        statusIcon.innerHTML = '<i class="fas fa-check-circle"></i>';
        statusMessage.textContent = 'Code validation passed. No syntax errors found.';
        detailsContainer.classList.add('d-none');
    } else {
        statusAlert.className = 'alert alert-danger';
        statusIcon.innerHTML = '<i class="fas fa-times-circle"></i>';
        statusMessage.textContent = `Code validation failed with ${results.error_count} error(s).`;
        
        // Display details if there are errors
        if (results.errors && results.errors.length > 0) {
            detailsContainer.classList.remove('d-none');
            
            // Clear previous issues
            issuesContainer.innerHTML = '';
            
            // Create list of issues
            const issuesList = document.createElement('ul');
            issuesList.className = 'list-group';
            
            results.errors.forEach(error => {
                const item = document.createElement('li');
                item.className = 'list-group-item list-group-item-danger small';
                item.innerHTML = `<i class="fas fa-times-circle me-2"></i>${error}`;
                issuesList.appendChild(item);
            });
            
            // Add warnings if available
            if (results.warnings && results.warnings.length > 0) {
                results.warnings.forEach(warning => {
                    const item = document.createElement('li');
                    item.className = 'list-group-item list-group-item-warning small';
                    item.innerHTML = `<i class="fas fa-exclamation-triangle me-2"></i>${warning}`;
                    issuesList.appendChild(item);
                });
            }
            
            issuesContainer.appendChild(issuesList);
        }
    }
}

// Expose function to global scope for PHP interaction
window.displayValidationResults = displayValidationResults;