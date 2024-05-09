<?php

session_start(); 
session_destroy();

echo 'Logout successful';
header('location:login.php');
//exit();
?>