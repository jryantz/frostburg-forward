if(isset($_POST['emailTo']) && isset($_POST['subject']) && isset($_POST['body']))
{
    $to = $_POST['emailTo'];
    $subject = $_POST['subject'];
    $body = $_POST['body'];

    echo $to;
}
