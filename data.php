<!DOCTYPE html>
<html lang="en">
    <head>
        <title>Results</title>
        <link rel="stylesheet" href="style.css">
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
                        $result= mysqli_query($conn, $query);
                        if (mysqli_num_rows($result) > 0) {
                            while ($row = mysqli_fetch_assoc($result)) {
                                echo '<script>console.log("' . $row['location_id'] . $row['suburb'] . $row['postcode'] . '")</script>';
                                echo '<option value = ' . $row['location_id'] . '>' 
                                    . $row['suburb'].' '.$row['postcode'].'</option>';
                            }
                        }
                    ?> 
                </select>
                <input type="submit" name="submit"/>
            </p>
        </form>
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
            $sql = "SELECT collisiontype_id,location_id,year,month,time FROM crashdata WHERE `location_id` LIKE " . $location . "";
            //run the query and store the result of the query in a variable called $result
            $result = mysqli_query($conn, $sql); //run the query
            // print table header and opening table tag
            if (mysqli_num_rows($result) > 0) {
                // output data of each row
                $results = mysqli_fetch_assoc($result);
                echo json_encode($results);
                echo "<p>There were <b>" . count($results) . " crashes</b> in " . $suburb . ".<br>" . "</p>";
            } else {
                echo "No results for " . $suburb . ".";
            }
        ?>
    </body>
</html>