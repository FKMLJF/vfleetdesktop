

function plusMinus(inc, id) {
    var _old = ($('#'+id).val() == '') ? 0  : parseFloat($('#'+id).val());
    var _new = _old + parseFloat(inc)
    $('#'+id).val((_new <= 0 )?0:_new);
}
