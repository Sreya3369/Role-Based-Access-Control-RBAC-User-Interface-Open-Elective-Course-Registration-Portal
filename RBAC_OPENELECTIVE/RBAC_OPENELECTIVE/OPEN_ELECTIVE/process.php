<?php
// Enable CORS (Cross-Origin Resource Sharing) headers
if (isset($_SERVER['HTTP_ORIGIN'])) {
    header("Access-Control-Allow-Origin: http://127.0.0.1:5500");
    header("Access-Control-Allow-Credentials: true");
    header("Access-Control-Allow-Methods: POST");
    header("Access-Control-Allow-Headers: Content-Type");
}

// Database connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "open_elective";
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Handle POST data
if (isset($_POST['param1']) && isset($_POST['param2'])) {
    $param1 = $_POST['param1'];
    $param2 = $_POST['param2'];
    
    // SQL query to retrieve user based on Roll_no and Pass (change column names as per your database)
    $sql = "SELECT Roll_no, Pass, Status FROM open_ele WHERE Roll_no = '$param1'";
    $result = $conn->query($sql);

    if ($result) {
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                if ($param1 != $row["Roll_no"] || $param2 != $row["Pass"]) {
                    $result = 1;
                    echo json_encode(array("result" => $result));
                } else {
                    if ($row["Status"] == NULL) {
                        // Example include file for courses
                        $data = array("param1" => $param1);
                        include 'course.php';
                    } else {
                        // Example include file for results
                        $data = array("param1" => $param1);
                        include 'result.php';
                    }
                }
            }
        } else {
            $result = 1; // Assuming user not found
            echo json_encode(array("result" => $result));
        }
    } else {
        // Handle query error
        echo json_encode(array("error" => $conn->error));
    }

    $conn->close();
} else {
    $result = 1; // Invalid POST parameters
    echo json_encode(array("result" => $result));
}
?>
