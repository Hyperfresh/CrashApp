<?php
include('../connect.php');

$crash = intval($_POST['type']); //intval will cast to integer
$location = intval($_POST['location']); //intval will cast to integer
$year = intval($_POST['year']);
$month = $_POST['month'];

function createTime($h,$m) {
    $ho = intval($h*100);
    $mi = intval($m);
    $out = intval($ho + $mi);
    echo '<script>console.log("' . $out . '")</script>';
    return $out;
}

$time = createTime($_POST['hour'],$_POST['min']);

$return = 'location:../../index.html';

if ($year < 2010 || $year > 2021) {
    echo "<script>alert('ERROR: Year cannot be later than 2021 or be earlier than 2010, you inputted $speed.')</script>";
    header($return);
    return;
}

//insert them into DB using SQL statement
$sql = "INSERT INTO crashdata(collisiontype_id,location_id,year,month,time) VALUES ($crash,$location,$year,'$month',$time)";
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