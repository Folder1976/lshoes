<?php
    date_default_timezone_set('Europe/Moscow');
 //Для shopsplum.com свой единый конфиг
    if(strpos($_SERVER['HTTP_HOST'], 'shopsplum.com') !== false){
        include ".assets/shopsplum.com/config.php";  
    }else{
        include ".assets/".$_SERVER['HTTP_HOST']."/config.php";    
    }
  
$mysqli = mysqli_connect(DB_HOSTNAME,DB_USERNAME,DB_PASSWORD,DB_DATABASE) or die("Error " . mysqli_error($mysqli));

$mysqli->query('SET NAMES utf8');
mysqli_set_charset($mysqli,"utf-8");





