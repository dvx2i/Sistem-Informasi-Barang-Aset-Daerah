<html>

<head>
    <style>
        
        html, body {
            width: 210mm;
            height: 297mm;
        }

        table, th, td {
        border: 1px solid black;
        border-collapse: collapse;
        }
        p.inline {
            display: inline-block;
        }

        span {
            font-size: 13px;
        }
    </style>
    <style type="text/css" media="print">
        @page {
            size: A4;
            /* auto is the initial value */
            margin: 0mm;
            /* this affects the margin in the printer settings */

        }
        /* @media print {
        html, body {
            width: 210mm;
            height: 297mm;
        }
        } */
    </style>
</head>

<body onload="window.print();">
    <div style="margin-left:14px; ">


        <?php for ($i = 0; $i < (COUNT($result)); $i++) : ?>
            <p class='inline'>
            <table width="340.15748px" style="font-size:13px;height: 132.28346px;">

                <tr>
                    <td style="height: 30px;">
                        <div align="center"><img style="width: 22px; height: 25px;"  src="<?=  base_url() . 'assets/files/images/logo_kir.png' ?>" align="top" /></div>
                    </td>
                    <td style="">
                        <div align="center"><span class="style13">Pemerintah Kota Yogyakarta</span></div>
                    </td>
                    <td rowspan="2" style="">
                        <div align="center"><img src="<?=  base_url() . 'qr/' ?><?=  $result[$i]['id_enkripsi'] . '.png' ?>" align="top" /> <br> <?= $result[$i]['lokasi'] ?> </div>
                        
                    </td>
                    <!--<td>&nbsp;</td>
    <td rowspan="2"><img src="<?=  base_url() . 'assets/files/images/logo_kir.png'; ?>" align="top"/></td>
    <td width="24%"><div align="center"><span class="style13">Pemerintah Kota Yogyakarta</span></div></td>
    <td rowspan="2"><img src="<?=  base_url() . 'qr/' ?><?=  $result[$i]['id_enkripsi'] . '.png' ?>" align="top"/></td>
    <td>&nbsp;</td>-->
                </tr>
                <tr>
                    <td colspan="2">
                        <div align="center"><?=  $result[$i]['nama_barang']. '<br>'  . 
                                                 $result[$i]['kode_barang'] . ' \ ' . $result[$i]['nomor_register'] . ' \ ' . $result[$i]['id_kib'] . '<br>' . 
                                                 $result[$i]['spesifikasi'] . ' \ ' . $result[$i]['tahun'] . '<br>' .
                                                 $result[$i]['ruang'] ?></div>
                    </td>
                    <!--<td>&nbsp;</td>
    <td><?=  $result[$i]['kode_jenis'] . '-' . $result[$i]['id_kib'] ?></td>
    <td>&nbsp;</td>-->
                </tr>
            </table>
            </p>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
        <?php endfor; ?>

        
    </div>
</body>

</html>