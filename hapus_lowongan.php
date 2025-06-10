<?php
session_start();

if (!isset($_SESSION['employer_id'])) {
    header("Location: login_employer.php");
    exit;
}

include 'koneksi.php';

// Get job ID from URL
$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

// Verify the job belongs to the logged-in employer
$sql = "SELECT id, logo FROM lowongan WHERE id = $id AND employer_id = {$_SESSION['employer_id']}";
$result = mysqli_query($conn, $sql);
$job = mysqli_fetch_assoc($result);

if (!$job) {
    $_SESSION['error'] = "Lowongan tidak ditemukan atau Anda tidak memiliki akses";
    header("Location: dashboard_employer.php");
    exit;
}

// Delete logo file if exists
if ($job['logo'] && file_exists($job['logo'])) {
    unlink($job['logo']);
}

// Delete job from database
$delete_sql = "DELETE FROM lowongan WHERE id = $id AND employer_id = {$_SESSION['employer_id']}";

if (mysqli_query($conn, $delete_sql)) {
    $_SESSION['success'] = "Lowongan berhasil dihapus";
} else {
    $_SESSION['error'] = "Gagal menghapus lowongan: " . mysqli_error($conn);
}

header("Location: dashboard_employer.php");
exit;
?>