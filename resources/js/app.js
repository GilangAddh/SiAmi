import "./bootstrap";
import flatpickr from "flatpickr";
import "flatpickr/dist/flatpickr.min.css";
import Swal from "sweetalert2";

function initializeFlatpickr() {
    flatpickr(".flatpickr-free", {
        dateFormat: "j F Y",
        disableMobile: true,
    });

    flatpickr(".flatpickr-nofuture", {
        dateFormat: "j F Y",
        disableMobile: true,
        maxDate: "today",
    });
}

initializeFlatpickr();

const modal = document.querySelector("dialog.modal");
if (modal) {
    const observer = new MutationObserver((mutationsList) => {
        mutationsList.forEach((mutation) => {
            if (
                mutation.type === "attributes" &&
                mutation.attributeName === "open"
            ) {
                if (modal.open) {
                    initializeFlatpickr();
                }
            }
        });
    });

    observer.observe(modal, { attributes: true });
}

const SwalGlobal = Swal.mixin({
    showConfirmButton: false,
    timer: 2000,
    timerProgressBar: true,
});

window.SwalGlobal = SwalGlobal;
