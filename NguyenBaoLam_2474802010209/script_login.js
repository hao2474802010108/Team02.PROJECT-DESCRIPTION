document.getElementById('loginForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const username = document.getElementById('username').value;
    const password = document.getElementById('password').value;
    
    // Kiểm tra thông tin đăng nhập
    if (username === 'admin' && password === 'admin') {
        // Đăng nhập với quyền admin
        localStorage.setItem('currentUser', JSON.stringify({
            username: username,
            role: 'admin'
        }));
        window.location.href = 'index.html';
    } else if ((username === 'user' && password === 'user') || username !== 'admin') {
        // Đăng nhập với quyền user
        localStorage.setItem('currentUser', JSON.stringify({
            username: username,
            role: 'user'
        }));
        window.location.href = 'index_user.html';
    } else {
        alert('Tên đăng nhập hoặc mật khẩu không đúng!');
    }
});
document.getElementById('loginForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const username = document.getElementById('username').value;
    const password = document.getElementById('password').value;
    
    // Lấy danh sách users từ localStorage
    const users = JSON.parse(localStorage.getItem('users') || '[]');
    console.log('Users from localStorage:', users);
    
    // Kiểm tra thông tin đăng nhập
    if (username === 'admin' && password === 'admin') {
        // Đăng nhập với quyền admin
        localStorage.setItem('currentUser', JSON.stringify({
            username: username,
            role: 'admin'
        }));
        window.location.href = 'index.html';
    } else {
        // Kiểm tra trong danh sách users đã đăng ký
        const user = users.find(u => u.username === username && u.password === password);
        if (user) {
            // Đăng nhập với quyền user
            localStorage.setItem('currentUser', JSON.stringify({
                username: username,
                role: 'user'
            }));
            window.location.href = 'index_user.html';
        } else {
            alert('Tên đăng nhập hoặc mật khẩu không đúng!');
        }
    }
});