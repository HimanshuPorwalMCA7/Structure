<?php
include_once '../common/header.php';

if (!isLogin()) {
    header('location:/modules/auth/login.php');
    exit; // Exit after header redirect
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

$query = "SELECT * FROM nexgi_adhar_pan_details WHERE user_id = ?";
$stmt = $conn->prepare($query);

$stmt->bind_param("i", $user_id);

$stmt->execute();
$result = $stmt->get_result();
$aadhar_pan = $result->fetch_assoc();

if (!$aadhar_pan) {
    ?>
    <h1>Enter Your Aadhar And PanCard Details</h1>

    <form method="POST" action="../auth/authProcessor.php" onsubmit="return validateForm()">
        <label for="aadhar_card">Aadhar Card Number:</label>
        <input type="text" id="aadhar_card" name="aadhar_card" required><br><br>
    
        <label for="pan_card">PanCard Number:</label>
        <input type="text" id="pan_card" name="pan_card"  required><br><br>
    
        <input type="hidden" name="_action" value="process_aadhar_pan_details">
        <input type="submit" value="Next">
    </form>
    <?php
}
else{

$aadhar_card_number = $aadhar_pan['aadhar_card'];
$pan_card_number = $aadhar_pan['pan_card'];

?>
<h1>Enter Your Aadhar And PanCard Details</h1>

<form method="POST" action="../auth/authProcessor.php" onsubmit="return validateForm()">
    <label for="aadhar_card">Aadhar Card Number:</label>
    <input type="text" id="aadhar_card" name="aadhar_card" value="<?php echo $aadhar_card_number; ?>" required><br><br>

    <label for="pan_card">PanCard Number:</label>
    <input type="text" id="pan_card" name="pan_card" value="<?php echo $pan_card_number; ?>" required><br><br>

    <input type="hidden" name="_action" value="process_aadhar_pan_details">
    <input type="submit" value="Next">
</form>
<?php
}
?>

<script>
function validateForm() {
    var aadharNumber = document.getElementById("aadhar_card").value;
    var panNumber = document.getElementById("pan_card").value;

    if (aadharNumber.length !== 12) {
        alert("Aadhar number must be exactly 12 digits.");
        return false;
    }

    if (panNumber.length !== 10) {
        alert("PAN card number must be exactly 10 characters.");
        return false;
    }

    return true;
}
</script>
