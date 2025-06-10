<?php
// Reviews section
$sql_reviews = "
                SELECT r.*, p.title 
                FROM crud_041_book_reviews r 
                JOIN crud_041_book p ON r.book_id = p.id 
                WHERE p.seller_id = ?
            ";
$stmt_reviews = $conn->prepare($sql_reviews);
$stmt_reviews->bind_param("i", $seller_id);
$stmt_reviews->execute();
$reviews = $stmt_reviews->get_result();
?>
<div class="header">
    <h2>Review Produk Saya</h2>
</div>
<?php if ($reviews->num_rows > 0): ?>
    <?php while ($review = $reviews->fetch_assoc()): ?>
        <div class="product-card">
            <strong>Produk: <?= htmlspecialchars($review['title']) ?></strong><br>
            <small>oleh: <?= htmlspecialchars($review['reviewer_name'] ?? 'Anonim') ?></small><br>
            <p><?= htmlspecialchars($review['comment']) ?></p>
            <p>Rating: <?= $review['rating'] ?>/5</p>
        </div>
    <?php endwhile; ?>
<?php else: ?>
    <p>Belum ada review untuk produk Anda.</p>
<?php endif; ?>