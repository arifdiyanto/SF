<div class="meToolbar form-inline text-right">
    <button class="btn btn-sm btn-success meDiv meFrm" onclick="oSave();" title="Save"><i class="fa fa-save"></i></button>
    <button class="btn btn-sm btn-danger meDiv meFrm" onclick="oDel();" title="Delete"><i class="fa fa-trash-o"></i></button>
    <button class="btn btn-sm btn-default meDiv meList" onclick="oNew();" title="New"><i class="fa fa-file-o"></i></button>
    <button class="btn btn-sm btn-default meDiv meFrm" onclick="meShow('meList')" title="Close"><i class="fa fa-times"></i></button>
    <div class="input-group meDiv meList">
        <input id="q" type="text" class="form-control input-sm" placeholder="Search">
        <span class="input-group-addon pointer" onclick="oSearch();"><i class="fa fa-search pointer"></i></span>
    </div>
</div>
<br>
<div class="meDiv meList">
    <div class="box box-widget">
        <div class="box-body">
            <div id="list1"></div>
        </div>
    </div>
</div>
<div class="meDiv meFrm">
    <div class="box box-widget">
        <div class="box-body">
            <form id='frm' method='POST' action='<?=ROOT?>/CSample/save'>
                <input type="hidden" id="crud" name="crud" value="c">
                <label>kode</label>
                <input type='text' id='kode' name='kode' class='form-control input-sm clear must' value='' placeholder=''>
                <label>nama</label>
                <input type='text' id='nama' name='nama' class='form-control input-sm clear' value='' placeholder=''>
                <label>created_date</label>
                <input type='text' id='created_date' name='created_date' class='form-control input-sm clear date' value='' placeholder='' readonly="">
            </form>
        </div>
    </div>
</div>
<script type="text/javascript">
$(document).ready(function() {
    oSearch();
});

function meShow(meId) {
    $(".meDiv").hide();
    $("." + meId).show();
}

function oNew() {
    meShow('meFrm');
    $("#crud").val('c');
    $(".clear").val('');
}

function oSearch() {
    meShow('meList');
    $('#list1').html("<i class='fa fa-spin fa-spinner'></i>");
    $('#list1').load("<?=ROOT . '/CSample/getList'?>?q=" + $("#q").val(), function() {
        oLoadPagination("#list1");
        $('#q').on("keydown", function(e) {
            if (e.keyCode == 13)
                oSearch(id);
        });
    });
}

function oShow(id) {
    meShow('meFrm');
    $.post("<?=ROOT . '/CSample/show';?>", {
        id: id
    }, function(data) {
        var obj = JSON.parse(data);
        oNew();
        $.each(obj.data, function(index, item) {
            $("#" + index).val(item);
        });
        $("#crud").val('u');
    });
}

function oDel() {
    var id = $("#kode").val();

    swal({
            title: "Are you sure?",
            text: "You will delete this data!",
            type: "warning",
            showCancelButton: true,
            confirmButtonColor: "#DD6B55",
            confirmButtonText: "Yes, delete it!",
            closeOnConfirm: true
        },
        function() {
            var $btn = $('#btn-del').button('loading');
            $.ajax({
                url: "<?=ROOT . '/CSample/del';?>?id=" + id,
                success: function(data) {
                    $btn.button('reset');
                    var patt = /Error|Kesalahan|Alert/g;
                    var result = patt.test(data);
                    if (result === false) {
                        oSearch();
                    } else {
                        swal('', data, error);
                    }
                    return false;
                },
                error: function(data) {
                    swal('', data.status, "error");
                }
            });
        });
}

function oSave() {
    $(".textError").removeClass("textError");
    var cek = true;
    var x = $(".must");
    x.each(function(index) {
        if ($(x[index]).val().trim() === "") {
            cek = false;
            $(x[index]).addClass("textError");
        }
    });
    if (cek === false) {
        swal('', 'Data tidak lengkap!', 'error');
        return false;
    }
    var formdata = new FormData();
    $.each($('#frm').serializeArray(), function(a, b) {
        formdata.append(b.name, b.value);
    });
    var $btn = $('#btn-save').button('loading');
    $.ajax({
        url: $("#frm").attr('action'),
        data: formdata,
        processData: false,
        contentType: false,
        enctype: 'multipart/form-data',
        type: 'POST',
        beforeSend: function() {
            // add event or loading animation
        },
        success: function(data) {
            $btn.button('reset');
            var patt = /Error|error|Kesalahan|Alert/g;
            var result = patt.test(data);
            if (result === false) {
                oSearch();
            } else {
                swal('', data, 'error');
            }
            return false;
        },
        error: function(e) {
            $btn.button('reset');
            swal('', data, 'error');
        }
    });
    return false;
}
</script>
