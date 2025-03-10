<?php
require_once('files/functions.php');
protected_area();
if (!is_admin()) {
    header("Location: index.php");
    exit();
}

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    die("Invalid Product ID.");
}

$product_id = intval($_GET['id']);
$product = db_select('products', "products_id = $product_id");

if (!$product || empty($product)) {
    die("Product not found.");
}

$product = $product[0];

$product_sizes = db_select('product_sizes', "product_id = $product_id");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name']);
    $price = trim($_POST['price']);
    $description = trim($_POST['description']);

    if ($name === '' || $price === '' || $description === '') {
        alert('danger', 'All fields are required.');
    } else {
        db_update('products', ['name' => $name, 'price' => $price, 'description' => $description], "products_id = $product_id");
    }

    if (isset($_POST['sizes'])) {
        foreach ($_POST['sizes'] as $size_id => $size_value) {
            if (isset($_POST['delete_sizes'][$size_id])) {
                db_delete('product_sizes', "size_id = $size_id");
            } else {
                db_update('product_sizes', ['size_name' => trim($size_value)], "size_id = $size_id");
            }
        }
    }

    alert('success', 'Updated Successfully');
    header('Location: ' . $_SERVER['REQUEST_URI']);
    exit;
}

require_once('files/header.php');
?>
<div class="page-title-overlap bg-dark pt-4">
    <div class="container d-lg-flex justify-content-between py-2 py-lg-3">
        <div class="order-lg-2 mb-3 mb-lg-0 pt-lg-2">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb breadcrumb-light flex-lg-nowrap justify-content-center justify-content-lg-start">
                    <li class="breadcrumb-item"><a class="text-nowrap" href="index-2.html"><i class="ci-home"></i>Home</a></li>
                    <li class="breadcrumb-item text-nowrap"><a href="#">Account</a></li>
                    <li class="breadcrumb-item text-nowrap active" aria-current="page">Edit Product</li>
                </ol>
            </nav>
        </div>
        <div class="order-lg-1 pe-lg-4 text-center text-lg-start">
            <h1 class="h3 text-light mb-0">Product Categories</h1>
        </div>
    </div>
</div>
<div class="container pb-5 mb-2 mb-md-4">
    <div class="row">
        <?php require_once('files/account-sidebar.php'); ?>
        <section class="col-lg-8 pt-lg-4 pb-4 mb-3">
            <div class="pt-2 px-4 ps-lg-0 pe-xl-5">
                <h2 class="h3 py-2 me-2 text-center text-sm-start">Edit Product</h2>

                <form action="edit-product.php?id=<?= $product_id ?>" method="POST">
                    <div class="row gx-4 gy-3">
                        <div class="col-sm-6">
                            <label class="form-label" for="product-name">Product Name</label>
                            <input class="form-control" type="text" id="product-name" name="name" value="<?= htmlspecialchars($product['name']) ?>" required>
                        </div>
                        <div class="col-sm-6">
                            <label class="form-label" for="product-price">Product Price</label>
                            <input class="form-control" type="text" id="product-price" name="price" value="<?= htmlspecialchars($product['price']) ?>" required>
                        </div>
                        <div class="col-12">
                            <label class="form-label" for="product-description">Product Description</label>
                            <textarea class="form-control" id="product-description" name="description" required><?= htmlspecialchars($product['description']) ?></textarea>
                        </div>

                        <div class="col-12">
                            <h5 class="mt-4">Product Sizes</h5>
                            <?php if (!empty($product_sizes)): ?>
                                <?php foreach ($product_sizes as $size): ?>
                                    <div class="d-flex align-items-center mb-2">
                                        <input class="form-control me-2" type="text" name="sizes[<?= $size['size_id'] ?>]" value="<?= htmlspecialchars($size['size_name']) ?>" required>
                                        <button type="submit" name="delete_sizes[<?= $size['size_id'] ?>]" class="btn btn-danger btn-sm">Delete</button>
                                    </div>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <p class="text-muted">No sizes available for this product.</p>
                            <?php endif; ?>
                        </div>

                        <div class="col-12">
                            <hr class="mt-2 mb-3">
                            <button class="btn btn-primary mt-3" type="submit">Update Product</button>
                        </div>
                    </div>
                </form>
            </div>
        </section>
    </div>
</div>

<?php require_once('files/footer.php'); ?>
