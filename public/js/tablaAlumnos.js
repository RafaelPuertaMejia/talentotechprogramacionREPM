  $(document).ready(function() {
        $('#tablaAlumnos').DataTable({
            "processing": true,
            "serverSide": true,
            "ajax": {
                "url": "../../models/getAlumnos.php", // Reemplaza "ruta/a/getAlumnos.php" con la ruta correcta
                "type": "POST"
            },
            "columns": [
                { "data": "dni_alumno" },
                { "data": "nombre_alumno" },
                { "data": "programa" },
                { "data": "email_alumno" },
                { "data": "celular_alumno" }
            ]
        });
    });



