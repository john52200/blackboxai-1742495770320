const utils = {
    showLoading: (message = 'Chargement en cours...') => {
        const loadingIndicator = document.getElementById('loading-indicator');
        if (loadingIndicator) {
            loadingIndicator.querySelector('p').textContent = message;
            loadingIndicator.classList.remove('hidden');
        }
    },

    hideLoading: () => {
        const loadingIndicator = document.getElementById('loading-indicator');
        if (loadingIndicator) {
            loadingIndicator.classList.add('hidden');
        }
    },

    showNotification: (message, type) => {
        const container = document.getElementById('error-container');
        if (!container) return;

        const notification = document.createElement('div');
        notification.className = `p-4 mb-4 rounded-lg shadow-lg transition-all duration-500 transform translate-x-0 ${
            type === 'success' ? 'bg-green-500' : type === 'error' ? 'bg-red-500' : 'bg-yellow-500'
        } text-white`;
        
        notification.innerHTML = `
            <div class="flex items-center">
                <i class="fas fa-${
                    type === 'success' ? 'check-circle' : type === 'error' ? 'exclamation-circle' : 'info-circle'
                } mr-2"></i>
                <span>${message}</span>
                <button onclick="this.parentElement.parentElement.remove()" class="ml-auto">
                    <i class="fas fa-times"></i>
                </button>
            </div>
        `;

        container.appendChild(notification);

        // Auto-remove after 5 seconds
        setTimeout(() => {
            notification.classList.add('translate-x-full', 'opacity-0');
            setTimeout(() => notification.remove(), 500);
        }, 5000);
    },

    showSuccess: (message) => {
        utils.showNotification(message, 'success');
    },

    showError: (message) => {
        utils.showNotification(message, 'error');
    },

    showInfo: (message) => {
        utils.showNotification(message, 'info');
    },

    formatDate: (date) => {
        return new Date(date).toLocaleDateString('fr-FR', {
            year: 'numeric',
            month: 'long',
            day: 'numeric',
            hour: '2-digit',
            minute: '2-digit'
        });
    },

    formatCurrency: (amount) => {
        return new Intl.NumberFormat('fr-FR', {
            style: 'currency',
            currency: 'EUR'
        }).format(amount);
    },

    // Form helpers
    getFormData: (form) => {
        const formData = new FormData(form);
        const data = {};
        for (let [key, value] of formData.entries()) {
            data[key] = value;
        }
        return data;
    },

    // API helpers
    async fetchAPI(endpoint, options = {}) {
        try {
            const response = await fetch(`/api/${endpoint}`, {
                ...options,
                headers: {
                    'Content-Type': 'application/json',
                    ...options.headers
                }
            });

            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }

            const data = await response.json();
            return data;
        } catch (error) {
            console.error('API Error:', error);
            throw error;
        }
    },

    // Modal helpers
    showModal: (modalId) => {
        const modal = document.getElementById(modalId);
        if (modal) {
            modal.classList.add('active');
            // Add backdrop
            const backdrop = document.createElement('div');
            backdrop.className = 'modal-backdrop fixed inset-0 z-40';
            backdrop.onclick = () => utils.hideModal(modalId);
            document.body.appendChild(backdrop);
        }
    },

    hideModal: (modalId) => {
        const modal = document.getElementById(modalId);
        if (modal) {
            modal.classList.remove('active');
            // Remove backdrop
            const backdrop = document.querySelector('.modal-backdrop');
            if (backdrop) {
                backdrop.remove();
            }
        }
    },

    // Permission helpers
    hasPermission: (permission) => {
        return window.auth && window.auth.hasPermission(permission);
    },

    getUserRole: () => {
        return window.auth && window.auth.getUserRole();
    },

    // Form validation helpers
    validateForm: (formData, rules) => {
        const errors = {};
        
        Object.entries(rules).forEach(([field, validations]) => {
            validations.forEach(validation => {
                const value = formData[field];
                
                if (validation === 'required' && !value) {
                    errors[field] = 'Ce champ est requis';
                }
                
                if (validation === 'email' && value && !value.includes('@')) {
                    errors[field] = 'Email invalide';
                }
                
                if (validation === 'number' && value && isNaN(value)) {
                    errors[field] = 'Doit être un nombre';
                }
                
                if (typeof validation === 'function') {
                    const error = validation(value);
                    if (error) {
                        errors[field] = error;
                    }
                }
            });
        });
        
        return Object.keys(errors).length === 0 ? null : errors;
    },

    // Error display helpers
    showFormErrors: (form, errors) => {
        // Remove existing error messages
        form.querySelectorAll('.error-message').forEach(el => el.remove());
        form.querySelectorAll('.error-field').forEach(el => el.classList.remove('error-field'));
        
        Object.entries(errors).forEach(([field, message]) => {
            const input = form.querySelector(`[name="${field}"]`);
            if (input) {
                input.classList.add('error-field');
                const errorDiv = document.createElement('div');
                errorDiv.className = 'error-message text-red-500 text-sm mt-1';
                errorDiv.textContent = message;
                input.parentNode.appendChild(errorDiv);
            }
        });
    }
};

// Export utils for use in other modules
window.utils = utils;