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

                    $length = $max['year']-$min['year'];

                    echo "<p>There were <b>" . mysqli_num_rows($result) . " crashes</b> in " . $suburb . " over the past " . $length . " years.<br></p>";

                    // Count how many of those were under DUI or Drugs
                    $drugs = "SELECT * FROM `crashdata` WHERE Drugs = 1";
                    $drugs = mysqli_query($conn, $drugs);
                    $drugs = mysqli_fetch_assoc($drugs);

                    $dui = "SELECT * FROM `crashdata` WHERE DUI = 1";
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

                    echo "<p><b>" . round($duiper, 2) . "% of which involved alcohol</b>, while <b>" . round($drugper, 2) . "% involved drugs</b>.<br></p>";

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
            <canvas id="PrintChartHere" width="50" height="50"></canvas>
                <script>
                    var ctx = document.getElementById('PrintChartHere').getContext('2d');
                    var myChart = new Chart(ctx, {
                        type: 'bar', //pie,line
                        data: {
                            // x axis data labels
                            labels: ['Orange', 'Blue', 'Yellow', 'Green', 'Purple', 'Orange'],
                            datasets: [{
                                label: 'Number of Votes',
                                // values to make chart
                                data: [20, 19, 300, 500, 600, 600],
                                // color of each data aligned by index
                                backgroundColor: [
                                    'orange',
                                    'rgba(54, 162, 235, 0.2)',
                                    '#ffff00',
                                    'rgba(75, 192, 192, 0.2)',
                                    'rgba(153, 102, 255, 0.2)',
                                    'rgba(255, 159, 64, 0.2)'
                                ],
                                //border color of each data
                                borderColor: [
                                    'red',
                                    'rgba(54, 162, 235, 1)',
                                    '#ffff00',
                                    'rgba(75, 192, 192, 1)',
                                    'rgba(153, 102, 255, 1)',
                                    'rgba(255, 159, 64, 1)'
                                ],
                                borderWidth: 1
                            }]
                        },
                        options: {
                            responsive: true,
                            maintainAspectRatio: true,
                            //graph main title
                            plugins: {
                                title: {
                                    display: true,
                                    text: 'Favourite Colour Vote'
                                }
                            },
                            scales: {
                                //set options for x axis
                                x:{
                                    title: {
                                        display: true,
                                        text: 'Colour Vote' //label
                                    }
                                },
                                //set options for y axis
                                y:{
                                    title: {
                                        display: true,
                                        text: 'Count' //label
                                    }
                                },
                            }
                        }
                    });
                    myChart.canvas.parentNode.style.height = '500px';
                    myChart.canvas.parentNode.style.width = '500px';
                </script>
        </div>
        <div class="table">
                <p>goodbye</p>
        </div>
    </body>
</html>