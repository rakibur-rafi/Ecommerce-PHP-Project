<?php
session_start();
require_once("files/functions.php"); 
protected_area();

if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

cart();

if (isset($_GET['remove'])) {
    $remove_id = (int)$_GET['remove'];
    $_SESSION['cart'] = array_filter($_SESSION['cart'], function ($item) use ($remove_id) {
        return $item['id'] !== $remove_id;
    });

    header("Location: cart.php");
    exit;
}
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_cart'])) {
    if (isset($_POST['quantities'])) {
        foreach ($_POST['quantities'] as $id => $quantity) {
            foreach ($_SESSION['cart'] as &$item) {
                if ($item['id'] == $id) {
                    $item['quantity'] = max(1, (int)$quantity); 
                    break;
                }
            }
        }
    }
    header("Location: cart.php");
    exit;
}

$subtotal = 0;
foreach ($_SESSION['cart'] as $item) {
    $subtotal += $item['price'] * $item['quantity'];
}

require_once("files/header.php");
?>

<!-- Page Title-->
<div class="page-title-overlap bg-dark pt-4">
    <div class="container d-lg-flex justify-content-between py-2 py-lg-3">
        <div class="order-lg-2 mb-3 mb-lg-0 pt-lg-2">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb breadcrumb-light flex-lg-nowrap justify-content-center justify-content-lg-start">
                    <li class="breadcrumb-item"><a class="text-nowrap" href="index.php"><i class="ci-home"></i>Home</a></li>
                    <li class="breadcrumb-item text-nowrap active" aria-current="page">Cart</li>
                </ol>
            </nav>
        </div>
        <div class="order-lg-1 pe-lg-4 text-center text-lg-start">
            <h1 class="h3 text-light mb-0">Your cart</h1>
        </div>
    </div>
</div>

<div class="container pb-5 mb-2 mb-md-4">
    <div class="row">
    <section class="col-lg-8">
    <div class="d-flex justify-content-between align-items-center pt-3 pb-4 pb-sm-5 mt-1">
        <h2 class="h6 text-light mb-0">Products</h2><a class="btn btn-outline-primary btn-sm ps-2" href="shop.php"><i class="ci-arrow-left me-2"></i>Continue shopping</a>
    </div>

    <?php
        if (!empty($_SESSION['cart'])) {
    ?>
    <form method="POST">
        <?php
            foreach ($_SESSION['cart'] as $item) {
        ?>
        <div class="d-sm-flex justify-content-between align-items-center my-2 pb-3 border-bottom">
            <div class="d-block d-sm-flex align-items-center text-center text-sm-start">
                <a class="d-inline-block flex-shrink-0 mx-auto me-sm-4" href="product.php?id=<?php echo $item['id']; ?>"><img src="<?php echo $item['image']; ?>" width="160" alt="Product"></a>
                <div class="pt-2">
                    <h3 class="product-title fs-base mb-2"><a href="product.php?id=<?php echo $item['id']; ?>"><?php echo $item['name']; ?></a></h3>
                    <div class="fs-sm"><span class="text-muted me-2">Size:</span><?php echo $item['size']; ?></div>
                    <div class="fs-sm"><span class="text-muted me-2">Color:</span><?php echo $item['color']; ?></div>
                    <div class="fs-lg text-accent pt-2">Tk <?php echo number_format($item['price']); ?></div>
                </div>
            </div>
            <div class="pt-2 pt-sm-0 ps-sm-3 mx-auto mx-sm-0 text-center text-sm-start" style="max-width: 9rem;">
                <label class="form-label" for="quantity<?php echo $item['id']; ?>">Quantity</label>
                <input class="form-control" type="number" id="quantity<?php echo $item['id']; ?>" name="quantities[<?php echo $item['id']; ?>]" min="1" value="<?php echo $item['quantity']; ?>">
                <a class="btn btn-link px-0 text-danger" href="cart.php?remove=<?php echo $item['id']; ?>"><i class="ci-close-circle me-2"></i><span class="fs-sm">Remove</span></a>
            </div>
        </div>
        <?php
            }
        ?>
        <button class="btn btn-outline-accent d-block w-100 mt-4" type="submit" name="update_cart"><i class="ci-loading fs-base me-2"></i>Update cart</button>
    </form> 
    <?php
        } else {
            echo "<p>Your cart is empty.</p>";
        }
    ?>
</section>

        <!-- Sidebar-->
        <aside class="col-lg-4 pt-4 pt-lg-0 ps-xl-5">
            <div class="bg-white rounded-3 shadow-lg p-4">
                <div class="py-2 px-xl-2">
                    <div class="text-center mb-4 pb-3 border-bottom">
                        <h2 class="h6 mb-3 pb-1">Subtotal</h2>
                        <h3 class="fw-normal">Tk <?php echo number_format($subtotal); ?></h3>
                    </div>
                    <!-- Checkout button -->
                    <div class="text-center">
                        <a class="btn btn-primary w-100 mt-4" href="checkout.php"><i class="ci-credit-card me-2"></i>Proceed to Checkout</a>
                    </div>

                </div>
            </div>
        </aside>
    </div>
</div>


<?php require_once("files/footer.php"); ?>
