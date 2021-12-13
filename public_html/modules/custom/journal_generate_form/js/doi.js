(function ($) {
  $(document).ready(function () {
    $('#form-journal-doi').on('input', function (event) {
      $('#form-journal-check-doi').attr('href', 'https://doi.org/' + event.target.value)
    })
  })
})(jQuery)
