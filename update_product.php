<?php
include('config.php');

// Update product
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id'])) {
    $id = $_POST['id'];
    $name = $_POST['name'] ?? '';
    $description = $_POST['description'] ?? '';
    $price = $_POST['price'] ?? '';
    $stock = $_POST['stock'] ?? '';
    $imageUrl = '';

    // If image is uploaded, update image
    if (isset($_FILES['image']) && $_FILES['image']['error'] === 0) {
        $imagePath = $_FILES['image']['tmp_name'];
        $apiKey = 'f8640845ff732d11d90ca0eafe13bd22';
        $url = 'https://api.imgbb.com/1/upload?key=' . $apiKey;
        $data = array('image' => base64_encode(file_get_contents($imagePath)));
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $response = curl_exec($ch);
        curl_close($ch);
        $responseData = json_decode($response, true);
        if (isset($responseData['data']['url'])) {
            $imageUrl = $responseData['data']['url'];
        }
    }

    // Build SQL
    // Ensure price and stock are numbers
    $price = is_numeric($price) ? floatval($price) : 0;
    $stock = is_numeric($stock) ? intval($stock) : 0;

    if ($imageUrl) {
        $sql = "UPDATE products SET name=?, description=?, price=?, stock=?, image_url=? WHERE id=?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssdsisi", $name, $description, $price, $stock, $imageUrl, $id);
    } else {
        $sql = "UPDATE products SET name=?, description=?, price=?, stock=? WHERE id=?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssdsi", $name, $description, $price, $stock, $id);
    }
    if ($stmt->execute()) {
        echo json_encode(['success' => true, 'message' => '✅ Product updated successfully!']);
    } else {
        echo json_encode(['success' => false, 'error' => $stmt->error, 'message' => '❌ Product update failed!']);
    }
    $stmt->close();
    exit;
}

echo json_encode(['success' => false, 'error' => 'Invalid request']);
?>
