<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="css/style.css">
    <link href="https://fonts.googleapis.com/css?family=Roboto" rel="stylesheet">
    <title>MLB Standings</title>
</head>
<body>
    <!-- <div class="bgImg"></div> -->
    <header>
        <h1>MLB Standings</h1>
    </header>
    <main>
        <?php
            //include databaseConnection
            include('databaseConnection.php');

            //connect to database
            $link = connectToDatabase();
        ?>
        <div class="ALNL">
            <h4 class = "ALHeader">American League West</h4>
            <table class="Division">
                <tr>
                    <th></th> <!--Blank header-->
                    <th>Wins</th>
                    <th>Losses</th>
                    <th>Win%</th>
                    <th>GB</th>
                    <th>L10</th>
                    <th>Streak</th>
                </tr>
                <?php
                    //select the data from the table ALWest
                    $sql = 'SELECT teamName, wins, losses, winPerc, gamesBack, lastTen, streak FROM alwest ORDER BY winPerc DESC';

                    //results gives an array of containing query results
                    $result = mysqli_query($link, $sql) or die('SQL syntax error: '.mysqli_error($link));

                    while($row = mysqli_fetch_array($result)){
                        echo "<tr>
                                <td class = 'team'>$row[teamName]</td>
                                <td>$row[wins]</td>
                                <td>$row[losses]</td>
                                <td>$row[winPerc]</td>
                                <td>$row[gamesBack]</td>
                                <td>$row[lastTen]</td>
                                <td>$row[streak]</td>
                             </tr>";
                    }
                ?>
            </table>
        </div>
        <div class="ALNL">
            <h4 class = "ALHeader">American League Central</h4>
            <table class="Division">
                <tr>
                    <th></th> <!--Blank header-->
                    <th>Wins</th>
                    <th>Losses</th>
                    <th>Win%</th>
                    <th>GB</th>
                    <th>L10</th>
                    <th>Streak</th>
                </tr>
                <?php
                    //select the data from the table ALWest
                    $sql = 'SELECT teamName, wins, losses, winPerc, gamesBack, lastTen, streak FROM alcentral ORDER BY winPerc DESC';

                    //results gives an array of containing query results
                    $result = mysqli_query($link, $sql) or die('SQL syntax error: '.mysqli_error($link));

                    while($row = mysqli_fetch_array($result)){
                        echo "<tr>
                                <td class = 'team'>$row[teamName]</td>
                                <td>$row[wins]</td>
                                <td>$row[losses]</td>
                                <td>$row[winPerc]</td>
                                <td>$row[gamesBack]</td>
                                <td>$row[lastTen]</td>
                                <td>$row[streak]</td>
                             </tr>";
                    }
                ?>
            </table>
        </div>
        <div class="ALNL">
            <h4 class = "ALHeader">American League East</h4>
            <table class="Division">
                <tr>
                    <th></th> <!--Blank header-->
                    <th>Wins</th>
                    <th>Losses</th>
                    <th>Win%</th>
                    <th>GB</th>
                    <th>L10</th>
                    <th>Streak</th>
                </tr>
                <?php
                    //select the data from the table ALEast
                    $sql = 'SELECT teamName, wins, losses, winPerc, gamesBack, lastTen, streak
                    FROM aleast ORDER BY winPerc DESC';

                    //results gives an array of containing query results
                    $result = mysqli_query($link, $sql) or die('SQL syntax error: '.mysqli_error($link));

                    while($row = mysqli_fetch_array($result)){
                        echo "<tr>
                                <td class = 'team'>$row[teamName]</td>
                                <td>$row[wins]</td>
                                <td>$row[losses]</td>
                                <td>$row[winPerc]</td>
                                <td>$row[gamesBack]</td>
                                <td>$row[lastTen]</td>
                                <td>$row[streak]</td>
                             </tr>";
                    }
                ?>
            </table>
        </div>
        <div class="ALNL">
            <h4 class = "NLHeader">National League West</h4>
            <table class="Division">
                <tr>
                    <th></th> <!--Blank header-->
                    <th>Wins</th>
                    <th>Losses</th>
                    <th>Win%</th>
                    <th>GB</th>
                    <th>L10</th>
                    <th>Streak</th>
                </tr>
                <?php
                    //select the data from the table ALWest
                    $sql = 'SELECT teamName, wins, losses, winPerc, gamesBack, lastTen, streak
                    FROM nlwest ORDER BY winPerc DESC';

                    //results gives an array of containing query results
                    $result = mysqli_query($link, $sql) or die('SQL syntax error: '.mysqli_error($link));

                    while($row = mysqli_fetch_array($result)){
                        echo "<tr>
                                <td class = 'team'>$row[teamName]</td>
                                <td>$row[wins]</td>
                                <td>$row[losses]</td>
                                <td>$row[winPerc]</td>
                                <td>$row[gamesBack]</td>
                                <td>$row[lastTen]</td>
                                <td>$row[streak]</td>
                             </tr>";
                    }
                ?>
            </table>
        </div>
        <div class="ALNL">
            <h4 class = "NLHeader">Natinoal League Central</h4>
            <table class="Division">
                <tr>
                    <th></th> <!--Blank header-->
                    <th>Wins</th>
                    <th>Losses</th>
                    <th>Win%</th>
                    <th>GB</th>
                    <th>L10</th>
                    <th>Streak</th>
                </tr>
                <?php
                    //select the data from the table ALWest
                    $sql = 'SELECT teamName, wins, losses, winPerc, gamesBack, lastTen, streak
                    FROM nlcentral ORDER BY winPerc DESC';

                    //results gives an array of containing query results
                    $result = mysqli_query($link, $sql) or die('SQL syntax error: '.mysqli_error($link));

                    while($row = mysqli_fetch_array($result)){
                        echo "<tr>
                                <td class = 'team'>$row[teamName]</td>
                                <td>$row[wins]</td>
                                <td>$row[losses]</td>
                                <td>$row[winPerc]</td>
                                <td>$row[gamesBack]</td>
                                <td>$row[lastTen]</td>
                                <td>$row[streak]</td>
                             </tr>";
                    }
                ?>
            </table>
        </div>
        <div class="ALNL">
            <h4 class = "NLHeader">National League East</h4>
            <table class="Division">
                <tr>
                    <th></th> <!--Blank header-->
                    <th>Wins</th>
                    <th>Losses</th>
                    <th>Win%</th>
                    <th>GB</th>
                    <th>L10</th>
                    <th>Streak</th>
                </tr>
                <?php
                    //select the data from the table ALWest
                    $sql = 'SELECT teamName, wins, losses, winPerc, gamesBack, lastTen, streak
                    FROM nleast ORDER BY winPerc DESC';

                    //results gives an array of containing query results
                    $result = mysqli_query($link, $sql) or die('SQL syntax error: '.mysqli_error($link));

                    while($row = mysqli_fetch_array($result)){
                        echo "<tr>
                                <td class = 'team'>$row[teamName]</td>
                                <td>$row[wins]</td>
                                <td>$row[losses]</td>
                                <td>$row[winPerc]</td>
                                <td>$row[gamesBack]</td>
                                <td>$row[lastTen]</td>
                                <td>$row[streak]</td>
                             </tr>";
                    }
                ?>
            </table>
        </div>
    </main>
</body>
</html>