function postAjaxForPriceSave() {
    $.ajax({
        type: "POST",
        url: 'http://tripvago.ga/kartoffel/api/price?start_date=2016-08-30&current_price=10000',
        data: JSON.stringify(window.tripData),
        success: successPriceSave,
        dataType: 'json'
    });
}

function successPriceSave(retText) {
    $('#savePriceText').html(retText);
}

$('body').on('kartoffel:data:loaded', function (event) {
    postAjaxForPriceSave();
});
