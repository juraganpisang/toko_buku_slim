<?php
require 'koneksi.php';

function rupiah($angka)
{

    $hasil_rupiah = number_format($angka, 2, ',', '.');
    return $hasil_rupiah;
}

$id_transaksi = $_POST['id_transaksi'];


date_default_timezone_set("Asia/Jakarta");

// untuk transaksi
$getUsers = $db->select()
    ->from('transaksi t')
    ->leftjoin('detail_transaksi dt', 't.id_transaksi = dt.transaksi_id')
    ->where('t.id_transaksi', '=', $id_transaksi)
    ->groupBy('t.id_transaksi')
    ->orderBy('t.created_at DESC')
    ->findAll();

$data_transaksi = array();
foreach ($getUsers as $getU) {
    $data = [
        'id_transaksi' => $getU->id_transaksi,
        'nota' => $getU->nota,
        'nama_customer' => $getU->nama_customer,
        'total_bayar' => $getU->total_bayar,
        'kembalian' => $getU->kembalian,
        'created_at' => $getU->created_at
    ];

    array_push($data_transaksi, $data);

    // print_r($data_transaksi);
}


$getUsers = $db->select()
    ->from('transaksi t')
    ->leftjoin('detail_transaksi dt', 't.id_transaksi = dt.transaksi_id')
    ->where('t.id_transaksi', '=', $id_transaksi)
    ->orderBy('t.created_at DESC')
    ->findAll();

$data_detailtransaksi = array();
foreach ($getUsers as $getU) {
    $data = [
        'transaksi_id' => $getU->transaksi_id,
        'kode_item' => $getU->kode_item,
        'nama_item' => $getU->nama_item,
        'qty' => $getU->qty,
        'harga' => $getU->harga,
        'disc' => $getU->disc,
        'subtotal' => $getU->subtotal
    ];

    array_push($data_detailtransaksi, $data);

    // print_r($data_detailtransaksi);
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">

</head>

<body>
    <div class="container">
        <div class="row">
            <div class="col">
                <?php foreach ($data_transaksi as $row) { ?>
                    <div class="row mt-4 mb-4">
                        <div class="col">
                            <h5>Nota : <?php echo $row['nota']; ?></h5>
                        </div>
                    </div>
                    <hr>
                    <div class="row">
                        <div class="col">
                            <h5 class="text-center">
                                Toko Buku Jaya<br>
                                Jalan Gunung Sari RT 22 RW 05<br>
                                Pandanlandung
                            </h5>
                        </div>
                    </div>
                    <div class="row mt-4 mb-4">
                        <div class="col">
                            <span>Tanggal Transaksi : <?php echo date('F d, Y h:mA', strtotime($row['created_at'])); ?></span>
                        </div>
                        <div class="col">
                            <span>Nama Penjual : Ival</span>
                        </div>
                        <div class="col">
                            <span>Nama Customer : <?php echo $row['nama_customer']; ?></span>
                        </div>
                    </div>
                    <table class="table">
                        <?php
                        $no = 1;
                        foreach ($data_detailtransaksi as $dt) { ?>
                            <tr>
                                <td colspan=3>
                                    <h5>Barang ke <?php echo $no; ?></h5>
                                </td>
                            </tr>
                            <tr>
                                <td>Kode Item</td>
                                <td>:</td>
                                <td><?php echo $dt['kode_item']; ?></td>
                            </tr>
                            <tr>
                                <td>Nama Item</td>
                                <td>:</td>
                                <td><?php echo $dt['nama_item']; ?></td>
                            </tr>
                            <tr>
                                <td>Quantity</td>
                                <td>:</td>
                                <td><?php echo $dt['qty'] . " buah"; ?></td>
                            </tr>
                            <tr>
                                <td>Harga</td>
                                <td>:</td>
                                <td><?php echo "Rp. " . rupiah($dt['harga']); ?></td>
                            </tr>
                            <tr>
                                <td>Diskon</td>
                                <td>:</td>
                                <td><?php echo $dt['disc'] . "%"; ?></td>
                            </tr>
                            <tr>
                                <td>Subtotal</td>
                                <td>:</td>
                                <td><?php echo "Rp. " . rupiah($dt['subtotal']); ?></td>
                            </tr>
                        <?php $no++;
                        } ?>
                        <tr>
                            <td>Total Bayar</td>
                            <td>:</td>
                            <td><?php echo "Rp. " . rupiah($row['total_bayar']); ?></td>
                        </tr>
                        <tr>
                            <td>Jumlah Kembalian</td>
                            <td>:</td>
                            <td><?php echo "Rp. " . rupiah($row['kembalian']); ?></td>
                        </tr>
                    </table>
                <?php } ?>
            </div>
        </div>
    </div>
</body>

</html>