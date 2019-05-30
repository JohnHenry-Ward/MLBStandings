<?php

//initial connection to the database mlbstandings
function connectToDatabase(){
    //create connection
    $link = @mysqli_connect('localhost', 'root', '', 'mlbstandings');

    //check if successfully linked
    if(!$link){
        die('Connection Error: '.mysqli_connect_error());
    }
    return $link;
}


//returns the win percentage of 1 team
function winPercentage($wins, $losses){
    $number = $wins / ($wins + $losses);
    $number = number_format($number, 3, '.', '');
    return substr($number, 1, 4);
}

//returns the name of the division leader from a division
function divisionLeader($division){
    $link = connectToDatabase();

    $sql = "SELECT MAX(winPerc) AS leader FROM mlbstandings WHERE divisionID = '$division'";

    $result = mysqli_query($link, $sql) or die('SQL syntax error: '.mysqli_error($link));
 
    $row = mysqli_fetch_array($result);

    $leadWinPerc = $row['leader'];

    return $leadWinPerc;
}

//returns gamesBack for specific team in division
function gamesBack($teamName, $division){
    $link = connectToDatabase();

    //first sql to determine which team is in the lead
    $sql = "SELECT teamName, wins, losses, gamesBack FROM mlbstandings WHERE divisionID = '$division'";

    $result = mysqli_query($link, $sql) or die('SQL syntax error: '.mysqli_error($link));

    $gamesBack = 0;
    $leaderWins = 0;
    $leaderLosses = 0;

    while($row = mysqli_fetch_array($result)){
        if($row['gamesBack'] == '-'){
            $leaderWins = $row['wins'];
            $leaderLosses = $row['losses'];
        }
    }

    //second sql to just get data for the specific team
    $sql = "SELECT teamName, wins, losses, gamesBack FROM mlbstandings WHERE teamName = '$teamName'";

    $result = mysqli_query($link, $sql) or die('SQL syntax error: '.mysqli_error($link));

    $row = mysqli_fetch_array($result);

    $wins = $row['wins'];
    $losses = $row['losses'];
    $winDiff = $leaderWins - $wins;
    $loseDiff = $losses - $leaderLosses;
    $gamesBack = ($winDiff + $loseDiff) / 2;

    return number_format($gamesBack, 1, '.', '');
}

//returns gamesAhead for the first place team of the wildcard
function gamesAheadWildCard($sql){
    $link = connectToDatabase();

    $sql = $sql;

    $result = mysqli_query($link, $sql) or die('SQL syntax error: '.mysqli_error($link));

    $firstTime = true;
    $secondTime = false;
    while($row = mysqli_fetch_array($result)){
        if($firstTime && !$secondTime){
            $leaderWins = $row['wins'];
            $leaderLosses = $row['losses'];
        }
        elseif($secondTime && !$firstTime){
            $wins = $row['wins'];
            $losses = $row['losses'];
            break;
        }
        $firstTime = false;
        $secondTime = true;
    }

    $winDiff = $leaderWins - $wins;
    $loseDiff = $losses - $leaderLosses;
    $gamesBack = ($winDiff + $loseDiff) / 2;

    return number_format($gamesBack, 1, '.', '');
}

//returns gamesBack for teams not in the top of the wildcard
function gamesBackWildCard($wins, $losses, $teamTop){
    $link = connectToDatabase();

    $sql = "SELECT wins, losses FROM mlbstandings WHERE winPerc = $teamTop";

    $result = mysqli_query($link, $sql) or die('SQL syntax error: '.mysqli_error($link));

    while($row = mysqli_fetch_array($result)){
        $leaderWins = $row['wins'];
        $leaderLosses = $row['losses'];
    }

    $winDiff = $leaderWins - $wins;
    $loseDiff = $losses - $leaderLosses;
    $gamesBack = ($winDiff + $loseDiff) / 2;

    return number_format($gamesBack, 1, '.', '');

}
?>