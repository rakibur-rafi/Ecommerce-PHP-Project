<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
define('BASE_URL', 'http://localhost/ecommerce');
$conn= new mysqli('localhost','root','','ecommerce');
function db_insert($table, $data) {
    global $conn;
    
    $columns = implode(", ", array_keys($data));
    $placeholders = implode(", ", array_fill(0, count($data), "?"));
    
    $sql = "INSERT INTO $table ($columns) VALUES ($placeholders)";
    
    $stmt = $conn->prepare($sql);
    $types = str_repeat("s", count($data)); // Assuming all values are strings
    $stmt->bind_param($types, ...array_values($data));
    
    if ($stmt->execute()) {
        return $stmt->insert_id;
    } else {
        return false;
    }
}

function db_select($table, $condition = null, $order_by = null, $join = null) {
    $sql = "SELECT * FROM $table";

    if ($join != null) {
        $sql .= " " . $join;
    }

    if ($condition != null) {
        $sql .= " WHERE $condition";
    }

    if ($order_by != null) {
        $sql .= " $order_by";
    }

    global $conn;
    $res = $conn->query($sql);
    $rows = [];

    while ($row = $res->fetch_assoc()) {
        $rows[] = $row;
    }

    return $rows;
}
function db_update($table, $data, $condition) {
    global $conn;
    
    $set = [];
    foreach ($data as $column => $value) {
        $value = $conn->real_escape_string($value);
        $set[] = "$column = '$value'";
    }

    $sql = "UPDATE $table SET " . implode(', ', $set) . " WHERE $condition";
    
    return $conn->query($sql);
}
function db_delete($table, $condition) {
    global $conn;
    $sql = "DELETE FROM $table WHERE $condition";
    
    return $conn->query($sql);
}
function upload_and_save_images($files, $product_id, $conn) {
    $uploaded_images = [];

    foreach ($files as $key => $file) {
        if ($file['error'] == 0) {
            $file_name = time() . "_" . basename($file["name"]);
            $target_file = "uploads/" . $file_name;
            
            if (move_uploaded_file($file["tmp_name"], $target_file)) {
                $stmt = $conn->prepare("INSERT INTO product_images (product_id, src) VALUES (?, ?)");
                $stmt->bind_param("is", $product_id, $target_file);
                $stmt->execute();
                $stmt->close();
                $uploaded_images[] = $target_file;
            }
        }
    }

    return $uploaded_images;
}
function upload_image($file) {
    $target_dir = "uploads/";

    $allowed_types = ['jpg', 'jpeg', 'png'];
    $file_ext = strtolower(pathinfo($file["name"], PATHINFO_EXTENSION));

    if (!in_array($file_ext, $allowed_types)) {
        return null;
    }

    $file_name = time() . "_" . uniqid() . "." . $file_ext;
    $target_file = $target_dir . $file_name;

    if (move_uploaded_file($file["tmp_name"], $target_file)) {
        return $target_file; 
    } else {
        return null; 
    }
}
function url($path = "/"){
    return BASE_URL.$path;
}
function protected_area() {
    if (!isset($_SESSION['user'])) {
        alert('warning','Unauthorized access, Log in before your proceed');
        header('Location: login.php');
        die();
    }
}
function logout() {
    if(isset($_SESSION['user'])){
        unset( $_SESSION['user'] );
    }
    alert('success','Logged out');
    header('Location: login.php');
    die();

}
function is_admin() {
    return isset($_SESSION['user']) && $_SESSION['user']['user_type'] === "admin";
}
function is_logged_in() {
    if(isset($_SESSION['user'])){
        return true;
    } else{
        return false;
    }
}
function alert($type, $msg) {
    $_SESSION['alert']['type']= $type;
    $_SESSION['alert']['msg']= $msg;
}
function login_user($email,$password){

    global $conn;
    $sql = "SELECT * FROM users WHERE email = '{$email}'";
    $res = $conn->query(query: $sql);

    if ($res->num_rows < 1) {
        return false;
    }

    $row = $res->fetch_assoc();

    if(!password_verify($password,$row["password"])){
        return false;
    }

    $_SESSION['user'] = $row;
    
    return true;
}
function text_input($data){
    $name = isset( $data['name'] ) ? $data['name'] :"";
    $attributes = isset( $data['attributes'] ) ? $data['attributes'] :"";
    $value = "";
    $error = "";
    $error_text = "";
    if (isset($_SESSION['form'])){
        if (isset($_SESSION['form']['value'])){
            if (isset($_SESSION['form']['value'][$name])){
                $value = $_SESSION['form']['value'][$name];
            }
            
        }
    }
    if (isset($_SESSION['form'])){
        if (isset($_SESSION['form']['error'])){
            if (isset($_SESSION['form']['error'][$name])){
                $error = $_SESSION['form']['error'][$name];
                $error_text = '<div class="form-text text-danger">'.$error.'</div>';
            }
            
        }
    }
    $label = isset( $data['label'] ) ? $data['label'] : $name;
    $value = isset( $data['value'] ) ? $data['value'] : $value;
    $error = isset( $data['error'] ) ? $data['error'] : $error;
    return
    '<label class="form-label text-capitalize" for="'.$name.'">'.$label.'</label>
    <input name="'.$name.'" value="'.$value.'" class="form-control" type="text" id="'.$name.'" placeholder="'.$label.'" '.$attributes.'>'.$error_text;
}
function select_input($data,$options){
    $name = isset( $data['name'] ) ? $data['name'] :"";
    $attributes = isset( $data['attributes'] ) ? $data['attributes'] :"";
    $value = "";
    $error = "";
    $error_text = "";
    if (isset($_SESSION['form'])){
        if (isset($_SESSION['form']['value'])){
            if (isset($_SESSION['form']['value'][$name])){
                $value = $_SESSION['form']['value'][$name];
            }
            
        }
    }
    if (isset($_SESSION['form'])){
        if (isset($_SESSION['form']['error'])){
            if (isset($_SESSION['form']['error'][$name])){
                $error = $_SESSION['form']['error'][$name];
                $error_text = '<div class="form-text text-danger">'.$error.'</div>';
            }
            
        }
    }
    $label = isset( $data['label'] ) ? $data['label'] : $name;
    $value = isset( $data['value'] ) ? $data['value'] : $value;
    
    

    $select_options = ""; 
    $selected = "";
    foreach ($options as $key => $val) {
        $selected = ($key == $value) ? 'selected' : '';
        $select_options .= '<option value="' . $key . '" ' . $selected . '>' . $val . '</option>';
    }
    
    $select_tag = '<select name="' . $name . '" '. $selected .' class="form-control" id="' . $name . '" ' . $attributes . '>'
        . $select_options . '</select>';

    return '<label class="form-label text-capitalize" for="' . $name . '">' . $label . '</label>' . $select_tag . $error_text;

}
function get_product_image($product_id, $conn) {
    $sql = "SELECT src FROM product_images WHERE product_id = $product_id LIMIT 1";
    $res = $conn->query($sql);

    if ($res && $row = $res->fetch_assoc()) {
        return $row['src'];
    }

    return "assets/no_image.jpg";
}
function product_photos($product_id, $conn) {
    $photos = [['src' => "assets/no_image.jpg"]];
    $stmt = $conn->prepare("SELECT src FROM product_images WHERE product_id = ?");
    $stmt->bind_param("i", $product_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $photos = [];
        while ($row = $result->fetch_assoc()) {
            $photos[] = ['src' => $row['src']];
        }
    }

    return $photos;
}
function product_item_ui1($pro, $image) {
    
    $str = <<<EOF
    <div class="col-lg-3 col-md-4 col-sm-6 px-2 mb-4">
        <div class="card product-card">
            <a class="card-img-top d-block overflow-hidden" href="product.php?id={$pro['products_id']}">
                <img src="{$image}" alt="Product">
            </a>
            <div class="card-body py-2">
                <div class="product-meta d-block fs-xs pb-1">{$pro['color']}</div>
                <h3 class="product-title fs-sm"><a href="product.php?id={$pro['products_id']}">{$pro['name']}</a></h3>
                <div class="d-flex justify-content-between">
                    <div class="product-price"><span class="text-accent">{$pro['price']}<small> Tk</small></span></div>
                    <div class="star-rating">
                        <i class="star-rating-icon ci-star-filled active"></i>
                        <i class="star-rating-icon ci-star-filled active"></i>
                        <i class="star-rating-icon ci-star-filled active"></i>
                        <i class="star-rating-icon ci-star-filled active"></i>
                        <i class="star-rating-icon ci-star"></i>
                    </div>
                </div>
            </div>
        </div>
        <hr class="d-sm-none">
    </div>
    EOF;
    return $str;
}
function get_product($id){
    $sql = "SELECT * FROM products WHERE products.products_id = $id";
    
    global $conn;
    $data['pro'] = $conn->query($sql)->fetch_assoc();
    $data['cat'] = null;
    if ($data['pro'] != null){
        $cat_id = $data['pro']['category_id'];
        $sql = "SELECT * FROM categories WHERE category_id = $cat_id";
        $data["cat"] = $conn->query($sql)->fetch_assoc();
    }
    return $data;

}
function cart(){
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['product_id'])) {
    $product_id = (int)$_POST['product_id'];
    $product_name = $_POST['product_name'];
    $product_image = $_POST['product_image'];
    $color = $_POST['color'];
    $size = $_POST['size'];
    $price = (float)$_POST['price'];
    $quantity = (int)$_POST['quantity'];

    $found = false;
    foreach ($_SESSION['cart'] as &$item) {
        if ($item['id'] == $product_id && $item['size'] == $size) {
            $item['quantity'] += $quantity;
            $found = true;
            break;
        }
    }

    if (!$found) {
        $_SESSION['cart'][] = [
            'id' => $product_id,
            'name' => $product_name,
            'image' => $product_image,
            'color' => $color,
            'size' => $size,
            'price' => $price,
            'quantity' => $quantity
        ];
    }

    header("Location: cart.php");
    exit;
}

}
