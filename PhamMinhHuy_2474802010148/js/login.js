// login.js
document.addEventListener('DOMContentLoaded', function() {
    initPasswordToggle();
    initFormValidation();
    initLoginForm();
    initInputEffects();
    initKeyboardShortcuts();
    initRememberMe();
});

function initPasswordToggle() {
    const toggleBtn = document.querySelector('.toggle-password');
    const passwordInput = document.querySelector('input[name="password"]');
    const icon = toggleBtn?.querySelector('i');
    
    if (toggleBtn && passwordInput && icon) {
        toggleBtn.addEventListener('click', function() {
            const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
            passwordInput.setAttribute('type', type);
            
            // Thay đổi icon
            if (type === 'password') {
                icon.className = 'fas fa-eye';
            } else {
                icon.className = 'fas fa-eye-slash';
            }
        });
    }
}

function initFormValidation() {
    const form = document.getElementById('loginForm');
    const usernameInput = document.getElementById('username');
    const passwordInput = document.getElementById('password');
    
    if (form) {
        form.addEventListener('submit', function(e) {
            let isValid = true;
            
            // Validate username
            if (!usernameInput.value.trim()) {
                showError(usernameInput, 'usernameError', 'Vui lòng nhập tên đăng nhập');
                isValid = false;
            } else {
                clearError(usernameInput, 'usernameError');
            }
            
            // Validate password
            if (!passwordInput.value) {
                showError(passwordInput, 'passwordError', 'Vui lòng nhập mật khẩu');
                isValid = false;
            } else {
                clearError(passwordInput, 'passwordError');
            }
            
            if (!isValid) {
                e.preventDefault();
            }
        });
    }
    
    // Real-time validation
    if (usernameInput) {
        usernameInput.addEventListener('blur', function() {
            if (!this.value.trim()) {
                showError(this, 'usernameError', 'Vui lòng nhập tên đăng nhập');
            } else {
                clearError(this, 'usernameError');
            }
        });
    }
    
    if (passwordInput) {
        passwordInput.addEventListener('blur', function() {
            if (!this.value) {
                showError(this, 'passwordError', 'Vui lòng nhập mật khẩu');
            } else {
                clearError(this, 'passwordError');
            }
        });
    }
}

function showError(input, errorId, message) {
    const errorElement = document.getElementById(errorId);
    
    if (errorElement) {
        errorElement.textContent = message;
        errorElement.classList.add('show');
        input.classList.add('error');
        
        // Thêm icon cảnh báo cho input
        const inputIcon = input.parentElement.querySelector('.input-icon');
        if (inputIcon) {
            inputIcon.style.color = '#d32f2f';
        }
    }
}

function clearError(input, errorId) {
    const errorElement = document.getElementById(errorId);
    
    if (errorElement) {
        errorElement.classList.remove('show');
    }
    
    input.classList.remove('error');
    
    // Khôi phục màu icon
    const inputIcon = input.parentElement.querySelector('.input-icon');
    if (inputIcon) {
        inputIcon.style.color = '#d32f2f';
    }
}

function initLoginForm() {
    const form = document.getElementById('loginForm');
    const loginBtn = document.getElementById('loginBtn');
    
    if (form && loginBtn) {
        form.addEventListener('submit', function() {
            loginBtn.classList.add('loading');
            loginBtn.disabled = true;
            loginBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> ĐANG XỬ LÝ...';
            
            setTimeout(() => {
                loginBtn.classList.remove('loading');
                loginBtn.disabled = false;
                loginBtn.innerHTML = '<i class="fas fa-sign-in-alt"></i> ĐĂNG NHẬP';
            }, 3000);
        });
    }
}

function initInputEffects() {
    document.querySelectorAll('.form-control').forEach(input => {
        input.addEventListener('focus', function() {
            this.parentElement.classList.add('focused');
            const inputIcon = this.parentElement.querySelector('.input-icon');
            if (inputIcon) {
                inputIcon.style.color = '#b71c1c';
            }
        });
        
        input.addEventListener('blur', function() {
            if (!this.value) {
                this.parentElement.classList.remove('focused');
            }
            const inputIcon = this.parentElement.querySelector('.input-icon');
            if (inputIcon && !this.classList.contains('error')) {
                inputIcon.style.color = '#d32f2f';
            }
        });
        
        input.addEventListener('input', function() {
            const errorId = this.id + 'Error';
            clearError(this, errorId);
        });
    });
}

function initKeyboardShortcuts() {
    document.addEventListener('keydown', function(e) {
        if (e.ctrlKey && e.key === 'Enter') {
            const form = document.getElementById('loginForm');
            if (form) {
                form.dispatchEvent(new Event('submit'));
            }
        }
        
        if (e.key === 'Escape') {
            const usernameInput = document.getElementById('username');
            if (usernameInput) {
                usernameInput.focus();
                usernameInput.select();
            }
        }
    });
}

function initRememberMe() {
    const rememberCheckbox = document.getElementById('remember');
    const usernameInput = document.getElementById('username');
    
    if (localStorage.getItem('rememberMe') === 'true') {
        rememberCheckbox.checked = true;
        const savedUsername = localStorage.getItem('username');
        if (savedUsername) {
            usernameInput.value = savedUsername;
        }
    }
    
    const form = document.getElementById('loginForm');
    form.addEventListener('submit', function() {
        if (rememberCheckbox.checked) {
            localStorage.setItem('rememberMe', 'true');
            localStorage.setItem('username', usernameInput.value);
        } else {
            localStorage.removeItem('rememberMe');
            localStorage.removeItem('username');
        }
    });
}

window.addEventListener('load', function() {
    const usernameInput = document.getElementById('username');
    if (usernameInput && !usernameInput.value) {
        usernameInput.focus();
    }
});