<?php

$con = mysqli_connect('localhost', 'root', '', 'anime_zajednica');

function escape($string){
    global $con;
    return mysqli_real_escape_string($con, $string);
} // pomoci ce kada budemo izvrsavali upite u bazi podataka

function query($query){
    global $con;
    return mysqli_query($con, $query); //uzima query iz argumenta ubacuje ga dole u return 
}

function confirm($result){
    global $con;
    if(!$result){ // ako postoji greska on ce da kaze query failed zaustavi 
        die("Query Failed" . mysqli_error($con));
    }
}