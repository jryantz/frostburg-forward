<?php
require_once('app/init.php');
/*$ser="localhost";
$user="root";
$password="WMDBizAssist";
$db="frostburgforward";*/

// Establishing a connection to the database.
$con = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
//$con = new mysqli($ser, $user, $password, $db);

// Check the connection to the database.
if($con->connect_error) { die('Connection Failed: ' . $con->connect_error); }
echo 'Connection Successful';

$resource_conditions = array();

$sql = "SELECT * FROM resources";
$result = $con->query($sql);

if($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        $resource_conditions[$row['answer_id']] = array($row['tag'], $row['question_condition'], $row['answer_condition'], $row['text'], $row['link']);
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
                <a href="../index.html" class="title">Western Maryland BizAssist</a>

                <ul>
                    <li><a href="../index.html">Home</a></li>
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
                        <section id="basic_chk_data"></section>
                        <br></br>
                      </section>
                      <section id="site_selection">
                        <h2>SITE SELECTION</h2>
                        <section id="site_selection_data"></section>
                        <br></br>
                      </section>
                      <section id="use_and_occ">
                        <h2>USE & OCCUPANCY PERMIT</h2>
                        <section id="use_and_occ_data"></section>
                        <br></br>
                      </section>
                      <section id="other">
                        <h2>OTHER PERMITS & APPROVALS</h2>
                        <section id="other_data"></section>
                        <br></br>
                      </section>
                </div>
            </section>
        </main>
        <footer><div><p>Photo Credit: Gerald Snelson</p></div></footer>
        <script>
           var session = JSON.parse(localStorage.getItem('session'));
           var responses = session.responses;
           var resource_conditions = <?=json_encode($resource_conditions); ?>;
           console.log(session);
           console.log(resource_conditions);
           
           for(var ans in responses){
           	if(responses.hasOwnProperty(ans)){
           		var answer = responses[ans];
           		for(var ans_id in resource_conditions){
           			if(ans_id == answer) {
           				var div = document.createElement("div");
           				var p = document.createElement("p");
           				p.innerHTML = resource_conditions[ans_id][3];
           				var a;
           				if(resource_conditions[ans_id][4] != null){
           					a = document.createElement("a");
           					a.innerHTML = resource_conditions[ans_id][4];
           					a.href = resource_conditions[ans_id][4];
           				}
           				div.appendChild(p);
           				if(a != null){
           					div.appendChild(a);
           				}
           				var br = document.createElement("br");
           				var tag = resource_conditions[ans_id][0];
	           			switch(tag){
	           				case 'basic_chk':
	           					document.getElementById("basic_chk_data").appendChild(div);
	           					document.getElementById("basic_chk_data").appendChild(br);
	           					break;
	           				case 'site_selection':
	           					document.getElementById("site_selection_data").appendChild(div);
	           					document.getElementById("site_selection_data").appendChild(br);
	           					break;
	           				case 'use_and_occ':
	           					document.getElementById("use_and_occ_data").appendChild(div);
	           					document.getElementById("use_and_occ_data").appendChild(br);
	           					break;
	           				case 'other':
	           					document.getElementById("other_data").appendChild(div);
	           					document.getElementById("other_data").appendChild(br);
	           					break;
	           				default :
	           					
	           			}
           			}
           		}
           	}
           }
        </script>
    </body>
</html>
