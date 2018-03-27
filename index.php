<?php
require_once('init.php');

// Establishing a connection to the database.
$con = new mysqli(DB_HOST, DB_USER, DB_PASS);

// Check the connection to the database.
if($con->connect_error) {
    die('Connection Failed: ' . $con->connect_error);
}
echo 'Connection Successful'.

$questions = array();

$sql = "SELECT * FROM questions";
$result = $con->query($sql);

if($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        $questions[$row['id']] = $row['question'];
    }
} else {
    echo 'No Results';
}

$answers = array();

$sql = "SELECT * FROM answers";
$result = $con->query($sql);

if($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        $answers[$row['question_id']] = $row['answer'];
    }
} else {
    echo 'No Results';
}

$question_flow = array();

$sql = "SELECT * FROM question_flow";
$result = $con->query($sql);
?>

<html>
    <head></head>
    <body></body>
</html>
