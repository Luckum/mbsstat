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

function getProducts(obj)
{
    var html = $.ajax({
        url: "/product/getproduct",
        async: false,
        type: "POST",
        data: {category: $(obj).val()}
    }).responseText;
    if (html != '') {
        $("#product").html(html);
    }
}

function getProductDetails(obj)
{
    var html = $.ajax({
        url: "/product/getdetails",
        async: false,
        type: "POST",
        data: {product: $(obj).val()}
    }).responseText;
    if (html != '') {
        $("#product-details").html(html);
    }
}

function showPrice()
{
    if ($("#old_price").prop('checked')) {
        $("#new_price").hide();
        $("#sold_old").hide();
    } else {
        $("#new_price").show();
        $("#sold_old").show();
    }
}

function changePriceSelling(obj)
{
    $("#price_selling_update").val($(obj).find("span").html().replace(/\s{1,}/g, ''));
    $("#product_id_update").val($(obj).find("[name=product_id_td]").val());
    $("#site_id_update").val($(obj).find("[name=site_id_td]").val());
    $("#site_name_label").html($(obj).find("[name=site_name_td]").val());
    $("#price_selling_modal").modal('show');
}

function updatePriceSelling()
{
    var html = $.ajax({
        url: "/product/updateselling",
        async: false,
        type: "POST",
        data: {product_id: $("#product_id_update").val(), site_id:  $("#site_id_update").val(), price_selling: $("#price_selling_update").val()}
    }).responseText;
    
    if (html != '') {
        $('#td_price_selling_'+$("#product_id_update").val()).html(html);
    }
    
    var html = $.ajax({
        url: "/product/updateincome",
        async: false,
        type: "POST",
        data: {product_id: $("#product_id_update").val(), site_id:  $("#site_id_update").val(), price_selling: $("#price_selling_update").val()}
    }).responseText;
    
    if (html != '') {
        $('#td_income_'+$("#product_id_update").val()).html(html);
    }
}

function changeComment(obj)
{
    $("#comment_update").val($(obj).find("span").html());
    $("#product_id_update_c").val($(obj).find("[name=product_id_td]").val());
    $("#site_id_update_c").val($(obj).find("[name=site_id_td]").val());
    $("#site_name_label_c").html($(obj).find("[name=site_name_td]").val());
    $("#comment_modal").modal('show');
}

function updateComment()
{
    var html = $.ajax({
        url: "/product/updatecomment",
        async: false,
        type: "POST",
        data: {product_id: $("#product_id_update_c").val(), site_id:  $("#site_id_update_c").val(), comment: $("#comment_update").val()}
    }).responseText;
    
    if (html != '') {
        $('#td_comment_'+$("#product_id_update_c").val()).html(html);
    }
}

function getRenderProductDetails(obj)
{
    var html = $.ajax({
        url: "/product/getrenderdetails",
        async: false,
        type: "POST",
        data: {product: $(obj).val()}
    }).responseText;
    if (html != '') {
        $("#render-product-details").html(html);
    }
}