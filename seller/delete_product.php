<?php
// Delete product
$id = $_GET['id'];

// First get image path to delete it
$stmt = $conn->prepare("SELECT image_url FROM crud_041_book WHERE id=? AND seller_id=?");
$stmt->bind_param("ii", $id, $seller_id);
$stmt->execute();
$result = $stmt->get_result();
$product = $result->fetch_assoc();
$stmt->close();

// Delete image file if exists
if (!empty($product['image_url'])) {
    $imagePath = '../images/' . $product['image_url'];
    if (file_exists($imagePath)) {
        unlink($imagePath);
    }
}

// Delete product from database
$stmt = $conn->prepare("DELETE FROM crud_041_book WHERE id=? AND seller_id=?");
$stmt->bind_param("ii", $id, $seller_id);

if ($stmt->execute()) {
    $_SESSION['success'] = "Produk berhasil dihapus";
} else {
    $_SESSION['error'] = "Gagal menghapus produk: " . $stmt->error;
}

$stmt->close();
header("Location: ?page=products");
exit();
