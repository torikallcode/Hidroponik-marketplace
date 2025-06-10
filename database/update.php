<?php
include 'db.php';

// Pastikan id dikirim melalui POST untuk mengidentifikasi review yang akan diupdate
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['id'])) {
    $id = $_POST['id']; // ID review yang akan diperbarui
    $name = $_POST['name'];
    $book_id = $_POST['book_title'];
    $review = $_POST['review'];
    $rating = $_POST['rating'];
    $date = $_POST['date'];

    // Validasi input
    if (!empty($name) && !empty($book_id) && !empty($review) && !empty($rating) && !empty($date)) {
        // SQL query untuk mengupdate review
        $sql = "UPDATE crud_041_book_reviews 
                SET name = ?, book_id = ?, review = ?, rating = ?, date = ? 
                WHERE id = ?";

        // Persiapkan statement SQL
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssssss", $name, $book_id, $review, $rating, $date, $id);

        // Eksekusi statement
        if ($stmt->execute()) {
            header("Location: ../thanks.php");  // Arahkan ke halaman terima kasih setelah berhasil update
            exit();
        } else {
            echo "Error: " . $conn->error;  // Tampilkan error jika gagal
        }
    } else {
        echo "Semua field harus diisi!";  // Jika ada field yang kosong
    }
}

// Menutup koneksi database
$conn->close();
?>
