var colIndex = 0;

var initSlick = function(selector) {
    $(selector).slick({
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
}

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

function fillColumn(locationId, startDate, endDate, index, done) {
    var url = "http://tripvago.ga/kartoffel/api/search/hotel-collection?path=" + locationId + "&start_date=" + startDate + "&end_date=" + endDate

    var source = $("#tile-template").html();
    var template = Handlebars.compile(source);

    var $tmp = $('<div style="width: 230px; height: 230px;"></div>');
    $.getJSON(url, function function_name(data) {

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

            var html = template(info);

            $tmp.append(html);
        });
        $('.hotel-collection-result .slick-wrapper .slick').eq(index)
            .removeAttr('class')
            .addClass('center slick col-md-12 slick-' + index)
            .html($tmp.html());

        console.log($('.hotel-collection-result .slick-wrapper .slick').eq(index).html());
        done();
    });
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

var fillInital = function() {
    var ROWS = 3;
    var i = 0;
    var source = $('#tile-template-empty').html();
    var emptyTile = Handlebars.compile(source);
    var $item = emptyTile();

    for(i = 0; i < ROWS; i++) {
        $('.hotel-collection-result .slick-wrapper')
            .append('<div class="slick center col-md-12 slick-'+i+'" style="width: 230px; height: 230px;">' + $item + '</div>');
    }
    $('.hotel-collection-result .tile:last').removeClass('is-active');
};

fillInital();

initSlick('.center');