<?php
include_once '../common/header.php';

?>
<br>
<br>
<?php
$con = new mysqli('localhost', 'root', '', 'nexgi-intern-cms-blog');
if ($con->connect_error) {
    die('Connection failed: ' . $con->connect_error);
} else {
    $id = $_SESSION['authUser']['id'];
    $query = "SELECT stage FROM nexgi_users where id = $id";
    $result = $con->query($query);

    if ($result->num_rows == 0) {
        header("Location: basic_details.php");
        exit();
    } else {
        $row = $result->fetch_assoc();
        $stage = $row['stage'];

        if ($stage == 0) {
            header("Location: basic_details.php");
            exit();
        } else if ($stage == 2) {
            header("Location: education.php");
            exit();
        } else if ($stage == 1) {
            header("Location: adhar_pan.php");
            exit();
        } else if ($stage == 3) {
            header("Location: final.php");
            exit();
        } else {
            header("Location:thankyou.php");
            exit();
        }
    }
}
?>
