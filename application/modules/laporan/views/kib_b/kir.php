<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
        
        <style type="text/css">

            @page {
        margin: 0in;
    }

    body {
        
        
        -webkit-background-size: cover;
        -moz-background-size: cover;
        -o-background-size: cover;
        background-size: cover;
        ;
        background-position: top left;
        background-repeat: no-repeat;
        background-size: 100%;
        
        width: 100%;
        height: 100%;

    }
    td{
    padding-bottom: 0px;
    padding-top: 0px;
}

 .break_before { page-break-before: always; }
            .break_after { page-break-after: always; }
            
            .page-break { 
                display:block;
                page-break-before: always;
                page-break-after: always;
            }
        .style13 {font-size: 18px; font-weight: bold; }
        </style>

</head>
 
    <body onLoad="window.print();">
   <!-- <body onLoad="window.print();">
   <div class="garis_verikal">
   <div>-->
   
        <?php 
        //print_r($result);exit;
        for($i=0;$i<(COUNT($result));$i++)
        { ?>
    <div class="page-break"></div>
    <?php if($i< (COUNT($result))) {?>
    <!-- and ($i % 10 == 0)-->
        <div style="margin-left: 10px; margin-right: 20px;">
          <table width="50%"  style="font-size:13px;height: 15px;">
           
  <tr>
    <td width="3%">&nbsp;</td>
    <td width="8%">&nbsp;</td>
    <td width="24%">&nbsp;</td>
    <td width="12%">&nbsp;</td>
    <td width="3%">&nbsp;</td>
    <!--<td width="3%">&nbsp;</td>
    <td width="8%">&nbsp;</td>
    <td width="24%">&nbsp;</td>
    <td width="12%">&nbsp;</td>
    <td width="3%">&nbsp;</td>-->
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td rowspan="2" style="border-left: 1px solid black;border-top: 1px solid black;border-bottom: 1px solid black;"><div align="center"><img src="<?php echo base_url() .'assets/files/images/logo_kir.png' ?>" align="top"/></div></td>
    <td width="24%" style="border-right: 1px solid black;border-left: 1px solid black;border-top: 1px solid black;border-bottom: 1px solid black;"><div align="center"><span class="style13">Pemerintah Kota Yogyakarta</span></div></td>
    <td rowspan="2"style="border-right: 1px solid black;border-top: 1px solid black;border-bottom: 1px solid black;"><div align="center"><img src="<?php echo base_url() .'qr/'?><?php   echo $result[$i]['id_enkripsi'].'.png' ?>" align="top"/></div></td>
    <td>&nbsp;</td>
    <!--<td>&nbsp;</td>
    <td rowspan="2"><img src="<?php echo base_url() .'assets/files/images/logo_kir.png'; ?>" align="top"/></td>
    <td width="24%"><div align="center"><span class="style13">Pemerintah Kota Yogyakarta</span></div></td>
    <td rowspan="2"><img src="<?php echo base_url() .'qr/'?><?php   echo $result[$i]['id_enkripsi'].'.png' ?>" align="top"/></td>
    <td>&nbsp;</td>-->
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td style="border-right: 1px solid black;border-left: 1px solid black;border-bottom: 1px solid black;">
      <div align="center"><?php echo $result[$i]['kode_barang'].'<br>'.$result[$i]['kode_jenis'].'-'.$result[$i]['id_kib']?></div></td>
    <td>&nbsp;</td>
    <!--<td>&nbsp;</td>
    <td><?php echo $result[$i]['kode_jenis'].'-'.$result[$i]['id_kib']?></td>
    <td>&nbsp;</td>-->
  </tr>
   <tr>
    <td width="3%">&nbsp;</td>
    <td width="8%">&nbsp;</td>
    <td>&nbsp;</td>
    <td width="12%">&nbsp;</td>
    <td width="3%">&nbsp;</td>
    <!--<td width="3%">&nbsp;</td>
    <td width="8%">&nbsp;</td>
    <td>&nbsp;</td>
    <td width="12%">&nbsp;</td>
    <td width="3%">&nbsp;</td>-->
  </tr>
        </table>
     </div>
         
    
        <?php 
    }
    
    
        }?>
     <!--</div>
        </div>-->
</body>
</html>