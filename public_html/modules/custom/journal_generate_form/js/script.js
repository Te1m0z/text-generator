(function ($) {
    $(document).ready(function () {

        $('#display-journal-stroke-form-btn').click(function (event) {
            event.preventDefault()

            let str = getjournalStroke()

            $('#form-journal-result-input').val(str)
            $('#result-journal-text').html(str)
        })

        $('#form-journal-result-input').on('input', function (event) {
            $('#result-journal-text').html(event.target.value)
        })
    })

    function getjournalStroke() {
        let author = $('#form-journal-author').val()
        let release = $('#form-journal-release').val()
        let name = $('#form-journal-name').val()
        let year = $('#form-journal-year').val()
        let tomeNum = $('#form-journal-tome-num').val()
        let volume = $('#form-journal-volume').val()
        let pagesFrom = $('#form-journal-pages-from').val()
        let pagesTo = $('#form-journal-pages-to').val()
        let other = $('#form-journal-other').val()
        let lang = $('#form-journal-lang').val()

        let isRU = (lang === 'ru') ? true : false

        let stroke = `${author}, "${release}", <i>${name}</i>, ${tomeNum}:${volume} (${year}), ${pagesFrom}-${pagesTo}. ${other}`

        // if (tomeNum && tomeMax) {
        //     stroke = `<i>${author}</i> ${name} : ${isRU ? 'в' : 'in'} ${tomeMax} ${isRU ? 'т' : 'v'}. ${isRU ? 'Т' : 'Vol'}. ${tomeNum}. ${city} : ${publish}, ${year}. ${pages} ${isRU ? 'с' : 'p'}.`
        // }

        // // если есть название тома
        // if (tomeNum && tomeMax && tomeName) {
        //     stroke = `<i>${author}</i> ${name} : ${isRU ? 'в' : 'in'} ${tomeMax} ${isRU ? 'т' : 'v'}. ${isRU ? 'Т' : 'Vol'}. ${tomeNum} : ${tomeName}. ${city} : ${publish}, ${year}. ${pages} ${isRU ? 'с' : 'p'}.`
        // }

        return stroke
    }
})(jQuery)