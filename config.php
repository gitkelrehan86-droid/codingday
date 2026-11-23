<?php
session_start();
$host = 'localhost';
$db = 'gudang';
$user = 'root';
$pass = '';
try {
 $pdo = new PDO("mysql:host=$host;dbname=$db;charset=utf8mb4",$user,$pass,[PDO::ATTR_ERRMODE=>PDO::ERRMODE_EXCEPTION]);
} catch(PDOException $e) { die("Koneksi database gagal. Periksa config.php."); }
function redirect($url){ header("Location: $url"); exit; }
function require_login(){ if(!isset($_SESSION['user'])) redirect('login.php'); }
function is_admin(){ return isset($_SESSION['user']) && $_SESSION['user']['role']==='admin'; }
