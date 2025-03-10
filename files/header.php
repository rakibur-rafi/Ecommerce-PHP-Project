<?php
require_once('files/functions.php');

$cart = isset($_SESSION['cart']) ? $_SESSION['cart'] : [];
$subtotal = 0;
?>
<!DOCTYPE html>
<html lang="en">
<meta http-equiv="content-type" content="text/html;charset=utf-8" />
<head>
    <meta charset="utf-8">
    <title>Maze</title>
    <!-- SEO Meta Tags-->
    <meta name="description" content="Maze">
    <meta name="keywords" content="bootstrap, shop, e-commerce, market, modern, responsive,  business, mobile, bootstrap, html5, css3, js, gallery, slider, touch, creative, clean">
    <meta name="author" content="Rakibur Rahaman">
    <!-- Viewport-->
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- Favicon and Touch Icons-->
    <link rel="apple-touch-icon" sizes="180x180" href="apple-touch-icon.png">

    <link rel="manifest" href="site.webmanifest">
    <link rel="mask-icon" color="#fe6a6a" href="safari-pinned-tab.svg">
    <meta name="msapplication-TileColor" content="#ffffff">
    <meta name="theme-color" content="#ffffff">
    <!-- Vendor Styles including: Font Icons, Plugins, etc.-->
    <link rel="stylesheet" media="screen" href="vendor/simplebar/dist/simplebar.min.css"/>
    <link rel="stylesheet" media="screen" href="vendor/tiny-slider/dist/tiny-slider.css"/>
    <!-- Main Theme Styles + Bootstrap-->
    <link rel="stylesheet" media="screen" href="css/theme.min.css">
    <link rel="stylesheet" href="css/style.css">
  </head>
  <!-- Body-->

  <body class="handheld-toolbar-enabled">
    <!-- Google Tag Manager (noscript)-->
    <noscript>
      <iframe src="http://www.googletagmanager.com/ns.html?id=GTM-WKV3GT5" height="0" width="0" style="display: none; visibility: hidden;"></iframe>
    </noscript>
    
    <main class="page-wrapper">
      <!-- Navbar 3 Level (Light)-->
      <header class="shadow-sm">
        <!-- Remove "navbar-sticky" class to make navigation bar scrollable with the page.-->
        <div class="navbar-sticky bg-light">
          <div class="navbar navbar-expand-lg navbar-light">
            <div class="container"><a class="navbar-brand d-none d-sm-block flex-shrink-0" href="<?= url('') ?>"><img src="img/Company logo.png" width="142" alt="Cartzilla"></a><a class="navbar-brand d-sm-none flex-shrink-0 me-2" href="index-2.html"><img src="img/Icon.svg" width="24" alt="Cartzilla"></a>
              <div class="input-group d-none d-lg-flex mx-4">
                <input class="form-control rounded-end pe-5" type="text" placeholder="Search for products"><i class="ci-search position-absolute top-50 end-0 translate-middle-y text-muted fs-base me-3"></i>
              </div>
              <div class="navbar-toolbar d-flex flex-shrink-0 align-items-center">
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarCollapse"><span class="navbar-toggler-icon"></span></button><a class="navbar-tool navbar-stuck-toggler" href="#"><span class="navbar-tool-tooltip">Expand menu</span>
              
                  
                  <a class="navbar-tool ms-1 ms-lg-0 me-n1 me-lg-2" 
                  href="<?php echo is_logged_in() ? 'account.php' : 'login.php'; ?>">
                    <div class="navbar-tool-icon-box"><i class="navbar-tool-icon ci-user"></i></div>
                    <div class="navbar-tool-text ms-n3 text-black">
                        <?php if(is_logged_in()) { ?>
                            <small>Hello, <?= htmlspecialchars($_SESSION['user']['first_name']) ?> </small>
                        <?php } else { ?>
                            <small>Log in</small>
                        <?php } ?>
                        My Account
                    </div>
                </a>



                <div class="navbar-tool dropdown ms-3">
                    <a class="navbar-tool-icon-box bg-secondary dropdown-toggle" href="cart.php">
                        <span class="navbar-tool-label text-white"><?php echo count($cart); ?></span>
                        <i class="navbar-tool-icon ci-cart"></i>
                    </a>
                    <a class="navbar-tool-text" href="cart.php">
                        <small>My Cart</small> Tk
                        <?php
                        foreach ($cart as $item) {
                            $subtotal += $item['price'] * $item['quantity'];
                        }
                        echo number_format($subtotal);
                        ?>
                    </a>

                    <!-- Cart dropdown -->
                    <div class="dropdown-menu dropdown-menu-end">
                        <div class="widget widget-cart px-3 pt-2 pb-3" style="width: 20rem;">
                            <div style="height: 15rem;" data-simplebar data-simplebar-auto-hide="false">
                                <?php if (!empty($cart)): ?>
                                    <?php foreach ($cart as $item): ?>
                                        <div class="widget-cart-item py-2 border-bottom">
                                            <div class="d-flex align-items-center">
                                                <a class="flex-shrink-0" href="product.php?id=<?php echo $item['id']; ?> ">
                                                    <img src="<?php echo $item['image']; ?>" width="64" alt="Product">
                                                </a>
                                                <div class="ps-2">
                                                    <h6 class="widget-product-title">
                                                        <a href="product.php?id=<?php echo $item['id']; ?>">
                                                            <?php echo htmlspecialchars($item['name']); ?>
                                                        </a>
                                                    </h6>
                                                    <div class="widget-product-meta">
                                                        <span class="text-accent me-2">Tk<?php echo number_format($item['price']); ?></span>
                                                        <span class="text-muted">x <?php echo $item['quantity']; ?></span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <p class="text-center py-3">Your cart is empty.</p>
                                <?php endif; ?>
                            </div>

                            <div class="d-flex flex-wrap justify-content-between align-items-center py-3">
                                <div class="fs-sm me-2 py-2">
                                    <span class="text-muted">Subtotal:</span>
                                    <span class="text-accent fs-base ms-1">Tk<?php echo number_format($subtotal); ?></span>
                                </div>
                                <a class="btn btn-primary btn-sm" href="cart.php">
                                    Expand cart <i class="ci-arrow-right ms-1 me-n1"></i>
                                </a>
                            </div>
                            
                        </div>
                    </div>
                </div>
              </div>
            </div>
          </div>
          <div class="navbar navbar-expand-lg navbar-light navbar-stuck-menu mt-n2 pt-0 pb-2">
            <div class="container">
              <div class="collapse navbar-collapse" id="navbarCollapse">
                <!-- Search-->
                <div class="input-group d-lg-none my-3"><i class="ci-search position-absolute top-50 start-0 translate-middle-y text-muted fs-base ms-3"></i>
                  <input class="form-control rounded-start" type="text" placeholder="Search for products">
                </div>
                <!-- Departments menu-->
                <ul class="navbar-nav navbar-mega-nav pe-lg-2 me-lg-2">
                  <li class="nav-item dropdown"><a class="nav-link dropdown-toggle ps-lg-0 text-black" href="#" data-bs-toggle="dropdown"><i class="ci-view-grid me-2"></i>All Categories</a>
                    <div class="dropdown-menu px-2 pb-4">
                      <div class="d-flex flex-wrap flex-sm-nowrap">
                        <div class="mega-dropdown-column pt-3 pt-sm-4 px-2 px-lg-3">
                          <div class="widget widget-links">
                            <h6 class="fs-base mb-2">Men</h6>
                            <ul class="widget-list">
                              <li class="widget-list-item mb-1"><a class="widget-list-link" href="category-shop.php?category_id=108">T-shirts</a></li>
                              <li class="widget-list-item mb-1"><a class="widget-list-link" href="category-shop.php?category_id=109">Pants</a></li>
                              <li class="widget-list-item mb-1"><a class="widget-list-link" href="category-shop.php?category_id=111">Shirts</a></li>
                              <li class="widget-list-item mb-1"><a class="widget-list-link" href="category-shop.php?category_id=110">Full-Sleeve Shirts</a></li>
                            </ul>
                          </div>
                        </div>
                        <div class="mega-dropdown-column pt-3 pt-sm-4 px-2 px-lg-3">
                          <div class="widget widget-links">
                            <h6 class="fs-base mb-2">Women</h6>
                            <ul class="widget-list">
                              <li class="widget-list-item mb-1"><a class="widget-list-link" href="category-shop.php?category_id=112">T-shirts</a></li>
                              <li class="widget-list-item mb-1"><a class="widget-list-link" href="category-shop.php?category_id=113">Tops</a></li>
                              <li class="widget-list-item mb-1"><a class="widget-list-link" href="category-shop.php?category_id=114">Pants</a></li>
                              <li class="widget-list-item mb-1"><a class="widget-list-link" href="category-shop.php?category_id=115">Knitwears</a></li>
                            </ul>
                          </div>
                        </div>
                        <div class="mega-dropdown-column pt-3 pt-sm-4 px-2 px-lg-3">
                          <div class="widget widget-links">
                            <h6 class="fs-base mb-2">Shoes</h6>
                            <ul class="widget-list">
                              <li class="widget-list-item mb-1"><a class="widget-list-link" href="category-shop.php?category_id=105">Sneakers</a></li>
                              <li class="widget-list-item mb-1"><a class="widget-list-link" href="category-shop.php?category_id=106">Loafers</a></li>
                              <li class="widget-list-item mb-1"><a class="widget-list-link" href="category-shop.php?category_id=107">Slides</a></li>
                            </ul>
                          </div>
                        </div>
                        <div class="mega-dropdown-column pt-3 pt-sm-4 px-2 px-lg-3">
                          <div class="widget widget-links">
                            <h6 class="fs-base mb-2">Accessories</h6>
                            <ul class="widget-list">
                              <li class="widget-list-item mb-1"><a class="widget-list-link" href="category-shop.php?category_id=116">Belts</a></li>
                              <li class="widget-list-item mb-1"><a class="widget-list-link" href="category-shop.php?category_id=117">Wallets</a></li>
                            </ul>
                          </div>
                        </div>
                        
                      </div>
                    
                    </div>
                  </li>
                </ul>
                <!-- Primary menu-->
                <ul class="navbar-nav">
                  <li class="nav-item dropdown active"><a class="nav-link text-black" href="<?= url('') ?>">Home</a>
                  </li>
                  <li class="nav-item dropdown"><a class="nav-link text-black" href="shop.php">Shop</a>
                  </li>
                  <li class="nav-item dropdown"><a class="nav-link text-black" href="account.php">Account</a>
                   
                      </li>
                      
                    </ul>
                  </li>
                  
                  
                 
                </ul>
              </div>
            </div>
          </div>
        </div>
      </header>

      <?php
        if (isset($_SESSION['alert'])) {

        

      ?>
        <div class="container pt-5">
          <div class="alert alert-<?= $_SESSION['alert']['type'] ?>">
              <?= $_SESSION['alert']['msg'] ?>
          </div>
        </div>
      <?php unset($_SESSION['alert']);
        }
      ?>
      