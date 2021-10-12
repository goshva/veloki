<?php

// Include CORS headers
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE');
header('Access-Control-Allow-Headers: X-Requested-With');
header('Content-Type: application/json');
// Include action.php file
include_once 'db.php';
// Create object of Users class
$stock = new Stock();

// create a api variable to get HTTP method dynamically
$api = $_SERVER['REQUEST_METHOD'];

// get id from url
$id = intval($_GET['id'] ?? '');

// Get all or a single user from database
if ($api == 'GET') {
    if ($id != 0) {
        $data = $stock->fetch($id);
    } else {
        $data = $stock->fetch();
    }
    echo json_encode($data);
}

if ($api == 'POST') {
    $tel = $stock->test_input($_POST['tel']);
    $name = $stock->test_input($_POST['name']);
    $items = $_POST['items'];
    $itemsStr = $stock->test_input(join("', '",$_POST['items']));
    $money = $stock->test_input($_POST['money']);
    $sign = $stock->test_input($_SERVER['PHP_AUTH_USER']);
    $comment = $stock->test_input($_POST['comment']);
    $timein = date('Y-m-d H:i:s');
    if ($stock->insert($tel, $name, $items, $itemsStr, $money, $sign, $comment, $timein)) {
        echo $stock->message('Order added successfully!',false);
    } else {
        echo $stock->message('Failed to add an order!',true);
    }
}

// Update an order in database
if ($api == 'PUT') {
    parse_str(file_get_contents('php://input'), $post_input);
    $tel = $stock->test_input($post_input['tel']);
    $name = $stock->test_input($post_input['name']);
    $itemsStr = $stock->test_input($post_input['items']);
    $money = $stock->test_input($post_input['money']);
    $sign = $stock->test_input($post_input['sign']);
    $comment = $stock->test_input($post_input['comment']);

    if ($id == null) {
        echo $stock->message('Order not found!',true);
    } else {
        if ($stock->update($id, $tel, $name, $itemsStr, $money, $sign, $comment)) {
            echo $stock->message('Order updated successfully!',false);
        } else {
            echo $stock->message('Failed to update an order!',true);
        }
    }
}

// Delete an order from database
if ($api == 'DELETE') {
    if ($id == null) {
        echo $stock->message('Order not found!', true);
    } else {
        if ($stock->delete($id)) {
            echo $stock->message('Order deleted successfully!', false);
        } else {
            echo $stock->message('Failed to delete an order!', true);
        }
    }
}

?>