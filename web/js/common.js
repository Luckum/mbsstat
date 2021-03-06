$(document).ready(function() {
    $("#sync_select_settings_mbs").treeMultiselect({
        searchable: true,
        searchParams: ['section', 'text'],
        startCollapsed: true,
        enableSelectAll: true
    });
    $("#sync_select_settings_sd").treeMultiselect({
        searchable: true,
        searchParams: ['section', 'text'],
        startCollapsed: true,
        enableSelectAll: true
    });
    $('.search').focus(function() {
        $(this).attr('placeholder', '')
    }).blur(function() {
        $(this).attr('placeholder', 'Поиск...')
    });
    $('.tooltip-top-td').tooltip();
    $("#sidebar").on("click", "a", function (event) {
        event.preventDefault();
        var id  = $(this).attr('href'),
            top = $(id).offset().top - 50;
        $('body,html').animate({scrollTop: top}, 1500);
    });
    $('#up-btn').click(function() {
        $('body,html').animate({scrollTop:0},500);
        return false;
    });
    $(window).scroll(function() {
        if ($(this).scrollTop() > 150) {
            $('#back-top').show();
        } else {
            $('#back-top').hide();
        }
    });
});

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
    $("#product_name_label_s").html($(obj).find("[name=product_name_td]").val());
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
        $('#td_price_selling_' + $("#product_id_update").val() + '_' + $("#site_id_update").val()).html(html);
    }
    
    var html = $.ajax({
        url: "/product/updateincome",
        async: false,
        type: "POST",
        data: {product_id: $("#product_id_update").val(), price_selling: $("#price_selling_update").val()}
    }).responseText;
    
    if (html != '') {
        $('#td_income_'+$("#product_id_update").val()).html(html);
    }
    
    var html = $.ajax({
        url: "/product/updateincomeclear",
        async: false,
        type: "POST",
        data: {product_id: $("#product_id_update").val()}
    }).responseText;
    
    if (html != '') {
        $('#td_income_clear_' + $("#product_id_update").val()).html(html);
    }
    
    var html = $.ajax({
        url: "/product/updateincomecleartotal",
        async: false,
        type: "POST",
        data: {product_id: $("#product_id_update").val()}
    }).responseText;
    
    if (html != '') {
        $('#td_income_clear_total_'+$("#product_id_update").val()).html(html);
    }
    
    var html = $.ajax({
        url: "/product/updaterevenue",
        async: false,
        type: "POST",
        data: {}
    }).responseText;
    
    if (html != '') {
        $('#revenue-tbl').html(html);
    }
    
    var html = $.ajax({
        url: "/product/updatecashbox",
        async: false,
        type: "POST",
        data: {}
    }).responseText;
    
    if (html != '') {
        $('#cashbox-container').html(html);
    }
    var html = $.ajax({
        url: "/product/updateresiduepurchase",
        async: false,
        type: "POST",
        data: {}
    }).responseText;
    
    if (html != '') {
        $('#residue-purchase-td').html(html);
    }
    
    var html = $.ajax({
        url: "/product/updateresiduetotal",
        async: false,
        type: "POST",
        data: {}
    }).responseText;
    
    if (html != '') {
        $('#residue-total-td').html(html);
    }
}

function changeComment(obj)
{
    $("#comment_update").val($(obj).find("span").html());
    $("#product_id_update_c").val($(obj).find("[name=product_id_td]").val());
    $("#product_name_label_c").html($(obj).find("[name=product_name_td]").val());
    $("#comment_modal").modal('show');
}

function updateComment()
{
    var html = $.ajax({
        url: "/product/updatecomment",
        async: false,
        type: "POST",
        data: {product_id: $("#product_id_update_c").val(), comment: $("#comment_update").val()}
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

function changePricePurchase(obj)
{
    $("#price_purchase_update").val($(obj).find("span").html().replace(/\s{1,}/g, ''));
    $("#product_id_update").val($(obj).find("[name=product_id_td]").val());
    $("#product_name_label").html($(obj).find("[name=product_name_td]").val());
    $("#price_purchase_modal").modal('show');
}

function updatePricePurchase()
{
    var html = $.ajax({
        url: "/product/updatepurchase",
        async: false,
        type: "POST",
        data: {product_id: $("#product_id_update").val(), price_purchase: $("#price_purchase_update").val()}
    }).responseText;
    
    if (html != '') {
        $('#td_price_purchase_'+$("#product_id_update").val()).html(html);
    }
    
    var html = $.ajax({
        url: "/product/updateincomeclear",
        async: false,
        type: "POST",
        data: {product_id: $("#product_id_update").val(), price_purchase: $("#price_purchase_update").val()}
    }).responseText;
    
    if (html != '') {
        $('#td_income_clear_' + $("#product_id_update").val()).html(html);
    }
    
    var html = $.ajax({
        url: "/product/updateincomecleartotal",
        async: false,
        type: "POST",
        data: {product_id: $("#product_id_update").val(), price_purchase: $("#price_purchase_update").val()}
    }).responseText;
    
    if (html != '') {
        $('#td_income_clear_total_'+$("#product_id_update").val()).html(html);
    }
    
    var html = $.ajax({
        url: "/product/updaterevenue",
        async: false,
        type: "POST",
        data: {}
    }).responseText;
    
    if (html != '') {
        $('#revenue-tbl').html(html);
    }
    
    var html = $.ajax({
        url: "/product/updatecashbox",
        async: false,
        type: "POST",
        data: {}
    }).responseText;
    
    var html = $.ajax({
        url: "/product/updateresiduepurchase",
        async: false,
        type: "POST",
        data: {}
    }).responseText;
    
    if (html != '') {
        $('#residue-purchase-td').html(html);
    }
    
    var html = $.ajax({
        url: "/product/updateresiduetotal",
        async: false,
        type: "POST",
        data: {}
    }).responseText;
    
    if (html != '') {
        $('#residue-total-td').html(html);
    }
}

function getAdPrice(obj)
{
    if ($(obj).val() == '-1') {
        $('#new-creator-container').show();
        $("#price").val('');
        $("#amount").val('');
        $("#ad_type").val('C');
    } else {
        $('#new-creator-container').hide();
        var html = $.ajax({
            url: "/ad/getprice",
            async: false,
            type: "POST",
            data: {creator: $(obj).val()}
        }).responseText;
        if (html != '') {
            $("#price").val(html);
        }
        var html = $.ajax({
            url: "/ad/getamount",
            async: false,
            type: "POST",
            data: {creator: $(obj).val()}
        }).responseText;
        if (html != '') {
            $("#amount").val(html);
        }
        var html = $.ajax({
            url: "/ad/gettype",
            async: false,
            type: "POST",
            data: {creator: $(obj).val()}
        }).responseText;
        if (html != '') {
            $("#ad_type").val(html);
        }
    }
}

function deletePublic(id)
{
    if (confirm('Вы уверены?')) {
        var html = $.ajax({
            url: "/ad/deletepublic",
            async: false,
            type: "POST",
            data: {id: id}
        }).responseText;
        $('#public-container-' + id).hide();
    }
}
