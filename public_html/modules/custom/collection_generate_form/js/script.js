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
        let doi = $('#form-journal-doi').val()
        let author = $('#form-journal-author').val()
        let release = $('#form-journal-release').val()
        let name = $('#form-journal-name').val()
        let year = $('#form-journal-year').val()
        let tomeNum = $('#form-journal-tome-num').val()
        let volume = $('#form-journal-volume').val()
        let pagesFrom = $('#form-journal-pages-from').val()
        let pagesTo = $('#form-journal-pages-to').val()
        let other = $('#form-journal-other').val()
        let url = $('#form-journal-url').val()
        let date = $('#form-journal-date').val()
        // let lang = $('#form-journal-lang').val()    
        // let material = $('#form-journal-material').val()

        let isRU = (lang === 'ru')
        let isEVersion = (material === 'electronic')

        let stroke = `<i>${author}</i> ${release} // ${name}. ${year}. ${isRU ? "T" : "Vol"}. ${tomeNum}, № ${volume}. ${isRU ? "С" : "P"}. ${pagesFrom}-${pagesTo}.`

        if (isEVersion) {
			stroke += ` URL: ${url} (${isRU ? 'дата обращения' : 'accessed'}: ${date}).`
		}

        if (doi) stroke += ` ${doi}`;

        if (other) stroke += ` ${other}`;

        return stroke
    }
})(jQuery)