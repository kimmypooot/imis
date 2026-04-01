/**
 * Optimized Idle Session Manager for Hostinger Hosting
 * Monitors user activity and handles automatic logout with improved performance
 * Features: Throttled event handling, efficient DOM queries, optimized timers
 */
class IdleSessionManager {
    constructor(options = {}) {
        // Configuration with sensible defaults
        this.idleTime = options.idleTime || 3600000; // 60 minutes default
        this.warningTime = options.warningTime || 3420000; // 57 minutes (3min warning)
        this.checkInterval = options.checkInterval || 5000; // Check every 5 seconds (reduced frequency)
        this.throttleDelay = options.throttleDelay || 100; // Throttle user events to 100ms
        
        // State management
        this.lastActivity = Date.now();
        this.warningShown = false;
        this.isIdle = false;
        this.isDestroyed = false;
        
        // Timer references for cleanup
        this.intervalId = null;
        this.warningTimeoutId = null;
        this.throttleTimeoutId = null;
        
        // Cached DOM elements to avoid repeated queries
        this.cachedElements = {
            profile: null,
            header: null,
            profileChecked: false,
            headerChecked: false
        };
        
        // Bind methods to preserve context
        this.handleActivity = this.handleActivity.bind(this);
        this.throttledActivityHandler = this.throttledActivityHandler.bind(this);
        
        this.init();
    }
    
    /**
     * Initialize the session manager with optimized event listeners
     */
    init() {
        if (this.isDestroyed) return;
        
        // Reduced event list - only essential events for activity detection
        const events = ['mousedown', 'keydown', 'touchstart', 'scroll'];
        
        // Use passive listeners for better scroll performance
        const passiveEvents = ['scroll', 'touchstart'];
        
        events.forEach(event => {
            const options = passiveEvents.includes(event) 
                ? { passive: true, capture: true }
                : { capture: true };
                
            document.addEventListener(event, this.throttledActivityHandler, options);
        });
        
        // Start monitoring with reduced frequency
        this.startMonitoring();
        
        console.log('Idle session manager initialized with optimized performance settings');
    }
    
    /**
     * Throttled activity handler to prevent excessive function calls
     */
    throttledActivityHandler() {
        if (this.throttleTimeoutId) return; // Already throttled
        
        this.throttleTimeoutId = setTimeout(() => {
            this.handleActivity();
            this.throttleTimeoutId = null;
        }, this.throttleDelay);
    }
    
    /**
     * Handle user activity with optimized state management
     */
    handleActivity() {
        if (this.isDestroyed || this.isIdle) return;
        
        this.lastActivity = Date.now();
        this.warningShown = false;
        
        // Clear warning timeout efficiently
        if (this.warningTimeoutId) {
            clearTimeout(this.warningTimeoutId);
            this.warningTimeoutId = null;
        }
        
        // Optimized modal closure - only check if likely to be open
        if (this.warningShown && typeof Swal !== 'undefined' && Swal.isVisible()) {
            const title = Swal.getTitle();
            if (title?.textContent?.includes('Session Timeout Warning')) {
                Swal.close();
            }
        }
    }
    
    /**
     * Start monitoring with optimized interval timing
     */
    startMonitoring() {
        if (this.intervalId || this.isDestroyed) return;
        
        this.intervalId = setInterval(() => {
            // Early exit for destroyed or idle states
            if (this.isDestroyed || this.isIdle) {
                this.stopMonitoring();
                return;
            }
            
            const now = Date.now();
            const idleFor = now - this.lastActivity;
            
            // Show warning at specified time
            if (idleFor >= this.warningTime && !this.warningShown) {
                this.showWarning();
                return;
            }
            
            // Auto-logout if time exceeded and no modal visible
            if (idleFor >= this.idleTime && !this.isModalVisible()) {
                this.logout();
            }
        }, this.checkInterval);
    }
    
    /**
     * Optimized modal visibility check
     */
    isModalVisible() {
        return typeof Swal !== 'undefined' && Swal.isVisible();
    }
    
    /**
     * Show session timeout warning with improved UX
     */
    showWarning() {
        if (this.warningShown || this.isDestroyed) return;
        
        this.warningShown = true;
        const remainingMs = this.idleTime - (Date.now() - this.lastActivity);
        const remainingSeconds = Math.max(1, Math.ceil(remainingMs / 1000));
        
        let timerInterval;
        
        Swal.fire({
            title: 'Session Timeout Warning',
            html: `Your session will expire in <strong>${this.formatTime(remainingSeconds)}</strong> due to inactivity.<br><br>Click "Stay Logged In" to continue your session.`,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: '<i class="bi bi-check-circle"></i> Stay Logged In',
            cancelButtonText: '<i class="bi bi-box-arrow-right"></i> Logout Now',
            allowOutsideClick: false,
            allowEscapeKey: false,
            timer: remainingMs,
            timerProgressBar: true,
            customClass: {
                popup: 'idle-warning-popup'
            },
            didOpen: () => {
                // Update timer display every second instead of 100ms for better performance
                timerInterval = setInterval(() => {
                    const timeLeft = Swal.getTimerLeft();
                    if (timeLeft > 0) {
                        const secondsLeft = Math.ceil(timeLeft / 1000);
                        const strongElement = Swal.getHtmlContainer()?.querySelector('strong');
                        if (strongElement) {
                            strongElement.textContent = this.formatTime(secondsLeft);
                        }
                    }
                }, 1000);
            },
            willClose: () => {
                if (timerInterval) {
                    clearInterval(timerInterval);
                }
            }
        }).then((result) => {
            this.handleWarningResult(result);
        }).catch((error) => {
            console.warn('Warning modal error:', error);
            this.resetIdleTimer();
        });
    }
    
    /**
     * Handle warning modal result efficiently
     */
    handleWarningResult(result) {
        if (this.isDestroyed) return;
        
        if (result.isConfirmed) {
            // User chose to stay - reset session
            this.resetIdleTimer();
            console.log('Session extended by user choice');
        } else if (result.dismiss === Swal.DismissReason.cancel || 
                   result.dismiss === Swal.DismissReason.timer) {
            // User clicked logout or timer expired
            this.logout();
        }
    }
    
    /**
     * Reset idle timer with minimal overhead
     */
    resetIdleTimer() {
        if (this.isDestroyed) return;
        
        this.lastActivity = Date.now();
        this.warningShown = false;
        this.isIdle = false;
        
        if (this.warningTimeoutId) {
            clearTimeout(this.warningTimeoutId);
            this.warningTimeoutId = null;
        }
    }
    
    /**
     * Perform logout with optimized redirect logic
     */
    logout() {
        if (this.isIdle || this.isDestroyed) return;
        
        this.isIdle = true;
        this.stopMonitoring();
        
        // Close any existing modals
        if (this.isModalVisible()) {
            Swal.close();
        }
        
        // Show logout message with faster redirect
        let timerInterval;
        Swal.fire({
            title: 'Session Expired',
            html: 'You have been logged out due to inactivity.<br><br>Redirecting in <strong>2</strong> seconds.',
            icon: 'info',
            timer: 2000, // Reduced from 3.5 seconds
            timerProgressBar: true,
            allowOutsideClick: false,
            allowEscapeKey: false,
            showConfirmButton: false,
            customClass: {
                popup: 'idle-logout-popup'
            },
            didOpen: () => {
                Swal.showLoading();
                timerInterval = setInterval(() => {
                    const timeLeft = Swal.getTimerLeft();
                    if (timeLeft > 0) {
                        const strongElement = Swal.getHtmlContainer()?.querySelector('strong');
                        if (strongElement) {
                            strongElement.textContent = Math.ceil(timeLeft / 1000);
                        }
                    }
                }, 100);
            },
            willClose: () => {
                if (timerInterval) {
                    clearInterval(timerInterval);
                }
                this.performLogout();
            }
        });
    }
    
    /**
     * Optimized logout URL generation for Hostinger hosting
     */
    performLogout() {
        try {
            const { hostname, pathname } = window.location;
            
            // Optimized subdomain detection for Hostinger
            const isSubdomain = hostname.includes('imis.') || hostname === 'imis.cscro8.com';
            
            let logoutUrl;
            
            if (isSubdomain) {
                logoutUrl = '/inc/logout?reason=idle';
            } else {
                // More efficient path parsing
                const pathSegments = pathname.split('/').filter(Boolean);
                const imisIndex = pathSegments.indexOf('imis');
                
                if (imisIndex !== -1 && imisIndex < pathSegments.length - 1) {
                    const depth = pathSegments.length - imisIndex - 1;
                    logoutUrl = '../'.repeat(depth) + 'inc/logout?reason=idle';
                } else {
                    logoutUrl = './inc/logout?reason=idle';
                }
            }
            
            // Use replace instead of href for better performance
            window.location.replace(logoutUrl);
            
        } catch (error) {
            console.error('Logout redirect error:', error);
            // Fallback logout
            window.location.replace('./inc/logout?reason=idle');
        }
    }
    
    /**
     * Stop monitoring and clean up resources
     */
    stopMonitoring() {
        if (this.intervalId) {
            clearInterval(this.intervalId);
            this.intervalId = null;
        }
        
        if (this.throttleTimeoutId) {
            clearTimeout(this.throttleTimeoutId);
            this.throttleTimeoutId = null;
        }
    }
    
    /**
     * Format time display efficiently
     */
    formatTime(seconds) {
        if (seconds < 60) {
            return `${seconds} second${seconds !== 1 ? 's' : ''}`;
        }
        
        const minutes = Math.floor(seconds / 60);
        const remainingSeconds = seconds % 60;
        return `${minutes} minute${minutes !== 1 ? 's' : ''} and ${remainingSeconds} second${remainingSeconds !== 1 ? 's' : ''}`;
    }
    
    /**
     * Update configuration dynamically
     */
    updateConfig(newOptions = {}) {
        if (this.isDestroyed) return;
        
        this.idleTime = newOptions.idleTime || this.idleTime;
        this.warningTime = newOptions.warningTime || Math.max(this.idleTime - 180000, 30000); // 3min warning minimum
        this.checkInterval = newOptions.checkInterval || this.checkInterval;
        
        // Restart monitoring with new settings
        this.stopMonitoring();
        this.resetIdleTimer();
        this.startMonitoring();
        
        console.log('Idle manager configuration updated');
    }
    
    /**
     * Cached DOM element checker for better performance
     */
    isUserLoggedIn() {
        // Cache profile element check
        if (!this.cachedElements.profileChecked) {
            this.cachedElements.profile = document.querySelector('.nav-profile');
            this.cachedElements.profileChecked = true;
        }
        
        // Cache header element check
        if (!this.cachedElements.headerChecked) {
            this.cachedElements.header = document.querySelector('#header');
            this.cachedElements.headerChecked = true;
        }
        
        return !!(this.cachedElements.profile || this.cachedElements.header);
    }
    
    /**
     * Destroy instance and clean up all resources
     */
    destroy() {
        this.isDestroyed = true;
        this.stopMonitoring();
        
        // Remove event listeners
        const events = ['mousedown', 'keydown', 'touchstart', 'scroll'];
        events.forEach(event => {
            document.removeEventListener(event, this.throttledActivityHandler, true);
        });
        
        // Clear all timeouts
        if (this.warningTimeoutId) {
            clearTimeout(this.warningTimeoutId);
        }
        if (this.throttleTimeoutId) {
            clearTimeout(this.throttleTimeoutId);
        }
        
        // Close any open modals
        if (this.isModalVisible()) {
            Swal.close();
        }
        
        console.log('Idle session manager destroyed');
    }
}

/**
 * Optimized initialization with proper error handling
 */
function initializeIdleManager() {
    try {
        // Check if already initialized
        if (window.idleManager) {
            console.warn('Idle manager already initialized');
            return;
        }
        
        // Create new instance
        const manager = new IdleSessionManager({
            idleTime: 3600000,      // 60 minutes
            warningTime: 3420000,   // 57 minutes (3-minute warning)
            checkInterval: 5000,    // Check every 5 seconds (optimized)
            throttleDelay: 100      // Throttle events to 100ms
        });
        
        // Only activate if user is logged in
        if (manager.isUserLoggedIn()) {
            window.idleManager = manager;
            console.log('Idle session manager initialized - 60min timeout with 3min warning (Hostinger optimized)');
        } else {
            manager.destroy();
            console.log('User not logged in - idle manager not activated');
        }
        
    } catch (error) {
        console.error('Failed to initialize idle manager:', error);
    }
}

// Optimized DOM ready detection
if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', initializeIdleManager);
} else {
    // DOM already loaded
    initializeIdleManager();
}

// Handle page visibility changes to pause/resume monitoring
document.addEventListener('visibilitychange', () => {
    if (window.idleManager && !window.idleManager.isDestroyed) {
        if (document.hidden) {
            // Page hidden - could pause monitoring to save resources
            console.log('Page hidden - idle monitoring continues');
        } else {
            // Page visible - reset activity
            window.idleManager.handleActivity();
            console.log('Page visible - activity reset');
        }
    }
});

// Export for module systems
if (typeof module !== 'undefined' && module.exports) {
    module.exports = IdleSessionManager;
}