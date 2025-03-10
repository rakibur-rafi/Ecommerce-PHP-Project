<aside class="col-lg-4 pt-4 pt-lg-0 pe-xl-5">
    <div class="bg-white rounded-3 shadow-lg pt-1 mb-5 mb-lg-0">
        <div class="d-md-flex justify-content-between align-items-center text-center text-md-start p-4">
            <div class="d-md-flex align-items-center">
                
                <div class="ps-md-3">
                    <h2 class="mb-0 h5"><?= $_SESSION['user']['first_name'] ?></h2>
                    <span class="text-accent fs-sm"><?= $_SESSION['user']['email'] ?></span>
                </div>
            </div>
            <a class="btn btn-primary d-lg-none mb-2 mt-3 mt-md-0" href="#account-menu" data-bs-toggle="collapse" aria-expanded="false"><i class="ci-menu me-2"></i>Account menu</a>
        </div>

        <div class="d-lg-block collapse" id="account-menu">
            <?php if (is_admin()) { ?>
                <div class="bg-secondary px-4 py-3">
                    <h3 class="fs-sm mb-0 text-muted">Admin Dashboard</h3>
                </div>
                <ul class="list-unstyled mb-0">
                    <li class="border-bottom mb-0"><a class="nav-link-style d-flex align-items-center px-4 py-3 text-black" href="admin-products.php"><i class="ci-user opacity-60 me-2"></i>Products</a></li>
                    <li class="border-bottom mb-0"><a class="nav-link-style d-flex align-items-center px-4 py-3 text-black" href="admin-products-add.php"><i class="ci-user opacity-60 me-2"></i>Create product</a></li>
                    <li class="border-bottom mb-0"><a class="nav-link-style d-flex align-items-center px-4 py-3 text-black" href="admin-categories-add.php"><i class="ci-user opacity-60 me-2"></i>Create categories</a></li>
                </ul>
            <?php } ?>
            <?php if (!is_admin()) { ?>
            <div class="bg-secondary px-4 py-3">
                <h3 class="fs-sm mb-0 text-muted">My Account</h3>
            </div>
            <?php } ?>
            <ul class="list-unstyled mb-0">
                <?php if (is_admin()) { ?>
                    <li class="border-bottom mb-0"><a class="nav-link-style d-flex align-items-center px-4 py-3 text-black" href="user-orders.php"><i class="ci-user opacity-60 me-2"></i>User Orders</a></li>

                <?php } ?>
                <?php if (!is_admin()) { ?>
                    <li class="border-bottom mb-0"><a class="nav-link-style d-flex align-items-center px-4 py-3 text-black" href="account-settings.php"><i class="ci-user opacity-60 me-2"></i>Profile Settings</a></li>
                    <li class="border-bottom mb-0"><a class="nav-link-style d-flex align-items-center px-4 py-3 text-black" href="my-orders.php"><i class="ci-user opacity-60 me-2"></i>My Orders</a></li>

                    <?php } ?>
                    <li class="border-bottom mb-0"><a class="nav-link-style d-flex align-items-center px-4 py-3 text-black" href="logout.php"><i class="ci-user opacity-60 me-2"></i>Sign Out</a></li>

            </ul>
        </div>
    </div>
</aside>
