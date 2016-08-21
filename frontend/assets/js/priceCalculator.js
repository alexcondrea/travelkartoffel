function getTotalPrice() {
    var price = window.tripStatus.reduce(function (prev, curr) {
        return prev + curr.price * curr.nights;
    }, 0);

    return price;
}

function postAjaxForPriceSave() {
    var price = getTotalPrice();
    var date = $('#startDate input').val();

    $.ajax({
        type: "POST",
        url: 'http://tripvago.ga/kartoffel/api/price?start_date=' + date + '&current_price=' + price,
        data: JSON.stringify(window.tripStatus),
        success: successPriceSave,
        dataType: 'json'
    });
}

function successPriceSave(retText) {
    $('#savePriceText').html(retText);
}

$('body').on('kartoffel:data:loaded', function () {
    postAjaxForPriceSave();
});
