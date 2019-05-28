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
                <li class = 'alWild'><a href = '?wildCard=al'>AL WildCard</a></li>
                <li class = 'nlWest'><a href = '#nlwest'>NL West</a></li>
                <li class = 'nlCentral'><a href = '#nlcentral'>NL Central</a></li>
                <li class = 'nlEast'><a href = '#nleast'>NL East</a></li>
                <li class = 'nlWild'><a href = '?wildCard=nl'>NL WildCard</a></li>
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

                $sql = "SELECT teamName, wins, losses, winPerc, gamesBack, divisionID FROM mlbstandings WHERE teamName = '$updateTeam'";

                $result = mysqli_query($link, $sql) or die('SQL syntax error: '.mysqli_error($link));

                $row = mysqli_fetch_array($result);

                $wins = $row['wins'];
                $losses = $row['losses'];
                $winPerc = $row['winPerc'];
                $gamesBack = $row['gamesBack'];
                $divisionID = $row['divisionID'];
                
        ?>
                <!-- Popup GUI for editing wins and losses -->
                <div class = 'editPopup'>
                        <h2>Updating team: <?php echo $updateTeam ?></h2>
                        <form>
                            <div class = 'formGroup'>
                                <label>Wins:</label>
                                <input name = 'wins' type = 'text' class = 'poWins' value = <?php echo $wins ?> autofocus> <br>
                            </div>
                            <div class = 'formGroup'>
                                <label>Losses:</label>
                                <input name = 'losses' type = 'text' class = 'poLosses' value = <?php echo $losses ?>> <br>
                            </div>
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
                $sql = "UPDATE mlbstandings
                        SET wins = '$wins',
                            losses = '$losses',
                            winPerc = '$winPerc'
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

            //if wildcard link is selected, display ONLY wildcard (al or nl)
            if(!empty($_GET['wildCard'])){
                $league = substr($_GET['wildCard'], 0, 2);

                $sql = "SELECT teamName, wins, losses, winPerc, gamesBack FROM mlbstandings WHERE divisionID LIKE '%$league' ";

                $result = mysqli_query($link, $sql) or die('SQL syntax error: '.mysqli_error($link));

                $row = mysqli_fetch_array($result);

                //need all teams in that league (al/nl) - the 3 division leaders, maybe display them above everyone else??


            }

            //else, display all 6 divisions like normal
            else{

            //for each division, print out the standings
            for($i = 0; $i < count($divisionName); $i++){
                $sql = "SELECT divisionName, divisionID FROM mlbstandings WHERE divisionID = '$divisionName[$i]'";

                $result = mysqli_query($link, $sql) or die('SQL syntax error: '.mysqli_error($link));

                $row = mysqli_fetch_array($result);
    
                $divName = $row['divisionName'];
                $divID = $row['divisionID'];
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
                                        <td class = 'team'><a class = 'link' href='?updateTeam=$row[teamName]#$divID'>$row[teamName]</a></td>
                                        <td>$row[wins]</td>
                                        <td>$row[losses]</td>
                                        <td>$winPerc</td>
                                        <td>$gamesBack</td>
                                    </tr>";
                            }
                        ?>
                    </table>
                </div>
        <?php 
            } //end of for loop printing division
            } //end of if else for wildcard link 
        ?>
    </main>
</body>
</html>