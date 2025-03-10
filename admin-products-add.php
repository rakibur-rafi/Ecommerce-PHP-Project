<?php
require_once('files/functions.php');
protected_area();
if (!is_admin()) {
  header("Location: index.php");
  exit();
}

$rows = db_select('categories', 'parent_id != 0');
$categories = [];
foreach ($rows as $row) {
  $categories[$row['category_id']] = $row['name'];
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
  $_SESSION['form']['value'] = $_POST;
  $data['name'] = $_POST['name'];
  $data['price'] = $_POST['price'];
  $data['description'] = $_POST['description'];
  $data['color'] = $_POST['color'];
  $data['category_id'] = $_POST['category_id'] ?? null;

  $product_id = db_insert('products', $data);

  if ($product_id) {
      if (!empty($_POST['sizes'])) {
          foreach ($_POST['sizes'] as $size) {
              $size = trim($size);
              if (!empty($size)) {
                  db_insert('product_sizes', ['product_id' => $product_id, 'size_name' => $size]);
              }
          }
      }

      upload_and_save_images($_FILES, $product_id, $conn);

      alert('success', 'Product created successfully');
      header('Location: admin-products.php');
      unset($_SESSION['form']);
  } else {
      alert('danger', 'Failed to create product, please try again');
      header('Location: admin-products-add.php');
  }
  die();
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
          <li class="breadcrumb-item text-nowrap active" aria-current="page">Orders history</li>
        </ol>
      </nav>
    </div>
    <div class="order-lg-1 pe-lg-4 text-center text-lg-start">
      <h1 class="h3 text-light mb-0"> Add Product</h1>
    </div>
  </div>
</div>

<div class="container pb-5 mb-2 mb-md-4">
  <div class="row">
    <?php require_once('files/account-sidebar.php') ?>
    <section class="col-lg-8 pt-lg-4 pb-4 mb-3">
      <div class="pt-2 px-4 ps-lg-0 pe-xl-5">
        <div class="d-sm-flex flex-wrap justify-content-between align-items-center pb-2">
          <h2 class="h3 py-2 me-2 text-center text-sm-start">Add New Product</h2>
        </div>
        <form action="admin-products-add.php" method="POST" enctype="multipart/form-data">
          <div class="mb-3 pb-2">
            <div class="row">
              <div class="col-md-12 mt-4">
                <?= text_input(['name' => 'name']) ?>
              </div>
            </div>
            <div class="row">
              <div class="col-md-6 mt-4">
                <div class="form-group">
                  <?= select_input(['name' => 'category_id', 'label' => 'Category'], $categories) ?>
                </div>
              </div>
            </div>
            <div class="row">
              <div class="col-md-6 mt-4">
                <?= text_input(['name' => 'price', 'label' => 'Selling price']) ?>
              </div>
              <div class="col-md-6 mt-4">
                <?= text_input(['name' => 'color', 'label' => 'Color']) ?>
              </div>
            </div>
            <div class="row">
              <div class="col-md-6 mt-4">
                <div class="form-group">
                  <label for="sizes">Sizes</label>
                  <div id="size-container">
                      <input type="text" name="sizes[]" class="form-control mb-2" placeholder="Enter size">
                  </div>
                  <button type="button" id="add-size" class="btn btn-sm btn-success mt-2">Add More</button>
                </div>
              </div>
            </div>
            <script>
                document.getElementById('add-size').addEventListener('click', function() {
                    let container = document.getElementById('size-container');
                    let input = document.createElement('input');
                    input.type = 'text';
                    input.name = 'sizes[]';
                    input.className = 'form-control mb-2';
                    input.placeholder = 'Enter size';
                    container.appendChild(input);
                });
            </script>
            <div class="row">
              <div class="col-md-6 mt-4">
                <div class="form-group">
                  <label for="image">Product Image 1</label>
                  <input class="form-control" type="file" name="image1" id="image1" accept=".jpg,.jpeg,.png">
                </div>
              </div>
              <div class="col-md-6 mt-4">
                <div class="form-group">
                  <label for="image">Product Image 2</label>
                  <input class="form-control" type="file" name="image2" id="image2" accept=".jpg,.jpeg,.png">
                </div>
              </div>
            </div>
            <div class="col-12 mt-4">
              <div class="form-group">
                <label for="description">Description</label>
                <textarea class="form-control" name="description" id="description"></textarea>
              </div>
            </div>
          </div>
          <button class="btn btn-primary d-block w-100" type="submit"><i class="ci-cloud-upload fs-lg me-2"></i>Submit</button>
        </form>
      </div>
    </section>
  </div>
</div>

<?php require_once('files/footer.php'); ?>