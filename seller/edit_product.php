<?php
// Bagian untuk handle update product - perbaikan
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['update_product'])) {
        // Debug: tampilkan data yang diterima
        error_log("Update product data: " . print_r($_POST, true));

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

        // Update existing product
        $id = intval($_POST['id']); // Pastikan ID adalah integer
        $title = trim($_POST['title']);
        $price = floatval($_POST['price']);
        $genre = trim($_POST['genre']);
        $description = trim($_POST['description']);
        $stock = intval($_POST['stock']);

        // Handle image update
        $image = $_POST['current_image'];
        if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
            $uploadDir = '../images/';

            // Validasi file
            $allowedTypes = ['image/jpeg', 'image/png', 'image/gif', 'image/jpg'];
            $fileType = $_FILES['image']['type'];

            if (in_array($fileType, $allowedTypes)) {
                $imageName = uniqid() . '_' . basename($_FILES['image']['name']);
                $targetPath = $uploadDir . $imageName;

                if (move_uploaded_file($_FILES['image']['tmp_name'], $targetPath)) {
                    // Delete old image if exists
                    if (!empty($image) && file_exists($uploadDir . $image)) {
                        unlink($uploadDir . $image);
                    }
                    $image = $imageName;
                } else {
                    $_SESSION['error'] = "Gagal mengupload gambar baru";
                    header("Location: ?page=products");
                    exit();
                }
            } else {
                $_SESSION['error'] = "Format gambar tidak didukung. Gunakan JPG, PNG, atau GIF";
                header("Location: ?page=products");
                exit();
            }
        }

        // Debug: tampilkan query yang akan dijalankan
        error_log("Updating product ID: $id for seller ID: $seller_id");

        $stmt = $conn->prepare("UPDATE crud_041_book SET title=?, price=?, genre=?, description=?, stock=?, image_url=? WHERE id=? AND seller_id=?");

        if (!$stmt) {
            $_SESSION['error'] = "Gagal menyiapkan query: " . $conn->error;
            header("Location: ?page=products");
            exit();
        }

        $stmt->bind_param("sdssisii", $title, $price, $genre, $description, $stock, $image, $id, $seller_id);

        if ($stmt->execute()) {
            if ($stmt->affected_rows > 0) {
                $_SESSION['success'] = "Produk berhasil diperbarui";
            } else {
                $_SESSION['error'] = "Tidak ada perubahan yang disimpan atau produk tidak ditemukan";
            }
        } else {
            $_SESSION['error'] = "Gagal memperbarui produk: " . $stmt->error;
        }

        $stmt->close();
        header("Location: ?page=products");
        exit();
    }
}
?>

<!-- Edit Modal yang diperbaiki -->
<div class='modal fade' id='editProductModal<?= $product['id'] ?>' tabindex='-1' aria-labelledby='editProductModalLabel<?= $product['id'] ?>' aria-hidden='true'>
    <div class='modal-dialog'>
        <div class='modal-content'>
            <div class='modal-header'>
                <h5 class='modal-title' id='editProductModalLabel<?= $product['id'] ?>'>Edit Produk</h5>
                <button type='button' class='btn-close' data-bs-dismiss='modal' aria-label='Close'></button>
            </div>
            <form action='?page=products' method='post' enctype='multipart/form-data' id='editProductForm<?= $product['id'] ?>'>
                <div class='modal-body'>
                    <input type='hidden' name='id' value='<?= $product['id'] ?>'>
                    <input type='hidden' name='current_image' value='<?= htmlspecialchars($product['image_url']) ?>'>

                    <div class='form-group mb-3'>
                        <label for='editTitle<?= $product['id'] ?>' class='form-label'>Nama Produk *</label>
                        <input type='text' class='form-control' id='editTitle<?= $product['id'] ?>' name='title'
                            value='<?= htmlspecialchars($product['title']) ?>' required>
                    </div>

                    <div class='form-group mb-3'>
                        <label for='editPrice<?= $product['id'] ?>' class='form-label'>Harga *</label>
                        <input type='number' step='0.01' min='0' class='form-control' id='editPrice<?= $product['id'] ?>'
                            name='price' value='<?= $product['price'] ?>' required>
                    </div>

                    <div class='form-group mb-3'>
                        <label for='editGenre<?= $product['id'] ?>' class='form-label'>Kategori *</label>
                        <select class='form-control' id='editGenre<?= $product['id'] ?>' name='genre' required>
                            <?php
                            $categories = ['Sayuran', 'Buah', 'Bunga', 'Alat', 'Bibit'];
                            foreach ($categories as $cat) {
                                $selected = ($cat == $product['genre']) ? 'selected' : '';
                                echo "<option value='$cat' $selected>$cat</option>";
                            }
                            ?>
                        </select>
                    </div>

                    <div class='form-group mb-3'>
                        <label for='editDescription<?= $product['id'] ?>' class='form-label'>Deskripsi *</label>
                        <textarea class='form-control' id='editDescription<?= $product['id'] ?>' name='description'
                            rows='3' required><?= htmlspecialchars($product['description']) ?></textarea>
                    </div>

                    <div class='form-group mb-3'>
                        <label for='editStock<?= $product['id'] ?>' class='form-label'>Stok *</label>
                        <input type='number' min='0' class='form-control' id='editStock<?= $product['id'] ?>'
                            name='stock' value='<?= $product['stock'] ?>' required>
                    </div>

                    <div class='form-group mb-3'>
                        <label for='editImage<?= $product['id'] ?>' class='form-label'>Gambar Produk</label>
                        <input type='file' class='form-control' id='editImage<?= $product['id'] ?>'
                            name='image' accept='image/*'>
                        <small class='form-text text-muted'>Kosongkan jika tidak ingin mengubah gambar</small>
                        <?php if (!empty($product['image_url'])): ?>
                            <img src='../images/<?= $product['image_url'] ?>' class='preview-image mt-2'
                                id='editPreview<?= $product['id'] ?>' style='max-width: 200px; max-height: 200px;'>
                        <?php else: ?>
                            <img src='../images/default-product.jpg' class='preview-image mt-2'
                                id='editPreview<?= $product['id'] ?>' style='max-width: 200px; max-height: 200px;'>
                        <?php endif; ?>
                    </div>
                </div>
                <div class='modal-footer'>
                    <button type='button' class='btn btn-secondary' data-bs-dismiss='modal'>Batal</button>
                    <button type='submit' class='btn btn-primary' name='update_product'>Simpan Perubahan</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    // Form validation untuk edit
    document.getElementById('editProductForm<?= $product['id'] ?>').addEventListener('submit', function(e) {
        const form = this;
        const genre = form.querySelector('select[name="genre"]').value;
        const title = form.querySelector('input[name="title"]').value.trim();
        const price = form.querySelector('input[name="price"]').value;
        const description = form.querySelector('textarea[name="description"]').value.trim();
        const stock = form.querySelector('input[name="stock"]').value;

        // Validasi basic
        if (!title || !price || !genre || !description || !stock) {
            e.preventDefault();
            alert('Semua field yang bertanda * wajib diisi!');
            return false;
        }

        if (parseFloat(price) <= 0) {
            e.preventDefault();
            alert('Harga harus lebih dari 0!');
            return false;
        }

        if (parseInt(stock) < 0) {
            e.preventDefault();
            alert('Stok tidak boleh negatif!');
            return false;
        }
    });

    // Image preview untuk edit
    document.getElementById('editImage<?= $product['id'] ?>').addEventListener('change', function(e) {
        const preview = document.getElementById('editPreview<?= $product['id'] ?>');
        const file = e.target.files[0];
        if (file) {
            // Validasi ukuran file (max 5MB)
            if (file.size > 5 * 1024 * 1024) {
                alert('Ukuran file terlalu besar! Maksimal 5MB');
                this.value = '';
                return;
            }

            // Validasi tipe file
            const allowedTypes = ['image/jpeg', 'image/jpg', 'image/png', 'image/gif'];
            if (!allowedTypes.includes(file.type)) {
                alert('Format file tidak didukung! Gunakan JPG, PNG, atau GIF');
                this.value = '';
                return;
            }

            const reader = new FileReader();
            reader.onload = function(e) {
                preview.src = e.target.result;
            }
            reader.readAsDataURL(file);
        }
    });
</script>