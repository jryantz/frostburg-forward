<?php
require_once('app/init.php');
$ser="localhost";
$user="root";
$password="WMDBizAssist";
$db="frostburgforward";

// Establishing a connection to the database.
$con = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
//$con = new mysqli($ser, $user, $password, $db);

$resource_conditions = array();

$sql = "SELECT * FROM resources";
$result = $con->query($sql);

if($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
      //var_dump($row);
    $resource_conditions[$row['answer_id']][] = array(utf8_encode($row['text']), utf8_encode($row['link']), utf8_encode($row['condition']), $row['tag']);
    }
} else { echo 'No Results'; }

//If POST parameters are set, submit the given parameters to the PHP mail function to send the report.
$report_id = "none";
if(isset($_GET['report_id'])){
  $report_id = $_GET['report_id'];
  $queryStr = str_pad($report_id,6,"0",STR_PAD_LEFT);
  $sql = "SELECT * FROM reports WHERE id=".$queryStr;
  $result = $con->query($sql);
  if($result->num_rows > 0) {
      while($row = $result->fetch_assoc()) {
        $reportFromDB = str_getcsv($row['report'], ",", '"');
        $report = array();
        $size = count($reportFromDB);
        $count = 0;
        echo $size;
        while($count < $size){
          echo $count;
          $report_item = array();
          for($j=0; $j<3; $j++){
            array_push($report_item, $reportFromDB[$count+$j]);
          }
          array_push($report, $report_item);
          $count+=3;
        }
      }
  } else { echo 'No Results'; }
}
if(isset($_POST['reportArr'])){
  echo 'Success';
  $report = mysqli_real_escape_string($con, $_POST['reportArr']);
  $report = json_encode($_POST['reportArr']);
  $report = utf8_encode($report);
}
if(isset($_POST['emailInput']))
{
    $sql = "INSERT INTO `reports` (`report`) VALUES ('".$report."')";
    if ($con->query($sql) === TRUE) {
        echo "New record created successfully";
    } else {
        echo "Error: " . $sql . "<br>" . $con->error;
    }

    $sql = "SELECT MAX(`id`) FROM `reports`";
    $result = $con->query($sql);
    $row = $result->fetch_assoc();
    $report_id = $row["MAX(`id`)"];

    $to = $_POST['emailInput'];
    $subject = "Your BizAssist Report";
    $report_link = "https://wmdbizassist.org/pages/report.php?report_id=".$report_id;
    $message = "Thank you for using WMD BizAssist. Click the link below to access your personal BizAssist report. \n\n" . $report_link;
    $message = wordwrap($message, 70);
    $headers = "From: donotreply@wmdbizassist.org";

    mail($to, $subject, $message, $headers);

    $sendToSBDC = isset($_POST['sendToSBDC']) ? 1 : 0;

    if($sendToSBDC == 1){
      $to = "adm.wmdbizassist@gmail.com";
      $subject = "A new report has been generated. ID: ".$report_id;
      $message = "The following BizAssist report has been generated, you can view it at the link below. \n\n".$report_link;
      $message = wordwrap($message, 70);
      mail($to, $subject, $message, $headers);
    }
}

// Check the connection to the database.
if($con->connect_error) { die('Connection Failed: ' . $con->connect_error); }
echo 'Connection Successful';
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
                    <li><a href="start.html">Quick Start Tool</a></li>
                    <li><a href="AboutUs.html">About</a></li>
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
                        <a id="report_link" href="https://businessexpress.maryland.gov"><strong>https://businessexpress.maryland.gov</strong></a>
                        <br></br>
                      </section>
                      <section id="basic_chk">
                        <h2>BASIC BUSINESS CHECKLIST FOR MARYLAND</h2>
                        <ul id="basic_chk_data"></ul>
                        <br></br>
                      </section>
                      <section id="site_selection">
                        <h2>SITE SELECTION</h2>
                        <ul id="site_selection_data"></ul>
                        <br></br>
                      </section>
                      <section id="use_and_occ">
                        <h2>USE & OCCUPANCY PERMIT</h2>
                        <ul id="use_and_occ_data"></ul>
                        <br></br>
                      </section>
                      <section id="other">
                        <h2>OTHER PERMITS & APPROVALS</h2>
                        <ul id="other_data"></ul>
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
        if(session != null){
          var responses = session.responses;
        }
        var resource_conditions;
        var resource_conditions = <?php echo json_encode($resource_conditions); ?>;
        var report = <?php echo json_encode($report) ?>;
        console.log(report);
        if(report == null) {
          report = generateReport();
        }
        setReportContent();

        $(document).ready(function(){
        });

        function generateReport(){
          report = [];
          for(var ans in responses){
            if(responses.hasOwnProperty(ans)){
              var answer = responses[ans];
              for(var ans_id in resource_conditions){
                if(ans_id == answer) {
                  for(var i=0; i<resource_conditions[ans_id].length; i++){
                    var entry = resource_conditions[ans_id][i];
                    if(entry[2] != ""){
                      var jsonObj = JSON.parse(entry[2]);
                      var conditions = jsonObj.conditions;
                      for(var i=0; i<conditions.length; i++){
                        for(var ans in responses){
                          if(conditions[i].question == ans){
                            if(conditions[i].answer == responses[ans]){
                              report.push([entry[3]+'\"', '\"'+conditions[i].text+'\"', conditions[i].link]);
                            }
                          }
                        }
                      }
                    } else {
                      report.push([entry[3]+'\"', '\"'+entry[0]+'\"', entry[1]]);
                    }
                  }
                }
              }
            }
          }
          console.log(report);
          return report;
        }

        function setReportContent(){
          for(var i=0; i<report.length; i++){
            var entry = report[i];
            console.log(entry);
            var li = document.createElement("li");
            var div = document.createElement("div");
            var p = document.createElement("p");
            p.innerHTML = entry[1];
            console.log(p);
            var a;
            if(entry[2] != ""){
              a = document.createElement("a");
              a.id = "report_link";
              a.innerHTML = entry[2];
              a.href = entry[2];
            }
            div.appendChild(p);
            if(a != null){
              div.appendChild(a);
            }
            var br = document.createElement("br");
            li.appendChild(div);
            li.appendChild(br);
            var tag = entry[0].replace("\"", "");
            switch(tag){
              case 'basic_chk':
              // document.getElementById("basic_chk_data").appendChild(div);
              // document.getElementById("basic_chk_data").appendChild(br);
              document.getElementById("basic_chk_data").appendChild(li);
              break;
              case 'site_selection':
              // document.getElementById("site_selection_data").appendChild(div);
              // document.getElementById("site_selection_data").appendChild(br);
              document.getElementById("site_selection_data").appendChild(li);
              break;
              case 'use_and_occ':
              console.log("hi");
              //document.getElementById("use_and_occ_data").appendChild(div);
              //document.getElementById("use_and_occ_data").appendChild(br);
              document.getElementById("use_and_occ_data").appendChild(li);
              break;
              case 'other':
              //document.getElementById("other_data").appendChild(div);
              //document.getElementById("other_data").appendChild(br);
              document.getElementById("other_data").appendChild(li);
              break;
              default :
            }
          }
        }

        function showContact(){
          if(!document.getElementById("contact_form")){
            var form = document.createElement("form");
            form.action = "";
            form.method = "POST";
            form.id = "contact_form";


            var input = document.createElement("input");
            input.type = "email";
            input.name = "emailInput";
            input.id = "emailInput";
            input.placeholder = "Enter your e-mail address (Optional)";
            var br = document.createElement("br");
            var br1 = document.createElement("br");
            var br2 = document.createElement("br");

            var label = document.createElement("label");
            label.for = "sendToSBDC";
            label.innerHTML = "I would like my information to be sent to the Maryland Small Business Development Center";

            var check = document.createElement("input");
            check.type = "checkbox";
            check.id = "sendToSBDC";
            check.name = "sendToSBDC";

            var reportInput = document.createElement("input");
            reportInput.type = "hidden";
            reportInput.name = "reportArr";
            reportInput.value = report;

            var submit = document.createElement("input");
            submit.type = "submit";
            submit.id = "submitContact";
            submit.value = "Send";

            form.appendChild(input);
            form.appendChild(br);
            form.appendChild(br1);
            form.appendChild(br2);
            form.appendChild(label);
            form.appendChild(check);
            form.appendChild(br);
            form.appendChild(reportInput);
            form.appendChild(submit);

            document.getElementById("contact_div").appendChild(form);
            form.style.display = "none";
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
              data: JSON.stringify({ subject: subject, body: body }),
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
        <footer><div></div></footer>
        <!-- <footer>
            <div>
                <div class="seals">
                  <a href= "https://alleganyworks.org/">
                      <img src="assets/County Econ Logo-CIRCLE.jpg">
                  </a>
                  <a href= "http://www.marylandsbdc.org/locations/western-region">
                      <img src="assets/CitySeal.png">
                  </a>
                  <a href= "https://www.choosecumberland.org/">
                      <img src="assets/CEDC_full-color.png">
                  </a>
                  <a href = "https://www.garrettcounty.org/">
                      <img src="assets/Garrett-County-Seal-2.png">
                  </a>
                </div>
                <p>Photo Credit: Gerald Snelson</p>
            </div>
        </footer> -->
    </body>
</html>
