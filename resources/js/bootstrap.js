import _ from 'lodash';
window._ = _;

import $ from 'jquery';
window.$ = window.jQuery = $;

import "bootstrap";
import "jquery.nicescroll";

// These packages are required by the template, please consult the documentation of each package for more information
// import "summernote";
// import "owl.carousel";
// import "popper.js";
// import "tooltip.js";
// import "moment";
// import "summernote";
// import "chocolat";
// import "chart.js";
// import "simpleweather";
// import "prismjs";
// import "dropzone";
// import "bootstrap-social";
// import "cleave.js";
// import "bootstrap-daterangepicker";
// import "bootstrap-colorpicker";
// import "bootstrap-timepicker";
// import "bootstrap-tagsinput";
// import "select2";
// import "selectric";
import CodeMirror from "codemirror";
window.CodeMirror = CodeMirror
// import "fullcalendar";
// import "datatables";
// import "sweetalert";
// import "izitoast";
// import "gmaps";

// import "flag-icon-css";
// import "weathericons";
// import "jquery-ui-dist";
// import "ionicons201";

// import "jqvmap";
// import "jquery-sparkline";

/**
 * We'll load the axios HTTP library which allows us to easily issue requests
 * to our Laravel back-end. This library automatically handles sending the
 * CSRF token as a header based on the value of the "XSRF" token cookie.
 */

import axios from 'axios';
window.axios = axios;

window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';

/**
 * Echo exposes an expressive API for subscribing to channels and listening
 * for events that are broadcast by Laravel. Echo and event broadcasting
 * allows your team to easily build robust real-time web applications.
 */

import Echo from 'laravel-echo';

import Pusher from 'pusher-js';
window.Pusher = Pusher;

window.Echo = new Echo({
    broadcaster: `${window.echoConfig.broadcaster}`,
    key: `${window.echoConfig.key}`,
    wsHost: `${window.echoConfig.host}`,
    wsPort: `${window.echoConfig.port}`,
    wssPort: `${window.echoConfig.port}`,
    forceTLS: window.echoConfig.forceTLS,
    enabledTransports: ['ws', 'wss'],
});