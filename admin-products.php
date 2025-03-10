<?php
require_once('files/functions.php');
protected_area();
if (!is_admin()) {
  header("Location: index.php");
  exit();
}

if (isset($_POST['delete_product'])) {
  $productId = (int) $_POST['delete_product'];

  $result_sizes = db_delete('product_sizes', 'product_id = ' . $productId);

  $images = db_select('product_images', 'product_id = ' . $productId);
  foreach ($images as $image) {
      if (file_exists($image['src'])) {
          unlink($image['src']);
      }
  }

  $result_products = db_delete('products', 'products_id = ' . $productId);

  if ($result_sizes && $result_products) {
      alert('success', 'Product deleted successfully');
  } else {
      alert('danger', 'Failed to delete product');
  }

  header('Location: admin-products.php');
  exit;
}

$products = db_select(
    'products',
    null,
    'ORDER BY products.products_id DESC',
    'LEFT JOIN product_images ON products.products_id = product_images.product_id GROUP BY products.products_id'
);

require_once('files/header.php');
?>

<div class="page-title-overlap bg-dark pt-4">
        <div class="container d-lg-flex justify-content-between py-2 py-lg-3">
          <div class="order-lg-2 mb-3 mb-lg-0 pt-lg-2">
            <nav aria-label="breadcrumb">
              <ol class="breadcrumb breadcrumb-light flex-lg-nowrap justify-content-center justify-content-lg-start">
                <li class="breadcrumb-item"><a class="text-nowrap" href="index-2.html"><i class="ci-home"></i>Home</a></li>
                <li class="breadcrumb-item text-nowrap"><a href="#">Account</a>
                </li>
                <li class="breadcrumb-item text-nowrap active" aria-current="page">Orders history</li>
              </ol>
            </nav>
          </div>
          <div class="order-lg-1 pe-lg-4 text-center text-lg-start">
            <h1 class="h3 text-light mb-0">Products</h1>
          </div>
        </div>
      </div>
      <div class="container pb-5 mb-2 mb-md-4">
        <div class="row">
          <!-- Sidebar-->
          <?php require_once('files/account-sidebar.php') ?>
          <!-- Content  -->
          <section class="col-lg-8 pt-lg-4 pb-4 mb-3">
              <div class="pt-2 px-4 ps-lg-0 pe-xl-5">
                <!-- Title-->
                <div class="d-sm-flex flex-wrap justify-content-between align-items-center border-bottom">
                  <h2 class="h3 py-2 me-2 text-center text-sm-start">Product Categories</h2>
               
                </div>

                <?php foreach ($products as $key => $pro) { ?>
    <div class="d-block d-sm-flex align-items-center py-4 border-bottom">
        <div class="d-block mb-3 mb-sm-0 me-sm-4 ms-sm-0 mx-auto" >
            <img style="width: 12.5rem;" class="rounded-3" src="<?= htmlspecialchars($pro['src'] ?? 'default.jpg'); ?>" alt="Product">
        </div>
        <div class="text-center text-sm-start">
            <h3 class="h6 product-title mb-2">
                <a href="#"><?= htmlspecialchars($pro['name']) ?></a>
            </h3>
            
            <div class="d-inline-block text-accent"><?= number_format($pro['price'], 2) ?> Tk</div>
            <div class="d-inline-block text-muted fs-ms border-start ms-2 ps-2">
                Sales: <span class="fw-medium">26</span>
            </div>
            <div class="d-flex justify-content-center justify-content-sm-start pt-3">
                <a href="edit-product.php?id=<?= urlencode($pro['products_id']) ?>" class="btn bg-faded-info btn-icon me-2" data-bs-toggle="tooltip" title="Edit">
                    <i class="ci-edit text-info"></i>
                </a>
                <!-- Product Deletion Form -->
                <form method="POST" style="display:inline;">
                    <button class="btn bg-faded-danger btn-icon" type="submit" name="delete_product" value="<?= $pro['products_id'] ?>" data-bs-toggle="tooltip" title="Delete">
                        <i class="ci-trash text-danger"></i>
                    </button>
                </form>
            </div>
        </div>
    </div>
<?php } ?>


              </div>
            </section>
        </div>
      </div>

<?php
require_once('files/footer.php');
?>