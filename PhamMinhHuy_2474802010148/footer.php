<!-- Footer -->
<footer class="footer-main">
    <div class="container">
        <div class="footer-content">
            <!-- About Column -->
            <div class="footer-column footer-about">
                <h3><i class="fas fa-store"></i> VỀ CHÚNG TÔI</h3>
                <p><i class="fas fa-quote-left"></i> InT3rNet - Thương hiệu thời trang trẻ trung với những sản phẩm chất lượng và phong cách độc đáo. <i class="fas fa-quote-right"></i></p>
                
                <div class="social-section">
                    <div class="social-title"><i class="fas fa-share-alt"></i> Theo dõi chúng tôi:</div>
                    <div class="social-icons">
                        <a href="#" class="social-icon facebook" aria-label="Facebook">
                            <i class="fab fa-facebook-f"></i>
                        </a>
                        <a href="#" class="social-icon instagram" aria-label="Instagram">
                            <i class="fab fa-instagram"></i>
                        </a>
                        <a href="#" class="social-icon twitter" aria-label="Twitter">
                            <i class="fab fa-twitter"></i>
                        </a>
                        <a href="#" class="social-icon youtube" aria-label="YouTube">
                            <i class="fab fa-youtube"></i>
                        </a>
                        <a href="#" class="social-icon tiktok" aria-label="TikTok">
                            <i class="fab fa-tiktok"></i>
                        </a>
                    </div>
                </div>
            </div>

            <!-- Information Column -->
            <div class="footer-column">
                <h3><i class="fas fa-info-circle"></i> THÔNG TIN</h3>
                <ul class="footer-links">
                    <li><a href="#"><i class="fas fa-chevron-right"></i> Về chúng tôi</a></li>
                    <li><a href="#"><i class="fas fa-chevron-right"></i> Chính sách bảo mật</a></li>
                    <li><a href="#"><i class="fas fa-chevron-right"></i> Điều khoản sử dụng</a></li>
                    <li><a href="#"><i class="fas fa-chevron-right"></i> Hướng dẫn mua hàng</a></li>
                    <li><a href="#"><i class="fas fa-chevron-right"></i> Chính sách đổi trả</a></li>
                </ul>
            </div>

            <!-- Support Column -->
            <div class="footer-column">
                <h3><i class="fas fa-headset"></i> HỖ TRỢ</h3>
                <ul class="footer-links">
                    <li><a href="#"><i class="fas fa-chevron-right"></i> Trung tâm hỗ trợ</a></li>
                    <li><a href="#"><i class="fas fa-chevron-right"></i> Hướng dẫn đặt hàng</a></li>
                    <li><a href="#"><i class="fas fa-chevron-right"></i> Phương thức vận chuyển</a></li>
                    <li><a href="#"><i class="fas fa-chevron-right"></i> Chính sách bảo hành</a></li>
                    <li><a href="#"><i class="fas fa-chevron-right"></i> Câu hỏi thường gặp</a></li>
                </ul>
            </div>

            <!-- Contact Column -->
            <div class="footer-column">
                <h3><i class="fas fa-map-marker-alt"></i> LIÊN HỆ</h3>
                <ul class="contact-info">
                    <li>
                        <i class="fas fa-map-marker-alt"></i>
                        <span>69/68 Đ. Đặng Thuỳ Trâm, Phường 13, Bình Thạnh, TP.HCM</span>
                    </li>
                    <li>
                        <i class="fas fa-phone-alt"></i>
                        <span>0971542606</span>
                    </li>
                    <li>
                        <i class="fas fa-envelope"></i>
                        <span>hao.2474802010108@vanlanguni.vn</span>
                    </li>
                </ul>
            </div>
        </div>

        <!-- Footer Bottom -->
        <div class="footer-bottom">
            <div class="copyright">
                <i class="far fa-copyright"></i>
                <p>&copy;2025 INT3Rnet - Tất cả các quyền được bảo lưu</p>
            </div>
            
            <!-- Payment Methods -->
            <div class="payment-methods">
                <i class="fab fa-cc-visa" title="Visa"></i>
                <i class="fab fa-cc-mastercard" title="Mastercard"></i>
                <i class="fab fa-cc-paypal" title="PayPal"></i>
                <i class="fab fa-cc-apple-pay" title="Apple Pay"></i>
            </div>
            
            <!-- Back to Top Button -->
            <a href="#" class="back-to-top" aria-label="Lên đầu trang">
                <i class="fas fa-chevron-up"></i>
            </a>
        </div>
    </div>
</footer>

<!-- Link đến CSS riêng cho footer -->
<link rel="stylesheet" href="css/footer.css">

<!-- JavaScript cho Back to Top -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Back to Top functionality
    const backToTop = document.querySelector('.back-to-top');
    
    if (backToTop) {
        window.addEventListener('scroll', function() {
            if (window.pageYOffset > 300) {
                backToTop.style.display = 'flex';
            } else {
                backToTop.style.display = 'none';
            }
        });
        
        backToTop.addEventListener('click', function(e) {
            e.preventDefault();
            window.scrollTo({
                top: 0,
                behavior: 'smooth'
            });
        });
    }
});
</script>