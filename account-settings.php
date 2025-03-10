<?php
require_once('files/functions.php'); 
if (!isset($_SESSION['user'])) {
    header("Location: login.php"); 
    exit();
}
$user_id = $_SESSION['user']['id']; 
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['delete_profile'])) {
        db_delete('users', "id = $user_id"); 
        session_destroy();
        header("Location: login.php");
        exit();
    } else {
        $profile_data = [
            'first_name' => $_POST['first_name'],
            'last_name'  => $_POST['last_name'],
            'username'   => $_POST['username'],
            'phone_number' => $_POST['phone_number'],
            'address'    => $_POST['address']
        ];

        $update_result = db_update('users', $profile_data, "id = $user_id");

        if ($update_result) {
            alert('success', 'Profile updated successfully.');
        } else {
            alert('danger', 'Error updating profile.');
        }
    }
}

$user = db_select('users', "id = $user_id");

if (!$user || empty($user)) {
    echo "User not found.";
    exit();
}

$user = $user[0]; 

require_once('files/header.php');
?>
<div class="page-title-overlap bg-dark pt-4">
    <div class="container d-lg-flex justify-content-between py-2 py-lg-3">
        <div class="order-lg-2 mb-3 mb-lg-0 pt-lg-2">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb breadcrumb-light flex-lg-nowrap justify-content-center justify-content-lg-start">
                    <li class="breadcrumb-item"><a class="text-nowrap" href="index-2.html"><i class="ci-home"></i>Home</a></li>
                    <li class="breadcrumb-item text-nowrap"><a href="#">Account</a></li>
                    <li class="breadcrumb-item text-nowrap active" aria-current="page">My Account</li>
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
        <!-- Sidebar-->
        <?php require_once('files/account-sidebar.php') ?>
        <!-- Content  -->
        <section class="col-lg-8 pt-lg-4 pb-4 mb-3">
            <div class="pt-2 px-4 ps-lg-0 pe-xl-5">
                <!-- Title-->
                <div class="d-sm-flex flex-wrap justify-content-between align-items-center pb-2">
                    <h2 class="h3 py-2 me-2 text-center text-sm-start">Profile Information</h2>
                </div>

                <!-- Display Update Message -->
                <?php if (isset($update_message)): ?>
                    <div class="alert alert-<?php echo $message_type; ?>" role="alert">
                        <?php echo $update_message; ?>
                    </div>
                <?php endif; ?>

                <!-- Profile Form -->
                <form method="POST" action="">
                    

                    <div class="row gx-4 gy-3">
                        <div class="col-sm-6">
                            <label class="form-label" for="account-fn">First Name</label>
                            <input class="form-control" type="text" id="account-fn" name="first_name" value="<?php echo htmlspecialchars($user['first_name']); ?>">
                        </div>
                        <div class="col-sm-6">
                            <label class="form-label" for="account-ln">Last Name</label>
                            <input class="form-control" type="text" id="account-ln" name="last_name" value="<?php echo htmlspecialchars($user['last_name']); ?>">
                        </div>
                        <div class="col-sm-6">
                            <label class="form-label" for="account-username">Username</label>
                            <input class="form-control" type="text" id="account-username" name="username" value="<?php echo htmlspecialchars($user['username']); ?>">
                        </div>
                        <div class="col-sm-6">
                            <label class="form-label" for="account-phone">Phone Number</label>
                            <input class="form-control" type="text" id="account-phone" name="phone_number" value="<?php echo htmlspecialchars($user['phone_number']); ?>" required>
                        </div>
                        <div class="col-sm-12">
                            <label class="form-label" for="account-email">Email Address</label>
                            <input class="form-control" type="email" id="account-email" name="email" value="<?php echo htmlspecialchars($user['email']); ?>" disabled>
                        </div>
                        <div class="col-12">
                            <label class="form-label" for="account-address">Address</label>
                            <input class="form-control" type="text" id="account-address" name="address" value="<?php echo htmlspecialchars($user['address']); ?>">
                        </div>
                        <div class="col-12">
                            <hr class="mt-2 mb-3">
                            <div class="d-flex flex-wrap justify-content-between align-items-center">
                                <button class="btn btn-primary mt-3 mt-sm-0" type="submit">Update Profile</button>
                                <!-- Delete Profile Button -->
                                <button class="btn btn-danger mt-3" type="submit" name="delete_profile">Delete Profile</button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </section>
    </div>
</div>

<?php require_once('files/footer.php'); ?>
