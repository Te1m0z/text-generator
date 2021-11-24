(function ($) {
    $(document).ready(function () {
        $('#submit-form-btn').click(function () {
            $('#form-result-input').val($('#form-book').val())
            $('#result-text').html($('#form-book').val())
        })

        $('#form-result-input').on('input', function (event) {
            $('#result-text').html(event.target.value)
        })
    })
})(jQuery)