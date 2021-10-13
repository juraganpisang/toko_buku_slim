<?php
require 'vendor/autoload.php';
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
            var datatablenya = $('#data-buku').DataTable({
                data: hasil_buku,
                columns: [{
                        title: "No"
                    },
                    {
                        title: "Kode Item"
                    },
                    {
                        title: "Nama Item"
                    },
                    {
                        title: "Qty"
                    },
                    {
                        title: "Harga"
                    },
                    {
                        title: "Disc %"
                    },
                    {
                        title: "Subtotal + Tax"
                    },
                    {
                        title: "Aksi"
                    }
                ],
                columnDefs: [{
                    "defaultContent": "-",
                    "targets": "_all"
                }]
            });

            // delete 
            $('#data-buku tbody').on('click', 'button.btn_hapus', function() {

                console.log(this.id);
                // console.log(hasil_buku);

                hasil_buku = hasil_buku.slice(0); // make copy
                hasil_buku.splice(this.id - 1, 1);

                var harga = $(this).data('harga');
                var jumlah = $(this).data('jumlah');
                var subtotal = $(this).data('subtotal');

                // menghitung total
                var temp_total = document.getElementById("temp_total").value;
                var total_hitung = Number(temp_total) - Number(subtotal);
                $('.total_semua').text(total_hitung);

                document.getElementById("temp_total").value = parseInt(total_hitung);

                // menghitung subtotal
                var temp_subtotal = document.getElementById("temp_subtotal").value;
                var subtotal_hitung = Number(temp_subtotal) - (Number(harga) * Number(jumlah));
                $('.subtotal_semua').text(subtotal_hitung);

                document.getElementById("temp_subtotal").value = parseInt(subtotal_hitung);

                datatablenya
                    .row($(this).parents('tr'))
                    .remove()
                    .draw();
            });

            $("#belibuku").click(function(e) {


                $('#subtotal').val(subtotal);

                var kode = $("#kode").val();
                var buku = $("#nama_buku").val();
                var jumlah = $("#jumlah").val();
                var harga = $("#harga").val();
                var diskon = $("#diskon").val();

                var sub_diskon = (Number(harga) / 100) * Number(diskon);
                var harga_diskon = Number(harga) - Number(sub_diskon);
                var subtotal = Number(harga_diskon) * Number(jumlah);

                e.preventDefault();
                $('#exampleModal').modal('hide');

                var btn_hapus = '<button type="button" name="btn_hapus" data-harga="' + harga + '" data-jumlah="' + jumlah + '" data-subtotal="' + subtotal + '" id="' + no + '" class="btn btn-danger btn_hapus">Hapus</button>';

                hasil_buku.push([kode, buku, jumlah, harga, diskon, subtotal, btn_hapus]);

                datatablenya.row.add([
                    no,
                    kode,
                    buku,
                    jumlah,
                    harga,
                    diskon,
                    subtotal,
                    btn_hapus
                ]).draw(false);
                no++;

                // menghitung total
                var temp_total = document.getElementById("temp_total").value;
                var total_hitung = Number(temp_total) + Number(subtotal);
                $('.total_semua').text(total_hitung);

                document.getElementById("temp_total").value = parseInt(total_hitung);

                $("#total_bayar").attr({
                    "min": parseInt(total_hitung)
                });

                // menghitung subtotal
                var temp_subtotal = document.getElementById("temp_subtotal").value;
                var subtotal_hitung = Number(temp_subtotal) + (Number(harga) * Number(jumlah));
                $('.subtotal_semua').text(subtotal_hitung);

                document.getElementById("temp_subtotal").value = parseInt(subtotal_hitung);

                // reset
                $('#kode').val('');
                $('#nama_buku').val('');
                $('#subtotal').val('');
                $('#diskon').val('');
                $('#harga').val('');
                $('#jumlah').val('');
            });
        });
    </script>

</head>

<body>
    <div class="contain-header p-4 mb-4">
        <div class="row">
            <div class="col-6"><img src="https://cdn-icons-png.flaticon.com/512/25/25231.png" style="max-width:56px" /></div>
            <div class="col-2 border-kanan">Tanggal <br>
                <h5 style="font-weight: bold;"><?php echo date("Y-m-d"); ?></h5>
            </div>
            <?php

            require 'koneksi.php';
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

    <form id="tambahData">
        <div class="contain-body">
            <div class="container">
                <div class="row mb-4">
                    <div class=col>
                        <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#exampleModal">
                            + Tambah Item
                        </button>
                        <!-- Modal -->
                        <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                            <div class="modal-dialog" role="document">
                                <div class="modal-content">
                                    <form method="POST" id="formBeli">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="exampleModalLabel">Modal title</h5>
                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                        </div>
                                        <div class="modal-body">
                                            <div class="row">
                                                <div class="col">
                                                    <div class="form-group">
                                                        <label for="kode">Kode</label>
                                                        <input type="text" name="kode" id="kode" class="form-control" placeholder="0002" />
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col">
                                                    <div class="form-group">
                                                        <label for="nama_buku">Pilih Buku</label>
                                                        <input type="text" class="form-control" name="nama_buku" id="nama_buku" placeholder="Jurnal Risa" />
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col">
                                                    <div class="form-group">
                                                        <label for="jumlah">Jumlah</label>
                                                        <input type="number" class="form-control" name="jumlah" id="jumlah" placeholder="3" />
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col text-right">
                                                    <label>Harga</label>
                                                    <div class="input-group mb-3">
                                                        <div class="input-group-prepend">
                                                            <span class="input-group-text" id="basic-addon1">Rp. </span>
                                                        </div>
                                                        <input type="text" name="harga" id="harga" class="form-control" placeholder="200000">
                                                    </div>
                                                </div>
                                                <div class="col text-right">
                                                    <label>Diskon</label>
                                                    <div class="input-group mb-3">
                                                        <div class="input-group-prepend">
                                                            <span class="input-group-text" id="basic-addon1">Rp. </span>
                                                        </div>
                                                        <input type="text" name="diskon" id="diskon" class="form-control" placeholder="0">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                            <button type="button" id="belibuku" class="btn btn-primary">Save changes</button>
                                        </div>

                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col">
                        <input type="hidden" id="temp_total" />
                        <h2 class="text-right">Total : Rp. <span class="total_semua">-</span></h2>
                    </div>`
                </div>
                <div class="row mb-4">
                    <div class="col">
                        <table class="table table-striped table-bordered" style="width:100%" id="data-buku">
                        </table>
                    </div>
                </div>
                <div class="row">
                    <div class="col">
                        <input type="hidden" id="temp_subtotal" />
                        <h5>Subtotal : Rp. <span class="subtotal_semua">-</span></h5>
                    </div>
                    <div class="col">
                        <h5 class="text-danger text-right">Total : Rp. <?php echo '<span class="total_semua">-</span>' ?></h5>
                    </div>
                </div>
                <div class="row mb-4 mt-4 p-4 card">
                    <div class="form-row">
                        <div class="col-6">
                            <div class="form-group">
                                <label for="nama_cust">Nama Customer</label>
                                <input type="text" name="nama_cust" id="nama_cust" class="form-control" placeholder="Rina" />
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="form-group">
                                <input type="hidden" id="kembalian" />
                                <label for="jumlah_bayar">Jumlah Bayar Customer</label>
                                <input type="text" name="total_bayar" id="total_bayar" class="form-control" placeholder="Rp. 20000" />
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row d-flex mb-4 justify-content-end">
                    <div class="col text-right">
                        <div>
                            <a href="/" name="simpan" class="btn btn-danger">
                                < Kembali</a>
                                    <button type="submit" name="simpan" class="btn btn-success" id="simpan">Simpan</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>

</body>

</html>
<script>
    $("#tambahData").submit(function(e) {

        var nama_cust = document.getElementById("nama_cust").value;
        var total_bayar = document.getElementById("total_bayar").value;

        // total
        var totale = document.getElementById("temp_total").value;
        var kembalian = Number(total_bayar) - Number(totale);
        e.preventDefault(); // avoid to execute the actual submit of the form.

        $.ajax({
            type: "POST",
            url: "/tambah",
            data: {
                hasil_buku,
                nama_cust,
                total_bayar,
                kembalian
            },
            success: function(data) {
                window.location.href = "/";
            }
        });
    });
</script>