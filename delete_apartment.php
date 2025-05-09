<?php
session_start();
require_once 'includes/db.php';
$id = $_GET['id'];
$conn->query("DELETE FROM apartments WHERE id = $id");
header("Location: view_apartments.php");
exit;
