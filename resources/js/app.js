import "./bootstrap";
import "bootstrap-icons/font/bootstrap-icons.css";
import "@fortawesome/fontawesome-free/css/all.min.css";
import flatpickr from "flatpickr";
import "flatpickr/dist/flatpickr.min.css";
import monthSelectPlugin from "flatpickr/dist/plugins/monthSelect";
import "flatpickr/dist/plugins/monthSelect/style.css";
import Swal from "sweetalert2";
import "sweetalert2/dist/sweetalert2.min.css";
import "animate.css/animate.min.css";

tailwind.config = {
    theme: {
        extend: {
            colors: {
                "hijau-utama": "#2E8B57",
                "hijau-gelap": "#1F5F3F",
                "hijau-terang": "#E8F5E9",
                "hijau-pudar": "#C8E6C9",
            },
            fontFamily: {
                poppins: ["Poppins", "sans-serif"],
            },
        },
    },
};
