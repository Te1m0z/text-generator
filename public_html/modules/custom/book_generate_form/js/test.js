(function ($) {
    $(document).ready(function () {

        $('#display-book-stroke-form-btn').click(function (event) {
            event.preventDefault()

            let str = getBookStroke()

            $('#form-book-result-input').val(str)
            $('#result-book-text').html(str)
        })

        $('#form-book-result-input').on('input', function (event) {
            $('#result-book-text').html(event.target.value)
        })
    })

    function getBookStroke() {
        let doi = $('#form-book-doi').val()
        let author = $('#form-book-author').val()
        let name = $('#form-book-name').val()
        let tomeNum = $('#form-book-tome-num').val()
        let tomeMax = $('#form-book-tome-max').val()
        let tomeName = $('#form-book-tome-name').val()
        let city = $('#form-book-city').val()
        let publish = $('#form-book-publish').val()
        let year = $('#form-book-year').val()
        let pages = $('#form-book-pages').val()
        let other = $('#form-book-other').val()
        let release = $('#form-book-release').val()
        let url = $('#form-book-url').val()
        let date = $('#form-book-date').val()
        let lang = $('#form-book-lang').val()
        let material = $('#form-book-material').val()

        let isRU = (lang === 'ru')
        let isEVersion = (material === 'electronic')

        let stroke = `<i>${author}</i> ${name}. ${city} : ${publish}, ${year}. ${pages} ${isRU ? 'с' : 'p'}.`

        // если есть том
        if (tomeNum && tomeMax) {
            stroke = `<i>${author}</i> ${name} : ${isRU ? 'в' : 'in'} ${tomeMax} ${isRU ? 'т' : 'v'}. ${isRU ? 'Т' : 'Vol'}. ${tomeNum}. ${city} : ${publish}, ${year}. ${pages} ${isRU ? 'с' : 'p'}.`
        }

        // если есть название тома
        if (tomeNum && tomeMax && tomeName) {
            stroke = `<i>${author}</i> ${name} : ${isRU ? 'в' : 'in'} ${tomeMax} ${isRU ? 'т' : 'v'}. ${isRU ? 'Т' : 'Vol'}. ${tomeNum} : ${tomeName}. ${city} : ${publish}, ${year}. ${pages} ${isRU ? 'с' : 'p'}.`
        }

        // если есть серия
        if (release) stroke += ` (${release}).`

        // если электронная версия
        if (isEVersion) {
            // let _url = url.current.value || '',
            //     _date = date.current.getAttribute("re-date") || '';
            stroke += ` URL: ${url} (${isRU ? 'дата обращения' : 'accessed'}: ${date}).`
        }

        // если есть doi
        if (doi) stroke += ` ${doi}`;

        // прочее
        if (other) stroke += ` ${other}`;

        return stroke
    }
})(jQuery)