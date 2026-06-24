// script.js - Common JavaScript for all pages

document.addEventListener('DOMContentLoaded', function() {
    // ===== INDEX PAGE FUNCTIONALITY =====
    if (document.querySelector('.stat-number')) {
        // Animated counter for stats on index page
        const counters = document.querySelectorAll('.stat-number');
        const speed = 200;
        
        counters.forEach(counter => {
            const updateCount = () => {
                const target = +counter.getAttribute('data-count');
                const count = +counter.innerText;
                
                const inc = target / speed;
                
                if (count < target) {
                    counter.innerText = Math.ceil(count + inc);
                    setTimeout(updateCount, 1);
                } else {
                    counter.innerText = target;
                }
            };
            
            updateCount();
        });
    }
    
    // ===== NAVIGATION SCROLL =====
    const navLinks = document.querySelectorAll('.nav a');
    
    navLinks.forEach(link => {
        link.addEventListener('click', function(e) {
            if (this.getAttribute('href').startsWith('#')) {
                e.preventDefault();
                const targetId = this.getAttribute('href');
                const targetElement = document.querySelector(targetId);
                
                if (targetElement) {
                    window.scrollTo({
                        top: targetElement.offsetTop - 100,
                        behavior: 'smooth'
                    });
                    
                    // Update active link
                    navLinks.forEach(l => l.classList.remove('active'));
                    this.classList.add('active');
                }
            }
        });
    });
    
    // ===== RIPPLE EFFECT ON BUTTONS =====
    const allButtons = document.querySelectorAll('button, .btn, .submit-btn');
    allButtons.forEach(button => {
        button.addEventListener('click', function(e) {
            // Create ripple effect
            const ripple = document.createElement('span');
            const rect = this.getBoundingClientRect();
            const size = Math.max(rect.width, rect.height);
            const x = e.clientX - rect.left - size/2;
            const y = e.clientY - rect.top - size/2;
            
            ripple.style.cssText = `
                position: absolute;
                border-radius: 50%;
                background: rgba(255, 255, 255, 0.5);
                transform: scale(0);
                animation: ripple 0.6s linear;
                width: ${size}px;
                height: ${size}px;
                top: ${y}px;
                left: ${x}px;
                pointer-events: none;
            `;
            
            this.style.position = 'relative';
            this.style.overflow = 'hidden';
            this.appendChild(ripple);
            
            setTimeout(() => ripple.remove(), 600);
        });
    });
    
    // ===== ALERT SYSTEM =====
    window.showAlert = function(message, type = 'info') {
        const existingAlert = document.querySelector('.alert-notification');
        if (existingAlert) existingAlert.remove();
        
        const alert = document.createElement('div');
        alert.className = `alert-notification alert-${type}`;
        alert.innerHTML = `
            <div class="alert-content">
                <i class="fas ${getAlertIcon(type)}"></i>
                <span>${message}</span>
                <button class="alert-close"><i class="fas fa-times"></i></button>
            </div>
        `;
        
        document.body.appendChild(alert);
        
        // Add styles if not already present
        if (!document.querySelector('#alert-styles')) {
            const style = document.createElement('style');
            style.id = 'alert-styles';
            style.textContent = `
                .alert-notification {
                    position: fixed;
                    top: 20px;
                    right: 20px;
                    min-width: 300px;
                    background: rgba(255, 255, 255, 0.95);
                    color: #333;
                    border-radius: 10px;
                    padding: 15px 20px;
                    box-shadow: 0 5px 15px rgba(0, 0, 0, 0.2);
                    z-index: 1000;
                    animation: slideIn 0.3s ease-out;
                    border-left: 5px solid #4a69bd;
                }
                
                @keyframes slideIn {
                    from { transform: translateX(100%); opacity: 0; }
                    to { transform: translateX(0); opacity: 1; }
                }
                
                .alert-error { border-left-color: #ff6b6b; }
                .alert-success { border-left-color: #1dd1a1; }
                .alert-info { border-left-color: #48dbfb; }
                
                .alert-content {
                    display: flex;
                    align-items: center;
                    gap: 15px;
                }
                
                .alert-content i { font-size: 20px; }
                .alert-error .alert-content i { color: #ff6b6b; }
                .alert-success .alert-content i { color: #1dd1a1; }
                .alert-info .alert-content i { color: #48dbfb; }
                
                .alert-content span {
                    flex: 1;
                    font-size: 14px;
                }
                
                .alert-close {
                    background: transparent;
                    border: none;
                    color: #888;
                    cursor: pointer;
                    transition: color 0.3s;
                }
                
                .alert-close:hover { color: #333; }
                
                @keyframes slideOut {
                    from { transform: translateX(0); opacity: 1; }
                    to { transform: translateX(100%); opacity: 0; }
                }
            `;
            document.head.appendChild(style);
        }
        
        // Close button
        alert.querySelector('.alert-close').addEventListener('click', () => {
            alert.style.animation = 'slideOut 0.3s ease-out';
            setTimeout(() => alert.remove(), 300);
        });
        
        // Auto remove
        setTimeout(() => {
            if (alert.parentNode) {
                alert.style.animation = 'slideOut 0.3s ease-out';
                setTimeout(() => alert.remove(), 300);
            }
        }, 5000);
    };
    
    function getAlertIcon(type) {
        switch(type) {
            case 'error': return 'fa-exclamation-circle';
            case 'success': return 'fa-check-circle';
            case 'info': return 'fa-info-circle';
            default: return 'fa-info-circle';
        }
    }
});