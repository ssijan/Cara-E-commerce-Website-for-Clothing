<?php
include('config.php'); 


$query = "SELECT id, name, description, price, stock, image_url FROM products";
$result = mysqli_query($conn, $query);

if ($result) {
    $products = [];
    
   
    while ($row = mysqli_fetch_assoc($result)) {
        $products[] = $row;
    }

    echo json_encode(['success' => true, 'products' => $products]);
} else {
    
    echo json_encode(['success' => false, 'error' => 'Error fetching products']);
}

mysqli_close($conn);
?>
