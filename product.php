<?php 
$id = 0;
if (isset($_GET['id'])) {
    $id = (int)$_GET['id'];
}
if ($id < 1) {
    die('NOT FOUND');
}
require_once('files/functions.php');
require_once("files/header.php");

$data = get_product($id);
$pro = $data["pro"];
$cat = $data["cat"];

if ($pro == null) {
    die("Product not found");
}
if ($cat == null) {
    die("Category not found");
}

$images = product_photos($id, $conn);
$sizes = [];
$product_id = $pro['products_id'];
$sql = "SELECT size_name FROM product_sizes WHERE product_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $product_id);
$stmt->execute();
$result = $stmt->get_result();
while ($row = $result->fetch_assoc()) {
    $sizes[] = $row['size_name'];
}
$stmt->close();


?>
      <div class="page-title-overlap bg-dark pt-4">
      <div class="container d-lg-flex justify-content-between py-2 py-lg-3">
          <div class="order-lg-2 mb-3 mb-lg-0 pt-lg-2">
            <nav aria-label="breadcrumb">
              <ol class="breadcrumb breadcrumb-light flex-lg-nowrap justify-content-center justify-content-lg-start">
                <li class="breadcrumb-item"><a class="text-nowrap" href="<?= BASE_URL ?>"><i class="ci-home"></i>Home</a></li>
                <li class="breadcrumb-item text-nowrap"><a href="shop.php">Shop</a>
                </li>
                <li class="breadcrumb-item text-nowrap active" aria-current="page"><?= $pro['name'] ?></li>
              </ol>
            </nav>
          </div>
          <div class="order-lg-1 pe-lg-4 text-center text-lg-start">
            <div class="h3 text-light mb-0"><?= $pro['name'] ?></h1>
          </div>
        </div>
      </div>
      </div>
      <div class="container">
        <!-- Gallery + details-->
        <div class="bg-light shadow-lg rounded-3 px-4 py-3 mb-5">
          <div class="px-lg-3">
            <div class="row">
              <!-- Product gallery-->
              <div class="col-lg-7 pe-lg-0 pt-lg-4">
                <div class="product-gallery">
                    <div class="product-gallery-preview order-sm-2">
                        <?php 
                        $first_image = reset($images); 
                        ?>
                        <img id="mainImage" class="image-zoom" src="<?= $first_image['src'] ?>" data-zoom="<?= $first_image['src'] ?>" alt="Product image" class="img-fluid">
                    </div>

                    <div class="product-gallery-thumblist order-sm-1 justify-content-start me-2">
                        <?php 
                        foreach ($images as $key => $image) { ?>
                            <img 
                                class="product-gallery-thumblist-item img-thumbnail" 
                                src="<?= $image['src'] ?>" 
                                alt="Product thumb"
                                style="width: 100px; height: 100px; object-fit: cover; cursor: pointer;"
                                onclick="document.getElementById('mainImage').src = '<?= $image['src'] ?>';">
                        <?php } ?>
                    </div>
                </div>
            </div>

              <!-- Product details-->
              <div class="col-lg-5 pt-4 pt-lg-0">
                <div class="product-details ms-auto pb-3">
                    <div class="d-flex justify-content-between align-items-center mb-2">
                    <a href="#reviews" data-scroll>
                        <div class="star-rating">
                        <i class="star-rating-icon ci-star-filled active"></i>
                        <i class="star-rating-icon ci-star-filled active"></i>
                        <i class="star-rating-icon ci-star-filled active"></i>
                        <i class="star-rating-icon ci-star-filled active"></i>
                        <i class="star-rating-icon ci-star"></i>
                        </div>
                        <span class="d-inline-block fs-sm text-body align-middle mt-1 ms-1"></span>
                    </a>
                    <button class="btn-wishlist me-0 me-lg-n3" type="button" data-bs-toggle="tooltip" title="Add to wishlist">
                        <i class="ci-heart"></i>
                    </button>
                    </div>
                    <div class="mb-3">
                    <span class="h3 fw-normal text-accent me-1"><?= $pro['price'] ?> <small>Tk</small></span>
                    </div>
                    <div class="fs-sm mb-4"><span class="text-heading fw-medium me-1">Color:</span><span class="text-muted" id="colorOption"><?= $pro['color'] ?></span></div>

                    <form class="mb-grid-gutter" method="post" action="cart.php">
                        <input type="hidden" name="product_id" value="<?= $pro['products_id'] ?>">
                        <input type="hidden" name="product_name" value="<?= htmlspecialchars($pro['name']) ?>">
                        <input type="hidden" name="product_image" value="<?= htmlspecialchars($first_image['src']) ?>">
                        <input type="hidden" name="color" value="<?= htmlspecialchars($pro['color']) ?>">
                        <input type="hidden" name="price" value="<?= $pro['price'] ?>">
                        <div class="mb-3">
                        <div class="d-flex justify-content-between align-items-center pb-1">
                            <label class="form-label" for="product-size">Size:</label>
                            <a class="nav-link-style fs-sm" href="#size-chart" data-bs-toggle="modal">
                                <i class="ci-ruler lead align-middle me-1 mt-n1"></i>Size guide
                            </a>
                        </div>
                        <select class="form-select" required id="product-size" name="size">
                            <option value="">Select size</option>
                            <?php foreach ($sizes as $size) { ?>
                                <option value="<?= htmlspecialchars($size) ?>"><?= htmlspecialchars($size) ?></option>
                            <?php } ?>
                        </select>
                    </div>

                    <div class="mb-3 d-flex align-items-center">
                        <select class="form-select me-3" name="quantity" style="width: 5rem;">
                            <option value="1">1</option>
                            <option value="2">2</option>
                            <option value="3">3</option>
                            <option value="4">4</option>
                            <option value="5">5</option>
                        </select>
                        <button class="btn btn-primary btn-shadow d-block w-100" type="submit">
                            <i class="ci-cart fs-lg me-2"></i>Add to Cart
                        </button>
                    </div>
                 </form>

                    <!-- Product panels-->
                    <div class="accordion mb-4" id="productPanels">
                    <div class="accordion-item">
                        <h3 class="accordion-header">
                        <a class="accordion-button" href="#productInfo" role="button" data-bs-toggle="collapse" aria-expanded="true" aria-controls="productInfo">
                            <i class="ci-announcement text-muted fs-lg align-middle mt-n1 me-2"></i>Product info
                        </a>
                        </h3>
                        <div class="accordion-collapse collapse show" id="productInfo" data-bs-parent="#productPanels">
                        <div class="accordion-body">
                        <?= $pro['description'] ?>
                        </div>
                        </div>
                    </div>
                    </div>
                    <!-- Sharing-->
                    <label class="form-label d-inline-block align-middle my-2 me-3">Share:</label>
                    <a class="btn-share btn-twitter me-2 my-2" href="#"><i class="ci-twitter"></i>Twitter</a>
                    <a class="btn-share btn-instagram me-2 my-2" href="#"><i class="ci-instagram"></i>Instagram</a>
                    <a class="btn-share btn-facebook my-2" href="#"><i class="ci-facebook"></i>Facebook</a>
                    </div>
                </div>
            </div>
          </div>
        </div>
<?php 
require_once("files/footer.php");
?>

