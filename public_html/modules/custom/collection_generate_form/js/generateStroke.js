(function ($) {
  $(document).ready(function () {
    let str = getcollectionStroke()
    $('#result-collection-text').html(str)
    $('#result-collection-text-hidden').val(str)
  })

  function getcollectionStroke() {
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

    let doi = $('#form-collection-doi').val()
    let release = $('#form-collection-release').val()
    let name = $('#form-collection-name').val()
    let tomeNum = $('#form-collection-tome-num').val()
    let tomeName = $('#form-collection-tome-name').val()
    let issue = $('#form-collection-issue').val()
    let place = $('#form-collection-place').val()
    let publish = $('#form-collection-publish').val()
    let pagesFrom = $('#form-collection-pages-from').val()
    let pagesTo = $('#form-collection-pages-to').val()
    let other = $('#form-collection-other').val()
    let url = $('#form-collection-url').val()
    let date = $('#form-collection-date').val()
    let lang = $('#form-collection-lang').val()
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

    let stroke = `<i>${author}</i> ` + release + ' ' + name + '. ' + publish + '. ' + (isRU ? "T" : "Vol") + '. ' + tomeNum + ', № ' + issue + '. ' +
			(isRU ? "С" : "P") + '. ' + pagesFrom + '-' + pagesTo + '.'

    if (isEVersion) {
      stroke += ` URL: ${url} (${isRU ? 'дата обращения' : 'accessed'}: ${date}).`
    }

    if (doi) stroke += ` ${doi}`;

    if (other) stroke += ` ${other}`;

    return stroke
  }
})(jQuery)
