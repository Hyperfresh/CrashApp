<?php
include('../connect.php');

//get values from post
//good practice to validate these values first

function validate($data) {
    $data = htmlspecialchars($data);
    return trim($data);
}

$type = validate($_POST['collisionType']);
$sev = validate($_POST['positionType']);

//insert them into DB using SQL statement
$sql = "INSERT INTO collisiontype(collisionType, positionType) VALUES ('$type', '$sev')";
echo "<script>console.log('Attempting to run \'$sql\'...')</script>";
if(mysqli_query($conn, $sql)){
    echo "<script>alert('Data added.')</script>";
} else {
    echo 'Something bad happened when I tried to run "' . $sql . '". Details: "' . mysqli_error($conn) . '"';
}

//we can now redirect to another page once completed
header('location:../../index.html')
?>