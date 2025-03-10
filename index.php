<?php
require_once('files/functions.php');
require_once('files/header.php');
?>


     <!-- Hero slider-->
      <section class="tns-carousel tns-controls-lg mb-4 mb-lg-5">
        <div class="tns-carousel-inner" data-carousel-options="{&quot;mode&quot;: &quot;gallery&quot;, &quot;responsive&quot;: {&quot;0&quot;:{&quot;nav&quot;:true, &quot;controls&quot;: false},&quot;992&quot;:{&quot;nav&quot;:false, &quot;controls&quot;: true}}}">
          <!-- Item-->
          <div class="px-lg-5">
            <img class="d-block w-100" src="img/images/slider-1.png" alt="Women Sportswear">
          </div>
          <div class="px-lg-5">
            <img class="d-block w-100" src="img/images/slider-2.png" alt="Women Sportswear">
          </div>
          <div class="px-lg-5">
            <img class="d-block w-100" src="img/images/slider-3.png" alt="Women Sportswear">
          </div>
        </div>
        
      </section>
      <?php
      $query_sneakers = " SELECT p.products_id, p.name, 
       GROUP_CONCAT(DISTINCT pi.src ORDER BY pi.src LIMIT 1) AS image, 
       p.price 
       FROM products p 
       JOIN categories c ON p.category_id = c.category_id 
       LEFT JOIN product_images pi ON p.products_id = pi.product_id 
       WHERE c.name = 'Sneakers' 
       GROUP BY p.products_id 
       ORDER BY p.products_id DESC LIMIT 3

    ";
    $stmt = $conn->prepare($query_sneakers);
    $stmt->execute();
    $result_sneakers = $stmt->get_result();
    $sneaker_products = $result_sneakers->fetch_all(MYSQLI_ASSOC);
    $stmt->close();

    $query_w = " SELECT p.products_id, p.name, GROUP_CONCAT(DISTINCT pi.src ORDER BY pi.src LIMIT 1) AS image, p.price  
    FROM products p 
    JOIN categories c ON p.category_id = c.category_id 
    LEFT JOIN product_images pi ON p.products_id = pi.product_id 
    WHERE c.name = 'Wallets' 
    GROUP BY p.products_id 
    ORDER BY p.products_id DESC LIMIT 3
    ";  
    $stmt = $conn->prepare($query_w);
    $stmt->execute();
    $result_w = $stmt->get_result();
    $w_products = $result_w->fetch_all(MYSQLI_ASSOC);
    $stmt->close();

    $query_slides = " SELECT p.products_id, p.name, GROUP_CONCAT(DISTINCT pi.src ORDER BY pi.src LIMIT 1) AS image, p.price  
    FROM products p 
    JOIN categories c ON p.category_id = c.category_id 
    LEFT JOIN product_images pi ON p.products_id = pi.product_id 
    WHERE c.name = 'Slides' 
    GROUP BY p.products_id 
    ORDER BY p.products_id DESC LIMIT 3
    ";  
    $stmt = $conn->prepare($query_slides);
    $stmt->execute();
    $result_slides = $stmt->get_result();
    $slides_products = $result_slides->fetch_all(MYSQLI_ASSOC);
    $stmt->close();
?>

<!-- Product widgets -->
<section class="container pt-md-3 pb-4 pb-md-5 mb-lg-2">
    <div class="row">
        <!-- New Arrivals - Sneakers -->
        <div class="col-lg-4 col-md-6 mb-2 py-3">
            <div class="widget">
                <h3 class="widget-title">New Arrivals - Sneakers</h3>
                
                <?php if (!empty($sneaker_products)) { ?>
                    <?php foreach ($sneaker_products as $product) { ?>
                        <div class="d-flex align-items-center py-2 border-bottom">
                            <a class="d-block" href="product.php?id=<?php echo $product['products_id']; ?>">
                                <img src="<?php echo $product['image']; ?>" width="64" alt="Product">
                            </a>
                            <div class="ps-2">
                                <h6 class="widget-product-title">
                                    <a href="product.php?id=<?php echo $product['products_id']; ?>">
                                        <?php echo $product['name']; ?>
                                    </a>
                                </h6>
                                <div class="widget-product-meta">
                                    <span class="text-accent me-2">
                                        $<?php echo number_format($product['price'], 2); ?>
                                    </span>
                                </div>
                            </div>
                        </div>
                    <?php } ?>
                <?php } else { ?>
                    <p>No new arrivals found in the Sneakers category.</p>
                <?php } ?>

                <p class="mb-0">...</p>
                <a class="fs-sm" href="shop-grid-ls.html">View more<i class="ci-arrow-right fs-xs ms-1"></i></a>
            </div>
        </div>
         <!-- New Arrivals - Sneakers -->
         <div class="col-lg-4 col-md-6 mb-2 py-3">
            <div class="widget">
                <h3 class="widget-title">New Arrivals - Slides</h3>
                
                <?php if (!empty($slides_products)) { ?>
                    <?php foreach ($slides_products as $product) { ?>
                        <div class="d-flex align-items-center py-2 border-bottom">
                            <a class="d-block" href="product.php?id=<?php echo $product['products_id']; ?>">
                                <img src="<?php echo $product['image']; ?>" width="64" alt="Product">
                            </a>
                            <div class="ps-2">
                                <h6 class="widget-product-title">
                                    <a href="product.php?id=<?php echo $product['products_id']; ?>">
                                        <?php echo $product['name']; ?>
                                    </a>
                                </h6>
                                <div class="widget-product-meta">
                                    <span class="text-accent me-2">
                                        $<?php echo number_format($product['price'], 2); ?>
                                    </span>
                                </div>
                            </div>
                        </div>
                    <?php } ?>
                <?php } else { ?>
                    <p>No new arrivals found in the Sneakers category.</p>
                <?php } ?>

                <p class="mb-0">...</p>
                <a class="fs-sm" href="shop-grid-ls.html">View more<i class="ci-arrow-right fs-xs ms-1"></i></a>
            </div>
        </div>

        <!-- New Arrivals - Watch -->
        <div class="col-lg-4 col-md-6 mb-2 py-3">
            <div class="widget">
                <h3 class="widget-title">New Arrivals - Wallets</h3>
                
                <?php if (!empty($w_products)) { ?>
                    <?php foreach ($w_products as $product) { ?>
                        <div class="d-flex align-items-center py-2 border-bottom">
                            <a class="d-block" href="product.php?id=<?php echo $product['products_id']; ?>">
                                <img src="<?php echo $product['image']; ?>" width="64" alt="Product">
                            </a>
                            <div class="ps-2">
                                <h6 class="widget-product-title">
                                    <a href="product.php?id=<?php echo $product['products_id']; ?>">
                                        <?php echo $product['name']; ?>
                                    </a>
                                </h6>
                                <div class="widget-product-meta">
                                    <span class="text-accent me-2">
                                        $<?php echo number_format($product['price'], 2); ?>
                                    </span>
                                </div>
                            </div>
                        </div>
                    <?php } ?>
                <?php } else { ?>
                    <p>No new arrivals found in the Watch category.</p>
                <?php } ?>

                <p class="mb-0">...</p>
                <a class="fs-sm" href="shop-grid-ls.html">View more<i class="ci-arrow-right fs-xs ms-1"></i></a>
            </div>
        </div>
    </div>
</section>
    </div>
</section>


      <!-- Shop by brand-->
      <section class="container py-lg-4">
        <h2 class="h3 text-center pb-4">Brands</h2>
        <div class="row">
          <div class="col-md-3 col-sm-4 col-6"><a class="d-block bg-white shadow-sm rounded-3 py-3 py-sm-4 mb-grid-gutter" href="#"><img class="d-block mx-auto" src="img/shop/brands/01.png" style="width: 150px;" alt="Brand"></a></div>
          <div class="col-md-3 col-sm-4 col-6"><a class="d-block bg-white shadow-sm rounded-3 py-3 py-sm-4 mb-grid-gutter" href="#"><img class="d-block mx-auto" src="img/shop/brands/02.png" style="width: 150px;" alt="Brand"></a></div>
          <div class="col-md-3 col-sm-4 col-6"><a class="d-block bg-white shadow-sm rounded-3 py-3 py-sm-4 mb-grid-gutter" href="#"><img class="d-block mx-auto" src="img/shop/brands/03.png" style="width: 150px;" alt="Brand"></a></div>
          <div class="col-md-3 col-sm-4 col-6"><a class="d-block bg-white shadow-sm rounded-3 py-3 py-sm-4 mb-grid-gutter" href="#"><img class="d-block mx-auto" src="img/shop/brands/04.png" style="width: 150px;" alt="Brand"></a></div>
          <div class="col-md-3 col-sm-4 col-6"><a class="d-block bg-white shadow-sm rounded-3 py-3 py-sm-4 mb-grid-gutter" href="#"><img class="d-block mx-auto" src="img/shop/brands/05.png" style="width: 150px;" alt="Brand"></a></div>
          <div class="col-md-3 col-sm-4 col-6"><a class="d-block bg-white shadow-sm rounded-3 py-3 py-sm-4 mb-grid-gutter" href="#"><img class="d-block mx-auto" src="img/shop/brands/06.png" style="width: 150px;" alt="Brand"></a></div>
          <div class="col-md-3 col-sm-4 col-6"><a class="d-block bg-white shadow-sm rounded-3 py-3 py-sm-4 mb-grid-gutter" href="#"><img class="d-block mx-auto" src="img/shop/brands/07.png" style="width: 150px;" alt="Brand"></a></div>
          <div class="col-md-3 col-sm-4 col-6"><a class="d-block bg-white shadow-sm rounded-3 py-3 py-sm-4 mb-grid-gutter" href="#"><img class="d-block mx-auto" src="img/shop/brands/08.png" style="width: 150px;" alt="Brand"></a></div>
          <div class="col-md-3 col-sm-4 col-6"><a class="d-block bg-white shadow-sm rounded-3 py-3 py-sm-4 mb-grid-gutter" href="#"><img class="d-block mx-auto" src="img/shop/brands/09.png" style="width: 150px;" alt="Brand"></a></div>
          <div class="col-md-3 col-sm-4 col-6"><a class="d-block bg-white shadow-sm rounded-3 py-3 py-sm-4 mb-grid-gutter" href="#"><img class="d-block mx-auto" src="img/shop/brands/10.png" style="width: 150px;" alt="Brand"></a></div>
          <div class="col-md-3 col-sm-4 col-6"><a class="d-block bg-white shadow-sm rounded-3 py-3 py-sm-4 mb-grid-gutter" href="#"><img class="d-block mx-auto" src="img/shop/brands/11.png" style="width: 150px;" alt="Brand"></a></div>
          <div class="col-md-3 col-sm-4 col-6"><a class="d-block bg-white shadow-sm rounded-3 py-3 py-sm-4 mb-grid-gutter" href="#"><img class="d-block mx-auto" src="img/shop/brands/12.png" style="width: 150px;" alt="Brand"></a></div>
        </div>
      </section>
 

      
<?php
require_once('files/footer.php');
?>