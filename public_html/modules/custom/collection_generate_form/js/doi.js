(function ($) {
  $(document).ready(function () {
    $('#form-collection-doi').on('input', function (event) {
      $('#form-collection-check-doi').attr('href', 'https://doi.org/' + event.target.value)
    })
  })
})(jQuery)
