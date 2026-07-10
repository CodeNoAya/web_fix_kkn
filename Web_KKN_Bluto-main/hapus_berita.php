<?php
session_start();
require_once 'config/database.php';

if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header("Location: login.php");
    exit;
}

if (isset($_GET['id'])) {
    $id_berita = $_GET['id'];
    
    
    $stmt = $koneksi->prepare("DELETE FROM berita WHERE id_berita = :id");
    $stmt->bindParam(':id', $id_berita);
    $stmt->execute();
}

header("Location: admin.php");
exit;
?>
