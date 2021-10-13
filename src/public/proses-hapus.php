<?php
require 'koneksi.php';

$id = $_POST['id'];
$db->delete('transaksi', ['id_transaksi' => $id]);
$db->delete('detail_transaksi', ['transaksi_id' => $id]);
