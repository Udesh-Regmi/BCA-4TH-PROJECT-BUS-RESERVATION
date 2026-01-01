// UI/JS/MAIN.JS - Enhanced with More Functionality

document.addEventListener('DOMContentLoaded', function() {
    // Mobile Navigation Toggle
    const navToggle = document.createElement('button');
    navToggle.className = 'nav-toggle';
    navToggle.innerHTML = '<i class="fas fa-bars"></i>';
    navToggle.style.display = 'none';
    
    const navbar = document.querySelector('.navbar .container');
    const navMenu = document.querySelector('.nav-menu');
    
    if (navbar && navMenu) {
        navbar.insertBefore(navToggle, navMenu);
        
        navToggle.addEventListener('click', function() {
            navMenu.classList.toggle('active');
        });
        
        // Show toggle on mobile
        window.addEventListener('resize', function() {
            if (window.innerWidth <= 768) {
                navToggle.style.display = 'block';
            } else {
                navToggle.style.display = 'none';
                navMenu.classList.remove('active');
            }
        });
        
        // Initial check
        if (window.innerWidth <= 768) {
            navToggle.style.display = 'block';
        }
    }
    
    // Form Validation
    const forms = document.querySelectorAll('form');
    forms.forEach(form => {
        form.addEventListener('submit', function(e) {
            const password = form.querySelector('input[name="password"]');
            const confirmPassword = form.querySelector('input[name="confirm_password"]');
            
            if (password && confirmPassword) {
                if (password.value !== confirmPassword.value) {
                    e.preventDefault();
                    showAlert('Passwords do not match!', 'danger');
                    return false;
                }
                
                if (password.value.length < 6) {
                    e.preventDefault();
                    showAlert('Password must be at least 6 characters long!', 'danger');
                    return false;
                }
            }
            
            // Email validation
            const email = form.querySelector('input[type="email"]');
            if (email && !isValidEmail(email.value)) {
                e.preventDefault();
                showAlert('Please enter a valid email address!', 'danger');
                return false;
            }
            
            // Phone validation
            const phone = form.querySelector('input[type="tel"]');
            if (phone && phone.value && !isValidPhone(phone.value)) {
                e.preventDefault();
                showAlert('Please enter a valid phone number!', 'danger');
                return false;
            }
        });
    });
    
    // Date Input Restrictions
    const dateInputs = document.querySelectorAll('input[type="date"]');
    const today = new Date().toISOString().split('T')[0];
    dateInputs.forEach(input => {
        if (!input.getAttribute('min')) {
            input.setAttribute('min', today);
        }
    });
    
    // Auto-hide alerts
    const alerts = document.querySelectorAll('.alert');
    alerts.forEach(alert => {
        setTimeout(() => {
            alert.style.opacity = '0';
            setTimeout(() => {
                alert.style.display = 'none';
            }, 300);
        }, 5000);
    });
    
    // Confirmation dialogs
    const confirmButtons = document.querySelectorAll('[data-confirm]');
    confirmButtons.forEach(button => {
        button.addEventListener('click', function(e) {
            const message = this.getAttribute('data-confirm');
            if (!confirm(message)) {
                e.preventDefault();
                return false;
            }
        });
    });
    
    // Table Search Functionality
    const searchInput = document.querySelector('.table-search input');
    if (searchInput) {
        searchInput.addEventListener('input', function() {
            const searchTerm = this.value.toLowerCase();
            const tableRows = document.querySelectorAll('tbody tr');
            
            tableRows.forEach(row => {
                const text = row.textContent.toLowerCase();
                if (text.includes(searchTerm)) {
                    row.style.display = '';
                } else {
                    row.style.display = 'none';
                }
            });
        });
    }
    
    // Smooth Scroll
    const smoothScrollLinks = document.querySelectorAll('a[href^="#"]');
    smoothScrollLinks.forEach(link => {
        link.addEventListener('click', function(e) {
            const target = document.querySelector(this.getAttribute('href'));
            if (target) {
                e.preventDefault();
                target.scrollIntoView({
                    behavior: 'smooth',
                    block: 'start'
                });
            }
        });
    });
    
    // Loading State for Forms
    forms.forEach(form => {
        form.addEventListener('submit', function() {
            const submitButton = this.querySelector('button[type="submit"]');
            if (submitButton) {
                submitButton.disabled = true;
                submitButton.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Loading...';
            }
        });
    });
    
    // Print Functionality
    const printButtons = document.querySelectorAll('[data-print]');
    printButtons.forEach(button => {
        button.addEventListener('click', function() {
            window.print();
        });
    });
});

// Helper Functions
function showAlert(message, type = 'success') {
    const alertDiv = document.createElement('div');
    alertDiv.className = `alert alert-${type}`;
    alertDiv.textContent = message;
    alertDiv.style.position = 'fixed';
    alertDiv.style.top = '80px';
    alertDiv.style.right = '20px';
    alertDiv.style.zIndex = '9999';
    
    document.body.appendChild(alertDiv);
    
    setTimeout(() => {
        alertDiv.style.opacity = '0';
        setTimeout(() => {
            alertDiv.remove();
        }, 300);
    }, 3000);
}

function isValidEmail(email) {
    const re = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    return re.test(email);
}

function isValidPhone(phone) {
    const re = /^[\d\s\-\+\(\)]{10,}$/;
    return re.test(phone);
}

function formatCurrency(amount) {
    return new Intl.NumberFormat('en-US', {
        style: 'currency',
        currency: 'USD'
    }).format(amount);
}

function formatDate(dateString) {
    const date = new Date(dateString);
    return date.toLocaleDateString('en-US', {
        year: 'numeric',
        month: 'short',
        day: 'numeric'
    });
}

// UI/JS/ADMIN.JS - Admin Specific JavaScript

document.addEventListener('DOMContentLoaded', function() {
    // Statistics Animation
    const statNumbers = document.querySelectorAll('.stat-info h3');
    statNumbers.forEach(stat => {
        const target = parseInt(stat.textContent);
        let current = 0;
        const increment = target / 50;
        
        const updateNumber = () => {
            current += increment;
            if (current < target) {
                stat.textContent = Math.ceil(current);
                requestAnimationFrame(updateNumber);
            } else {
                stat.textContent = target;
            }
        };
        
        updateNumber();
    });
    
    // Bulk Actions
    const bulkActionSelect = document.querySelector('.bulk-actions select');
    const checkboxes = document.querySelectorAll('tbody input[type="checkbox"]');
    const selectAll = document.querySelector('thead input[type="checkbox"]');
    
    if (selectAll) {
        selectAll.addEventListener('change', function() {
            checkboxes.forEach(checkbox => {
                checkbox.checked = this.checked;
            });
        });
    }
    
    if (bulkActionSelect) {
        bulkActionSelect.addEventListener('change', function() {
            const selectedIds = Array.from(checkboxes)
                .filter(cb => cb.checked)
                .map(cb => cb.value);
            
            if (selectedIds.length === 0) {
                alert('Please select at least one item');
                this.value = '';
                return;
            }
            
            const action = this.value;
            if (action && confirm(`Are you sure you want to ${action} ${selectedIds.length} item(s)?`)) {
                // Handle bulk action
                console.log('Bulk action:', action, 'IDs:', selectedIds);
            }
            this.value = '';
        });
    }
    
    // Modal Functionality
    const modalTriggers = document.querySelectorAll('[data-modal]');
    const modals = document.querySelectorAll('.modal');
    
    modalTriggers.forEach(trigger => {
        trigger.addEventListener('click', function(e) {
            e.preventDefault();
            const modalId = this.getAttribute('data-modal');
            const modal = document.getElementById(modalId);
            if (modal) {
                modal.classList.add('active');
            }
        });
    });
    
    const closeButtons = document.querySelectorAll('.modal-close');
    closeButtons.forEach(button => {
        button.addEventListener('click', function() {
            this.closest('.modal').classList.remove('active');
        });
    });
    
    modals.forEach(modal => {
        modal.addEventListener('click', function(e) {
            if (e.target === this) {
                this.classList.remove('active');
            }
        });
    });
    
    // Export Functionality
    const exportButtons = document.querySelectorAll('[data-export]');
    exportButtons.forEach(button => {
        button.addEventListener('click', function() {
            const format = this.getAttribute('data-export');
            const table = document.querySelector('table');
            
            if (format === 'csv') {
                exportTableToCSV(table);
            } else if (format === 'pdf') {
                alert('PDF export would be implemented with a library like jsPDF');
            }
        });
    });
    
    // Real-time Search
    const realTimeSearch = document.querySelector('[data-realtime-search]');
    if (realTimeSearch) {
        let searchTimeout;
        realTimeSearch.addEventListener('input', function() {
            clearTimeout(searchTimeout);
            const searchTerm = this.value;
            
            searchTimeout = setTimeout(() => {
                // Perform AJAX search
                console.log('Searching for:', searchTerm);
            }, 500);
        });
    }
    
    // Chart Initialization (if needed)
    initializeCharts();
});

// Export Table to CSV
function exportTableToCSV(table) {
    const rows = table.querySelectorAll('tr');
    const csv = [];
    
    rows.forEach(row => {
        const cols = row.querySelectorAll('td, th');
        const rowData = Array.from(cols).map(col => {
            return '"' + col.textContent.trim().replace(/"/g, '""') + '"';
        });
        csv.push(rowData.join(','));
    });
    
    const csvContent = csv.join('\n');
    const blob = new Blob([csvContent], { type: 'text/csv' });
    const url = window.URL.createObjectURL(blob);
    const a = document.createElement('a');
    a.href = url;
    a.download = 'export_' + Date.now() + '.csv';
    a.click();
    window.URL.revokeObjectURL(url);
}

// Initialize Charts (placeholder)
function initializeCharts() {
    const chartContainers = document.querySelectorAll('[data-chart]');
    chartContainers.forEach(container => {
        const chartType = container.getAttribute('data-chart');
        console.log('Initialize chart:', chartType);
        // Chart library integration would go here
    });
}

// Dashboard Auto-refresh
let autoRefreshInterval;
function startAutoRefresh(interval = 60000) {
    autoRefreshInterval = setInterval(() => {
        const refreshElements = document.querySelectorAll('[data-auto-refresh]');
        refreshElements.forEach(element => {
            // Refresh logic here
            console.log('Auto-refreshing:', element);
        });
    }, interval);
}

function stopAutoRefresh() {
    if (autoRefreshInterval) {
        clearInterval(autoRefreshInterval);
    }
}

// UI/JS/VALIDATION.JS - Advanced Form Validation

class FormValidator {
    constructor(form) {
        this.form = form;
        this.errors = [];
        this.init();
    }
    
    init() {
        this.form.addEventListener('submit', (e) => {
            this.errors = [];
            
            if (!this.validate()) {
                e.preventDefault();
                this.displayErrors();
            }
        });
        
        // Real-time validation
        const inputs = this.form.querySelectorAll('input, select, textarea');
        inputs.forEach(input => {
            input.addEventListener('blur', () => {
                this.validateField(input);
            });
        });
    }
    
    validate() {
        const inputs = this.form.querySelectorAll('[required]');
        
        inputs.forEach(input => {
            this.validateField(input);
        });
        
        return this.errors.length === 0;
    }
    
    validateField(field) {
        this.clearFieldError(field);
        
        // Required validation
        if (field.hasAttribute('required') && !field.value.trim()) {
            this.addError(field, 'This field is required');
            return false;
        }
        
        // Email validation
        if (field.type === 'email' && field.value) {
            if (!this.isValidEmail(field.value)) {
                this.addError(field, 'Please enter a valid email address');
                return false;
            }
        }
        
        // Phone validation
        if (field.type === 'tel' && field.value) {
            if (!this.isValidPhone(field.value)) {
                this.addError(field, 'Please enter a valid phone number');
                return false;
            }
        }
        
        // Number validation
        if (field.type === 'number') {
            const min = field.getAttribute('min');
            const max = field.getAttribute('max');
            const value = parseFloat(field.value);
            
            if (min && value < parseFloat(min)) {
                this.addError(field, `Value must be at least ${min}`);
                return false;
            }
            
            if (max && value > parseFloat(max)) {
                this.addError(field, `Value must not exceed ${max}`);
                return false;
            }
        }
        
        // Password confirmation
        if (field.name === 'confirm_password') {
            const password = this.form.querySelector('[name="password"]');
            if (password && field.value !== password.value) {
                this.addError(field, 'Passwords do not match');
                return false;
            }
        }
        
        return true;
    }
    
    addError(field, message) {
        this.errors.push({ field, message });
        this.displayFieldError(field, message);
    }
    
    displayFieldError(field, message) {
        const errorDiv = document.createElement('div');
        errorDiv.className = 'field-error';
        errorDiv.textContent = message;
        errorDiv.style.color = 'var(--danger)';
        errorDiv.style.fontSize = '0.875rem';
        errorDiv.style.marginTop = '0.25rem';
        
        field.style.borderColor = 'var(--danger)';
        field.parentElement.appendChild(errorDiv);
    }
    
    clearFieldError(field) {
        const existingError = field.parentElement.querySelector('.field-error');
        if (existingError) {
            existingError.remove();
        }
        field.style.borderColor = '';
    }
    
    displayErrors() {
        if (this.errors.length > 0) {
            const firstError = this.errors[0];
            firstError.field.focus();
            showAlert(firstError.message, 'danger');
        }
    }
    
    isValidEmail(email) {
        return /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email);
    }
    
    isValidPhone(phone) {
        return /^[\d\s\-\+\(\)]{10,}$/.test(phone);
    }
}

// Initialize validators for all forms
document.addEventListener('DOMContentLoaded', function() {
    const forms = document.querySelectorAll('form[data-validate]');
    forms.forEach(form => new FormValidator(form));
});

// Seat Selection Handler
class SeatSelector {
    constructor(container, busId, date) {
        this.container = container;
        this.busId = busId;
        this.date = date;
        this.selectedSeat = null;
        this.init();
    }
    
    init() {
        this.renderSeats();
        this.attachEventListeners();
    }
    
    renderSeats() {
        // This would fetch seat data and render the seat map
        console.log('Rendering seats for bus:', this.busId, 'date:', this.date);
    }
    
    attachEventListeners() {
        const seats = this.container.querySelectorAll('.seat.available');
        seats.forEach(seat => {
            seat.addEventListener('click', () => {
                this.selectSeat(seat);
            });
        });
    }
    
    selectSeat(seat) {
        // Clear previous selection
        const previouslySelected = this.container.querySelector('.seat.selected');
        if (previouslySelected) {
            previouslySelected.classList.remove('selected');
        }
        
        // Select new seat
        seat.classList.add('selected');
        this.selectedSeat = seat.getAttribute('data-seat-number');
        
        // Update hidden input
        const seatInput = document.querySelector('input[name="seat_number"]');
        if (seatInput) {
            seatInput.value = this.selectedSeat;
        }
    }
}

// Initialize seat selector if on reservation page
document.addEventListener('DOMContentLoaded', function() {
    const seatMap = document.querySelector('[data-seat-map]');
    if (seatMap) {
        const busId = seatMap.getAttribute('data-bus-id');
        const date = seatMap.getAttribute('data-date');
        new SeatSelector(seatMap, busId, date);
    }
});