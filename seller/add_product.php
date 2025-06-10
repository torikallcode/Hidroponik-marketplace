<?php
session_start();
include '../database/db.php';

if (isset($_POST['add_product'])) {
    // Validate required fields
    $required = [
        'title' => 'Nama Produk',
        'price' => 'Harga',
        'genre' => 'Kategori',
        'description' => 'Deskripsi',
        'stock' => 'Stok'
    ];

    $missing = [];
    foreach ($required as $field => $label) {
        if (empty($_POST[$field])) {
            $missing[] = $label;
        }
    }

    if (!empty($missing)) {
        $_SESSION['error'] = "Field yang wajib diisi: " . implode(', ', $missing);
        header("Location: ?page=products");
        exit();
    }

    // Add new product
    $title = $_POST['title'];
    $price = $_POST['price'];
    $genre = $_POST['genre'];
    $description = $_POST['description'];
    $stock = $_POST['stock'];

    // Handle image upload
    $image = '';
    if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
        $uploadDir = '../images/';
        $imageName = uniqid() . '_' . basename($_FILES['image']['name']);
        $targetPath = $uploadDir . $imageName;

        if (move_uploaded_file($_FILES['image']['tmp_name'], $targetPath)) {
            $image = $imageName;
        }
    } else {
        $_SESSION['error'] = "Gambar produk wajib diupload";
        header("Location: ?page=products");
        exit();
    }

    $stmt = $conn->prepare("INSERT INTO crud_041_book (title, price, genre, description, stock, image_url, seller_id) VALUES (?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("sdssisi", $title, $price, $genre, $description, $stock, $image, $seller_id);

    if ($stmt->execute()) {
        $_SESSION['success'] = "Produk berhasil ditambahkan";
    } else {
        $_SESSION['error'] = "Gagal menambahkan produk: " . $stmt->error;
    }

    $stmt->close();
    header("Location: ?page=products");
    exit();
}
