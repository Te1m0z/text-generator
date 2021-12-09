(function ($) {
  $(document).ready(function () {
    $('#form-book-doi').on('input', function (event) {
      $('#form-book-check-doi').attr('href', 'https://doi.org/' + event.target.value)
    })
  })
})(jQuery)
