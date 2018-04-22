<?php
//require_once('init.php');
$ser="localhost";
$user="root";
$password="WMDBizAssist";
$db="frostburgforward";

// Establishing a connection to the database.
//$con = new mysqli(DB_HOST, DB_USER, DB_PASS);
$con = new mysqli($ser, $user, $password, $db);

// Check the connection to the database.
if($con->connect_error) { die('Connection Failed: ' . $con->connect_error); }
echo 'Connection Successful';

$resources = array();

$sql = "SELECT * FROM resources";
$result = $con->query($sql);

if($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        $resources[$row['answer_id']][$row['answer_condition']] = array($row['text'], $row['link']);
    }
} else { echo 'No Results'; }


?>

<!doctype html>

<html>
    <head>
        <title>BizAssist - Report</title>

        <meta charset="utf-8">
        <meta name="description" content="">
        <meta name="keywords" content="">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <link href="https://fonts.googleapis.com/css?family=Roboto:300,400,400i,700" rel="stylesheet">
        <link href="css/app.css" type="text/css" rel="stylesheet">
    </head>

    <body>
        <header>
            <nav class="grid-fixed">
                <a href="index.html" class="title">Western Maryland BizAssist</a>

                <ul>
                    <li><a href="index.html">Home</a></li>
                    <li><a href="start.html">Business</a></li>
                    <li><a href="#">About</a></li>
                    <li><a href="#">Contact</a></li>
                </ul>
            </nav>
        </header>

        <main>
            <section class="single color">
                <div class="grid-fixed cf">
                    <div id="report_div" class="c12">
                      <section id="disclaimer">
                        <h2>*** THIS INFORMATION IS IN REGARDS TO LOCAL REGUALTIONS IN REGARDS TO OPENING A BUSINESS. THERE ARE FEDERAL AND STATE REGULATIONS THAT GOVERN BUSINESSES AND A GOOD RESOURCE FOR MORE INFORMATION IS:</h2>
                        <a href="https://businessexpress.maryland.gov"><strong>https://businessexpress.maryland.gov</strong></a>
                        <br></br>
                      </section>
                      <section id="basic_chk">
                        <h2>BASIC BUSINESS CHECKLIST FOR MARYLAND</h2>
                        <br></br>
                      </section>
                      <section id="site_selection">
                        <h2>SITE SELECTION</h2>
                        <br></br>
                      </section>
                      <section id="use_and_occ">
                        <h2>USE & OCCUPANCY PERMIT</h2>
                        <section id="use_and_occ_data"></section>
                        <br></br>
                      </section>
                      <section id="other">
                        <h2>OTHER PERMITS & APPROVALS</h2>
                        <br></br>
                      </section>
                </div>
            </section>
        </main>
        <script>
          var session = JSON.parse(localStorage.getItem('session'));
          var resources = <?php echo json_encode($resources); ?>;
          console.log(session);
          console.log(resources);
        </script>
        <footer><div><p>Photo Credit: Gerald Snelson</p></div></footer>
    </body>
</html>
