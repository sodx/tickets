import datepicker from 'js-datepicker'

const datepickersFrom = document.querySelectorAll('.datefrom');
const datepickersTo = document.querySelectorAll('.datefrom');
if(datepickersFrom.length > 0 && datepickersTo.length > 0) {
    const picker = datepicker('.datefrom', {
        onSelect: (instance, date) => {
            const dateFormated = date.getFullYear() + '-' + (("0" + (date.getMonth() + 1)).slice(-2)) + '-' + ("0" + (date.getDate())).slice(-2);
            const url = new URL(window.location.href);
            url.searchParams.set('date', dateFormated);
            window.location.href = url.href;
        },
        formatter: (input, date, instance) => {
            input.value = date.getFullYear() + '-' + (("0" + (date.getMonth() + 1)).slice(-2)) + '-' + ("0" + (date.getDate())).slice(-2) // => '1/1/2099'
        }
    })
    const pickerto = datepicker('.dateto', {
        onSelect: (instance, date) => {
            const dateFormated = date.getFullYear() + '-' + (("0" + (date.getMonth() + 1)).slice(-2)) + '-' + ("0" + (date.getDate())).slice(-2);
            const url = new URL(window.location.href);
            url.searchParams.set('date_to', dateFormated);
            window.location.href = url.href;
        },
        formatter: (input, date, instance) => {
            input.value = date.getFullYear() + '-' + (("0" + (date.getMonth() + 1)).slice(-2)) + '-' + ("0" + (date.getDate())).slice(-2) // => '1/1/2099'
        }
    })
}
