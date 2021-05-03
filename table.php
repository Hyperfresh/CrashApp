<!DOCTYPE html>
<html lang="en">
<head>
    <title>Table</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <?php
        include('./scripts/connect.php');
    ?>
    <h1>Locations</h1>
    <?php
        //store the query into a variable
        $sql = "SELECT location_id, suburb, lga, postcode, generalLocation FROM location";
        //run the query and store the result of the query in a variable called $result
        $result = mysqli_query($conn, $sql); //run the query
        // print table header and opening table tag
        if (mysqli_num_rows($result) > 0) {
            echo "<table>
            <tr>
                <th>Location ID</th>
                <th>Suburb</th>
                <th>LGA</th>
                <th>General Location</th>
            </tr>";
            // output data of each row
            while($row = mysqli_fetch_assoc($result)) {
                echo "<tr>
                    <td>".$row["location_id"]."</td>
                    <td>".$row["suburb"]."</td>
                    <td>".$row["lga"]."</td>
                    <td>".$row['generalLocation']."</td>
                </tr>";
            }
            echo "</table>";
        } else {
            echo "No data to show.";
        }
    ?>
    <h1>Crash types</h1>
    <?php
        //store the query into a variable
        $sql = "SELECT crashType_id, collisionType, speed, casualties, highestSeverity, Drugs, DUI FROM collisiontype";
        //run the query and store the result of the query in a variable called $result
        $result = mysqli_query($conn, $sql); //run the query
        // print table header and opening table tag
        if (mysqli_num_rows($result) > 0) {
            echo "<table>
            <tr>
                <th>Crash Type ID</th>
                <th>Collision Type</th>
                <th>Speed</th>
                <th>Casualties</th>
                <th>Highest severity</th>
                <th>Drugs & DUI</th>
            </tr>";
            // output data of each row
            while($row = mysqli_fetch_assoc($result)) {
                echo "<tr>
                    <td>".$row["crashType_id"]."</td>
                    <td>".$row["collisionType"]."</td>
                    <td>".$row["speed"]."</td>
                    <td>".$row['casualties']."</td>
                    <td>".$row['highestSeverity']."</td>";
                if ($row['Drugs'] == 1) {
                    $DrugsInvolved = "Drugs involved";
                } else {
                    $DrugsInvolved = "No drugs involved";
                }
                if ($row['DUI'] == 1) {
                    $DUI = "driver under influence";
                } else {
                    $DUI = "driver not under influence";
                }
                echo "<td>".$DrugsInvolved.", ".$DUI."</td>";
                echo "</tr>";
            }
            echo "</table>";
        } else {
            echo "No data to show.";
        }
    ?>
    <h1>Collision data</h1>
    <?php
        /*
        //store the query into a variable
        $sql = "SELECT ccollisiontype_id,location_id,year,month,time FROM crashdata";
        //run the query and store the result of the query in a variable called $result
        $result = mysqli_query($conn, $sql); //run the query
        // print table header and opening table tag
        if (mysqli_num_rows($result) > 0) {
            echo "<table>
            <tr>
                <th>Time of occurence</th>
                <th>Collision Type</th>
                <th>Speed</th>
                <th>Casualties</th>
                <th>Highest severity</th>
                <th>Drugs & DUI</th>
            </tr>";
            // output data of each row
            while($row = mysqli_fetch_assoc($result)) {
                echo "<tr>
                    <td>".$row["crashType_id"]."</td>
                    <td>".$row["collisionType"]."</td>
                    <td>".$row["speed"]."</td>
                    <td>".$row['casualties']."</td>
                    <td>".$row['highestSeverity']."</td>";
                if ($row['Drugs'] == 1) {
                    $DrugsInvolved = "Drugs involved";
                } else {
                    $DrugsInvolved = "No drugs involved";
                }
                if ($row['DUI'] == 1) {
                    $DUI = "driver under influence";
                } else {
                    $DUI = "driver not under influence";
                }
                echo "<td>".$DrugsInvolved.", ".$DUI."</td>";
                echo "</tr>";
            }
            echo "</table>";
            //close connection
            mysqli_close($conn);
        } else {
            */
            echo "No data to show.";
        //}
    ?>
</body>