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
$result = $stmt->get_result();
$basic_details = $result->fetch_assoc();

if (!$basic_details) {
?>
    <h1>Fill Basic Details</h1>

<form method="POST" action="../auth/authProcessor.php" onsubmit="return validateDOB()">
    <label for="mother_name">Mother Name:</label>
    <input type="text" id="mother_name" name="mother_name"  required><br><br>

    <label for="father_name">Father Name:</label>
    <input type="text" id="father_name" name="father_name"  required><br><br>

    <label for="dob">Date of Birth:</label>
    <input type="date" id="dob" name="dob"  onchange="checkAge()" required><br><br>

    <label for="location">Location:</label>
    <select id="location" name="location" required>
        <option value="USA"> USA</option>
        <option value="UK">UK</option>
        <option value="Canada">Canada</option>
        <option value="Australia" >Australia</option>
        <option value="India" >India</option>
    </select><br><br>

    <input name="_action" value="process_basic_details" type="hidden">
    <input type="submit" value="Next">
</form>
<?php
}
else{
?>

<h1>Fill Basic Details</h1>

<form method="POST" action="../auth/authProcessor.php" onsubmit="return validateDOB()">
    <label for="mother_name">Mother Name:</label>
    <input type="text" id="mother_name" name="mother_name" value="<?php echo $basic_details['mother_name']; ?>" required><br><br>

    <label for="father_name">Father Name:</label>
    <input type="text" id="father_name" name="father_name" value="<?php echo $basic_details['father_name']; ?>" required><br><br>

    <label for="dob">Date of Birth:</label>
    <input type="date" id="dob" name="dob" value="<?php echo $basic_details['dob']; ?>" onchange="checkAge()" required><br><br>

    <label for="location">Location:</label>
    <select id="location" name="location" required>
        <option value="USA" <?php if ($basic_details['location'] == 'USA') echo 'selected'; ?>>USA</option>
        <option value="UK" <?php if ($basic_details['location'] == 'UK') echo 'selected'; ?>>UK</option>
        <option value="Canada" <?php if ($basic_details['location'] == 'Canada') echo 'selected'; ?>>Canada</option>
        <option value="Australia" <?php if ($basic_details['location'] == 'Australia') echo 'selected'; ?>>Australia</option>
        <option value="India" <?php if ($basic_details['location'] == 'India') echo 'selected'; ?>>India</option>
    </select><br><br>

    <input name="_action" value="process_basic_details" type="hidden">
    <input type="submit" value="Next">
</form>
<?php
}
?>

<script>
    function checkAge() {
        var dob = document.getElementById('dob').value;
        var today = new Date();
        var birthDate = new Date(dob);
        var age = today.getFullYear() - birthDate.getFullYear();
        var m = today.getMonth() - birthDate.getMonth();
        if (m < 0 || (m === 0 && today.getDate() < birthDate.getDate())) {
            age--;
        }
        if (age < 18) {
            alert("You must be 18 or older to submit this form.");
            return false;
        }
        return true;
    }

    function validateDOB() {
        return checkAge();
    }
</script>
