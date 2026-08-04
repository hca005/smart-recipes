// Modal Component JavaScript

class Modal {
    constructor(modalId) {
        this.modal = document.getElementById(modalId);
        this.overlay = this.modal?.querySelector('.modal-overlay');
        this.closeButtons = this.modal?.querySelectorAll('[data-modal-close]');
        
        if (this.modal) {
            this.init();
        }
    }

    init() {
        // Close button handlers
        this.closeButtons?.forEach(button => {
            button.addEventListener('click', () => this.close());
        });

        // Click outside to close
        this.overlay?.addEventListener('click', (e) => {
            if (e.target === this.overlay) {
                this.close();
            }
        });

        // ESC key to close
        document.addEventListener('keydown', (e) => {
            if (e.key === 'Escape' && this.isOpen()) {
                this.close();
            }
        });
    }

    open() {
        this.overlay?.classList.add('active');
        document.body.style.overflow = 'hidden';
    }

    close() {
        this.overlay?.classList.remove('active');
        document.body.style.overflow = '';
    }

    isOpen() {
        return this.overlay?.classList.contains('active');
    }

    setContent(content) {
        const modalBody = this.modal?.querySelector('.modal-body');
        if (modalBody) {
            modalBody.innerHTML = content;
        }
    }

    setTitle(title) {
        const modalTitle = this.modal?.querySelector('.modal-title');
        if (modalTitle) {
            modalTitle.textContent = title;
        }
    }
}

// Create a simple modal programmatically
function createModal(options = {}) {
    const {
        title = '',
        content = '',
        size = 'medium',
        showClose = true,
        buttons = []
    } = options;

    const modalId = 'modal-' + Date.now();
    
    const modalHTML = `
        <div id="${modalId}">
            <div class="modal-overlay">
                <div class="modal modal-${size}">
                    ${title ? `
                        <div class="modal-header">
                            <h3 class="modal-title">${title}</h3>
                            ${showClose ? `
                                <button class="modal-close" data-modal-close>
                                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                        <line x1="18" y1="6" x2="6" y2="18"></line>
                                        <line x1="6" y1="6" x2="18" y2="18"></line>
                                    </svg>
                                </button>
                            ` : ''}
                        </div>
                    ` : ''}
                    <div class="modal-body">
                        ${content}
                    </div>
                    ${buttons.length > 0 ? `
                        <div class="modal-footer">
                            ${buttons.map(btn => `
                                <button class="btn ${btn.className || 'btn-primary'}" 
                                        data-action="${btn.action || ''}">${btn.text}</button>
                            `).join('')}
                        </div>
                    ` : ''}
                </div>
            </div>
        </div>
    `;

    document.body.insertAdjacentHTML('beforeend', modalHTML);
    
    const modal = new Modal(modalId);
    
    // Handle button clicks
    buttons.forEach(btn => {
        if (btn.onClick) {
            const buttonEl = document.querySelector(`[data-action="${btn.action}"]`);
            buttonEl?.addEventListener('click', () => {
                btn.onClick(modal);
            });
        }
    });

    return modal;
}

// Confirmation modal
function confirmModal(message, onConfirm, onCancel) {
    return createModal({
        title: 'Confirm Action',
        content: `<p>${message}</p>`,
        buttons: [
            {
                text: 'Cancel',
                className: 'btn-outline',
                action: 'cancel',
                onClick: (modal) => {
                    modal.close();
                    if (onCancel) onCancel();
                }
            },
            {
                text: 'Confirm',
                className: 'btn-primary',
                action: 'confirm',
                onClick: (modal) => {
                    modal.close();
                    if (onConfirm) onConfirm();
                }
            }
        ]
    });
}

// Alert modal
function alertModal(message, title = 'Alert') {
    return createModal({
        title: title,
        content: `<p>${message}</p>`,
        buttons: [
            {
                text: 'OK',
                className: 'btn-primary',
                action: 'ok',
                onClick: (modal) => modal.close()
            }
        ]
    });
}

// Export modal functions
window.Modal = Modal;
window.createModal = createModal;
window.confirmModal = confirmModal;
window.alertModal = alertModal;
