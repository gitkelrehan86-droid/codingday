<?php
require 'config.php'; require_login();
$msg='';
if(isset($_POST['catat'])){
 $id=(int)$_POST['barang']; $jumlah=(int)$_POST['jumlah']; $jenis=$_POST['jenis'];
 if($jumlah>0 && in_array($jenis,['masuk','keluar'])){
  $pdo->beginTransaction();
  $stmt=$pdo->prepare("SELECT * FROM barang WHERE id_barang=? FOR UPDATE");$stmt->execute([$id]);$b=$stmt->fetch(PDO::FETCH_ASSOC);
  if($b && ($jenis==='masuk'||$b['stok']>=$jumlah)){
   $delta=$jenis==='masuk'?$jumlah:-$jumlah;$pdo->prepare("UPDATE barang SET stok=stok+? WHERE id_barang=?")->execute([$delta,$id]);
   $pdo->prepare("INSERT INTO transaksi_barang(id_user,id_barang,jenis_transaksi,jumlah,keterangan) VALUES(?,?,?,?,?)")->execute([$_SESSION['user']['id_user'],$id,$jenis,$jumlah,trim($_POST['keterangan'])]);$pdo->commit();redirect('transaksi.php');
  }else{$pdo->rollBack();$msg='Stok tidak cukup untuk transaksi keluar.';}
 }else $msg='Jumlah transaksi harus lebih dari 0.';
}
$barang=$pdo->query("SELECT * FROM barang ORDER BY nama_barang")->fetchAll(PDO::FETCH_ASSOC);
$rows=$pdo->query("SELECT t.*,b.nama_barang,u.nama FROM transaksi_barang t JOIN barang b ON t.id_barang=b.id_barang JOIN users u ON t.id_user=u.id_user ORDER BY t.tanggal DESC")->fetchAll(PDO::FETCH_ASSOC);
?>
<!doctype html><html lang="id"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1"><title>Transaksi</title><link rel="stylesheet" href="style.css"></head><body><?php include 'nav.php'; ?><main class="main"><header class="page-head"><div><p class="eyebrow">ACTIVITY</p><h1>Transaksi Barang</h1><p class="muted">Catat barang masuk dan keluar.</p></div></header><?php if($msg): ?><div class="alert"><?= $msg ?></div><?php endif ?><section class="panel transaction-form"><h2>Catat transaksi</h2><form method="post"><div class="form-row"><div><label>Barang</label><select name="barang" required><?php foreach($barang as $b): ?><option value="<?= $b['id_barang'] ?>"><?= htmlspecialchars($b['nama_barang']) ?> — stok <?= $b['stok'] ?></option><?php endforeach ?></select></div><div><label>Jenis</label><select name="jenis"><option value="masuk">Barang masuk</option><option value="keluar">Barang keluar</option></select></div><div><label>Jumlah</label><input type="number" min="1" name="jumlah" required></div></div><label>Keterangan (opsional)</label><input name="keterangan" placeholder="Contoh: pembelian / pemakaian"><button name="catat" class="btn primary">Simpan transaksi</button></form></section><section class="panel"><div class="panel-head"><h2>Riwayat transaksi</h2></div><div class="table-wrap"><table><thead><tr><th>Barang</th><th>Jenis</th><th>Jumlah</th><th>Petugas</th><th>Keterangan</th><th>Tanggal</th></tr></thead><tbody><?php foreach($rows as $r): ?><tr><td><?= htmlspecialchars($r['nama_barang']) ?></td><td><span class="badge <?= $r['jenis_transaksi']==='masuk'?'green':'orange' ?>"><?= ucfirst($r['jenis_transaksi']) ?></span></td><td><?= $r['jumlah'] ?></td><td><?= htmlspecialchars($r['nama']) ?></td><td><?= htmlspecialchars($r['keterangan']??'-') ?></td><td><?= date('d/m/Y H:i',strtotime($r['tanggal'])) ?></td></tr><?php endforeach ?><?php if(!$rows): ?><tr><td colspan="6" class="empty">Belum ada transaksi.</td></tr><?php endif ?></tbody></table></div></section></main></body></html>
