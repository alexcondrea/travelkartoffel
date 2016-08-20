$('body').on('change', '.tile .input-wrapper input', function(event) {
  var $el = $(this);
  var $wrapper = $el.closest('.input-wrapper');

  if ($el.val() === '') {
    $wrapper.addClass('is-empty');
  } else {
    $wrapper.removeClass('is-empty');
  }
});

$('body').on('click', '.tile button', function(event) {
  var $el = $(this);
  var $wrapper = $el.closest('.flex-row');
  var $input = $wrapper.find('input[type="text"]');
  var nights = parseInt($input.val(), 10);

  if (isNaN(nights)) {
    nights = 0;
  }

  if ($el.data('role') === 'increase') {
    if (nights < 100) {
      nights++;
    }
  } else {
    if (nights > 1) {
      nights--;
    }
  }

  $input.val(nights);
});