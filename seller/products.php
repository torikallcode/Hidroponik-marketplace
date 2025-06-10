<?php
// Products CRUD section
echo "<div class='header'><h2>Produk Saya (Total: $total_products)</h2></div>";

// Add Product Button
echo "<button type='button' class='btn-add' data-bs-toggle='modal' data-bs-target='#addProductModal'>
                    <i class='fas fa-plus'></i> Tambah Produk
                  </button>";

if ($total_products > 0) {
    echo "<div class='product-grid'>";
    while ($product = $products->fetch_assoc()) {
        echo "<div class='product-card'>";
        echo "<div class='product-image'>";
        if (!empty($product['image_url'])) {
            echo "<img src='../images/{$product['image_url']}' alt='{$product['title']}'>";
        } else {
            echo "<img src='../images/default-product.jpg' alt='Default product image'>";
        }
        echo "<span class='product-badge'>{$product['genre']}</span>";
        echo "</div>";

        echo "<div class='product-content'>";
        echo "<h3 class='product-title'>{$product['title']}</h3>";
        echo "<p class='product-description'>{$product['description']}</p>";

        echo "<div class='product-meta'>";
        echo "<span class='product-price'>Rp " . number_format($product['price'], 0, ',', '.') . "</span>";
        echo "<span class='product-stock'>Stok: {$product['stock']}</span>";
        echo "</div>";

        echo "<div class='product-actions'>";
        echo "<button type='button' class='btn-edit' data-bs-toggle='modal' data-bs-target='#editProductModal{$product['id']}'>
                            <i class='fas fa-edit'></i> Edit
                          </button>";
        echo "<a href='?page=products&action=delete&id={$product['id']}' class='btn-delete' onclick='return confirm(\"Apakah Anda yakin ingin menghapus produk ini?\")'>
                            <i class='fas fa-trash'></i> Hapus
                          </a>";
        echo "</div>";
        echo "</div>";
        echo "</div>";

        // Edit Modal for each product
        echo "<div class='modal fade' id='editProductModal{$product['id']}' tabindex='-1' aria-labelledby='editProductModalLabel{$product['id']}' aria-hidden='true'>";
        echo "<div class='modal-dialog'>";
        echo "<div class='modal-content'>";
        echo "<div class='modal-header'>";
        echo "<h5 class='modal-title' id='editProductModalLabel{$product['id']}'>Edit Produk</h5>";
        echo "<button type='button' class='btn-close' data-bs-dismiss='modal' aria-label='Close'></button>";
        echo "</div>";
        echo "<form action='?page=products' method='post' enctype='multipart/form-data'>";
        echo "<div class='modal-body'>";
        echo "<input type='hidden' name='id' value='{$product['id']}'>";
        echo "<input type='hidden' name='current_image' value='{$product['image_url']}'>";

        echo "<div class='form-group'>";
        echo "<label for='editTitle{$product['id']}' class='form-label'>Nama Produk</label>";
        echo "<input type='text' class='form-control' id='editTitle{$product['id']}' name='title' value='{$product['title']}' required>";
        echo "</div>";

        echo "<div class='form-group'>";
        echo "<label for='editPrice{$product['id']}' class='form-label'>Harga</label>";
        echo "<input type='number' class='form-control' id='editPrice{$product['id']}' name='price' value='{$product['price']}' required>";
        echo "</div>";

        echo "<div class='form-group'>";
        echo "<label for='editgenre{$product['id']}' class='form-label'>Kategori</label>";
        echo "<select class='form-control' id='editgenre{$product['id']}' name='genre' required>";
        $categories = ['Sayuran', 'Buah', 'Bunga', 'Alat', 'Bibit'];
        foreach ($categories as $cat) {
            $selected = $cat == $product['genre'] ? 'selected' : '';
            echo "<option value='$cat' $selected>$cat</option>";
        }
        echo "</select>";
        echo "</div>";

        echo "<div class='form-group'>";
        echo "<label for='editDescription{$product['id']}' class='form-label'>Deskripsi</label>";
        echo "<textarea class='form-control' id='editDescription{$product['id']}' name='description' rows='3' required>{$product['description']}</textarea>";
        echo "</div>";

        echo "<div class='form-group'>";
        echo "<label for='editStock{$product['id']}' class='form-label'>Stok</label>";
        echo "<input type='number' class='form-control' id='editStock{$product['id']}' name='stock' value='{$product['stock']}' required>";
        echo "</div>";

        echo "<div class='form-group'>";
        echo "<label for='editImage{$product['id']}' class='form-label'>Gambar Produk</label>";
        echo "<input type='file' class='form-control' id='editImage{$product['id']}' name='image' accept='image/*'>";
        if (!empty($product['image_url'])) {
            echo "<img src='../images/{$product['image_url']}' class='preview-image' id='editPreview{$product['id']}'>";
        } else {
            echo "<img src='../images/default-product.jpg' class='preview-image' id='editPreview{$product['id']}'>";
        }
        echo "</div>";

        echo "</div>";
        echo "<div class='modal-footer'>";
        echo "<button type='button' class='btn btn-secondary' data-bs-dismiss='modal'>Batal</button>";
        echo "<button type='submit' class='btn btn-primary' name='update_product'>Simpan Perubahan</button>";
        echo "</div>";
        echo "</form>";
        echo "</div>";
        echo "</div>";
        echo "</div>";

        // JavaScript for image preview
        echo "<script>
                            document.getElementById('editImage{$product['id']}').addEventListener('change', function(e) {
                                const preview = document.getElementById('editPreview{$product['id']}');
                                const file = e.target.files[0];
                                if (file) {
                                    const reader = new FileReader();
                                    reader.onload = function(e) {
                                        preview.src = e.target.result;
                                    }
                                    reader.readAsDataURL(file);
                                }
                            });
                          </script>";
    }
    echo "</div>";
} else {
    echo "<div class='alert alert-info'>Belum ada produk yang Anda jual.</div>";
}

// Add Product Modal
echo "<div class='modal fade' id='addProductModal' tabindex='-1' aria-labelledby='addProductModalLabel' aria-hidden='true'>";
echo "<div class='modal-dialog'>";
echo "<div class='modal-content'>";
echo "<div class='modal-header'>";
echo "<h5 class='modal-title' id='addProductModalLabel'>Tambah Produk Baru</h5>";
echo "<button type='button' class='btn-close' data-bs-dismiss='modal' aria-label='Close'></button>";
echo "</div>";
echo "<form action='?page=products' method='post' enctype='multipart/form-data' id='addProductForm'>";
echo "<div class='modal-body'>";

echo "<div class='form-group'>";
echo "<label for='addTitle' class='form-label'>Nama Produk</label>";
echo "<input type='text' class='form-control' id='addTitle' name='title' required>";
echo "</div>";

echo "<div class='form-group'>";
echo "<label for='addPrice' class='form-label'>Harga</label>";
echo "<input type='number' class='form-control' id='addPrice' name='price' required>";
echo "</div>";

echo "<div class='form-group'>";
echo "<label for='addgenre' class='form-label'>Kategori</label>";
echo "<select class='form-control' id='addgenre' name='genre' required>";
echo "<option value=''>-- Pilih Kategori --</option>";
$categories = ['Sayuran', 'Buah', 'Bunga', 'Alat', 'Bibit'];
foreach ($categories as $cat) {
    echo "<option value='$cat'>$cat</option>";
}
echo "</select>";
echo "</div>";

echo "<div class='form-group'>";
echo "<label for='addDescription' class='form-label'>Deskripsi</label>";
echo "<textarea class='form-control' id='addDescription' name='description' rows='3' required></textarea>";
echo "</div>";

echo "<div class='form-group'>";
echo "<label for='addStock' class='form-label'>Stok</label>";
echo "<input type='number' class='form-control' id='addStock' name='stock' required>";
echo "</div>";

echo "<div class='form-group'>";
echo "<label for='addImage' class='form-label'>Gambar Produk</label>";
echo "<input type='file' class='form-control' id='addImage' name='image' accept='image/*' required>";
echo "<img src='../images/default-product.jpg' class='preview-image' id='addPreview'>";
echo "</div>";

echo "</div>";
echo "<div class='modal-footer'>";
echo "<button type='button' class='btn btn-secondary' data-bs-dismiss='modal'>Batal</button>";
echo "<button type='submit' class='btn btn-primary' name='add_product'>Tambah Produk</button>";
echo "</div>";
echo "</form>";
echo "</div>";
echo "</div>";
echo "</div>";

// JavaScript for form validation and image preview
echo "<script>
                    // Form validation
                    document.getElementById('addProductForm').addEventListener('submit', function(e) {
                        const genreSelect = document.getElementById('addgenre');
                        if (genreSelect.value === '') {
                            e.preventDefault();
                            alert('Silakan pilih kategori produk');
                            genreSelect.focus();
                        }
                    });

                    // Image preview
                    document.getElementById('addImage').addEventListener('change', function(e) {
                        const preview = document.getElementById('addPreview');
                        const file = e.target.files[0];
                        if (file) {
                            const reader = new FileReader();
                            reader.onload = function(e) {
                                preview.src = e.target.result;
                            }
                            reader.readAsDataURL(file);
                        }
                    });
                  </script>";
