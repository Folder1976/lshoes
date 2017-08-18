<?php
     // DB
 if(!defined('TMP_DIR')) define('TMP_DIR', '');
 
 if(!defined('DB_HOSTNAME')) define('DB_HOSTNAME', 'localhost');
 if(!defined('DB_USERNAME')) define('DB_USERNAME', 'folder_lshoes');
 if(!defined('DB_PASSWORD')) define('DB_PASSWORD', '}N)(rdyXdu3e');
 if(!defined('DB_DATABASE')) define('DB_DATABASE', 'folder_lshoes');
 if(!defined('DB_PREFIX')) define('DB_PREFIX', 'fash_');
 
 if(!defined('MAIN_DIR')) define('MAIN_DIR', '/home/folder/public_html/lshoes.com.ua/');
 
 if(!defined('DIR_IMAGE')) define('DIR_IMAGE', MAIN_DIR.TMP_DIR.'image/');
   
    $mysqli = mysqli_connect(DB_HOSTNAME,DB_USERNAME,DB_PASSWORD,DB_DATABASE) or die("Error " . mysqli_error($mysqli)); 
    mysqli_set_charset($mysqli,"utf8"); 
     
 
?>