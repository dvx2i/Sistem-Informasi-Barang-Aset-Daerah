<?php
$session = $this->session->userdata('session');
// die(json_encode($session));
?>
<style>
    .info-box-icon-custom {
    border-top-left-radius: 2px;
    border-top-right-radius: 0;
    border-bottom-right-radius: 0;
    border-bottom-left-radius: 2px;
    display: block;
    float: left;
    height: 52px;
    width: 60px;
    text-align: center;
    font-size: 45px;
    line-height: 50px;
    background: rgba(0,0,0,0.2);
}
.info-box-content-custom {
    padding: 13px 12px;
    margin-left: 60px;
}
.info-box-custom {
    display: block;
    min-height: 50px;
    background: #fff;
    width: 100%;
    box-shadow: 0 1px 1px rgb(0 0 0 / 10%);
    border-radius: 2px;
    margin-bottom: 15px;
}
th {
background-color: #00c0ef !important;
}
</style>

<section class="content-header">
    <div class="row" >
        <div class="col-md-8 form-inline">
            <h2 style="margin-top:0px"><b>Laporan Ceklis Stock Opname Tahun </b>
            <select name="tahun" id="tahun" class="form-control input-lg form-inline">
                <option value="<?= date('Y'); ?>"><?= date('Y');  ?></option>    
                <option value="<?= date('Y')-1; ?>"><?= date('Y')-1; ?></option>    
            </select></h2>
        </div>
        <div class="col-md-3 form-inline pull-right">
            
            <div class="info-box-custom">
            <span class="info-box-icon-custom bg-aqua"><i class="fa fa-calendar"></i></span>

            <div class="info-box-content-custom  bg-aqua">
              <span class="info-box-text"></span>
              <span class="info-box-number"><?= date('d').' '.bulan_indo(date('n')).' '.date('Y');  ?></span>
            </div>
            <!-- /.info-box-content -->
          </div>
        </div>
    </div>
</section>
<section class="content">
    <div class="row">
        <div class="col-xs-12">
            <div class="box box-primary">
                <div class="box-body">
                    <div id="example" class='table table-responsive'></div>
                </div>
            </div>
        </div>
    </div>
</section>

<script>
    var dataInJson;
    dataInJson = $.ajax({type: "POST", data:{ tahun: $('#tahun').val() }, url: "<?= site_url('laporan/stock_opname/json_rekap') ?>", async: false}).responseText;
    
    dataInJson = JSON.parse(dataInJson);
    // var dataInJson = [
    //     {
    //         "data": {
    //             "name": "b1",
    //             "street": "s1",
    //             "city": "c1",
    //             "departments": 10,
    //             "offices": 15
    //         },
    //         "kids": [{
    //             "data": {
    //                 "department": "HR",
    //                 "supervisor": "Isidor Bristol",
    //                 "floor": 1,
    //                 "employees": 15
    //             },
    //             "kids": []
    //         },]
    //     }

    // ];

    var settigns = {
        "iDisplayLength": 30,
        "bLengthChange": false,
        "bFilter": true,
        "bSort": false,
        "bInfo": false
    };

    // console.log(dataInJson); 
    // return false;

    var table = new nestedTables.TableHierarchy("example", dataInJson, settigns);
    table.initializeTableHierarchy();

    $('#tahun').on('change', function(){
    dataInJson = $.ajax({type: "POST", data:{ tahun: $('#tahun').val() }, url: "<?= site_url('laporan/stock_opname/json_rekap') ?>", async: false}).responseText;
    dataInJson = JSON.parse(dataInJson);
    
    var settigns = {
        "iDisplayLength": 30,
        "bLengthChange": false,
        "bFilter": true,
        "bSort": false,
        "bInfo": false
    };

    // console.log(dataInJson); 
    // return false;

    var table = new nestedTables.TableHierarchy("example", dataInJson, settigns);
    table.initializeTableHierarchy();

    })
</script>