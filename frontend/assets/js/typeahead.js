var placesToVisit = new Bloodhound({
    datumTokenizer: function(datum) {
        return Bloodhound.tokenizers.whitespace(datum.value);
    },
    queryTokenizer: Bloodhound.tokenizers.whitespace,
    remote: {
        wildcard: '%QUERY',
        url: 'http://tripvago.ga/kartoffel/api/search/location?q=%QUERY',
        transform: function (locations) {
            window.typeaheadResult = locations.items[0];

            // Map the remote source JSON array to a JavaScript object array
            return $.map(locations.items, function (location) {
                return {
                    location: location['nameFormatted'],
                    country: location['pathName']
                };
            });
        }
    }
});

var typeaheadOptions = {
    hint: true,
    highlight: true,
    minLength: 1
};
var typeaheadDatasets = {
    display: 'location',
    templates: {
        suggestion: Handlebars.compile('<span></span>')
    },
    source: placesToVisit
};

ComponentManager.register(
    'typeahead',
    function(node) {
        $(node).typeahead(typeaheadOptions, typeaheadDatasets);
    },
    function(node) {
        $(node).typeahead('destroy');
    }
);

$('.typeahead').typeahead(typeaheadOptions, typeaheadDatasets);

ComponentManager.init();