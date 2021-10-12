<?php
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE');
header('Access-Control-Allow-Headers: X-Requested-With');
header('Content-Type: application/json');
// Include action.php file
include_once 'db.php';
// Create object of Users class
$bike = new Bikes();

// create a api variable to get HTTP method dynamically
$api = $_SERVER['REQUEST_METHOD'];

// get id from url
$id = intval($_GET['id'] ?? '');

// Get all or a single user from database
if ($api == 'GET') {
    if ($id != 0) {
        $data = $bike->fetch($id);
    } else {
        $data = $bike->fetch();
    }
    echo json_encode($data);
}

// Add a new user into database
if ($api == 'POST') {
    $name = $bike->test_input($_POST['name']);
    $class = $bike->test_input($_POST['class']);

    if ($bike->insert($name, $class)) {
        echo $bike->message('Bike added successfully!',false);
    } else {
        echo $bike->message('Failed to add an bike!',true);
    }
}

// Update an bike in database
if ($api == 'PUT') {
    parse_str(file_get_contents('php://input'), $post_input);
    $name = $bike->test_input($post_input['name']);
    $class = $bike->test_input($post_input['class']);
    $status = $bike->test_input($post_input['status']);
    $order = $bike->test_input($post_input['order']);

    if ($id == null) {
        echo $bike->message('Bike not found!',true);
    } else {
        if ($bike->update($id, $name, $class, $status, $order)) {
            echo $bike->message('Bike updated successfully!',false);
        } else {
            echo $bike->message('Failed to update an bike!',true);
        }
    }
}

// Delete a bike from database
if ($api == 'DELETE') {
    if ($id != null) {
        if ($bike->delete($id)) {
            echo $bike->message('Bike deleted successfully!', false);
        } else {
            echo $bike->message('Failed to delete an bike!', true);
        }
    } else {
        echo $bike->message('Bike not found!', true);
    }
}

?>