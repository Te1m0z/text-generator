(function ($) {
    console.log($)

})(jQuery);
// document.addEventListener('DOMContentLoaded', () => {

//     document.getElementById('book-generate-form').onsubmit = e => e.preventDefault()

//     const bookNameInput = document.getElementById('form-book')
//     const resultText = document.getElementById('result-text')
//     const resultInput = document.getElementById('form-result-input')

//     document.getElementById('submit-form-btn').onclick = () => {
//         resultInput.value = bookNameInput.value
//         resultText.innerHTML = bookNameInput.value

//         console.log('btn clicked', bookNameInput.value)
//     }

//     resultInput.oninput = e => {
//         resultText.innerHTML = e.target.value
//     }
// })