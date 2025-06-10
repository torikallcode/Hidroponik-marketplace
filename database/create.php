<?php
include 'db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['name'];
    $book_id = $_POST['book_title'];
    $review = $_POST['review'];
    $rating = $_POST['rating'];
    $date = $_POST['date'];

    if (!empty($name) && !empty($book_id) && !empty($review) && !empty($rating) && !empty($date)) {
        $sql = "INSERT INTO crud_041_book_reviews (name, book_id, review, rating, date) 
                VALUES ('$name', '$book_id', '$review', '$rating', '$date')";

        if ($conn->query($sql) === TRUE) {
            header("Location: ../thanks.php");  
            exit(); 
        } else {
            echo "Error: " . $conn->error;
        }
    } else {
        echo "Semua field harus diisi!";
    }
}

$conn->close();
?>