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
    <a href="index.html">Back to menu</a>
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
        //store the query into a variable
        $sql = "SELECT collisiontype_id,location_id,year,month,time FROM crashdata";
        //run the query and store the result of the query in a variable called $result
        $result = mysqli_query($conn, $sql); //run the query
        // print table header and opening table tag
        if (mysqli_num_rows($result) > 0) {
            echo "<table>
            <tr>
                <th>Time</th>
                <th>Collision type</th>
                <th>Location</th>
            </tr>";
            // output data of each row
            while($row = mysqli_fetch_assoc($result)) {
                echo "<tr><td>".$row["time"]."@".$row["month"]." ".$row["year"]."</td>";

                $colsql = "SELECT * FROM `collisiontype` WHERE `crashType_id` = " . $row['collisiontype_id'];
                $colres = mysqli_query($conn, $colsql);
                if (mysqli_num_rows($colres) > 0) {
                    echo "<script>console.log('test')</script>";
                    while($colrow = mysqli_fetch_assoc($colres)){
                        if ($colrow['DUI'] == 1) {
                            $DUI = "driver under influence";
                        } else {
                            $DUI = "driver not under influence";
                        }
                        echo '<script>console.log("' . $colrow['collisionType'] . '@' . $colrow['speed'] . 'km/h, ' . $colrow['casualties'] . ' casualties - ' . $DrugsInvolved.", ".$DUI.'")</script>';
                        echo "<td>". $colrow['collisionType'] . '@' . $colrow['speed'] . 'km/h, ' . $colrow['casualties'] . ' casualties - ' . $DrugsInvolved.", ".$DUI."</td>";
                        echo "<script>console.log('test3')</script>";
                    }
                } else {
                    echo "<td>No data</td>";
                }
                $locsql = "SELECT * FROM `location` WHERE `location_id` = " . $row['location_id'];
                $locres = mysqli_query($conn, $locsql);
                if (mysqli_num_rows($locres) > 0) {
                    while($locrow = mysqli_fetch_assoc($locres)) {
                        echo "<td>". $locrow['suburb'] . ", " . $locrow['lga'] . " " . $locrow['postcode'] . "</td>";
                    }
                } else {
                    echo "<td>No data</td>";
                }
                echo "</tr>";
            }
            echo "</table>";
            //close connection
            mysqli_close($conn);
        } else {
            echo "No data to show.";
        }
    ?>
</body>