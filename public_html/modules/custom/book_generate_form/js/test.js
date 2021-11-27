(function ($) {
    $(document).ready(function () {
        $('#display-stroke-form-btn').click(function (event) {
            event.preventDefault()

            let str = getBookStroke()

            $('#form-result-input').val(str)
            $('#result-text').html(str)
        })

        $('#form-result-input').on('input', function (event) {
            $('#result-text').html(event.target.value)
        })
    })

    function getBookStroke() {
        let author = $('#form-book-author').val()
        let name = $('#form-book-name').val()
        let tomeNum = $('#form-book-tome-num').val()
        let tomeMax = $('#form-book-tome-max').val()
        let tomeName = $('#form-book-tome-name').val()
        let city = $('#form-book-city').val()
        let publish = $('#form-book-publish').val()
        let year = $('#form-book-year').val()
        let pages = $('#form-book-pages').val()


        let stroke = `<i>${author}</i> ${name}. ${city} : ${publish}, ${year}. ${pages} с.`

        return stroke
    }
})(jQuery)