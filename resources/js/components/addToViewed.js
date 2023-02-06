import Cookies from "js-cookie";

const eventItem = document.querySelectorAll('.event-item');
if (eventItem.length > 0) {
    eventItem.forEach(function (item) {
        const id = item.getAttribute('data-id');
        let viewedCookies = Cookies.get('recently_viewed');
        if (typeof viewedCookies !== 'undefined') {
            viewedCookies = JSON.parse(viewedCookies);
            if (viewedCookies.indexOf(id) > -1) {
                viewedCookies.splice(viewedCookies.indexOf(id), 1);
            }
            if (viewedCookies.length > 3) {
                viewedCookies.shift();
            }
            viewedCookies.push(id);
        } else {
            viewedCookies = [id];
        }
        Cookies.set('recently_viewed', JSON.stringify(viewedCookies), { expires: 365 });
    });
}
