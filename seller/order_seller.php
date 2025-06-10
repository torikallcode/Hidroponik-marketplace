<!DOCTYPE html>
<html lang="id">

<head>
  <meta charset="UTF-8">
  <title>Daftar Produk</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
  <style>
    body {
      font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
      background-color: #f5f6fa;
      margin: 0;
      padding: 20px;
    }

    .products-container {
      max-width: 900px;
      margin: 0 auto;
      display: grid;
      grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
      gap: 20px;
    }

    .product-card {
      background-color: #fff;
      border-radius: 12px;
      padding: 20px;
      box-shadow: 0 8px 15px rgba(0, 0, 0, 0.05);
      border: 1px solid #ddd;
      display: flex;
      flex-direction: column;
      justify-content: space-between;
    }

    .product-card strong {
      font-size: 18px;
      color: #2c3e50;
      margin-bottom: 8px;
    }

    .product-card p {
      font-size: 14px;
      color: #555;
      margin-bottom: 16px;
    }

    .btn {
      text-decoration: none;
      padding: 10px 14px;
      background-color: #25D366;
      color: white;
      border-radius: 6px;
      font-weight: 600;
      display: inline-flex;
      align-items: center;
      justify-content: center;
      transition: background-color 0.3s ease;
    }

    .btn i {
      margin-right: 8px;
    }

    .btn:hover {
      background-color: #1eb858;
    }

    .no-product {
      text-align: center;
      font-size: 16px;
      color: #777;
      margin-top: 40px;
    }
  </style>
</head>

<body>

  <h2 style="text-align:center; margin-bottom:30px; color:#333;">📚 Daftar Produk Saya</h2>

  <div class="products-container">
    <?php
    $products = $conn->prepare("SELECT * FROM crud_041_book WHERE seller_id = ?");
    $products->bind_param("i", $seller_id);
    $products->execute();
    $products = $products->get_result();

    if ($products->num_rows > 0):
      while ($product = $products->fetch_assoc()):
        $wa_number = "62813398739335"; // Ganti dengan nomor WA Anda
        $message = "Halo, saya tertarik memesan produk *" . $product['title'] . "* dari penjual " . $seller_name . ". Bisa tolong info lebih lanjut?";
        $wa_link = "https://wa.me/" . $wa_number . "?text=" . urlencode($message);
    ?>
        <div class="product-card">
          <strong><?= htmlspecialchars($product['title']) ?></strong>
          <p>Deskripsi: <?= htmlspecialchars($product['description']) ?></p>
          <a href="<?= $wa_link ?>" target="_blank" class="btn">
            <i class="fab fa-whatsapp"></i> Pesan via WhatsApp
          </a>
        </div>
      <?php endwhile;
    else: ?>
      <p class="no-product">Belum ada produk untuk dipesan.</p>
    <?php endif; ?>
  </div>

</body>

</html>