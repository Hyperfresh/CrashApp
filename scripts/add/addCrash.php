<?php
include('../connect.php');

$crash = intval($_POST['type']); //intval will cast to integer
$speed = intval($_POST['speed']);
$cas = intval($_POST['casualties']);
$highSev = intval($_POST['highestSeverity']);
$drugs = $_POST['drugs'];
$dui = $_POST['dui'];

$location = intval($_POST['location']); //intval will cast to integer
$year = intval($_POST['year']);
$month = $_POST['month'];

if ($dui == 'on') {
    $dui = 1;
} else {
    $dui = 0;
}

if ($drugs == 'on') {
    $drugs = 1;
} else {
    $drugs = 0;
}

function createTime($h,$m) {
    $ho = intval($h*100);
    $mi = intval($m);
    $out = intval($ho + $mi);
    echo '<script>console.log("' . $out . '")</script>';
    return $out;
}

$time = createTime($_POST['hour'],$_POST['min']);

if ($year < 2010 || $year > 2021) {
    echo "<script>alert('ERROR: Year cannot be later than 2021 or be earlier than 2010, you inputted $year.')</script>";
    return;
}

//insert them into DB using SQL statement
$sql = "INSERT INTO crashdata(collisiontype_id,speed,casualties,highestSeverity,Drugs,DUI,location_id,year,month,time) VALUES ($crash,$speed,$cas,'$highSev',$drugs,$dui,$location,$year,'$month',$time)";
echo "<script>console.log('Attempting to run \'$sql\'...')</script>";
if(mysqli_query($conn, $sql)){
    echo "<script>alert('Data added.')</script>";
} else {
    echo 'Something bad happened when I tried to run "' . $sql . '". Details: "' . mysqli_error($conn) . '"';
}

//we can now redirect to another page once completed
header('location:../../index.html')
?>