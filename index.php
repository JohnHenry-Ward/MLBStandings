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
    <div class="bgImg"></div>
    <header>
        <h1>MLB Standings</h1>
        <nav>
            <ul>
                <li class = 'alWest'><a href = '#alwest'>AL West</a></li>
                <li class = 'alCentral'><a href = '#alcentral'>AL Central</a></li>
                <li class = 'alEast'><a href = '#aleast'>AL East</a></li>
                <li class = 'nlWest'><a href = '#nlwest'>NL West</a></li>
                <li class = 'nlCentral'><a href = '#nlcentral'>NL Central</a></li>
                <li class = 'nlEast'><a href = '#nleast'>NL East</a></li>
            </ul>
            </div>
        </nav>
    </header>
    <main>
        <?php
            //include databaseConnection
            include('databaseConnection.php');

            //connect to database
            $link = connectToDatabase();

            //array containing division names
            $divisionName = ['alwest', 'alcentral', 'aleast', 'nlwest', 'nlcentral', 'nleast'];

            //update step 1: fill textbox
            if(!empty($_GET['updateTeam'])){
                $updateTeam = $_GET['updateTeam'];

                $sql = "SELECT teamName, wins, losses, winPerc, gamesBack, lastTen, streak, divisionID FROM mlbstandings WHERE teamName = '$updateTeam'";

                $result = mysqli_query($link, $sql) or die('SQL syntax error: '.mysqli_error($link));

                $row = mysqli_fetch_array($result);

                $wins = $row['wins'];
                $losses = $row['losses'];
                $winPerc = $row['winPerc'];
                $gamesBack = $row['gamesBack'];
                $lastTen = $row['lastTen'];
                $streak = $row['streak'];
                $divisionID = $row['divisionID'];
                
        ?>
                <div class = 'editPopup'>
                        <h2>Updating team: <?php echo $updateTeam ?></h2>
                        <form>
                            <label>Wins:</label>
                            <input name = 'wins' type = 'text' value = <?php echo $wins ?>> <br>
                            <label>Losses:</label>
                            <input name = 'losses' type = 'text' value = <?php echo $losses ?>> <br>
                            <label>Win%:</label>
                            <input name = 'winPerc' type = 'text' value = <?php echo $winPerc ?>> <br>
                            <label>GB:</label>
                            <input name = 'gamesBack' type = 'text' value = <?php echo $gamesBack ?>> <br>
                            <label>L10:</label>
                            <input name = 'lastTen' type = 'text' value = <?php echo $lastTen ?>> <br>
                            <label>Streak:</label>
                            <input name = 'streak' type = 'text' value = <?php echo $streak ?>> <br>
                            <input name = 'sendUpdate' type = 'hidden' value = "<?php echo $updateTeam ?>">
                            <input name = 'divisionID' type = 'hidden' value = "<?php echo $divisionID ?>">
                            <input type="submit" value = "Update">
                        </form>
                </div>
        <?php
            }

            //update step 2: send SQL update
            if(!empty($_GET['sendUpdate'])){
                //get all update fields
                $updateTeam = $_GET['sendUpdate'];
                $wins = $_GET['wins'];
                $losses = $_GET['losses'];
                $winPerc = winPercentage($wins, $losses);
                $lastTen = $_GET['lastTen'];
                $streak = $_GET['streak'];
                $sql = "UPDATE mlbstandings
                        SET wins = '$wins',
                            losses = '$losses',
                            winPerc = '$winPerc',
                            lastTen = '$lastTen',
                            streak = '$streak'
                        WHERE teamName = '$updateTeam'";
                mysqli_query($link, $sql) or die('Update error: ' . mysqli_error($link));

                //now games back is calculated because the order had to be updated in each division first
                $divisionID = $_GET['divisionID'];
                $divisionLeader = divisionLeader($divisionID);
                if($divisionLeader == $winPerc){
                    $gamesBack = '-';
                }
                else{
                    $gamesBack = gamesBack($updateTeam, $divisionID);
                }
                $sql = "UPDATE mlbstandings
                        SET gamesBack = '$gamesBack'
                        WHERE teamName = '$updateTeam'";
                mysqli_query($link, $sql) or die('Update error: ' . mysqli_error($link));

                header("location: index.php");

            }
        
            //for each division, print out the standings
            for($i = 0; $i < count($divisionName); $i++){
                $sql = "SELECT divisionName FROM mlbstandings WHERE divisionID = '$divisionName[$i]'";

                $result = mysqli_query($link, $sql) or die('SQL syntax error: '.mysqli_error($link));

                $row = mysqli_fetch_array($result);
    
                $divName = $row['divisionName'];
        ?>
                <div class="ALNL">
                    <?php echo "<h4 class = $divisionName[$i] id = $divisionName[$i]>$divName</h4>" ?>
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
                            //select the data
                            $sql = "SELECT * FROM mlbstandings WHERE divisionID = '$divisionName[$i]' ORDER BY winPerc DESC";

                            //results gives an array of containing query results
                            $result = mysqli_query($link, $sql) or die('SQL syntax error: '.mysqli_error($link));

                            while($row = mysqli_fetch_array($result)){
                                $winPerc = winPercentage($row['wins'], $row['losses']);
                                $divisionLeader = divisionLeader($row['divisionID']);
                                if($divisionLeader == $row['winPerc']){
                                    $gamesBack = '-';
                                }
                                else{
                                    $gamesBack = gamesBack($row['teamName'], $row['divisionID']);
                                }
                                echo "<tr>
                                        <td class = 'team'><a class = 'link' href='?updateTeam=$row[teamName]'>$row[teamName]</a></td>
                                        <td>$row[wins]</td>
                                        <td>$row[losses]</td>
                                        <td>$winPerc</td>
                                        <td>$gamesBack</td>
                                        <td>$row[lastTen]</td>
                                        <td>$row[streak]</td>
                                    </tr>";
                            }
                        ?>
                    </table>
                </div>
        <?php } ?>
    </main>
</body>
</html>