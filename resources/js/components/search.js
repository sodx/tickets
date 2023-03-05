import Cookies from "js-cookie";
import $ from "jquery";
import "jquery-ui/dist/jquery-ui.min";
import "jquery-ui/ui/widgets/autocomplete";

//import "jquery-ui/ui/widgets/menu";
//import "jquery-autocomplete";
var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');

const searchBtn = document.querySelectorAll('.search-btn');
if (searchBtn) {
    searchBtn.forEach((btn) => {
        btn.addEventListener('click', () => {
            document.querySelector('.search-form').classList.toggle('show');
            document.querySelector('.search-wrapper').classList.toggle('show');
            //set search wrapper active input field
            document.querySelector('.search-wrapper .search-form').focus();
        });
    });
}
$(document).ready(function () {
    $("#search").autocomplete({
        delay: 500,
        minLength: 3,
        source: function (request, response) {
            // Fetch data
            $.ajax({
                url: "/autocomplete",
                type: 'get',
                dataType: "json",
                data: {
                    _token: CSRF_TOKEN,
                    search: request.term
                },
                success: function (data) {
                    response(data);
                }
            });
        },
        select: function (event, ui) {
            // Set selection
            $('#search').val(ui.item.label); // display the selected text
            //$('#employeeid').val(ui.item.value); // save selected id to input
            return true;
        }
    }).data("ui-autocomplete")._renderItem = function ( ul, item, something ) {
        let content = {
            heading: '',
            eventsHtml: ''
        }

        if (typeof item.eventsInUserCity !== 'undefined') {
            content = eventsInUserCityContent(item.eventsInUserCity);
        } else if (typeof item.tours !== 'undefined') {
            content = toursContent(item.tours);
        } else if (typeof item.attractions !== 'undefined') {
            content = attractionsContent(item.attractions);
        } else if (typeof item.all_events !== 'undefined') {
            content = allEventsContent(item.all_events);
        }

        if (content.heading !== '' && content.eventsHtml !== '') {
            return $("<li>")
                .append("<div class='heading'>" + content.heading + "</div>" + content.eventsHtml)
                .appendTo(ul);
        } else {
            return $("").appendTo(ul);
        }
    };

    //on click enter redirect to search results page
    $('#search').keypress(function (e) {
        if (e.which == 13) {
            window.location.href = '/search/' + $('#search').val();
        }
    });

});

const eventsInUserCityContent = ( events ) => {
    const heading = 'Upcoming events in ' + Cookies.get('user_location');
    let eventsHtml = '';
    for (const event in events) {
        eventsHtml += '<div class="event">' +
            '<a href="' + events[event].url + '"><img class="image" src="' + events[event].image + '" alt="">' + events[event].name + '</a>' +
            '</div>';
    }
    return {heading, eventsHtml}
}

const toursContent = ( tours ) => {
    const heading = 'Tours';
    let eventsHtml = '';
    for (const tour in tours) {
        eventsHtml += '<div class="event">' +
            '<a href="' + tours[tour].url + '">' + tours[tour].name + '</a>' +
            '</div>';
    }
    return {heading, eventsHtml}
}

const attractionsContent = ( attractions ) => {
    const heading = 'Attractions';
    let eventsHtml = '';
    for (const attraction in attractions) {
        eventsHtml += '<div class="event"><a href="' + attractions[attraction].url + '"><img class="image" src="' + attractions[attraction].image + '" alt="">' + attractions[attraction].name + '</a></div>';
    }
    return {heading, eventsHtml}
}

const allEventsContent = ( events ) => {
    const heading = 'All events';
    let eventsHtml = '';
    console.log(events);
    for (const event in events) {
        eventsHtml += '<div class="event"><a href="' + events[event] + '">' + events[event] + '</a></div>';
    }
    return {heading, eventsHtml}
}
