function sel_record_type_onchange(e)
{
    e.form.txt_record_type_code.value = e.value;
    if (trim(e.value) != '')
    {
        e.form.submit();
    }
}
function txt_record_type_code_onkeypress(evt)
{
    if (IE()){
        theKey=window.event.keyCode;
    } else {
        theKey=evt.which;
    }

    if(theKey == 13){
        v_record_type_code = trim($("#txt_record_type_code").val()).toUpperCase();
        $("#sel_record_type").val(v_record_type_code);
        if ($("#sel_record_type").val() != '')
        {
            $("#frmMain").submit();
        }
        else
        {
            $("#procedure").html('');
        }
    }
    return false;
}