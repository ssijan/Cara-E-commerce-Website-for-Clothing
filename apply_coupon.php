<?php
session_start();
include('config.php');
header('Content-Type: application/json');

$discount_percent = 0;
$coupon_error = '';

if (isset($_POST['coupon'])) {
    $coupon = strtoupper(trim($_POST['coupon']));
    $stmt = $conn->prepare("SELECT discount_percent, valid FROM coupons WHERE coupon_code = ?");
    $stmt->bind_param("s", $coupon);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($row = $result->fetch_assoc()) {
        if ($row['valid'] === 'yes') {
            $_SESSION['coupon'] = $coupon;
            $_SESSION['discount_percent'] = $row['discount_percent'];
            $discount_percent = $row['discount_percent'];
            $coupon_error = '';
        } else {
            $_SESSION['coupon'] = $coupon;
            $_SESSION['discount_percent'] = 0;
            $discount_percent = 0;
            $coupon_error = 'Coupon is not valid.';
        }
    } else {
        $_SESSION['coupon'] = $coupon;
        $_SESSION['discount_percent'] = 0;
        $discount_percent = 0;
        $coupon_error = 'Invalid coupon code.';
    }
    $stmt->close();
}

echo json_encode([
    'discount_percent' => $discount_percent,
    'error' => $coupon_error
]);
