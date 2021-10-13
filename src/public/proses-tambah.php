<?php
require 'koneksi.php';

$buku = $_POST['hasil_buku'];

$nama_customer = $_POST['nama_cust'];
$total_bayar = $_POST['total_bayar'];
$kembalian = $_POST['kembalian'];

$time = time(); 
// insert data
$transaksi = [
    'nota' => 'NT' . $time,
    'nama_customer' => $nama_customer,
    'total_bayar' => $total_bayar,
    'kembalian' => $kembalian
];

$db->insert('transaksi', $transaksi);

$last_id = $db->select('id_transaksi')
    ->from('transaksi')
    ->limit(1)
    ->orderBy('created_at DESC')
    ->find();
    
// insert detail

foreach($buku as $row) {

    $data = [
        'transaksi_id' => $last_id->id_transaksi,
        'kode_item' => $row[0],
        'nama_item' => $row[1],
        'qty' => $row[2],
        'harga' => $row[3],
        'disc' => $row[4],
        'subtotal' => $row[5]
    ];

    $db->insert('detail_transaksi', $data);
}