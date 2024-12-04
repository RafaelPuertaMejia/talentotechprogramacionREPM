$(document).ready(function () {
    $('form').on('submit', function (e) {
        e.preventDefault();
        
        $.ajax({
            url: '../../controllers/DocenteController.php',
            type: 'POST',
            data: $(this).serialize(),
            success: function (response) {
                alert(response);
                $('#section-content').load('../../views/Docentes/videoProyecto.php');
            },
            error: function (xhr, status, error) {
                console.error(error);
            }
        });
    });
});