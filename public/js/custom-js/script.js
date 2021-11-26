
//Validação dos campos cadastro
	// Example starter JavaScript for disabling form submissions if there are invalid fields
    (function() {
        'use strict';
        window.addEventListener('load', function() {
            // Fetch all the forms we want to apply custom Bootstrap validation styles to
            let forms = document.getElementsByClassName('needs-validation');
            // Loop over them and prevent submission
            let validation = Array.prototype.filter.call(forms, function(form) {
                form.addEventListener('submit', function(event) {
                    if (form.checkValidity() === false) {
                        event.preventDefault();
                        event.stopPropagation();
                    }
                    form.classList.add('was-validated');
                }, false);
            });
        }, false);
    })();



//action button home
function mostrar(id) {
    if (document.getElementById(id).style.display == "block") {
        document.getElementById(id).style.display = "none";
        document.getElementById("form").action = "/search_home_full";

    } else {
        document.getElementById(id).style.display = "block";
        document.getElementById("form").action = "/search_home"
    }
} 


var estado = document.getElementById('estado');
$('#onoff1').on('change', function () {
    var el = this;
    estado.innerHTML = el.checked ? 'ligado' : 'desligado';

});

//fim check action

//--------print
function printDiv(divName) {
    var printContents = document.getElementById(divName).innerHTML;
    var originalContents = document.body.innerHTML;

    document.body.innerHTML = printContents;

    window.print();

    document.body.innerHTML = originalContents;


}
	