<?php
require_once('files/functions.php');
protected_area();
if (!is_admin()) {
    header("Location: index.php");
    exit();
}

$rows = db_select('categories', 'parent_id = 0');
$categories = [];
$categories[0] = 'No parent';
foreach ($rows as $row) {
    $categories[$row['category_id']] = $row['name'];
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $_SESSION['form']['value'] = $_POST;
    $data['name'] = $_POST['name'];
    $data['parent_id'] = (int)$_POST['parent_id'];
    $data['description'] = $_POST['description'];

    if (db_insert('categories', $data)) {
        alert('success', 'Created category successfully.');
        header('Location: account.php');
        unset($_SESSION['form']);
    } else {
        alert('danger', 'Failed to create a category, please try again.');
        header('Location: admin-categories-add.php');
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
                    <li class="breadcrumb-item text-nowrap active" aria-current="page">Product Categories</li>
                </ol>
            </nav>
        </div>
        <div class="order-lg-1 pe-lg-4 text-center text-lg-start">
            <h1 class="h3 text-light mb-0">Product Categories</h1>
        </div>
    </div>
</div>
<div class="container pb-5 mb-2 mb-md-4">
    <div class="row">
        <?php require_once('files/account-sidebar.php') ?>
        <section class="col-lg-8 pt-lg-4 pb-4 mb-3">
            <div class="pt-2 px-4 ps-lg-0 pe-xl-5">
                <div class="d-sm-flex flex-wrap justify-content-between align-items-center pb-2">
                    <h2 class="h3 py-2 me-2 text-center text-sm-start">Add New Category</h2>
                </div>
                <form action="admin-categories-add.php" method="POST" enctype="multipart/form-data">
                    <div class="mb-3 pb-2">
                        <?= text_input(['name' => 'name']) ?>
                        <div class="row">
                            <div class="col-md-6 mt-4">
                                <div class="form-group">
                                    <?= select_input(['name' => 'parent_id', 'label' => 'Parent Category'], $categories) ?>
                                </div>
                            </div>
            
                        </div>
                        <div class="row">
                            <div class="col-12 mt-4">
                                <div class="form-group">
                                    <label for="description">Description</label>
                                    <textarea class="form-control" name="description" id="description"></textarea>
                                </div>
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
