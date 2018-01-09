require('moment');
require('../dist/bootstrap/bootstrap-datepicker/js/bootstrap-datepicker.min');

$(document).ready(function() {
    $("#form_term").datepicker({format: "yyyy-mm-dd", startView: 1});
});
