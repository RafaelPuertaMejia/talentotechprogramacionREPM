<?php

$routes = array(
    "../../config/" => "/config",
    "../../controllers/" => "/controllers",
    "../../img/" => "/img",
    "../../models/" => "/models",
    "../../public/" => "/public",
    "../../public/css/" => "/public/css",
    "../../public/inc/" => "/public/inc",
    "../../public/inc/footer.php" => "/public/inc/footer.php",
    "../../public/inc/header.php" => "/public/inc/header.php",
    "../../public/inc/nav.php" => "/public/inc/nav.php",
    "../../public/js/" => "/public/js",
    "../../public/views/" => "/public/views",
    "../../public/views/Alumnos/dashboardAlumnos.php" => "/public/views/Alumnos/dashboardAlumnos.php",
    "../../public/views/Usuarios/dashboardUsuarios.php" => "/public/views/Usuarios/dashboardUsuarios.php",
    "../../views/home.php" => "/views/home.php",
    "../../views/index.php" => "/views/index.php",
    "../../views/Docentes/dashboardDocentes.php" => "/views/Docentes/dashboardDocentes.php",
    "../../views/admin/dashboardAdmin.php" => "/views/admin/dashboardAdmin.php",
);

function getShortRoute($longRoute) {
    global $routes;

    if (isset($routes[$longRoute])) {
        return $routes[$longRoute];
    } else {
        return $longRoute;
    }
}

?>
