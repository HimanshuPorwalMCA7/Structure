<?php
include_once '../common/header.php';

if (!isLogin()) {
    header('location:/modules/auth/login.php');
    exit;
}

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "nexgi-intern-cms-blog";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$user_id = $_SESSION['authUser']['id'];

$query = "SELECT * FROM nexgi_basic_details WHERE user_id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$basic_details_result = $stmt->get_result();
$basic_details = $basic_details_result->fetch_assoc();


$query = "SELECT * FROM nexgi_adhar_pan_details WHERE user_id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$aadhar_pan_result = $stmt->get_result();
$aadhar_pan_details = $aadhar_pan_result->fetch_assoc();


$query = "SELECT * FROM nexgi_education_details WHERE user_id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$education_details_result = $stmt->get_result();
$education_details = $education_details_result->fetch_assoc();
?>

<h2>Basic Details:</h2>
<ul>
    <li>Mother Name: <?php echo $basic_details['mother_name']; ?></li>
    <li>Father Name: <?php echo $basic_details['father_name']; ?></li>
    <li>Date of Birth: <?php echo $basic_details['dob']; ?></li>
    <li>Location: <?php echo $basic_details['location']; ?></li>
</ul>

<h2>Aadhar and PanCard Details:</h2>
<ul>
    <li>Aadhar Card Number: <?php echo $aadhar_pan_details['aadhar_card'] ?? NULL; ?></li>
    <li>PanCard Number: <?php echo $aadhar_pan_details['pan_card'] ?? NULL; ?></li>
</ul>

<h2>Education Details:</h2>
<ul>
    <li>10th Grade: <?php echo $education_details['tenth']; ?></li>
    <li>12th Grade: <?php echo $education_details['twelfth']; ?></li>
    <li>Graduation: <?php echo $education_details['graduation']; ?></li>
</ul>

<form method="POST" action="f1.php">
    <input name="_action" value="process_final_submission" type="hidden">
    <input type="submit" value="Submit">
</form>
<a href="basic_details.php">Edit</a>