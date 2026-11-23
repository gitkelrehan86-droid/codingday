CREATE DATABASE IF NOT EXISTS inventaris_gudang;
USE inventaris_gudang;

CREATE TABLE users (
 id_user INT AUTO_INCREMENT PRIMARY KEY,
 username VARCHAR(50) NOT NULL UNIQUE,
 password VARCHAR(255) NOT NULL,
 nama VARCHAR(100) NOT NULL,
 role ENUM('admin','pengguna') NOT NULL DEFAULT 'pengguna'
);

CREATE TABLE barang (
 id_barang INT AUTO_INCREMENT PRIMARY KEY,
 nama_barang VARCHAR(100) NOT NULL,
 kategori VARCHAR(80) NOT NULL,
 satuan VARCHAR(30) NOT NULL,
 stok INT NOT NULL DEFAULT 0,
 stok_minimum INT NOT NULL DEFAULT 0
);

CREATE TABLE transaksi_barang (
 id_transaksi INT AUTO_INCREMENT PRIMARY KEY,
 id_user INT NOT NULL,
 id_barang INT NOT NULL,
 jenis_transaksi ENUM('masuk','keluar') NOT NULL,
 jumlah INT NOT NULL,
 tanggal DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
 keterangan VARCHAR(255),
 FOREIGN KEY (id_user) REFERENCES users(id_user),
 FOREIGN KEY (id_barang) REFERENCES barang(id_barang)
);

INSERT INTO users (username,password,nama,role) VALUES
('admin', 'admin123', 'Administrator', 'admin'),
('petugas', 'petugas123', 'Petugas Gudang', 'pengguna');
