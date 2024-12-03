import "./bootstrap";
import flatpickr from "flatpickr";
import "flatpickr/dist/flatpickr.min.css";

document.addEventListener("DOMContentLoaded", () => {
    flatpickr(".flatpickr-free", {
        dateFormat: "d/m/Y",
        disableMobile: true,
    });

    flatpickr(".flatpickr-nofuture", {
        dateFormat: "d/m/Y",
        disableMobile: true,
        maxDate: "today",
    });
});
