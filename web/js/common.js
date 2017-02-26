function addOutlay()
{
    var html = $.ajax({
        url: "/stat/outlay",
        async: false,
        type: "POST",
        data: {type: $("#outlay-type").val(), amount: $("#outlay-amount").val()}
    }).responseText;
    if (html != '') {
        $("#summary_t").html(html);
        $("#outlay-type").val("");
        $("#outlay-amount").val("");
    }
}

function updateOutlay()
{
    var html = $.ajax({
        url: "/stat/outlay",
        async: false,
        type: "POST",
        data: {type: $("#outlay-type-edit").val(), amount: $("#outlay-amount-edit").val(), id: $("#outlay-id-edit").val()}
    }).responseText;
    if (html != '') {
        $("#summary_t").html(html);
    }
}

function deleteOutlay(obj)
{
    var id_to_del = $(obj).parent().find(".o-id").val();
    var html = $.ajax({
        url: "/stat/outlay",
        async: false,
        type: "POST",
        data: {id: id_to_del, action: 'delete'}
    }).responseText;
    if (html != '') {
        $("#summary_t").html(html);
    }
}

function showOutlayModal(obj)
{
    $("#outlay-type-edit").val($(obj).parent().parent().find(".o-type").html());
    $("#outlay-amount-edit").val(($(obj).parent().parent().find(".o-amount").html()).replace(/\s{1,}/g, ''));
    $("#outlay-id-edit").val($(obj).parent().find(".o-id").val());
}

function pickup()
{
    var html = $.ajax({
        url: "/stat/outlay",
        async: false,
        type: "POST",
        data: {amount: $("#pickup-amount").val(), action: 'pickup'}
    }).responseText;
    if (html != '') {
        $("#summary_t").html(html);
        $("#pickup-amount").val("");
    }
}