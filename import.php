<?php

function conlog($info) {
    echo '<script>console.log("' . $info . '")</script>';
}

function alert($info) {
    echo '<script>alert("' . $info . '")</script>';
}

include('connect.php');
//check to see if form sent and submit button value pressed
if (isset($_POST["submit"]) && $_FILES['file']['name']) {
    conlog("File okay!");
    // access FILES go to item file and access name of stored file
    //explode the form name file and access its uploaded filename from the name property i.e. data.csv
    $filename = explode(".",$_FILES['file']['name']);
    //check to see if file has a csv extension_loaded
    if ($filename[1] =='csv') {
        conlog("Type okay!");
        //create a handler to read through file r
        $handle = fopen($_FILES['file']['name'],"r");
        if (!$handle) {
            alert("Handle couldn't be created. Please try again later.");
            return;
        }
        conlog("Opening stream with $handle.");
        //read through the document line by line and grab each cell and store in variable
        while ($data = fgetcsv($handle)) {
            //assign value from CSV to variable from row and check for SQL injection
            //mysqli_real_escape_string is a built in method to validate and remove unwanted characters
            $location = mysqli_real_escape_string($conn, $data[0]); // only id type

            $type = mysqli_real_escape_string($conn, $data[1]);
            $speed = mysqli_real_escape_string($conn, $data[2]);
            $cas = mysqli_real_escape_string($conn, $data[3]);
            $sev = mysqli_real_escape_string($conn, $data[4]);
            $drugs = mysqli_real_escape_string($conn, $data[5]);
            $dui = mysqli_real_escape_string($conn, $data[6]);

            $year = mysqli_real_escape_string($conn, $data[7]);
            $month = mysqli_real_escape_string($conn, $data[8]);
            $time = mysqli_real_escape_string($conn, $data[9]);

            conlog("Saw: $data");
            conlog("Got: $location $type $speed $cas $sev $drugs $dui $year $month $time");

            //you could build a function to test value ranges etc. = extension work
            //create a sql statement with variables for insert
            $crash = "INSERT INTO collisionType(collisionType, speed, casualties, highestSeverity, Drugs, DUI) VALUES ('$type', $speed, $cas, '$sev', $drugs, $dui)";

            //run query using functional procedure ->
	        if (mysqli_query($conn, $crash)) { 
                echo "<script>console.log('Ran \"" . $crash . "\" with no error.')</script>";
            } else {
                echo '<script>console.log("ERROR: Attempted to run \"' . $crash . "\" and got \"" . $conn->error . '\" back.")';
            }

            $search = "SELECT * FROM `collisiontype` WHERE `collisionType` LIKE '$type' AND `speed` = $speed AND `casualties` = $cas AND `highestSeverity` LIKE '$sev'";
            $result = mysqli_query($conn, $sql);
            $row = mysqli_fetch_assoc($result);

            $sql = "INSERT INTO crashdata(collisiontype_id,location_id,year,month,time) VALUES (" . $row['collisiontype_id'] . ",$location,$year,'$month',$time)";

            $count = 0;

	        //run query using functional procedure ->
	        if (mysqli_query($conn, $sql)) { 
                $count = $count + 1;
                echo "<script>console.log('Ran \"" . $crash . "\" with no error.')</script>";
            } else {
                echo '<script>console.log("ERROR: Attempted to run \"' . $crash . "\" and got \"" . $conn->error . '\" back.")';
            }
        }
        //close the file handler
        fclose($handle);
        //let the user know they are done via JS notification prompt
        echo "<script>alert('Imported " . $count . " entries.')</script>";
    } else {
        echo "<script>alert('This isn't a CSV file.')</script>";
    }
} else {
    echo "<script>alert('An error occured. Please check that you have uploaded a file.')</script>";
}
header('location:../importCSV.html');
?>