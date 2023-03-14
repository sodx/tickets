import Cookies from "js-cookie";

const addToFavorite = document.querySelectorAll('.add_to_favorites');
const navFavoritesCount = document.querySelector('.nav-favorites .count');
let favoritesCountValue = parseInt(navFavoritesCount.innerText);

addToFavorite.forEach(function (btn) {
    btn.addEventListener('click', function (event) {
        event.preventDefault();
        event.stopPropagation();
        const id = btn.getAttribute('data-id');
        let favoritesCookies = Cookies.get('favorites');
        Cookies.remove('favorites');
        if (typeof favoritesCookies !== 'undefined') {
            favoritesCookies = JSON.parse(favoritesCookies);
            if (favoritesCookies.indexOf(id) > -1) {
                favoritesCookies.splice(favoritesCookies.indexOf(id), 1);
                favoritesCountValue--;
            } else {
                favoritesCookies.push(id);
                favoritesCountValue++;
            }
        } else {
            favoritesCookies = [id];
            favoritesCountValue++;
        }

        Cookies.set('favorites', JSON.stringify(favoritesCookies), { expires: 365 });
        event.target.classList.toggle("active");
        navFavoritesCount.innerText = favoritesCountValue;
    });
});
