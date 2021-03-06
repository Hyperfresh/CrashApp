<!DOCTYPE html>
<html lang="en">
<head>
    <title>Add crash</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<?php
    include('./scripts/connect.php');
?>
<a href="index.html">Back to menu</a>
<form action="scripts/add/addcrash.php" method="post">
 <!-- Dynamic Drop down box of sports which looks up values from db for students -->    
    <h1>Add crash...</h1>
    Select Location: <select name ="location">
        <!-- Start PHP -->
        <?php
        //sql statement 
        $sql = "select * from location order by postcode asc";
        //run query and store results in memory
        $result = mysqli_query($conn, $sql);
        //if results were found then print out
        if (mysqli_num_rows($result) > 0) {
            // output data of each row using a while statement to loop through array
            while ($row = mysqli_fetch_assoc($result)) {
                echo '<script>console.log("' . $row['location_id'] . $row['suburb'] . $row['postcode'] . '")</script>';
                echo '<option value = ' . $row['location_id'] . '>' 
                    . $row['suburb'].' '.$row['postcode'].'</option>';
            }
        }
        ?>
    <!-- End PHP -->
    </select><br>
    <!-- Dynamic Drop down box of sports which looks up values from db for activities -->    
    Select crash type: <select name ="type">
        <!-- Start PHP -->
        <?php
        //sql statement
        $sql = "select * from collisiontype order by crashtype_id asc";
        //run query and store results in memory
        $result2 = mysqli_query($conn, $sql);
        //if results were found then print out
        if (mysqli_num_rows($result2) > 0) {
            // output data of each row using a while statement to loop through array
            while ($row = mysqli_fetch_assoc($result2)) {
                echo '<script>console.log("' . $row['crashtype_id'] . $row['collisionType'] . $row['positionType'] . '")</script>';
                echo '<option value=' . $row['crashtype_id'] . '>' . $row['collisionType'] . '@' . $row['positionType'] . '</option>';
            }
        }
        ?>
        </select><br>
    Speed: <input name="speed" maxlength="3" type="number" max="110" required><br>
                        Casualties: <input name="casualties" maxlength="2" type="number" required><br>
                        Highest severity: <select name="highestSeverity">
                                <option value="Fat">Fatal injury</option>
                                <option value="Ser">Serious injury</option>
                                </select><br>
                        Drugs: <input name="drugs" type="checkbox"><br>
                        DUI: <input name="dui" type="checkbox"><br>
    <!-- End PHP -->
    </select><br>
    Year of crash: <input name="year" type="number" min="2010" max="2021" required> <br>
    Month of crash: <select name="month">
        <option value="Jan">January</option>
        <option value="Feb">February</option>
        <option value="Mar">March</option>
        <option value="Apr">April</option>
        <option value="May">May</option>
        <option value="Jun">June</option>
        <option value="Jul">July</option>
        <option value="Aug">August</option>
        <option value="Sep">September</option>
        <option value="Oct">October</option>
        <option value="Nov">November</option>
        <option value="Dec">December</option>
    </select><br>
    Time (24h): <input name="hour" type="number" min="0" max="23" required>:<input name='min' type='number' min='0' max='59' required>
    <input type="submit">
</form>
</body>
</html>