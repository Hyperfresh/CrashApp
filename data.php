<!DOCTYPE html>
<html lang="en">

<head>
    <title>Results</title>
    <link rel="stylesheet" href="style.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>

<body>
    <?php
    include('./scripts/connect.php');
    ?>
    <a href="index.html">Back to menu</a>
    <h1>IntelliResults</h1>
    <form method="POST">
        <p>
            Select a location:
            <select name="location">
                <?php
                $query = "SELECT * FROM location ORDER BY postcode ASC";
                $result = mysqli_query($conn, $query);
                if (mysqli_num_rows($result) > 0) {
                    while ($row = mysqli_fetch_assoc($result)) {
                        echo '<script>console.log("' . $row['location_id'] . $row['suburb'] . $row['postcode'] . '")</script>';
                        echo '<option value = ' . $row['location_id'] . '>'
                            . $row['suburb'] . ' ' . $row['postcode'] . '</option>';
                    }
                }
                ?>
            </select>
            <input type="submit" name="submit" />
        </p>
    </form>
    <div class="results-grid">
        <div class="intelliresults">
            <?php
            //first convert location id to location
            $location;
            if ($_SERVER["REQUEST_METHOD"] == "POST") {
                $location = $_POST["location"];
                $query = mysqli_query($conn, "SELECT suburb FROM location WHERE `location_id` LIKE " . $location);
                $result = mysqli_fetch_assoc($query);
                $suburb = $result['suburb'];
            } else {
                $query = mysqli_query($conn, "SELECT location_id FROM location WHERE `suburb` LIKE 'Adelaide'");
                $result = mysqli_fetch_assoc($query);
                $location = $result['location_id'];
                $suburb = "Adelaide";
            }

            // next get crash data for location
            $sql = "SELECT collisiontype_id,speed,casualties,highestSeverity,Drugs,DUI,location_id,year,month,time FROM crashdata WHERE `location_id` LIKE " . $location . "";
            //run the query and store the result of the query in a variable called $result
            $result = mysqli_query($conn, $sql); //run the query
            // print table header and opening table tag
            if (mysqli_num_rows($result) > 0) {

                // Worded intelligent response.
                // Count crashes in area between years
                $max = "SELECT year FROM `crashdata` WHERE year = ( SELECT MAX(year) FROM `crashdata` )";
                $max = mysqli_query($conn, $max);
                $max = mysqli_fetch_assoc($max);

                $min = "SELECT year FROM `crashdata` WHERE year = ( SELECT MIN(year) FROM `crashdata` )";
                $min = mysqli_query($conn, $min);
                $min = mysqli_fetch_assoc($min);

                $length = $max['year'] - $min['year'];

                echo "<p>There were <b>" . mysqli_num_rows($result) . " crashes</b> in " . $suburb . " over the past " . $length . " years.<br></p>";

                // Count how many of those were under DUI or Drugs
                $drugs = "SELECT * FROM `crashdata` WHERE Drugs = 1 AND location_id LIKE " . $location;
                $drugs = mysqli_query($conn, $drugs);
                $drugs = mysqli_fetch_assoc($drugs);

                $dui = "SELECT * FROM `crashdata` WHERE DUI = 1 AND location_id LIKE " . $location;
                $dui = mysqli_query($conn, $dui);
                $dui = mysqli_fetch_assoc($dui);

                if ($drugs) { // Is there a result in "drugs"?
                    $drugper = $drugs['Drugs'] / mysqli_num_rows($result);
                } else {
                    $drugper = 0;
                }

                if ($dui) { // " " "dui"?
                    $duiper = $dui['DUI'] / mysqli_num_rows($result);
                } else {
                    $duiper = 0;
                }

                echo "<p><b>" . round($duiper, 2) * 100 . "% of which involved alcohol</b>, while <b>" . round($drugper, 2) * 100 . "% involved drugs</b>.<br></p>";

                // Most common crash
                $common = "SELECT collisiontype_id,
                        COUNT(collisiontype_id) AS `value_occurrence`
                        FROM `crashdata`
                        GROUP BY collisiontype_id
                        ORDER BY `value_occurrence` DESC
                        LIMIT 2";
                $common = mysqli_query($conn, $common);
                $types = [];
                while ($row = mysqli_fetch_row($common)) {
                    array_push($types, $row);
                }
                // convert collision type id
                $type1 = "SELECT collisionType FROM `collisiontype` WHERE crashtype_id LIKE " . $types[0][0];
                $type1 = mysqli_query($conn, $type1);
                $type1 = mysqli_fetch_assoc($type1);

                $type2 = "SELECT collisionType FROM `collisiontype` WHERE crashtype_id LIKE " . $types[1][0];
                $type2 = mysqli_query($conn, $type2);
                $type2 = mysqli_fetch_assoc($type2);

                // print
                echo "<p>The most common crash type was <b>" . $type1['collisionType'] . "</b>, followed by <b>" . $type2['collisionType'] . "</b>.<br></p>";
            } else {
                echo "No results for " . $suburb . ".";
            }
            ?>
        </div>
        <div class="chart">
            <!-- First create the data to display in JS with PHP code -->
            <form method="GET">
                <p>Compare crash types with: <select name="compare">
                        <option value="speed">speed</option>
                        <option value="cas">casualties</option>
                    </select>
                <input type="submit" name="submit">
                </p><br>
            </form>
            <?php
                if ($_SERVER['REQUEST_METHOD'] == 'GET') {
                    if (!isset($_GET['compare'])) { include('scripts/charts/speed.php'); }
                    else { 
                        include('scripts/charts/' . $_GET['compare'] . '.php');
                    }
                } else { include('scripts/charts/speed.php'); }
            ?>
        </div>
        <div class="table">
            <?php
            //store the query into a variable
            $sql = "SELECT collisiontype_id,speed,casualties,highestSeverity,Drugs,DUI,year,month,time FROM crashdata WHERE location_id LIKE " . $location;
            //run the query and store the result of the query in a variable called $result
            $result = mysqli_query($conn, $sql); //run the query
            // print table header and opening table tag
            if (mysqli_num_rows($result) > 0) {
                echo "<table>
                            <tr>
                                <th>Time (24h@MonYYYY)</th>
                                <th>Collision type</th>
                                <th>Speed (km/h)</th>
                                <th>Casualties</th>
                                <th>Highest severity</th>
                                <th>Drugs</th>
                                <th>Driver under influence</th>
                            </tr>";
                // output data of each row
                while ($row = mysqli_fetch_assoc($result)) {
                    echo "<tr><td>" . $row["time"] . "@" . $row["month"] . " " . $row["year"] . "</td>";

                    // Collision type ID to readable
                    $colsql = "SELECT * FROM `collisiontype` WHERE `crashType_id` = " . $row['collisiontype_id'];
                    $colres = mysqli_query($conn, $colsql);
                    if (mysqli_num_rows($colres) > 0) {
                        echo "<script>console.log('test')</script>";
                        while ($colrow = mysqli_fetch_assoc($colres)) {
                            echo '<script>console.log("' . $colrow['collisionType'] . '@' . $colrow['positionType'] . '")</script>';
                            echo "<td>" . $colrow['collisionType'] . '@' . $colrow['positionType'] . "</td>";
                        }
                    } else {
                        echo "<td>No data</td>";
                    }
                    // Speed, casualties and highest severity
                    echo "<td>" . $row['speed'] . "</td>
                                <td>" . $row['casualties'] . "</td>
                                <td>" . $row['highestSeverity'] . "</td>";
                    // Drugs
                    if ($row['Drugs'] == 1) {
                        $Drugs = "Yes";
                    } else {
                        $Drugs = "No";
                    }
                    echo "<td>" . $Drugs . "</td>";
                    // Driver under influence
                    if ($row['DUI'] == 1) {
                        $DUI = "Yes";
                    } else {
                        $DUI = "No";
                    }
                    echo "<td>" . $DUI . "</td>";
                    // End row
                    echo "</tr>";
                }
                echo "</table>";
                //close connection
                mysqli_close($conn);
            } else {
                echo "No data to show.";
            }
            ?>
        </div>
    </div>
</body>

</html>