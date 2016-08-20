var colIndex = 0;


var getRatingClass = function(value) {
    var rc = 0;

    if (value > 80) {
        rc = 4;
    } else if (value > 76 && value < 80) {
        rc = 3;
    } else if (value > 74 && value < 76) {
        rc = 2;
    } else if (value > 70 && value < 74) {
        rc = 1;
    }

    return rc;
};

var tileSource = $("#tile-template").html();
var tiletemplate = Handlebars.compile(tileSource);

data.items.forEach(function(item) {
    var info = {
        hotelName: item.name,
        locationName: item.city,
        nights: 3,
        rating: getRatingClass(item.ratingValue),
        imageUrl: item.mainImage.extraLarge,
        priceFormatte: item.deals[0].price.formatted,
        stars: 'star_' + item.category
    };
    var $tile = tiletemplate(info);

    $('#target').append($tile);
});

function getTrivagoColor() {
    var colors = [
        "rgb(244, 143, 0)",
        "rgb(0, 127, 175)",
        "rgb(201, 74, 56)"
    ]
    if (colIndex < 2) {
        colIndex++;
    } else {
        colIndex = 0;
    }
    return colors[colIndex];
}

function fillColumns() {
    var source = $("#tile-template").html();
    var template = Handlebars.compile(source);
    $.getJSON("http://tripvago.ga/kartoffel/api/search/hotel-collection?path=8514", function function_name(data) {
        console.log(data);
        var context = {
            locationName: "Berlin",
            nights: "2",
            stars: "4",
            rating: "10",
            priceFormat: "100€"
        };
        var html = template(context);

        $("#mainColumns").append(html);
    })

}

$('#startDate').datetimepicker({
    locale: 'en',
    format: 'YYYY-MM-DD'
});

window.addEventListener("keydown", function(e) {
    // space and arrow keys
    if ([32, 37, 38, 39, 40].indexOf(e.keyCode) > -1) {
        e.preventDefault();
    }
}, false);

$('.center').slick({
    centerMode: true,
    centerPadding: '200px',
    slidesToShow: 3,
    focusOnSelect: true,
    slidesToScroll: 1,
    arrows: false,
    infinite: false,
    speed: 100,
    vertical: true,
    verticalSwiping: true
});

fillColumns();