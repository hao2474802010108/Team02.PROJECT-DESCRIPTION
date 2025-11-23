<?php
require_once 'db_config.php';
require_once 'functions.php';

if (!isLoggedIn()) {
    $_SESSION['login_redirect'] = "my_orders.php";
    redirect('login.php');
}

$user_id = currentUserId();

// Lấy danh sách đơn hàng
$stmt = $conn->prepare("SELECT id, total_amount, status, created_at 
                        FROM orders 
                        WHERE user_id = ?
                        ORDER BY created_at DESC");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$resultOrders = $stmt->get_result();
$stmt->close();

include 'header.php';
?>

<h2>Đơn hàng của tôi</h2>

<?php if ($resultOrders->num_rows === 0): ?>
    <p>Bạn chưa có đơn hàng nào.</p>
<?php else: ?>
    <?php while ($order = $resultOrders->fetch_assoc()): ?>
        <div style="border:1px solid #ddd; padding:10px; margin-bottom:10px;">
            <p><strong>Mã đơn hàng:</strong> #<?php echo $order['id']; ?></p>
            <p><strong>Ngày tạo:</strong> <?php echo esc($order['created_at']); ?></p>
            <p><strong>Trạng thái:</strong> <?php echo esc($order['status']); ?></p>
            <p><strong>Tổng tiền:</strong> <?php echo number_format($order['total_amount']); ?> VND</p>

            <?php
            // Lấy chi tiết order
            $oid = $order['id'];
            $stmtItems = $conn->prepare("SELECT oi.quantity, oi.unit_price, oi.size, oi.color, p.name 
                                         FROM order_items oi
                                         JOIN products p ON oi.product_id = p.id
                                         WHERE oi.order_id = ?");
            $stmtItems->bind_param("i", $oid);
            $stmtItems->execute();
            $resItems = $stmtItems->get_result();
            ?>
            <table>
                <tr>
                    <th>Sản phẩm</th>
                    <th>Size</th>
                    <th>Màu</th>
                    <th>Số lượng</th>
                    <th>Giá</th>
                    <th>Thành tiền</th>
                </tr>
                <?php while ($it = $resItems->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo esc($it['name']); ?></td>
                        <td><?php echo esc($it['size']); ?></td>
                        <td><?php echo esc($it['color']); ?></td>
                        <td><?php echo (int)$it['quantity']; ?></td>
                        <td><?php echo number_format($it['unit_price']); ?> VND</td>
                        <td><?php echo number_format($it['unit_price'] * $it['quantity']); ?> VND</td>
                    </tr>
                <?php endwhile; ?>
            </table>
            <?php $stmtItems->close(); ?>
        </div>
    <?php endwhile; ?>
<?php endif; ?>

<?php
include 'footer.php';
