<?php
include('config.php'); 


if (isset($_FILES['image'])) {
    $image = $_FILES['image'];

 
    if ($image['error'] === 0) {
        
        $imagePath = $image['tmp_name'];

    
        $apiKey = 'f8640845ff732d11d90ca0eafe13bd22';  
        $url = 'https://api.imgbb.com/1/upload?key=' . $apiKey;

        // Prepare data for the API request
        $data = array(
            'image' => base64_encode(file_get_contents($imagePath))
        );

        // Use cURL to send the image to ImgBB
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $response = curl_exec($ch);

        
        if (curl_errno($ch)) {
            echo json_encode(['success' => false, 'error' => curl_error($ch)]);
            exit;
        }

        curl_close($ch);

       
        $responseData = json_decode($response, true);

   
        if (isset($responseData['data']['url'])) {
            $imageUrl = $responseData['data']['url'];

            
            $name = $_POST['name'] ?? '';
            $description = $_POST['description'] ?? '';
            $price = $_POST['price'] ?? '';
            $stock = $_POST['stock'] ?? '';

            
            $stmt = $conn->prepare("INSERT INTO products (name, description, price, stock, image_url) VALUES (?, ?, ?, ?, ?)");
            $stmt->bind_param("ssdsi", $name, $description, $price, $stock, $imageUrl);

            if ($stmt->execute()) {
                echo json_encode(['success' => true, 'image_url' => $imageUrl, 'message' => '✅ Product added successfully!']);
            } else {
                echo json_encode(['success' => false, 'error' => 'Database insert failed', 'db_error' => $stmt->error, 'message' => '❌ Product add failed!']);
            }
            $stmt->close();
        } else {
          
            echo json_encode(['success' => false, 'error' => 'Error uploading to ImgBB', 'response' => $responseData]);
        }
    } else {
        echo json_encode(['success' => false, 'error' => 'File upload error', 'error_code' => $image['error']]);
    }
} else {
    echo json_encode(['success' => false, 'error' => 'No image file uploaded']);
}
?>
