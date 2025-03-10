<?php
require_once("files/functions.php");
protected_area();
if (!is_admin()) {
    header("Location: index.php");
    exit();
}

if (isset($_POST['delete_order']) && $_POST['delete_order'] == 1 && isset($_POST['o_id'])) {
    $orderId = $_POST['o_id'];

    $query = "SELECT status FROM orders WHERE o_id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $orderId);
    $stmt->execute();
    $stmt->bind_result($status);
    $stmt->fetch();
    $stmt->close();

    if ($status == 'delivered') {
        $query = "DELETE FROM orderlist WHERE o_id = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("i", $orderId);
        if (!$stmt->execute()) {
            echo "failed";  
            $stmt->close();
            exit();
        }
        $stmt->close();

        $query = "DELETE FROM orders WHERE o_id = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("i", $orderId);
        if ($stmt->execute()) {
            echo "success";
        } else {
            echo "failed";
        }
        $stmt->close();
    } else {
        echo "not_delivered";  
    }
    exit();
}

$query = "SELECT o.o_id, o.order_date, o.status, o.total_price, u.username, u.email, u.address, u.phone_number 
          FROM orders o 
          JOIN users u ON o.user_id = u.id 
          ORDER BY o.order_date DESC";
$stmt = $conn->prepare($query);
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
                    <li class="breadcrumb-item text-nowrap"><a href="#">Admin</a></li>
                    <li class="breadcrumb-item text-nowrap active" aria-current="page">User Orders</li>
                </ol>
            </nav>
        </div>
        <div class="order-lg-1 pe-lg-4 text-center text-lg-start">
            <h1 class="h3 text-light mb-0">All Orders</h1>
        </div>
    </div>
</div>
<div class="container pb-5 mb-2 mb-md-4">
    <div class="row">
        <?php require_once('files/account-sidebar.php') ?>
        <section class="col-lg-8 pt-lg-4 pb-4 mb-3">
            <div class="pt-2 px-4 ps-lg-0 pe-xl-5">
                <div class="d-sm-flex flex-wrap justify-content-between align-items-center pb-2">
                    <h2 class="h3 py-2 me-2 text-center text-sm-start">All User Orders</h2>
                </div>

                <?php if (!empty($orders)) { ?>
                    <?php foreach ($orders as $order) { ?>
                        <?php
                        $status_class = '';
                        switch ($order['status']) {
                            case 'in progress':
                                $status_class = 'badge bg-info'; 
                                break;
                            case 'delivered':
                                $status_class = 'badge bg-success' ;
                                break;
                            case 'canceled':
                                $status_class = 'badge bg-danger'; 
                                break;
                            default:
                                $status_class = 'badge bg-secondary'; 
                                break;
                        }
                        ?>
                        <div class="card mb-4 shadow-lg">
                            <div class="card-header bg-dark text-light">
                                <strong>Order #<?php echo $order['o_id']; ?></strong>
                                <span class="float-end text-light">Date: <?php echo $order['order_date']; ?></span>
                            </div>
                            <div class="card-body">
                                <p class="h6"><strong>User</strong> 
                                <div> <?php echo !empty($order['username']) ? $order['username'] : $order['email']; ?></p></div>
                                <p class="h6"><strong>Address</strong> 
                               <div> <?php echo $order['address']; ?></p></div>
                                <p class="h6"><strong>Phone</strong> 
                                <div><?php echo $order['phone_number']; ?></p></div>
                                <p class="<?php echo $status_class; ?> text-uppercase order-status-<?php echo $order['o_id']; ?>">
                                    <?php echo ucfirst($order['status']); ?>
                                </p>

                                <p>Total Price : <strong>Tk <?php echo number_format($order['total_price'], 2); ?></strong></p>

                                <!-- Toggle Button -->
                                <button class="btn btn-success  btn-sm toggle-btn" data-target="order-<?php echo $order['o_id']; ?>">View Details</button>
                                
                                <?php if ($order['status'] == 'delivered') { ?>
                                    <a href="javascript:void(0);" class="delete-order btn btn-danger btn-sm" data-order-id="<?php echo $order['o_id']; ?>" title="Delete Order">
                                        <i class="ci-trash"></i> Delete
                                    </a>
                                <?php } ?>

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
                                    <table class="table table-striped table-dark table-hover">
                                        <thead class="table-light">
                                            <tr>
                                                <th>Product</th>
                                                <th>Product ID</th>
                                                <th>Size</th>
                                                <th>Quantity</th>
                                                <th>Price</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php foreach ($order_items as $item) { ?>
                                                <tr>
                                                    <td><a href="product.php?id=<?php echo $item['product_id']; ?>" class="text-light"><?php echo $item['name']; ?></a></td>
                                                    <td><?php echo $item['product_id']; ?></td>
                                                    <td><?php echo $item['size_name']; ?></td>
                                                    <td><?php echo $item['quantity']; ?></td>
                                                    <td>Tk <?php echo number_format($item['price'], 2); ?></td>
                                                </tr>
                                            <?php } ?>
                                        </tbody>
                                    </table>

                                    <form class=" update-status-form" data-order="<?php echo $order['o_id']; ?>">
                                        <label for="status-<?php echo $order['o_id']; ?>" class="form-label text-light">Update Status:</label>
                                        <select class="form-select status-select" id="status-<?php echo $order['o_id']; ?>" name="status">
                                            <option value="delivered" <?php if ($order['status'] == 'delivered') echo 'selected'; ?>>Delivered</option>
                                            <option value="canceled" <?php if ($order['status'] == 'canceled') echo 'selected'; ?>>Canceled</option>
                                        </select>
                                        <button type="submit" class="btn btn-success mt-2">Update</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    <?php } ?>
                <?php } else { ?>
                    <p class="text-light">No orders found.</p>
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

        // Delete order functionality
        document.querySelectorAll(".delete-order").forEach(function (deleteBtn) {
            deleteBtn.addEventListener("click", function () {
                let orderId = this.getAttribute("data-order-id");

                if (confirm("Are you sure you want to delete this order?")) {
                    fetch("<?php echo $_SERVER['PHP_SELF']; ?>", {
                        method: "POST",
                        headers: { "Content-Type": "application/x-www-form-urlencoded" },
                        body: "delete_order=1&o_id=" + orderId
                    })
                    .then(response => response.text())
                    .then(data => {
                        if (data === "success") {
                            let orderCard = this.closest(".card");
                            orderCard.remove();
                            alert("Order deleted successfully!");
                        } else if (data === "not_delivered") {
                            alert("Order cannot be deleted as it's not delivered.");
                        } else {
                            alert("Failed to delete the order.");
                        }
                    });
                }
            });
        });

        document.querySelectorAll(".update-status-form").forEach(form => {
            form.addEventListener("submit", function (e) {
                e.preventDefault();
                
                let orderId = this.getAttribute("data-order");
                let status = this.querySelector(".status-select").value;

                fetch("status-update.php", {
                    method: "POST",
                    headers: { "Content-Type": "application/x-www-form-urlencoded" },
                    body: "o_id=" + orderId + "&status=" + status
                })
                .then(response => response.text())
                .then(data => {
                    if (data === "success") {
                        let statusBadge = document.querySelector(".order-status-" + orderId);
                        statusBadge.textContent = status.charAt(0).toUpperCase() + status.slice(1);
                        statusBadge.className = "badge " + (status === "delivered" ? "bg-success" : "bg-danger") + " text-uppercase";
                    } else {
                        alert("Failed to update status.");
                    }
                });
            });
        });
    });
</script>

<?php require_once("files/footer.php"); ?>
