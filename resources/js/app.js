import "./bootstrap";
import flatpickr from "flatpickr";
import "flatpickr/dist/flatpickr.min.css";
import "flatpickr/dist/themes/material_blue.css";

document.addEventListener("DOMContentLoaded", () => {
    flatpickr(".flatpickr-free", {
        dateFormat: "j F Y",
        disableMobile: true,
        theme: "material_blue",
    });

    flatpickr(".flatpickr-nofuture", {
        dateFormat: "j F Y",
        disableMobile: true,
        maxDate: "today",
        theme: "material_blue",
    });
});
