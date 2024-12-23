import "./bootstrap";
import flatpickr from "flatpickr";
import "flatpickr/dist/flatpickr.min.css";
import Swal from "sweetalert2";

const flatpickrConfig = {
    free: {
        selector: ".flatpickr-free",
        options: {
            dateFormat: "j F Y",
            disableMobile: true,
        },
    },
    noFuture: {
        selector: ".flatpickr-nofuture",
        options: {
            dateFormat: "j F Y",
            disableMobile: true,
            maxDate: "today",
        },
    },
};

function initializeFlatpickr() {
    Object.values(flatpickrConfig).forEach(({ selector, options }) => {
        document.querySelectorAll(selector).forEach((element) => {
            if (!element._flatpickr) {
                flatpickr(element, options);
            }
        });
    });
}

function observeModal(modal) {
    const observer = new MutationObserver((mutations) => {
        mutations.forEach(({ type, attributeName }) => {
            if (
                type === "attributes" &&
                attributeName === "open" &&
                modal.open
            ) {
                initializeFlatpickr();
            }
        });
    });

    observer.observe(modal, { attributes: true });
    modal.addEventListener("close", () => observer.disconnect());
}

window.SwalGlobal = Swal.mixin({
    showConfirmButton: false,
    timer: 2000,
    timerProgressBar: true,
});

initializeFlatpickr();

const modal = document.querySelector("dialog.modal");
if (modal) observeModal(modal);

document.addEventListener(
    "livewire:navigated",
    () => {
        initializeFlatpickr();
    }
);
