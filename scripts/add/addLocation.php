<?php
include('../connect.php');

//get values from post
//good practice to validate these values first

$suburb = validate($_POST['suburb']);
$lga = validate($_POST['lga']);
$postcode = intval($_POST['postcode']);
$generalLocation = $_POST['generalLocation'];

//check data for no special characters

function validate($data) {
    $data = htmlspecialchars($data);
    return trim($data);
}

//insert them into DB using SQL statement
$sql = "INSERT INTO location(suburb, lga, postcode, generalLocation) VALUES ('$suburb', '$lga', $postcode, '$generalLocation')";
echo "<script>console.log('Attempting to run \'$sql\'...')</script>";
if(mysqli_query($conn, $sql)){
    echo "<script>alert('Data added.')</script>";
} else {
    echo "<script>console.log('Something bad happened when I tried to run " . $sql . ". Details: \"" . mysqli_error($conn) . "\"')</script>";
    echo "<script>alert('Something bad happened when I tried to run " . $sql . ". Details: \"" . mysqli_error($conn) . "\"')</script>";
}

//we can now redirect to another page once completed
    header('location:../../index.html');
?>