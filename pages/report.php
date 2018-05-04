<?php

//If POST parameters are set, submit the given parameters to the PHP mail function to send the report.
if(isset($_POST['emailTo']) && isset($_POST['subject']) && isset($_POST['body']))
{
    $to = $_POST['emailTo'];
    $subject = $_POST['subject'];
    $body = $_POST['body'];

    mail($to, $subject, $body);
}

require_once('app/init.php');
// $ser="localhost";
// $user="root";
// $password="WMDBizAssist";
// $db="frostburgforward";

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
      // if(isset($resource_conditions[$row['answer_id']])){
      //   $newarray = array($row['id'], $row['tag'], $row['question_condition'], $row['answer_condition'], $row['text'], $row['link']);
      //   array_push($resource_conditions[$row['answer_id']], $newarray);
      // } else {
      //   $resource_conditions[$row['answer_id']] = array();
      //   array_push($resource_conditions[$row['answer_id']], array($row['id'], $row['tag'], $row['question_condition'], $row['answer_condition'], $row['text'], $row['link']));
      // }
      $resource_conditions[$row['answer_id']] = array($row['tag'], $row['question_condition'], $row['answer_condition'], $row['text'], $row['link']);
    }
} else { echo 'No Results'; }

?>

<!doctype html>

<html>
    <head>
        <!-- Global site tag (gtag.js) - Google Analytics -->
        <script async src="https://www.googletagmanager.com/gtag/js?id=UA-118657595-1"></script>
        <script>
          window.dataLayer = window.dataLayer || [];
          function gtag(){dataLayer.push(arguments);}
          gtag('js', new Date());

          gtag('config', 'UA-118657595-1');
        </script>

        <title>BizAssist - Report</title>

        <meta charset="utf-8">
        <meta name="description" content="">
        <meta name="keywords" content="">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <link href="https://fonts.googleapis.com/css?family=Roboto:300,400,400i,700" rel="stylesheet">
        <link href="css/app.css" type="text/css" rel="stylesheet">
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
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
                    <div id="contact_div">
                      <h3 class="header">Want more information? Press the button below and your report will be sent to
                        someone who will be more than happy to help you take the next steps to open your business.</h3>
                      <div class="button">
                        <a onclick="showContact()" href="#">Contact Me</a>
                      </div>
                      <br></br>
                    </div>
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
                      <div class="button">
                        <a href="javascript:window.print()">Print Report</a>
                      </div>
                </div>
            </section>
        </main>

        <script>
           var session = JSON.parse(localStorage.getItem('session'));
           var responses = session.responses;
           var resource_conditions = <?php echo json_encode($resource_conditions); ?>;
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

           function showContact(){
             if(!document.getElementById("contact_form")){
               var div = document.createElement("div");
               div.id = "contact_form";
               div.className = "resource";

               var input = document.createElement("input");
               input.type = "email";
               input.id = "emailInput";
               input.placeholder = "Enter your e-mail address (Optional)";

               var submitDiv = document.createElement("div");
               submitDiv.className = "button";
               var a = document.createElement("a");
               a.href = "javascript:sendEmail()"
               a.innerHTML = "Send";
               submitDiv.appendChild(a);

               div.appendChild(input);
               div.appendChild(submitDiv);

               document.getElementById("contact_div").appendChild(div);
               div.style.display = "none";
             }
             var e = document.getElementById("contact_form");
             if(e.style.display === "none"){
               e.style.display = "block";
             } else {
               e.style.display = "none";
             }
           }

           function sendEmail(){
             var to = document.getElementById("emailInput").value;
             var subject = "Your BizAssist Report";
             var body = "";
             if(validate(to)){
               jQuery.ajax({
                   url: "",
                   type: 'POST',
                   data: JSON.stringify({ emailTo: to, subject: subject, body: body }),
                   cache: false
                }).done(function(){
                  <?php if(isset($_POST['emailTo']) && isset($_POST['subject']) && isset($_POST['body']))
                  {
                      $to = $_POST['emailTo'];
                      $subject = $_POST['subject'];
                      $body = $_POST['body'];
                      $headers = 'From: reports@wmdbizassist.org' . "\r\n";
                      $headers .= "To: $to\r\n";

                      mail($to, $subject, $body, $headers);
                  } ?>;
                });
                window.location.href = "";
              } else {
                alert("Please enter a valid email address");
              }
           }

           function validate(address){
             var regex = /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
             return regex.test(address);
           }
        </script>
        <footer><div><p>Photo Credit: Gerald Snelson</p></div></footer>
    </body>
</html>
