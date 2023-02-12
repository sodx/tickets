import {Splide} from "@splidejs/splide";
import {Video} from "@splidejs/splide-extension-video";

const splideContainer = document.querySelectorAll('.splide');
if (splideContainer.length > 0) {
    new Splide('.splide').mount({Video});
}
