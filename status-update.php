<?php
require_once("files/functions.php");

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["o_id"]) && isset($_POST["status"])) {
    $o_id = intval($_POST["o_id"]);
    $status = $_POST["status"];

    $query = "UPDATE orders SET status = ? WHERE o_id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("si", $status, $o_id);

    if ($stmt->execute()) {
        echo "success";
    } else {
        echo "error";
    }

    $stmt->close();
} else {
    echo "invalid";
}
?>
