<?php
include('config.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id'])) {
    $id = $_POST['id'];
    $stmt = $conn->prepare("DELETE FROM products WHERE id=?");
    $stmt->bind_param("i", $id);
    if ($stmt->execute()) {
        echo json_encode(['success' => true, 'message' => '✅ Product deleted successfully!']);
    } else {
        echo json_encode(['success' => false, 'error' => $stmt->error, 'message' => '❌ Product delete failed!']);
    }
    $stmt->close();
    exit;
}

echo json_encode(['success' => false, 'error' => 'Invalid request']);
?>
