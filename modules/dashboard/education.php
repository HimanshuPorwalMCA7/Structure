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

$query = "SELECT * FROM nexgi_education_details WHERE user_id = ?";
$stmt = $conn->prepare($query);

$stmt->bind_param("i", $user_id);

$stmt->execute();
$result = $stmt->get_result();
$education = $result->fetch_assoc();

if (!$education) {
    ?>
    <h1>Enter Your Education Details</h1>
    <form method="POST" action="../auth/authProcessor.php" onsubmit="return validateEducation()">
        <label for="tenth">10th Grade:</label>
        <input type="text" id="tenth" name="tenth" required><br><br>
        
        <label for="twelfth">12th Grade:</label>
        <input type="text" id="twelfth" name="twelfth" required><br><br>
        
        <label for="graduation">Graduation:</label>
        <input type="text" id="graduation" name="graduation" required><br><br>
        
        <input type="hidden" name="_action" value="process_education_details">
        <input type="submit" value="Next">
    </form>
<?php
} else {
    $tenth = $education['tenth'];
    $twelfth = $education['twelfth'];
    $graduation = $education['graduation'];
    ?>
    <h1>Enter Your Education Details</h1>
    <form method="POST" action="../auth/authProcessor.php" onsubmit="return validateEducation()">
        <label for="tenth">10th Grade:</label>
        <input type="text" id="tenth" name="tenth" value="<?php echo $tenth; ?>" required><br><br>
        
        <label for="twelfth">12th Grade:</label>
        <input type="text" id="twelfth" name="twelfth" value="<?php echo $twelfth; ?>" required><br><br>
        
        <label for="graduation">Graduation:</label>
        <input type="text" id="graduation" name="graduation" value="<?php echo $graduation; ?>" required><br><br>
        
        <input type="hidden" name="_action" value="process_education_details">
        <input type="submit" value="Next">
    </form>
<?php
}
?>

<script>
function validateEducation() {
    var tenth = parseFloat(document.getElementById("tenth").value);
    var twelfth = parseFloat(document.getElementById("twelfth").value);
    var graduation = parseFloat(document.getElementById("graduation").value);

    if (tenth < 60 || tenth > 100 || isNaN(tenth)) {
        alert("10th grade must be between 60% and 100%.");
        return false;
    }

    if (twelfth < 60 || twelfth > 100 || isNaN(twelfth)) {
        alert("12th grade must be between 60% and 100%.");
        return false;
    }

    if (graduation < 60 || graduation > 100 || isNaN(graduation)) {
        alert("Graduation must be between 60% and 100%.");
        return false;
    }

    return true;
}
</script>
