$(document).ready(function () {
    //Date picker
    $('.date_input').datepicker({
        format: "dd MM yyyy",
        autoclose: true,
        language: "id",
        locale: 'id',
        todayHighlight: true,
        endDate: '31-12-' + (new Date).getFullYear(),
    });

    $('select[data-role=select2]').select2({
        theme: 'bootstrap',
        width: '100%'
    });
    /*
        //Initialize Select2 Elements
        $('#kode_lokasi_penerima').select2();
        $('#barang').select2()
    
        //  BIDANG CLICK
        $('#kib').change(function (e) {
            $('#barang').html('');
    
            var kode_gol = $('option:selected', this).attr('kode_gol');
            url = base_url + 'global_controller/get_barang_kib/';
            $.post(url, { kode_gol: kode_gol, }, function (respon) {
                obj_respon = jQuery.parseJSON(respon);
                $('#barang').html(obj_respon.option);
            });
        });
        */
}); //ready function
