(function ($) {
  $(document).ready(function () {
    let str = getBookStroke()
    $('#result-book-text').html(str)
    $('#result-book-text-hidden').val(str)
  })

  function getBookStroke() {
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

    let doi = $('#form-book-doi').val()
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
          console.log(idx, authors_arr.length, 'добавлена , ')
        }
      }
    })

    let isRU = (lang === 'ru')

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
