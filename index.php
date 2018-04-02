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
        // Decode json...
    }
} else {
    echo 'No Results';
}

$answers = array(array());

$sql = "SELECT * FROM answers";
$result = $con->query($sql);

if($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        array_push($answers[$row['question_id']], $row['answer']);
        //$answers[$row['question_id']] = $row['answer'];
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

// Option 1 - with question flow table
question1 -> question2 = {
    "answer": {1,2}
}

question1 -> question3 = {
    "answer": {3}
}

question1 -> question4 = {
    "answer": {4,5}
}

// Option 2 - housed in question table
{
    "answer-1": 2,
    "answer-2": 2,
    "answer-3": 3,
    "answer-4": 4,
    "answer-5": 4
}
