// register.js
document.addEventListener('DOMContentLoaded', function() {
    initPasswordToggle();
    initFormValidation();
    initRegisterForm();
    initInputEffects();
});

function initPasswordToggle() {
    const toggleButtons = document.querySelectorAll('.toggle-password');
    
    toggleButtons.forEach(button => {
        const passwordInput = button.parentElement.querySelector('input[type="password"]');
        const icon = button.querySelector('i');
        
        if (button && passwordInput) {
            button.addEventListener('click', function() {
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
    });
}

function initFormValidation() {
    const form = document.getElementById('registerForm');
    const usernameInput = document.getElementById('username');
    const emailInput = document.getElementById('email');
    const passwordInput = document.getElementById('password');
    const password2Input = document.getElementById('password2');
    
    if (form) {
        form.addEventListener('submit', function(e) {
            let isValid = true;
            
            // Validate username
            if (!usernameInput.value.trim()) {
                showError(usernameInput, 'usernameError', 'Vui lòng nhập tên đăng nhập');
                isValid = false;
            } else if (usernameInput.value.length < 3) {
                showError(usernameInput, 'usernameError', 'Tên đăng nhập phải có ít nhất 3 ký tự');
                isValid = false;
            } else {
                clearError(usernameInput, 'usernameError');
            }
            
            // Validate email
            if (emailInput.value && !isValidEmail(emailInput.value)) {
                showError(emailInput, 'emailError', 'Email không hợp lệ');
                isValid = false;
            } else {
                clearError(emailInput, 'emailError');
            }
            
            // Validate password
            if (!passwordInput.value) {
                showError(passwordInput, 'passwordError', 'Vui lòng nhập mật khẩu');
                isValid = false;
            } else if (passwordInput.value.length < 6) {
                showError(passwordInput, 'passwordError', 'Mật khẩu phải có ít nhất 6 ký tự');
                isValid = false;
            } else {
                clearError(passwordInput, 'passwordError');
            }
            
            // Validate password confirmation
            if (!password2Input.value) {
                showError(password2Input, 'password2Error', 'Vui lòng nhập lại mật khẩu');
                isValid = false;
            } else if (passwordInput.value !== password2Input.value) {
                showError(password2Input, 'password2Error', 'Mật khẩu nhập lại không khớp');
                isValid = false;
            } else {
                clearError(password2Input, 'password2Error');
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
            } else if (this.value.length < 3) {
                showError(this, 'usernameError', 'Tên đăng nhập phải có ít nhất 3 ký tự');
            } else {
                clearError(this, 'usernameError');
            }
        });
    }
    
    if (emailInput) {
        emailInput.addEventListener('blur', function() {
            if (this.value && !isValidEmail(this.value)) {
                showError(this, 'emailError', 'Email không hợp lệ');
            } else {
                clearError(this, 'emailError');
            }
        });
    }
    
    if (passwordInput) {
        passwordInput.addEventListener('blur', function() {
            if (!this.value) {
                showError(this, 'passwordError', 'Vui lòng nhập mật khẩu');
            } else if (this.value.length < 6) {
                showError(this, 'passwordError', 'Mật khẩu phải có ít nhất 6 ký tự');
            } else {
                clearError(this, 'passwordError');
            }
        });
    }
    
    if (password2Input) {
        password2Input.addEventListener('blur', function() {
            if (!this.value) {
                showError(this, 'password2Error', 'Vui lòng nhập lại mật khẩu');
            } else if (passwordInput.value !== this.value) {
                showError(this, 'password2Error', 'Mật khẩu nhập lại không khớp');
            } else {
                clearError(this, 'password2Error');
            }
        });
        
        // Real-time password match check
        password2Input.addEventListener('input', function() {
            if (this.value && passwordInput.value !== this.value) {
                showError(this, 'password2Error', 'Mật khẩu nhập lại không khớp');
            } else if (this.value) {
                clearError(this, 'password2Error');
            }
        });
    }
}

function isValidEmail(email) {
    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    return emailRegex.test(email);
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

function initRegisterForm() {
    const form = document.getElementById('registerForm');
    const registerBtn = document.getElementById('registerBtn');
    
    if (form && registerBtn) {
        form.addEventListener('submit', function() {
            registerBtn.classList.add('loading');
            registerBtn.disabled = true;
            registerBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> ĐANG XỬ LÝ...';
            
            setTimeout(() => {
                registerBtn.classList.remove('loading');
                registerBtn.disabled = false;
                registerBtn.innerHTML = '<i class="fas fa-user-plus"></i> ĐĂNG KÝ';
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

// Auto-focus vào username khi trang load
window.addEventListener('load', function() {
    const usernameInput = document.getElementById('username');
    if (usernameInput && !usernameInput.value) {
        usernameInput.focus();
    }
});