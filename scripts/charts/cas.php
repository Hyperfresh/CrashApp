<?php
            $dbResult = "SELECT * FROM `crashdata` WHERE location_id LIKE " . $location;
            $dbResult = mysqli_query($conn, $dbResult);
            $crashes = [];
            $counts = [];
            if (mysqli_num_rows($dbResult) > 0) {
                while ($row = mysqli_fetch_assoc($dbResult)) {

                    // speed against location
                    array_push($counts, $row["casualties"]);
                    // id to readable
                    $result = 'SELECT collisionType, positionType FROM `collisiontype` WHERE crashtype_id LIKE ' . $row['collisiontype_id'];
                    $result = mysqli_query($conn, $result);
                    $result = mysqli_fetch_assoc($result);
                    $result = $result['collisionType'] . "@" . $result['positionType'] . ", " . $row["time"] . "@" . $row["month"] . " " . $row["year"];
                    array_push($crashes, $result);
                }
            }
            ?>
            <canvas id="PrintChartHere" width="50" height="50"></canvas>
            <script>
                var ctx = document.getElementById('PrintChartHere').getContext('2d');
                var myChart = new Chart(ctx, {
                    type: 'bar', //pie,line
                    data: {
                        // x axis data labels
                        labels: <?php
                            echo json_encode($crashes);
                        ?>,
                        datasets: [{
                            label: 'Casualties',
                            // values to make chart
                            data: <?php
                            echo json_encode($counts);
                            ?>,
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
                                text: <?php
                                echo json_encode("Casualties of crashes in " . $suburb);
                                ?>,
                            }
                        },
                        scales: {
                            //set options for x axis
                            x: {
                                title: {
                                    display: true,
                                    text: 'Crash types' //label
                                }
                            },
                            //set options for y axis
                            y: {
                                title: {
                                    display: true,
                                    text: 'Casualties' //label
                                }
                            },
                        }
                    }
                });
                myChart.canvas.parentNode.style.height = '500px';
                myChart.canvas.parentNode.style.width = '500px';
            </script>