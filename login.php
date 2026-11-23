<?php
require 'config.php';
$error='';
if(isset($_POST['login'])){
 $stmt=$pdo->prepare("SELECT * FROM users WHERE username=? AND password=? LIMIT 1");
 $stmt->execute([trim($_POST['username']),$_POST['password']]);
 $user=$stmt->fetch(PDO::FETCH_ASSOC);
 if($user){ $_SESSION['user']=$user; redirect('index.php'); }
 $error='Username atau password salah.';
}
?>
<!doctype html><html lang="id"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1"><title>Login — Gudang</title><link rel="stylesheet" href="style.css"></head><body class="login-body">
<div class="login-card"><div class="brand-mark">G</div><h1>Inventaris Gudang</h1><p class="muted">Masuk untuk mengelola stok barang.</p>
<?php if($error): ?><div class="alert"><?= htmlspecialchars($error) ?></div><?php endif ?>
<form method="post"><label>Username</label><input name="username" required autofocus><label>Password</label><input type="password" name="password" required><button name="login" class="btn primary full">Masuk</button></form>
<div class="demo"><b>Akun demo</b><br>Admin: admin / admin123<br>Pengguna: petugas / petugas123</div></div></body></html>
