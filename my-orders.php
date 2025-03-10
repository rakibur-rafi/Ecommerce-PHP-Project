<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
require_once("files/functions.php");
protected_area();

$user_id = $_SESSION['user']['id'];

$query = "SELECT o_id, order_date, status, total_price FROM orders WHERE user_id = ? ORDER BY order_date DESC";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$orders = $result->fetch_all(MYSQLI_ASSOC);
$stmt->close();
?>

<?php require_once("files/header.php"); ?>
<div class="page-title-overlap bg-dark pt-4">
    <div class="container d-lg-flex justify-content-between py-2 py-lg-3">
        <div class="order-lg-2 mb-3 mb-lg-0 pt-lg-2">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb breadcrumb-light flex-lg-nowrap justify-content-center justify-content-lg-start">
                    <li class="breadcrumb-item"><a class="text-nowrap" href="index-2.html"><i class="ci-home"></i>Home</a></li>
                    <li class="breadcrumb-item text-nowrap"><a href="#">Account</a></li>
                    <li class="breadcrumb-item text-nowrap active" aria-current="page">My Orders</li>
                </ol>
            </nav>
        </div>
        <div class="order-lg-1 pe-lg-4 text-center text-lg-start">
            <h1 class="h3 text-light mb-0">My Orders</h1>
        </div>
    </div>
</div>
<div class="container pb-5 mb-2 mb-md-4">
<div class="row">
        <?php require_once('files/account-sidebar.php') ?>
        <section class="col-lg-8 pt-lg-4 pb-4 mb-3">
            <div class="pt-2 px-4 ps-lg-0 pe-xl-5">
                <div class="d-sm-flex flex-wrap justify-content-between align-items-center pb-2">
                    <h2 class="h3 py-2 me-2 text-center text-sm-start">My Orders</h2>
                </div>
                <?php if (!empty($orders)) { ?>
                    <?php foreach ($orders as $order) { ?>
                        <div class="card mb-4">
                            <div class="card-header bg-dark text-light">
                                <strong>Order #<?php echo $order['o_id']; ?></strong>
                                <span class="float-end">Date: <?php echo $order['order_date']; ?></span>
                            </div>
                            <div class="card-body">
                                <?php 
                                    $status_class = 'badge ';
                                    switch ($order['status']) {
                                        case 'in progress':
                                            $status_class .= 'bg-info ';
                                            break;
                                        case 'delivered':
                                            $status_class .= 'bg-success ';
                                            break;
                                        case 'canceled':
                                            $status_class .= 'bg-danger ';
                                            break;
                                        default:
                                            $status_class .= 'bg-info ';
                                    }
                                ?>
                                <p class="badge <?php echo $status_class; ?> text-uppercase"><?php echo ucfirst($order['status']); ?></p>
                                <p>Total Price : <strong>Tk <?php echo number_format($order['total_price'], 2); ?></strong></p>

                                <!-- Toggle Button -->
                                <button class="btn btn-outline-primary btn-sm toggle-btn" data-target="order-<?php echo $order['o_id']; ?>">View Details</button>

                                <?php
                                $query = "SELECT ol.product_id, p.name, ps.size_name, ol.quantity, ol.price 
                                          FROM orderlist ol 
                                          JOIN products p ON ol.product_id = p.products_id
                                          JOIN product_sizes ps ON ol.size_id = ps.size_id
                                          WHERE ol.o_id = ?";
                                $stmt = $conn->prepare($query);
                                $stmt->bind_param("i", $order['o_id']);
                                $stmt->execute();
                                $result = $stmt->get_result();
                                $order_items = $result->fetch_all(MYSQLI_ASSOC);
                                $stmt->close();
                                ?>

                                <div id="order-<?php echo $order['o_id']; ?>" class="order-details mt-3" style="display: none;">
                                    <table class="table table-dark table-hover">
                                        <thead>
                                            <tr>
                                                <th>Product</th>
                                                <th>Size</th>
                                                <th>Quantity</th>
                                                <th>Price</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php foreach ($order_items as $item) { ?>
                                                <tr>
                                                    <td><a href="product.php?id=<?php echo $item['product_id']; ?>" class="text-light"><?php echo $item['name']; ?></a></td>
                                                    <td><?php echo $item['size_name']; ?></td>
                                                    <td><?php echo $item['quantity']; ?></td>
                                                    <td>$<?php echo number_format($item['price'], 2); ?></td>
                                                </tr>
                                            <?php } ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    <?php } ?>
                <?php } else { ?>
                    <p class="text-dark">No orders found.</p>
                <?php } ?>
            </div>
        </section>
    </div>
</div>


<script>
    document.addEventListener("DOMContentLoaded", function () {
        document.querySelectorAll(".toggle-btn").forEach(function (btn) {
            btn.addEventListener("click", function () {
                let targetId = this.getAttribute("data-target");
                let targetDiv = document.getElementById(targetId);
                
                if (targetDiv.style.display === "none") {
                    targetDiv.style.display = "block";
                    this.textContent = "Hide Details";
                } else {
                    targetDiv.style.display = "none";
                    this.textContent = "View Details";
                }
            });
        });
    });
</script>

<?php require_once("files/footer.php"); ?>
