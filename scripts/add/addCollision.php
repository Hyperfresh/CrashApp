<?php
include('../connect.php');

//get values from post
//good practice to validate these values first

function validate($data) {
    $data = htmlspecialchars($data);
    return trim($data);
}

$type = validate($_POST['collisionType']);
$speed = intval($_POST['speed']);
$cas = intval($_POST['casualties']);
$sev = $_POST['highestSeverity'];
$drugs = $_POST['drugs'];
$dui = $_POST['dui'];

$return = 'location:../../index.html';

// more functions
if ($speed > 110) {
    echo "<script>alert('ERROR: Speed cannot exceed 110, you inputted $speed.')</script>";
    header($return);
    return;
}
if ($cas > 10) {
    echo "<script>alert('ERROR: Casualty count cannot exceed 10, you inputted $cas.')</script>";
    header($return);
    return;
}

//insert them into DB using SQL statement
$sql = "INSERT INTO collisiontype(collisionType, speed, casualties, highestSeverity, Drugs, DUI) VALUES ('$type', $speed, $cas, '$sev', $drugs, $dui)";
echo "<script>console.log('Attempting to run \'$sql\'...')</script>";
if(mysqli_query($conn, $sql)){
    echo "<script>alert('Data added.')</script>";
} else {
    echo "<script>console.log('Something bad happened when I tried to run " . $sql . ". Details: \"" . mysqli_error($conn) . "\"')</script>";
    echo "<script>alert('Something bad happened when I tried to run " . $sql . ". Details: \"" . mysqli_error($conn) . "\"')</script>";
}

//we can now redirect to another page once completed
    header($return);
?>