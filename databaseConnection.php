<?php
function connectToDatabase(){
    //create connection
    $link = @mysqli_connect('localhost', 'root', '', 'mlbstandings');

    //check if successfully linked
    if(!$link){
        die('Connection Error: '.mysqli_connect_error());
    }
    return $link;
}
?>