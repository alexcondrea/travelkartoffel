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
    var element = $('#savePriceText');
    element.html(retText);

    element.closest("price-suggest").addClass("active");
}

$('body').on('kartoffel:data:loaded', function () {
    postAjaxForPriceSave();
});

$('.price-suggest a.close').click(function() {
    $(this).closest("price-suggest").removeClass("active");
});
