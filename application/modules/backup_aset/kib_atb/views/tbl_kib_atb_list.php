<?php
$session = $this->session->userdata('session');
// die(json_encode($session));
?>
<section class="content-header">
    <div class="row" style="margin-bottom: 10px">
        <div class="col-md-5 text-success">
            <h2 style="margin-top:0px">Daftar <?= $menu['nama'] ?></h2>
        </div>
        <div class="col-md-2 text-center">
            <div style="margin-top: 4px" id="message">
                <?php echo $this->session->userdata('message') <> '' ? $this->session->userdata('message') : ''; ?>
            </div>
        </div>
        <div class="col-md-5 text-right">
            <?php echo anchor(base_url('kib_atb/create'), 'Buat', 'class="btn btn-primary"'); ?>
            <?php //echo anchor(base_url('kib_a/excel'), 'Excel', 'class="btn btn-primary"'); 
            ?>
        </div>
    </div>
</section>
<section class="content">
    <div class="row">
        <div class="col-xs-12">
            <div class="box box-primary">
                <div class="box-body">
                    <div style="margin-bottom: 10px">
                        <div class="row form-group">
                            <div class="col-md-5">
                                <label>Pemilik </label>
                                <select class="form-control" name="pemilik" id="pemilik" data-role="select2" required>
                                    <option value="">Silahkan Pilih</option>
                                    <?php foreach ($pemilik as $key => $value) : ?>
                                        <option value="<?= $value->id_pemilik; ?>" <?= ($value->id_pemilik == '2') ? 'selected' : ''; ?>><?= $value->kode . ' - ' . $value->nama; ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>

                        <div class="row form-group">
                            <div class="col-md-10">
                                <label for="">SKPD </label>
                                <select class="form-control" name="id_pengguna" id="id_pengguna" data-role="select2">
                                    <?php if ($session->id_role == 1) : ?>
                                        <option value="">Semua</option>
                                        <?php foreach ($pengguna_list as $key => $value) : ?>
                                            <option value="<?= $value->pengguna; ?>" <?= ($value->pengguna == $pengguna->pengguna) ? 'selected' : ''; ?>><?= $value->pengguna . ' - ' . $value->instansi; ?></option>
                                        <?php endforeach; ?>
                                        <?php //elseif ( $session->id_role == 2 or $session->id_role == 3  ):
                                        ?>
                                    <?php else : ?>
                                        <option value="<?= $pengguna->pengguna; ?>" selected><?= $pengguna->pengguna . ' - ' . $pengguna->instansi; ?></option>
                                    <?php endif; ?>
                                </select>
                            </div>
                        </div>

                        <div class="row form-group">
                            <div class="col-md-10">
                                <label for="">Lokasi </label>
                                <select class="form-control" name="id_kuasa_pengguna" id="id_kuasa_pengguna" data-role="select2">
                                    <?php if ($session->id_role == 1) : ?>
                                        <option value="">Silahkan Pilih</option>
                                        <?php if ($kuasa_pengguna) : ?>
                                            <?php foreach ($kuasa_pengguna_list as $key => $value) : ?>
                                                <option value="<?= $value->id_kuasa_pengguna; ?>" <?= ($value->id_kuasa_pengguna == $kuasa_pengguna->id_kuasa_pengguna) ? 'selected' : ''; ?>><?= $value->kuasa_pengguna . ' - ' . $value->instansi; ?></option>
                                            <?php endforeach; ?>
                                        <?php else : ?>
                                            <option value="">Silahkan Pilih</option>
                                            <?php foreach ($kuasa_pengguna_list as $key => $value) : ?>
                                                <option value="<?= $value->id_kuasa_pengguna; ?>"><?= $value->kuasa_pengguna . ' - ' . $value->instansi; ?></option>
                                            <?php endforeach; ?>
                                        <?php endif; ?>
                                        <?php //elseif ( $session->id_role == 2 or $session->id_role == 3 ):
                                        ?>
                                    <?php else : ?> <?php //selain super admin 
                                                    ?>

                                        <?php if ($kuasa_pengguna->kuasa_pengguna != '0001' && $kuasa_pengguna->kuasa_pengguna != '*') : ?> <?php //jika kuasa_pengguna ada, maka kunci 
                                                                                                                                            ?>
                                            <option value="<?= $kuasa_pengguna->id_kuasa_pengguna; ?>" selected><?= $kuasa_pengguna->kuasa_pengguna . ' - ' . $kuasa_pengguna->instansi; ?></option>
                                        <?php else : ?> <?php //jika kuasa_pengguna tidak ada, tampilkan list 
                                                        ?>
                                            <option value="">Silahkan Pilih</option>
                                            <?php foreach ($kuasa_pengguna_list as $key => $value) : ?>
                                                <option value="<?= $value->id_kuasa_pengguna; ?>"><?= $value->kuasa_pengguna . ' - ' . $value->instansi; ?></option>
                                            <?php endforeach; ?>
                                        <?php endif; ?>

                                    <?php endif; ?>
                                </select>
                            </div>
                            <div class="col-md-3" style="margin-top: 25px;">
                                <button type="button" id="btn-filter" class="btn btn-primary">Filter</button>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="box-body">
                    <table class="table table-bordered table-striped" id="mytable">
                        <thead>
                            <tr>

                                <th width="80px">No</th>
                                <!-- <th>Id Pemilik</th> -->
                                <!-- <th>Id Kode Barang</th> -->
                                <!-- <th>Id Kode Lokasi</th> -->
                                <th>Status Validasi</th>
                                <th>Kode Barang</th>
                                <th>Nama Barang</th>
                                <th>Nomor Register</th>
                                <th>Judul Kajian Nama Software</th>
                                <th>Tanggal Perolehan</th>
                                <th>Asal Usul</th>
                                <th>Harga</th>
                                <th>Keterangan</th>
                                <th>ID Inventaris</th>
                                <!-- <th>Deskripsi</th> -->
                                <?php if ($show_lokasi) : ?>
                                    <th>Lokasi</th>
                                <?php endif; ?>
                                <!-- <th>Validasi</th> -->
                                <!-- <th>Created At</th>
                <th>Updated At</th> -->
                                <th width="200px">Aksi</th>

                                <!-- <th width="80px">No</th>
                <th>Jenis KIB</th>
                <th>Status Validasi</th>
                <th>Kode Barang</th>
                <th>Nama Barang</th>
                <th>Nomor Register</th>
                <th>Luas(M2)</th>
                <th>Tahun Pengadaan</th>
                <th>Letak/Alamat</th>
                <th>Status Hak</th>
                <th>Sertifikat Tanggal</th>
                <th>Sertifikat Nomor</th>
                <th>Penggunaan</th>
                <th>Asal Usul</th>
                <th>Harga(Rp.)</th>
                <th>Keterangan</th>
                <th>Kode Lokasi</th>
                <th width="200px">Aksi</th> -->
                            </tr>
                        </thead>

                    </table>
                </div>
            </div>
        </div>
    </div>
</section>
<script src="<?= base_url() ?>/assets/datatables/jquery.dataTables.min.js"></script>
<script src="<?= base_url() ?>/assets/datatables/dataTables.min.js"></script>
<script src="<?= base_url() ?>/assets/datatables/dataTables.checkbox.js"></script>

<script type="text/javascript">
    $(document).ready(function() {
        $.fn.dataTableExt.oApi.fnPagingInfo = function(oSettings) {
            return {
                "iStart": oSettings._iDisplayStart,
                "iEnd": oSettings.fnDisplayEnd(),
                "iLength": oSettings._iDisplayLength,
                "iTotal": oSettings.fnRecordsTotal(),
                "iFilteredTotal": oSettings.fnRecordsDisplay(),
                "iPage": Math.ceil(oSettings._iDisplayStart / oSettings._iDisplayLength),
                "iTotalPages": Math.ceil(oSettings.fnRecordsDisplay() / oSettings._iDisplayLength)
            };
        };

        var t = $("#mytable").dataTable({
            initComplete: function() {
                var api = this.api();
                $('#mytable_filter input')
                    .off('.DT')
                    .on('keyup.DT', function(e) {
                        if (e.keyCode == 13) {
                            api.search(this.value).draw();
                        }
                    });
                // alert('haloo');

                var button_all = "<button id='delete' class='btn btn-sm btn-danger ' style='margin-left: 20px;'><i class='fa fa-trash'></i> Hapus</button>";

                $('div#mytable_length>label').append(button_all);
            },
            scrollX: true,
            oLanguage: {
                sEmptyTable: "Tidak ada data yang tersedia pada tabel ini",
                sProcessing: "Sedang memproses...",
                sLengthMenu: "Tampilkan _MENU_ entri",
                sZeroRecords: "Tidak ditemukan data yang sesuai",
                sInfo: "Menampilkan _START_ sampai _END_ dari _TOTAL_ entri",
                sInfoEmpty: "Menampilkan 0 sampai 0 dari 0 entri",
                sInfoFiltered: "(disaring dari _MAX_ entri keseluruhan)",
                sInfoPostFix: "",
                sSearch: "Cari:",
                sUrl: "",
                oPaginate: {
                    sFirst: "Pertama",
                    sPrevious: "Sebelumnya",
                    sNext: "Selanjutnya",
                    sLast: "Terakhir"
                }
            },
            processing: true,
            serverSide: true,
            ajax: {
                "url": "kib_atb/json",
                "type": "POST",
                "data": function(data) {
                    data.id_pemilik = $('#pemilik').val();
                    data.id_pengguna = $('#id_pengguna').val();
                    data.id_kuasa_pengguna = $('#id_kuasa_pengguna').val();
                }
            },
            columns: [{
                    "data": "id_kib_atb",
                    "orderable": false,
                    "checkboxes": {
                        'selectRow': true
                    },
                    'width': '5%',
                    'createdCell': function(td, cellData, rowData, row, col) {
                        if (rowData.status_validasi == 2) {
                            this.api().cell(td).checkboxes.disable();
                        }
                    }
                },
                {
                    "data": "validasi"
                }, {
                    "data": "kode_barang"
                }, {
                    "data": "nama_barang_desc",
                    "searchable": false,
                }, {
                    "data": "nomor_register"
                }, {
                    "data": "judul_kajian_nama_software"
                },
                {
                    "data": "tanggal_perolehan"
                },
                {
                    "data": "asal_usul"
                },
                {
                    "data": "harga"
                },
                {
                    "data": "keterangan"
                    // }, {
                    //     "data": "deskripsi"
                }, {
                    "data": "id_inventaris",
                    // 'width': '20%'
                },
                <?php if ($show_lokasi) : ?> {
                        "data": "instansi"
                    },
                <?php endif; ?> {
                    "data": "action",
                    "orderable": false,
                    "className": "text-center"
                }, {
                    "data": "nama_barang",
                    "visible": false,
                }, {
                    "data": "nama_barang2",
                    "visible": false,
                },  {
                    "data": "id_kib_atb",
                    "visible": false,
                },{
                    "data": "nama_barang_migrasi",
                    "visible": false,
                }
            ],
            order: [
                [0, 'desc']
            ],
            rowCallback: function(row, data, iDisplayIndex) {
                // var info = this.fnPagingInfo();
                // var page = info.iPage;
                // var length = info.iLength;
                // var index = page * length + (iDisplayIndex + 1);
                // $('td:eq(0)', row).html(index);
                if (data['status_validasi'] == '1') $('td', row).css('background-color', 'white');
                else if (data['status_validasi'] == '2') $('td', row).css('background-color', 'ghostwhite');
                else if (data['status_validasi'] == '3') $('td', row).css('background-color', '#F5B7B1  ');
            }
        });
        $('#btn-filter').on('click', function() {
            table = $('#mytable').DataTable();
            table.ajax.reload();

        });


        //  pengguna
        $('#id_pengguna').change(function(e) {
            $('#id_kuasa_pengguna').html('<option value="">Silahkan Pilih</option>');
            $('#id_sub_kuasa_pengguna').html('<option value="">Silahkan Pilih</option>');
            var id_pengguna = $(this).val();
            url = base_url + 'global_controller/get_kuasa_pengguna_by_pengguna/';
            $.post(url, {
                pengguna: id_pengguna,
            }, function(respon) {
                obj_respon = jQuery.parseJSON(respon);
                $('#id_kuasa_pengguna').html(obj_respon.option);
            });
        });

        $('select[data-role=select2]').select2({
            theme: 'bootstrap',
            width: '100%',
        });
    });

    $(document).on('click', '#delete', function() {

        var table = $("#mytable").DataTable();
        var rows_selected = table.column(0).checkboxes.selected();
        list_id = [];
        $.each(rows_selected, function(index, rowId) {
            list_id.push(rowId);
        })

        // console.log(list_id);
        // return false;
        if (list_id.length < 1) {
            swal('Data Belum Dipilih!');
            return false;
        }
        // return false;
        swal({
            title: "Apakah kamu yakin?",
            text: "Data yang dipilih akan dihapus!",
            type: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Hapus',
            cancelButtonText: 'Batalkan'
        }).then((result) => {
            if (result.value) {

                $("#loadMe").modal({
                    backdrop: "static", //remove ability to close modal with click
                    keyboard: false, //remove option to close with keyboard
                    show: true //Display loader!
                });

                url = base_url + 'kib_atb/delete_all/';
                $.post(url, {
                    list_id: list_id
                }, function(respon) {
                    $("#loadMe").modal("hide");
                    obj = jQuery.parseJSON(respon);
                    if (!obj.status) {
                        swal({
                            type: 'error',
                            title: '!!!',
                            text: obj.message,
                            // footer: '<a href>Why do I have this issue?</a>'
                        })

                    } else {
                        swal({
                            title: 'Berhasil',
                            text: "Berhasil dihapus!",
                            type: 'success',
                            // showCancelButton: true,
                            confirmButtonColor: '#3085d6',
                            cancelButtonColor: '#d33',
                            confirmButtonText: 'Ya'
                        }).then((result) => {
                            if (result.value) {
                                location.reload()
                            }
                        })
                    }
                    $('#mytable_wrapper').find('li.paginate_button.active>a').click();
                    // get_request_validasi();
                });
            } else {}
        })
    })
</script>


<?php /*
<!doctype html>
<html>

<head>
    <title>harviacode.com - codeigniter crud generator</title>
    <link rel="stylesheet" href="<?php echo base_url('assets/bootstrap/css/bootstrap.min.css') ?>" />
    <link rel="stylesheet" href="<?php echo base_url('assets/datatables/dataTables.bootstrap.css') ?>" />
    <link rel="stylesheet" href="<?php echo base_url('assets/datatables/dataTables.bootstrap.css') ?>" />
    <style>
        .dataTables_wrapper {
            min-height: 500px
        }

        .dataTables_processing {
            position: absolute;
            top: 50%;
            left: 50%;
            width: 100%;
            margin-left: -50%;
            margin-top: -25px;
            padding-top: 20px;
            text-align: center;
            font-size: 1.2em;
            color: grey;
        }

        body {
            padding: 15px;
        }
    </style>
</head>

<body>
    <div class="row" style="margin-bottom: 10px">
        <div class="col-md-4">
            <h2 style="margin-top:0px">Tbl_kib_atb List</h2>
        </div>
        <div class="col-md-4 text-center">
            <div style="margin-top: 4px" id="message">
                <?php echo $this->session->userdata('message') <> '' ? $this->session->userdata('message') : ''; ?>
            </div>
        </div>
        <div class="col-md-4 text-right">
            <?php echo anchor(site_url('tbl_kib_atb/create'), 'Create', 'class="btn btn-primary"'); ?>
        </div>
    </div>
    <table class="table table-bordered table-striped" id="mytable">
        <thead>
            <tr>
                <th width="80px">No</th>
                <th>Id Pemilik</th>
                <th>Id Kode Barang</th>
                <th>Id Kode Lokasi</th>
                <th>Nama Barang</th>
                <th>Nomor Register</th>
                <th>Judul Kajian Nama Software</th>
                <th>Tahun Perolehan</th>
                <th>Asal Usul</th>
                <th>Keterangan</th>
                <th>Deskripsi</th>
                <th>Kode Lokasi</th>
                <th>Validasi</th>
                <th>Created At</th>
                <th>Updated At</th>
                <th width="200px">Action</th>
            </tr>
        </thead>

    </table>
    <script src="<?php echo base_url('assets/js/jquery-1.11.2.min.js') ?>"></script>
    <script src="<?php echo base_url('assets/datatables/jquery.dataTables.js') ?>"></script>
    <script src="<?php echo base_url('assets/datatables/dataTables.bootstrap.js') ?>"></script>
    <script type="text/javascript">
        $(document).ready(function() {
            $.fn.dataTableExt.oApi.fnPagingInfo = function(oSettings) {
                return {
                    "iStart": oSettings._iDisplayStart,
                    "iEnd": oSettings.fnDisplayEnd(),
                    "iLength": oSettings._iDisplayLength,
                    "iTotal": oSettings.fnRecordsTotal(),
                    "iFilteredTotal": oSettings.fnRecordsDisplay(),
                    "iPage": Math.ceil(oSettings._iDisplayStart / oSettings._iDisplayLength),
                    "iTotalPages": Math.ceil(oSettings.fnRecordsDisplay() / oSettings._iDisplayLength)
                };
            };

            var t = $("#mytable").dataTable({
                initComplete: function() {
                    var api = this.api();
                    $('#mytable_filter input')
                        .off('.DT')
                        .on('keyup.DT', function(e) {
                            if (e.keyCode == 13) {
                                api.search(this.value).draw();
                            }
                        });
                },
                oLanguage: {
                    sProcessing: "loading..."
                },
                processing: true,
                serverSide: true,
                ajax: {
                    "url": "tbl_kib_atb/json",
                    "type": "POST"
                },
                columns: [{
                        "data": "id_kib_atb",
                        "orderable": false
                    }, {
                        "data": "id_pemilik"
                    }, {
                        "data": "id_kode_barang"
                    }, {
                        "data": "id_kode_lokasi"
                    }, {
                        "data": "nama_barang"
                    }, {
                        "data": "nomor_register"
                    }, {
                        "data": "judul_kajian_nama_software"
                    }, {
                        "data": "tahun_perolehan"
                    }, {
                        "data": "asal_usul"
                    }, {
                        "data": "keterangan"
                    }, {
                        "data": "deskripsi"
                    }, {
                        "data": "kode_lokasi"
                    }, {
                        "data": "validasi"
                    }, {
                        "data": "created_at"
                    }, {
                        "data": "updated_at"
                    },
                    {
                        "data": "action",
                        "orderable": false,
                        "className": "text-center"
                    }
                ],
                order: [
                    [0, 'desc']
                ],
                rowCallback: function(row, data, iDisplayIndex) {
                    var info = this.fnPagingInfo();
                    var page = info.iPage;
                    var length = info.iLength;
                    var index = page * length + (iDisplayIndex + 1);
                    $('td:eq(0)', row).html(index);
                }
            });
        });
    </script>
</body>

</html>
 */
