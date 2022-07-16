/*
 * Welcome to your app's main JavaScript file!
 *
 * We recommend including the built version of this JavaScript file
 * (and its CSS file) in your base layout (base.html.twig).
*/

const formShow = document.getElementById('formShow');
const formHidden = document.getElementById('formHidden');

formShow.addEventListener('click', () => {
    formHidden.classList.remove('hidden');
});

// start the Stimulus application
import './bootstrap';

// any CSS you import will output into a single css file (app.css in this case)
import './styles/app.scss';
import 'bootstrap-icons/font/bootstrap-icons.css'; 

import 'bootstrap';

const $ = require('jquery');
require('bootstrap');
$(document).ready(function() {
    $('[data-toggle="popover"]').popover();
});