<?php
include_once '../common/header.php';
include_once '../auth/UserClass.php';

if (!isLogin()) {
    header('location:/modules/auth/login.php');
    exit;
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['_action']) && $_POST['_action'] == "process_final_submission") {
    $basic_details = $_SESSION['basic_details'];
    $aadhar_pan_details = $_SESSION['aadhar_pan_details'] ?? NULL;
    $education_details = $_SESSION['education_details'];

    $conn = new mysqli('localhost', 'root', '', 'nexgi-intern-cms-blog');

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    $user_id = $_SESSION['authUser']['id'];

    $sql_check_user = "SELECT * FROM nexgi_reviewed_information WHERE user_id = ?";
    $stmt_check_user = $conn->prepare($sql_check_user);
    $stmt_check_user->bind_param("i", $user_id);
    $stmt_check_user->execute();
    $result_check_user = $stmt_check_user->get_result();

    if ($result_check_user->num_rows > 0) {
        $sql = "UPDATE nexgi_reviewed_information SET mother_name = ?, father_name = ?, dob = ?, location = ?, aadhar_card = ?, pan_card = ?, tenth = ?, twelfth = ?, graduation = ? WHERE user_id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sssssssssi", $mother_name, $father_name, $dob, $location, $aadhar_card, $pan_card, $tenth, $twelfth, $graduation, $user_id);
    } else {
        $sql = "INSERT INTO nexgi_reviewed_information (user_id, mother_name, father_name, dob, location, aadhar_card, pan_card, tenth, twelfth, graduation) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("isssssssss", $user_id, $mother_name, $father_name, $dob, $location, $aadhar_card, $pan_card, $tenth, $twelfth, $graduation);
    }

    $mother_name = $basic_details['mother_name'];
    $father_name = $basic_details['father_name'];
    $dob = $basic_details['dob'];
    $location = $basic_details['location'];
    $aadhar_card = $aadhar_pan_details['aadhar_card'] ?? NULL;
    $pan_card = $aadhar_pan_details['pan_card'] ?? NULL;
    $tenth = $education_details['tenth'];
    $twelfth = $education_details['twelfth'];
    $graduation = $education_details['graduation'];

    $stmt->execute();

    
    $stmt->close();

    $userObject = new UserClass();
    $stage = 4; 
    $result = $userObject->updateStage($stage);

    if ($result) {
        $_SESSION['auth_user']['stage'] = $stage;
        echo "Stage updated successfully.";
    } else {
        echo "Error updating stage.";
    }

    // Redirect to thankyou.php
    header('location: thankyou.php');
    exit;
}
?>
