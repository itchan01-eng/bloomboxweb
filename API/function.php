<?php
session_start();
include_once("conn.php");

if (!isset($_SESSION["user_id"])) {
    echo json_encode(["success" => false, "message" => "User not logged in"]);
    exit;
}

$userId = $_SESSION["user_id"];
$type   = $_GET["type"] ?? "";
$action = $_GET["action"] ?? "";

if ($type === "cart") {
    if ($action === "totalorders") {
        getCart($userId);
    } elseif ($action === "add" && isset($_POST["item_id"], $_POST["quantity"])) {
        addToCart($userId, $_POST["item_id"], $_POST["quantity"]);
    } elseif ($action === "remove" && isset($_POST["item_id"])) {
        removeFromCart($userId, $_POST["item_id"]);
    } elseif ($action === "orders") {
        addToOrders($userId, $_POST["fname"], $_POST["lname"], $_POST["phonenumber"], $_POST["barangay"], $_POST["fulladdress"], $_POST["notes"], $_POST["paymentmethod"]);
    }
}
exit;

function addToOrders($userId, $firstname, $lastname, $phonenumber, $barangay, $fulladdress, $notes, $paymentmethod) {
    global $conn;

    $result = $conn->query("
        SELECT * 
        FROM cart c
        JOIN items i ON c.item_id = i.item_id
        WHERE c.user_id = '$userId'
    ");
    if ($result->num_rows == 0) {
        echo json_encode(["success" => false, "message" => "Your cart is empty."]);
        return;
    }

    $items = [];
    while ($row = $result->fetch_assoc()) {
        $items[] = [
            "item_id"       => $row["item_id"],
            "item_name"     => $row["item_name"],
            "price"    => $row["item_price"],
            "quantity" => $row["quantity"],
            "image_url"     => $row["item_img"]
        ];
    }

    $items_json = $conn->real_escape_string(json_encode($items));
    $order_id = random_int(100000000, 999999999);
    $dateToday = date("F j, Y");

    $query = "
        INSERT INTO orders 
        (order_id, user_id, fname, lname, phonenumber, barangay, full_address, notes, payment_method, items_ordered, date_ordered, status) 
        VALUES 
        ('$order_id', '$userId', '$firstname', '$lastname', '$phonenumber', '$barangay', '$fulladdress', '$notes', '$paymentmethod', '$items_json', '$dateToday', 'pending')
    ";

    if ($conn->query($query)) {
        $conn->query("DELETE FROM cart WHERE user_id = '$userId'");
        echo json_encode([
            "success" => true,
            "message" => "Successfully added to your orders. You may now check your orders page for monitoring."
        ]);
    } else {
        echo json_encode([
            "success" => false,
            "message" => "Failed to add your order. Please try again later."
        ]);
    }
}



function removeFromCart($userId, $itemId) {
    global $conn;

    $sql = "DELETE FROM cart WHERE user_id='$userId' AND item_id='$itemId'";
    $result = $conn->query($sql);

    if ($result) {
        echo json_encode([
            "success" => true,
            "message" => "Item removed from cart."
        ]);
    } else {
        echo json_encode([
            "success" => false,
            "message" => "Failed to remove item."
        ]);
    }
}


function addToCart($userId, $itemId, $quantity) {
    global $conn;

    $check = $conn->query("SELECT quantity FROM cart WHERE user_id='$userId' AND item_id='$itemId' LIMIT 1");

    if ($check && $check->num_rows > 0) {
        $newQty = $check->fetch_assoc()["quantity"] + $quantity;
        $query = "UPDATE cart SET quantity='$newQty' WHERE user_id='$userId' AND item_id='$itemId'";
    } else {
        $cartId = random_int(1000000, 9999999);
        $query  = "INSERT INTO cart (cart_id, user_id, item_id, quantity) VALUES ('$cartId', '$userId', '$itemId', '$quantity')";
    }

    $success = $conn->query($query);

    echo json_encode([
        "success" => $success,
        "message" => $success ? "Successfully added to cart." : "Failed to add to cart. Please try again."
    ]);
}

function getCart($userId) {
    global $conn;

    $result = $conn->query("
        SELECT i.item_id, i.item_name, i.item_price, i.item_img, c.quantity 
        FROM cart c 
        JOIN items i ON c.item_id = i.item_id 
        WHERE c.user_id = '$userId'
    ");

    $cartItems = [];
    while ($row = $result->fetch_assoc()) {
        $cartItems[] = [
            "item_id"       => $row["item_id"],
            "item_name"     => $row["item_name"],
            "item_price"    => $row["item_price"],
            "item_quantity" => $row["quantity"],
            "image_url"     => $row["item_img"]
        ];
    }

    echo json_encode([
        "success"       => true,
        "total_orders"  => count($cartItems),
        "cart_items"    => $cartItems
    ]);
}
?>
