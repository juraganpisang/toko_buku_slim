<?php
function rupiah($angka)
{

    $hasil_rupiah = number_format($angka, 2, ',', '.');
    return $hasil_rupiah;
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
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="//cdn.datatables.net/1.11.3/css/jquery.dataTables.min.css" />

    <script src="https://code.jquery.com/jquery-3.5.1.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
    <script src="https://cdn.datatables.net/1.11.3/js/jquery.dataTables.min.js"></script>

    <style>
        .contain-header {
            background-color: #28a745;
            border-bottom: 1px solid red;
            color: white;
        }

        .border-kanan {
            text-align: right;
            border-right: 1px solid white;
        }
    </style>
    <script>
        var no = 1;
        var hasil_buku = [];

        $(document).ready(function() {
            // datatable
            var datatablenya = $('#data-buku').DataTable();

        });
    </script>

</head>

<?php

require 'koneksi.php';
// untuk transaksi
$getUsers = $db->select()
    ->from('transaksi t')
    ->leftjoin('detail_transaksi dt', 't.id_transaksi = dt.transaksi_id')
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

    // print_r($data_detailtransaksi)

}


    // total
    $total_dijual = $db->find('SELECT sum(subtotal) as total_penjualan FROM detail_transaksi');

    // jumlah buku
    $total_buku = $db->find('SELECT sum(qty) jumlah_buku FROM detail_transaksi');

?>

<body>
    <div class="contain-header p-4 mb-4">
        <div class="row">
            <div class="col-6"><img src="https://cdn-icons-png.flaticon.com/512/25/25231.png" style="max-width:56px" /></div>
            <div class="col-2 border-kanan">Tanggal <br>
                <h5 style="font-weight: bold;"><?php echo date("Y-m-d"); ?></h5>
            </div>
            <?php
            $last_id = $db->select('nota')
                ->from('transaksi')
                ->limit(1)
                ->orderBy('created_at DESC')
                ->find();
            ?>
            <div class="col-2 border-kanan">No Penjualan <br>
                <h5 style="font-weight: bold;"><?php echo $last_id->nota; ?></h5>
            </div>
            <div class="col-2 border-kanan">Kasir 1 <br>
                <h5 style="font-weight: bold;">Ival</h5>
            </div>
        </div>
    </div>

    <div class="contain-body">
        <div class="container">
            <div class="row mb-4">
                <div class=col-4>
                    <a href="/tambah" class="btn btn-success">
                        + Tambah Pesanan
                    </a>
                </div>
                <div class="col-4">
                    <h4 class="text-right">Jumlah Buku Terjual :<br> <span class="total_buku"><?php echo $total_buku->jumlah_buku . " buah"; ?></span></h4>
                </div>
                <div class="col-4">
                    <h4 class="text-right">Total Penjualan :<br> Rp. <span class="total_semua"><?php echo rupiah($total_dijual->total_penjualan); ?></span></h4>
                </div>
            </div>
            <div class="row mb-4">
                <div class="col">
                    <table class="table table-striped table-bordered" style="width:100%" id="data-buku">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Nota</th>
                                <th>Nama Customer</th>
                                <th>Tanggal Transaksi</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $no = 1;
                            foreach ($data_transaksi as $row) { ?>
                                <!-- Modal Detail -->
                                <div class="modal fade" id="detailModal<?php echo $row['id_transaksi']; ?>" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                    <div class="modal-dialog" role="document">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <input type="hidden" id="nota_hidden" value="<?php echo $row['nota']; ?>" />
                                                <h5 class="modal-title" id="exampleModalLabel">Nota <?php echo $row['nota']; ?></h5>
                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                    <span aria-hidden="true">&times;</span>
                                                </button>
                                            </div>
                                            <div class="modal-body">
                                                <h5 class="text-center">
                                                    Toko Buku Jaya<br>
                                                    Jalan Gunung Sari RT 22 RW 05<br>
                                                    Pandanlandung
                                                </h5>
                                                <hr>
                                                <div class="row">
                                                    <div class="col">
                                                        <input type="hidden" id="created_at_hidden" value="<?php echo $row['created_at']; ?>" />
                                                        <span>Tanggal Transaksi : <br><?php echo date('F d, Y h:mA', strtotime($row['created_at'])); ?></span>
                                                    </div>
                                                    <div class="col">
                                                        <span>Nama Penjual <br> <b>Ival</b></span>
                                                    </div>
                                                    <div class="col">
                                                        <input type="hidden" id="nama_customer_hidden" value="<?php echo $row['nama_customer']; ?>" />
                                                        <span>Nama Customer <br> <b><?php echo $row['nama_customer']; ?></b></span>
                                                    </div>
                                                </div>
                                                <?php
                                                $ke = 1;
                                                foreach ($data_detailtransaksi as $dt) {
                                                    if ($dt['transaksi_id'] == $row['id_transaksi']) {
                                                ?>
                                                        <hr>
                                                        Barang ke <?php echo $ke; ?>
                                                        <div class="row">
                                                            <div class="col-4">
                                                                Kode Item
                                                            </div>
                                                            <div class="col-2 text-right">
                                                                :
                                                            </div>
                                                            <input type="hidden" id="kode_item_hidden" value="<?php echo $dt['kode_item']; ?>" />
                                                            <div class="col-6 text-left"><?php echo $dt['kode_item']; ?>
                                                            </div>
                                                        </div>
                                                        <div class="row">
                                                            <div class="col-4">
                                                                Nama Item
                                                            </div>
                                                            <div class="col-2 text-right">
                                                                :
                                                            </div>
                                                            <input type="hidden" id="nama_item_hidden" value="<?php echo $dt['nama_item']; ?>" />
                                                            <div class="col-6 text-left"><?php echo $dt['nama_item']; ?>
                                                            </div>
                                                        </div>
                                                        <div class="row">
                                                            <div class="col-4">
                                                                Quantity
                                                            </div>
                                                            <div class="col-2 text-right">
                                                                :
                                                            </div>
                                                            <input type="hidden" id="qty_hidden" value="<?php echo $dt['qty'] . " buah"; ?>" />
                                                            <div class="col-6 text-left"><?php echo $dt['qty'] . " buah"; ?>
                                                            </div>
                                                        </div>
                                                        <div class="row">
                                                            <div class="col-4">
                                                                Harga
                                                            </div>
                                                            <div class="col-2 text-right">
                                                                :
                                                            </div>
                                                            <input type="hidden" id="harga_hidden" value="<?php echo "Rp. " . rupiah($dt['harga']); ?>" />
                                                            <div class="col-6 text-left"><?php echo "Rp. " . rupiah($dt['harga']); ?>
                                                            </div>
                                                        </div>
                                                        <div class="row">
                                                            <div class="col-4">
                                                                Diskon
                                                            </div>
                                                            <div class="col-2 text-right">
                                                                :
                                                            </div>
                                                            <input type="hidden" id="disc_hidden" value="<?php echo $dt['disc'], "%"; ?>" />
                                                            <div class="col-6 text-left"><?php echo $dt['disc'] . "%"; ?>
                                                            </div>
                                                        </div>
                                                        <div class="row">
                                                            <div class="col-4">
                                                                Subtotal
                                                            </div>
                                                            <div class="col-2 text-right">
                                                                :
                                                            </div>
                                                            <input type="hidden" id="subtotal_hidden" value="<?php echo "Rp. " . rupiah($dt['subtotal']); ?>" />
                                                            <div class="col-6 text-left"><?php echo "Rp. " . rupiah($dt['subtotal']); ?>
                                                            </div>
                                                        </div>
                                                <?php
                                                        $ke++;
                                                    }
                                                } ?>
                                                <hr>
                                                <div class="row">
                                                    <div class="col-6">
                                                        Total Bayar
                                                    </div>
                                                    <div class="col-2 text-right">
                                                        :
                                                    </div>
                                                    <div class="col text-right">
                                                        <input type="hidden" id="total_bayar_hidden" value="<?php echo $row['total_bayar']; ?>" />
                                                        <b><?php echo "Rp. " . rupiah($row['total_bayar']); ?></b>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-6">
                                                        Jumlah Kembalian
                                                    </div>
                                                    <div class="col-2 text-right">
                                                        :
                                                    </div>
                                                    <div class="col text-right">
                                                        <input type="hidden" id="kembalian_hidden" value="<?php echo $row['kembalian']; ?>" />
                                                        <b><?php echo "Rp. " . rupiah($row['kembalian']); ?></b>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="modal-footer">
                                                <!-- <form action="/print" method="POST"> -->
                                                <input type="hidden" name="id_transaksi" id="id_transaksi" value="<?php echo $row['id_transaksi']; ?>" />
                                                <button type="submit" class="btn btn-success" onclick="cetak(<?php echo $row['id_transaksi']; ?>)">Cetak</button>
                                                <!-- </form> -->
                                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Modal Edit -->
                                <!-- <div class="modal fade" id="editModal<?php echo $row['id_transaksi']; ?>" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                    <div class="modal-dialog" role="document">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <input type="hidden" id="id_transaksi" value="<?php echo $row['id_transaksi']; ?>" />
                                                <h5 class="modal-title" id="exampleModalLabel">Nota <?php echo $row['nota']; ?></h5>
                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                    <span aria-hidden="true">&times;</span>
                                                </button>
                                            </div>
                                            <div class="modal-body">
                                                <h5 class="text-center">
                                                    Toko Buku Jaya<br>
                                                    Jalan Gunung Sari RT 22 RW 05<br>
                                                    Pandanlandung
                                                </h5>
                                                <hr>
                                                <div class="row">
                                                    <div class="col">
                                                        <input type="hidden" id="created_at_hidden" value="<?php echo $row['created_at']; ?>" />
                                                        <span>Tanggal Transaksi : <br><?php echo date('F d, Y h:mA', strtotime($row['created_at'])); ?></span>
                                                    </div>
                                                    <div class="col">
                                                        <span>Nama Penjual <br> <b>Ival</b></span>
                                                    </div>
                                                    <div class="col">
                                                        <span>Nama Customer <br> <input type="text" id="nama_customer_edit" class="form-control" value="<?php echo $row['nama_customer']; ?>" /> </span>
                                                    </div>
                                                </div>
                                                <?php
                                                $ke = 1;
                                                foreach ($data_detailtransaksi as $dt) {
                                                    if ($dt['transaksi_id'] == $row['id_transaksi']) {
                                                ?>
                                                        <hr>
                                                        Barang ke <?php echo $ke; ?>
                                                        <div class="row">
                                                            <div class="col">
                                                                <div class="form-group">
                                                                    <label> Kode Item </label>
                                                                    <input type="text" class="form-control" id="kode_item_edit" value="<?php echo $dt['kode_item']; ?>" />
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="row">
                                                            <div class="col">
                                                                <div class="form-group">
                                                                    <label> Nama Item </label>
                                                                    <input type="text" class="form-control" id="nama_item_edit" value="<?php echo $dt['nama_item']; ?>" />
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="row">
                                                            <div class="col">
                                                                <div class="form-group">
                                                                    <label> Quantity </label>
                                                                    <input type="text" class="form-control" id="qty_edit" value="<?php echo $dt['qty'] . " buah"; ?>" />
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="row">
                                                            <div class="col">
                                                                <div class="form-group">
                                                                    <label> Harga </label>
                                                                    <input type="text" class="form-control" id="harga_edit" value="<?php echo $dt['harga']; ?>" />
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="row">
                                                            <div class="col">
                                                                <div class="form-group">
                                                                    <label> Diskon </label>
                                                                    <input type="text" class="form-control" id="disc_edit" value="<?php echo $dt['disc'] ?>" />
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="row">
                                                            <div class="col">
                                                                <div class="form-group">
                                                                    <label> Subtotal </label>
                                                                    <input type="text" class="form-control" id="subtotal_edit" value="<?php echo $dt['subtotal'] ?>" />
                                                                </div>
                                                            </div>
                                                        </div>
                                                <?php
                                                        $ke++;
                                                    }
                                                } ?>
                                                <hr>
                                                <div class="row">
                                                    <div class="col-6">
                                                        Total Bayar
                                                    </div>
                                                    <div class="col-2 text-right">
                                                        :
                                                    </div>
                                                    <div class="col text-right">
                                                        <input type="hidden" id="total_bayar_hidden" value="<?php echo $row['total_bayar']; ?>" />
                                                        <b><?php echo "Rp. " . rupiah($row['total_bayar']); ?></b>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-6">
                                                        Jumlah Kembalian
                                                    </div>
                                                    <div class="col-2 text-right">
                                                        :
                                                    </div>
                                                    <div class="col text-right">
                                                        <input type="hidden" id="kembalian_hidden" value="<?php echo $row['kembalian']; ?>" />
                                                        <b><?php echo "Rp. " . rupiah($row['kembalian']); ?></b>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="submit" class="btn btn-success" onclick="cetak(<?php echo $row['id_transaksi']; ?>)">Cetak</button>
                                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                            </div>
                                        </div>
                                    </div>
                                </div> -->

                                <!-- Modal Hapus -->
                                <div class="modal fade" id="hapusModal<?php echo $row['id_transaksi']; ?>" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                    <div class="modal-dialog" role="document">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="exampleModalLabel">Nota <?php echo $row['nota']; ?></h5>
                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                    <span aria-hidden="true">&times;</span>
                                                </button>
                                            </div>
                                            <div class="modal-body">
                                                <p>Apakah Anda Yakin ingin menghapus data dengan Nota <?php echo $row['nota']; ?> ? </p>
                                            </div>
                                            <form id="deleteForm">
                                                <input type="hidden" value="<?php echo $row['id_transaksi'] ?>" id="id_transaksi" />
                                                <div class="modal-footer">
                                                    <button type="submit" class="btn btn-danger">Hapus</button>
                                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                                <tr>
                                    <td><?php echo $no; ?></td>
                                    <td><?php echo $row['nota'] ?></td>
                                    <td><?php echo $row['nama_customer'] ?></td>
                                    <td><?php echo date('F d, Y h:mA', strtotime($row['created_at'])); ?></td>
                                    <td>
                                        <?php echo '<button type="button" class="btn btn-info" data-toggle="modal" data-target="#detailModal' . $row['id_transaksi'] . '">Detail</button>'; ?>
                                        <?php echo '<button type="button" class="btn btn-danger" data-toggle="modal" data-target="#hapusModal' . $row['id_transaksi'] . '">Hapus</button>'; ?>
                                    </td>
                                </tr>
                            <?php $no++;
                            } ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</body>

</html>
<script>
    $("#deleteForm").submit(function() {
        var id = document.getElementById("id_transaksi").value;
        e.preventDefault(); // avoid to execute the actual submit of the form.

        $.ajax({
            type: "POST",
            url: "/hapus",
            data: {
                id
            },
            success: function(data) {
                window.location.href = "/";
            }
        });
    });
</script>
<script>
    function cetak(id_transaksi) {
        $.ajax({
            type: "POST",
            url: "/print",
            data: {
                id_transaksi
            },
            success: function(data) {
                printWindow = window.open('/print');
                printWindow.document.write(data);
                printWindow.print();
                // console.log(data);
                // window.location.href = "print";
            }
        });
    };
</script>