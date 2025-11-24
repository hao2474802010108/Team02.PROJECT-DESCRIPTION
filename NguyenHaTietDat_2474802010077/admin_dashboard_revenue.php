<?php
include 'db_config.php';
$q_total = "
    SELECT SUM(total_amount) AS total
    FROM orders
    WHERE status = 'delivered'
";
$total = mysqli_fetch_assoc(mysqli_query($conn, $q_total))['total'] ?? 0;
$q_daily = "
    SELECT DATE(created_at) AS day, SUM(total_amount) AS revenue
    FROM orders
    WHERE status = 'delivered'
    GROUP BY DATE(created_at)
    ORDER BY DATE(created_at) DESC
";
$daily = mysqli_query($conn, $q_daily);
$q_month = "
    SELECT DATE_FORMAT(created_at, '%Y-%m') AS month, SUM(total_amount) AS revenue
    FROM orders
    WHERE status = 'delivered'
    GROUP BY DATE_FORMAT(created_at, '%Y-%m')
    ORDER BY month DESC
";
$month = mysqli_query($conn, $q_month);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Revenue Dashboard</title>
    <style>
        body { font-family: Arial; padding: 20px; background: #f1f1f1; }
        .card { background: white; padding: 20px; border-radius: 10px; margin-bottom: 20px; box-shadow: 0 0 8px rgba(0,0,0,0.1); }
        table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        table, th, td { border: 1px solid #ccc; }
        th, td { padding: 10px; }
        h2 { margin-bottom: 10px; }
    </style>
</head>
<body>
    <h1>Revenue Dashboard</h1>

    <div class="card">
        <h2>Total Revenue</h2>
        <h1 style="color: green;">
            <?= number_format($total) ?> VND
        </h1>
    </div>
    <div class="card">
        <h2>Revenue by Day</h2>
        <table>
            <tr>
                <th>Date</th>
                <th>Revenue</th>
            </tr>
            <?php while($r = mysqli_fetch_assoc($daily)): ?>
            <tr>
                <td><?= $r['day'] ?></td>
                <td><?= number_format($r['revenue']) ?> VND</td>
            </tr>
            <?php endwhile; ?>
        </table>
    </div>
    <div class="card">
        <h2>Revenue by Month</h2>
        <table>
            <tr>
                <th>Month</th>
                <th>Revenue</th>
            </tr>
            <?php while($r = mysqli_fetch_assoc($month)): ?>
            <tr>
                <td><?= $r['month'] ?></td>
                <td><?= number_format($r['revenue']) ?> VND</td>
            </tr>
            <?php endwhile; ?>
        </table>
    </div>
</body>
</html>
