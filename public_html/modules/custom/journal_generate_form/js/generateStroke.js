(function ($) {
  $(document).ready(function () {
    let str = getjournalStroke()
    $('#result-journal-text').html(str)
    $('#result-journal-text-hidden').val(str)
  })

  function getjournalStroke() {
    let count_authors = $('.author_set_item') // 2
    let authors_arr = []

    count_authors.each(function (idx) {
      idx++
      if (
        $(this).find('#author_first_name_' + idx).val() &&
        $(this).find('#author_last_name_' + idx).val()
      ) {
        let arr_item = [] // [имя, фамилия, отчество]
        arr_item.push($(this).find('#author_first_name_' + idx).val())
        arr_item.push($(this).find('#author_last_name_' + idx).val())
        arr_item.push($(this).find('#author_middle_name_' + idx).val())
        authors_arr.push(arr_item)
      }
    })

    let doi = $('#form-journal-doi').val()
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
    let lang = $('#form-journal-lang').val()
    let isEVersion = $('#electronic-fieldset-wrapper').length

    let author = ''

    $.each(authors_arr, function (idx, item) {
      author += item[1] + ' ' + item[0][0] + '.'
      if (item[2][0]) {
        author += ' ' + item[2][0] + '.'
      }
      idx++
      if (authors_arr.length > 1) {
        if (idx !== authors_arr.length) {
          author += ', '
        }
      }
    })

    let isRU = (lang === 'ru')

    let stroke = `<i>${author}</i> ${release} // ${name}. ${year}. ${isRU ? "T" : "Vol"}. ${tomeNum}, № ${volume}. ${isRU ? "С" : "P"}. ${pagesFrom}-${pagesTo}.`

    if (isEVersion) {
      stroke += ` URL: ${url} (${isRU ? 'дата обращения' : 'accessed'}: ${date}).`
    }

    if (doi) stroke += ` ${doi}`;

    if (other) stroke += ` ${other}`;

    return stroke
  }
})(jQuery)
