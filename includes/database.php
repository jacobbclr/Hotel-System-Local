<?php
$hostname = 'mysql.db.mdbgo.com';
$username = 'jacob_84_admin';
$password = 'Jacobbue12#';
$database = 'jacob_84_hotelsystem';

$connect = mysqli_connect($hostname, $username, $password, $database);

if (!$connect) {
    exit('Failed to connect: ' . mysqli_connect_error());
}

// Function to query tl_bookings and return the results as an array
function getBookings() {
    global $connect;
    
    $query = "SELECT * FROM tl_bookings";
    $result = mysqli_query($connect, $query);
    
    $bookings = array();
    
    if (mysqli_num_rows($result) > 0) {
        while ($row = mysqli_fetch_assoc($result)) {
            $booking = array(
                'id' => $row['id'],
                'startDate' => $row['startDate'],
                'endDate' => $row['endDate'],
            );
            
            $bookings[] = $booking;
        }
    }
    
    return $bookings;
}
function getTodos() {
    global $connect;
    
    $query = "SELECT * FROM tl_todos";
    $result = mysqli_query($connect, $query);
    
    $todos = array();
    
    if (mysqli_num_rows($result) > 0) {
        while ($row = mysqli_fetch_assoc($result)) {
            $todo = array(
                'id_todo' => $row['id'],
                'date_todo' => $row['date']
            );
            
            $todos[] = $todo;
        }
    }
    
    return $todos;
}

if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['action']) && $_GET['action'] === 'getBookings') {
    $bookings = getBookings();
    $todos = getTodos();
    $data = array(
        'bookings' => $bookings,
        'todos' => $todos
    );
    header('Content-Type: application/json');
    echo json_encode($data);

    exit;
}

?>
