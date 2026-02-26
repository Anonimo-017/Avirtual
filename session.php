<?php
session_start();
require_once('dbcon.php');

if (!isset($_SESSION['id']) || empty($_SESSION['id'])) {
    header("location: index.php");
    exit();
}

$session_id = isset($_SESSION['id']) ? intval($_SESSION['id']) : 0;