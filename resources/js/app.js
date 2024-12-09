import "./bootstrap";
import flatpickr from "flatpickr";
import "flatpickr/dist/flatpickr.min.css";

document.addEventListener("DOMContentLoaded", () => {
    flatpickr(".flatpickr-free", {
        locale: "id",
        dateFormat: "j F Y",
        disableMobile: true,
    });

    flatpickr(".flatpickr-nofuture", {
        locale: "id",
        dateFormat: "j F Y",
        disableMobile: true,
        maxDate: "today",
    });
});
