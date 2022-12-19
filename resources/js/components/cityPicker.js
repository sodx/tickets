import Cookies from "js-cookie";

const cityPickerLink = document.querySelectorAll('.city-picker__link');
if (cityPickerLink.length > 0) {
    cityPickerLink.forEach(function (link) {
        link.addEventListener('click', function (event) {
            event.preventDefault();
            Cookies.remove('user_location');
            Cookies.remove('user_location_type');

            Cookies.set('user_location', link.innerText, {expires: 365})
            Cookies.set('user_location_type', link.getAttribute('data-type'), {expires: 365})
            window.location.href = link.getAttribute('href');
        });
    });
}

const resetCityPicker = document.querySelector('.modal__reset');
if (resetCityPicker) {
    resetCityPicker.addEventListener('click', function (event) {
        event.preventDefault();
        Cookies.remove('user_location');
        Cookies.remove('user_location_type');
        window.location.href = '/';
    });
}

const cityPickerInput = document.querySelector('.city-picker__search-input');
if(cityPickerInput) {
    cityPickerInput.addEventListener('keyup', function (event) {
        const value = event.target.value;
        const cities = document.querySelectorAll('.city-picker__searchable');
        cities.forEach(function (city) {
            if (city.innerText.toLowerCase().indexOf(value.toLowerCase()) > -1) {
                city.style.display = 'block';
            } else {
                city.style.display = 'none';
            }
        });
    });
}
