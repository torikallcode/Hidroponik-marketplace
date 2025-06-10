<?php
include 'db.php';

// Pastikan ID review diterima melalui URL
if (isset($_GET['id'])) {
    $id_review = $_GET['id'];

    $sql = "DELETE FROM crud_041_book_reviews WHERE id = ?";

    // Persiapkan statement
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $id_review);

    // Eksekusi query
    if ($stmt->execute()) {
        // Jika berhasil, arahkan pengguna kembali ke halaman utama atau halaman sebelumnya
        header("Location: ../Beranda.php");  // Ubah ke halaman yang sesuai
        exit();
    } else {
        echo "Error: " . $conn->error;
    }

    // Menutup statement
    $stmt->close();
} else {
    echo "ID review tidak ditemukan.";
}

// Menutup koneksi database
$conn->close();
?>