/**
 * ChunkShield Toast Notification System
 * 
 * Provides intuitive and user-friendly toast notifications
 * for various types of messages (success, error, warning, info)
 */

// Toast notification manager
class ToastManager {
    constructor(options = {}) {
        this.options = {
            position: options.position || 'top-right', // top-right, top-left, bottom-right, bottom-left, top-center, bottom-center
            duration: options.duration || 5000,       // Duration in milliseconds
            maxToasts: options.maxToasts || 5,        // Maximum number of toasts visible at once
            pauseOnHover: options.pauseOnHover !== undefined ? options.pauseOnHover : true,
            container: options.container || null      // Custom container element
        };
        
        this.toasts = [];
        this.createContainer();
    }
    
    /**
     * Create the toast container
     */
    createContainer() {
        // If we already have a container, don't create another one
        if (document.getElementById('toast-container')) {
            this.container = document.getElementById('toast-container');
            return;
        }
        
        // Create container element
        this.container = this.options.container || document.createElement('div');
        this.container.id = 'toast-container';
        this.container.className = `toast-container toast-${this.options.position}`;
        
        // Add to document
        if (!this.options.container) {
            document.body.appendChild(this.container);
        }
        
        // Add styles if they don't exist
        this.addStyles();
    }
    
    /**
     * Add necessary styles to document
     */
    addStyles() {
        if (document.getElementById('toast-styles')) {
            return;
        }
        
        const style = document.createElement('style');
        style.id = 'toast-styles';
        style.textContent = `
            /* Toast Container Styles */
            .toast-container {
                position: fixed;
                z-index: 9999;
                padding: 15px;
                max-width: 100%;
                box-sizing: border-box;
                display: flex;
                flex-direction: column;
                gap: 10px;
            }
            
            /* Container Positions */
            .toast-top-right {
                top: 0;
                right: 0;
                align-items: flex-end;
            }
            
            .toast-top-left {
                top: 0;
                left: 0;
                align-items: flex-start;
            }
            
            .toast-bottom-right {
                bottom: 0;
                right: 0;
                align-items: flex-end;
            }
            
            .toast-bottom-left {
                bottom: 0;
                left: 0;
                align-items: flex-start;
            }
            
            .toast-top-center {
                top: 0;
                left: 50%;
                transform: translateX(-50%);
                align-items: center;
            }
            
            .toast-bottom-center {
                bottom: 0;
                left: 50%;
                transform: translateX(-50%);
                align-items: center;
            }
            
            /* Toast Element Styles */
            .toast {
                min-width: 280px;
                max-width: 380px;
                padding: 15px;
                border-radius: 8px;
                box-shadow: 0 2px 15px rgba(0, 0, 0, 0.15);
                margin-bottom: 8px;
                overflow: hidden;
                animation: toast-in 0.3s ease-in-out forwards;
                cursor: default;
                pointer-events: auto;
                display: flex;
                align-items: flex-start;
                transition: all 0.3s ease;
                transform: translateX(0);
                position: relative;
            }
            
            /* Toast Status Bar */
            .toast::before {
                content: '';
                position: absolute;
                bottom: 0;
                left: 0;
                width: 100%;
                height: 4px;
                background-color: rgba(0, 0, 0, 0.2);
            }
            
            .toast.with-progress::before {
                animation: toast-progress linear forwards;
            }
            
            /* Toast Types */
            .toast-success {
                background-color: #4caf50;
                color: white;
                border-left: 5px solid #388e3c;
            }
            
            .toast-error {
                background-color: #f44336;
                color: white;
                border-left: 5px solid #ba000d;
            }
            
            .toast-info {
                background-color: #2196f3;
                color: white;
                border-left: 5px solid #0069c0;
            }
            
            .toast-warning {
                background-color: #ff9800;
                color: white;
                border-left: 5px solid #c66900;
            }
            
            /* Toast Content */
            .toast-icon {
                margin-right: 12px;
                font-size: 20px;
                display: flex;
                justify-content: center;
                align-items: center;
            }
            
            .toast-content {
                flex: 1;
                padding-right: 5px;
            }
            
            .toast-title {
                font-weight: bold;
                margin-bottom: 5px;
                display: block;
            }
            
            .toast-message {
                word-break: break-word;
                display: block;
                margin-right: 25px;
            }
            
            .toast-close {
                border: none;
                background: none;
                color: inherit;
                font-size: 18px;
                cursor: pointer;
                opacity: 0.7;
                position: absolute;
                top: 8px;
                right: 8px;
                padding: 0;
                margin: 0;
                height: 24px;
                width: 24px;
                text-align: center;
                line-height: 22px;
                transition: opacity 0.2s;
            }
            
            .toast-close:hover {
                opacity: 1;
            }
            
            /* Animations */
            @keyframes toast-in {
                0% {
                    opacity: 0;
                    transform: translateY(20px);
                }
                100% {
                    opacity: 1;
                    transform: translateY(0);
                }
            }
            
            @keyframes toast-out {
                0% {
                    opacity: 1;
                    transform: translateX(0);
                }
                100% {
                    opacity: 0;
                    transform: translateX(100%);
                }
            }
            
            @keyframes toast-progress {
                0% {
                    width: 100%;
                }
                100% {
                    width: 0%;
                }
            }
        `;
        
        document.head.appendChild(style);
    }
    
    /**
     * Create and show a toast notification
     * 
     * @param {Object} options Toast options
     * @returns {HTMLElement} Toast element
     */
    createToast(options) {
        // Enforce maximum number of visible toasts
        while (this.toasts.length >= this.options.maxToasts) {
            this.removeToast(this.toasts[0]);
        }
        
        const toast = document.createElement('div');
        toast.className = `toast toast-${options.type || 'info'}`;
        
        if (options.progress !== false) {
            toast.classList.add('with-progress');
        }
        
        // Icon based on type
        let icon = '';
        switch (options.type) {
            case 'success':
                icon = '<i class="fas fa-check-circle"></i>';
                break;
            case 'error':
                icon = '<i class="fas fa-times-circle"></i>';
                break;
            case 'warning':
                icon = '<i class="fas fa-exclamation-triangle"></i>';
                break;
            case 'info':
            default:
                icon = '<i class="fas fa-info-circle"></i>';
                break;
        }
        
        // Create content HTML
        toast.innerHTML = `
            <div class="toast-icon">${options.icon || icon}</div>
            <div class="toast-content">
                ${options.title ? `<span class="toast-title">${options.title}</span>` : ''}
                <span class="toast-message">${options.message}</span>
            </div>
            <button class="toast-close" aria-label="Close">&times;</button>
        `;
        
        // Add to container
        this.container.appendChild(toast);
        this.toasts.push(toast);
        
        // Configure duration, auto-hide
        const duration = options.duration || this.options.duration;
        let progressAnimation = null;
        
        if (duration) {
            toast.style.setProperty('--duration', `${duration}ms`);
            progressAnimation = toast.animate(
                [{ width: '100%' }, { width: '0%' }],
                {
                    duration: duration,
                    fill: 'forwards',
                    easing: 'linear'
                }
            );
        }
        
        // Event listeners for close button, hover and auto-dismiss
        toast.querySelector('.toast-close').addEventListener('click', () => {
            this.removeToast(toast);
        });
        
        let timeoutId = null;
        
        if (duration) {
            timeoutId = setTimeout(() => {
                this.removeToast(toast);
            }, duration);
        }
        
        // Handle pause on hover
        if (this.options.pauseOnHover && duration) {
            toast.addEventListener('mouseenter', () => {
                if (timeoutId) {
                    clearTimeout(timeoutId);
                    timeoutId = null;
                }
                
                if (progressAnimation) {
                    progressAnimation.pause();
                }
            });
            
            toast.addEventListener('mouseleave', () => {
                const remainingTime = progressAnimation ? 
                    duration * (1 - progressAnimation.currentTime / progressAnimation.effect.getTiming().duration) : 
                    duration;
                
                if (progressAnimation) {
                    progressAnimation.play();
                }
                
                timeoutId = setTimeout(() => {
                    this.removeToast(toast);
                }, remainingTime);
            });
        }
        
        // Custom events
        if (typeof options.onOpen === 'function') {
            options.onOpen(toast);
        }
        
        toast.addEventListener('transitionend', (event) => {
            if (event.propertyName === 'transform' && toast.classList.contains('toast-removing')) {
                this.finalizeRemoval(toast);
            }
        });
        
        return toast;
    }
    
    /**
     * Remove a toast notification
     * 
     * @param {HTMLElement} toast Toast element to remove
     */
    removeToast(toast) {
        if (!toast || toast.classList.contains('toast-removing')) {
            return;
        }
        
        toast.classList.add('toast-removing');
        toast.style.animation = 'toast-out 0.3s ease-in-out forwards';
        
        setTimeout(() => {
            this.finalizeRemoval(toast);
        }, 300);
    }
    
    /**
     * Complete toast removal from DOM
     * 
     * @param {HTMLElement} toast Toast element to remove
     */
    finalizeRemoval(toast) {
        if (!toast || !toast.parentNode) {
            return;
        }
        
        // Remove from container
        toast.parentNode.removeChild(toast);
        
        // Remove from array
        const index = this.toasts.indexOf(toast);
        if (index !== -1) {
            this.toasts.splice(index, 1);
        }
    }
    
    /**
     * Show a success toast notification
     * 
     * @param {string} message Toast message
     * @param {string|null} title Optional title
     * @param {Object} options Additional options
     * @returns {HTMLElement} Toast element
     */
    success(message, title = null, options = {}) {
        return this.createToast({
            ...options,
            type: 'success',
            message,
            title
        });
    }
    
    /**
     * Show an error toast notification
     * 
     * @param {string} message Toast message
     * @param {string|null} title Optional title
     * @param {Object} options Additional options
     * @returns {HTMLElement} Toast element
     */
    error(message, title = null, options = {}) {
        return this.createToast({
            ...options,
            type: 'error',
            message,
            title
        });
    }
    
    /**
     * Show an info toast notification
     * 
     * @param {string} message Toast message
     * @param {string|null} title Optional title
     * @param {Object} options Additional options
     * @returns {HTMLElement} Toast element
     */
    info(message, title = null, options = {}) {
        return this.createToast({
            ...options,
            type: 'info',
            message,
            title
        });
    }
    
    /**
     * Show a warning toast notification
     * 
     * @param {string} message Toast message
     * @param {string|null} title Optional title
     * @param {Object} options Additional options
     * @returns {HTMLElement} Toast element
     */
    warning(message, title = null, options = {}) {
        return this.createToast({
            ...options,
            type: 'warning',
            message,
            title
        });
    }
    
    /**
     * Clear all toast notifications
     */
    clearAll() {
        // Create a copy of the array to avoid issues with array modification during iteration
        const toastsCopy = [...this.toasts];
        toastsCopy.forEach(toast => {
            this.removeToast(toast);
        });
    }
}

// Create global toast instance
const toast = new ToastManager();

// Expose globally
window.toast = toast;