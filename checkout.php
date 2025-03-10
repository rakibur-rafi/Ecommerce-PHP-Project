<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
require_once("files/functions.php");
protected_area();

if (!isset($_SESSION['cart']) || empty($_SESSION['cart'])) {
    header("Location: cart.php");
    exit;
}

$user_id = $_SESSION['user']['id'];

$query = "SELECT address FROM users WHERE id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$stmt->bind_result($address);
$stmt->fetch();
$stmt->close();

if (empty($address)) {
    echo "No address found for the user.";
    exit;
}
$query = "SELECT phone_number FROM users WHERE id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$stmt->bind_result($phone_number);
$stmt->fetch();
$stmt->close();

if (empty($address)) {
    echo "No address found for the user.";
    exit;
}

$total_price = 0;
foreach ($_SESSION['cart'] as $item) {
    $total_price += $item['price'] * $item['quantity'];
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $order_date = date("Y-m-d H:i:s");
    $query = "INSERT INTO orders (user_id, total_price, order_date) VALUES (?, ?, ?)";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("ids", $user_id, $total_price, $order_date);
    $stmt->execute();
    $order_id = $stmt->insert_id;
    $stmt->close();

    foreach ($_SESSION['cart'] as $item) {
        $size_name = $item['size'];
        $size_query = "SELECT size_id FROM product_sizes WHERE size_name = ?";
        $size_stmt = $conn->prepare($size_query);
        $size_stmt->bind_param("s", $size_name);
        $size_stmt->execute();
        $size_stmt->bind_result($size_id);
        $size_stmt->fetch();
        $size_stmt->close();

        if (empty($size_id)) {
            echo "Size ID not found for size: " . $size_name;
            exit;
        }

        $query = "INSERT INTO orderlist (product_id, quantity, o_id, size_id, price) VALUES (?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("iiids", $item['id'], $item['quantity'], $order_id, $size_id, $item['price']);
        $stmt->execute();
        $stmt->close();
    }

    unset($_SESSION['cart']);
    alert('success','Order placed successfully');
    header("Location: my-orders.php");
    exit;
}
?>

<?php require_once("files/header.php"); ?>
<!-- Page Title-->
<div class="page-title-overlap bg-dark pt-4">
    <div class="container d-lg-flex justify-content-between py-2 py-lg-3">
        <div class="order-lg-2 mb-3 mb-lg-0 pt-lg-2">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb breadcrumb-light flex-lg-nowrap justify-content-center justify-content-lg-start">
                    <li class="breadcrumb-item"><a class="text-nowrap" href="index.php"><i class="ci-home"></i>Home</a></li>
                    <li class="breadcrumb-item text-nowrap"><a href="shop.php">Cart</a></li>
                    <li class="breadcrumb-item text-nowrap active" aria-current="page">Checkout</li>
                </ol>
            </nav>
        </div>
        <div class="order-lg-1 pe-lg-4 text-center text-lg-start">
            <h1 class="h3 text-light mb-0">Checkout</h1>
        </div>
    </div>
</div>
<div class="container pb-5 mb-2 mb-md-4">
    <div class="row">
        <section class="col-lg-8">
        <div class="d-flex justify-content-between align-items-center pt-3 pb-4 pb-sm-5 mt-1">
        <h2 class="h6 text-light mb-0">Products</h2><a class="btn btn-outline-success btn-sm ps-2" href="shop.php"><i class="ci-arrow-left me-2"></i>Back to cart</a>
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
                        <div class="fs-lg text-accent pt-2">Quantity: <?php echo $item['quantity']; ?></div>
                    </div>
                </div>
                <?php
                    }
                ?>
                <div class="mb-3 mt-3">
                    <div class="d-flex justify-content-left align-items-center">
                        <h5 class="h6 mb-0">Phone Number</h5>
                        <a href="account-settings.php" class="btn bg-faded-info btn-icon me-2" data-bs-toggle="tooltip" title="Edit">
                            <i class="ci-edit text-info"></i>
                        </a>
                    </div>
                    <div class="p-3 border border-dark rounded mt-2"><?php echo $phone_number; ?></div>
                </div>


                <div class="mb-3 mt-3">
                    <div class="d-flex justify-content-left align-items-center">
                        <h5 class="h6 mb-0">Address</h5>
                        <a href="account-settings.php" class="btn bg-faded-info btn-icon me-2" data-bs-toggle="tooltip" title="Edit">
                            <i class="ci-edit text-info"></i>
                        </a>
                    </div>
                    <div class="p-3 border border-dark rounded mt-2"><?php echo $address; ?></div>
                </div>

                <button class="btn btn-outline-dark d-block w-100 mt-4" type="submit"><i class="ci-check-circle me-2"></i>Complete Checkout</button>
            </form>
            <?php
                } else {
                    echo "<p>Your cart is empty.</p>";
                }
            ?>
        </section>

        <aside class="col-lg-4 pt-4 pt-lg-0 ps-xl-5">
            <div class="bg-white rounded-3 shadow-lg p-4">
                <div class="py-2 px-xl-2">
                    <div class="text-center mb-4 pb-3 border-bottom">
                        <h2 class="h6 mb-3 pb-1">Order Summary</h2>
                        <h3 class="fw-normal">Tk <?php echo number_format($total_price); ?></h3>
                    </div>
                </div>
            </div>
        </aside>
    </div>
</div>

<?php require_once("files/footer.php"); ?>
