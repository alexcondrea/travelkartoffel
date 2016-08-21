$('.glyphicon-floppy-disk').on('click', function(event) {
    
});

$('.glyphicon-heart').on('click', function(event) {
    $(event.currentTarget).removeClass('black');
    $(event.currentTarget).addClass('red');
});

$('.loginmodal-submit').on('click', function (event) {
    setTimeout(function () {
        $('#loginModal').modal('hide');
        $('#btn-login').text('Potato');
    }, 1500);
});
