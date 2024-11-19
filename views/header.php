<?php
    $template = '
        <!DOCTYPE html>
        <html lang="en">
        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <title>%s</title>
            <meta name="description" content="%s">
            <link rel="shortcut icon" type="image/png" href="./public/img/favicon.png">

            <!-- DataTables CSS -->
            <link href="https://cdn.datatables.net/2.0.8/css/dataTables.dataTables.min.css" rel="stylesheet" type="text/css"/>
            <script src="https://cdn.datatables.net/2.0.8/js/dataTables.min.js"></script>
            
            <!-- awesom -->
            <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" integrity="sha512-SnH5WK+bZxgPHs44uWIX+LLJAJ9/2PkPKZ5QiAj6Ta86w+fsb2TkcmfRyVX3pBnMFcV7oQPJkl9QevSCWr3W6A==" crossorigin="anonymous" referrerpolicy="no-referrer" />
            <link rel="stylesheet" href="https://code.jquery.com/ui/1.13.3/themes/base/jquery-ui.css">
            
            <!-- Bootstrap CSS -->
            <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous" />
            
            <!-- Google Fonts -->
            <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
            <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500&display=swap" rel="stylesheet">
            <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined" rel="stylesheet">

            <!-- Bootstrap Icons -->
            <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">

            <!-- jQuery y jQuery UI (JavaScript) -->
            <script src="https://code.jquery.com/jquery-3.7.1.js"></script>
            <script src="https://code.jquery.com/ui/1.13.3/jquery-ui.js"></script>
            
            <!-- CSS locales -->
            <link rel="stylesheet" href="./public/css/colors_backgrounds.css">
            <link rel="stylesheet" href="./public/css/buttons_SmartGrid.css">
            <link rel="stylesheet" href="./public/css/buttons_Confirmar.css">
            <link rel="stylesheet" href="./public/css/cards_SmartGrid.css">
            <link rel="stylesheet" href="./public/css/table_SmartGrid.css">
            <link rel="stylesheet" href="./public/css/tables.css">
            <link rel="stylesheet" href="./public/css/cards.css">
            <link rel="stylesheet" href="../public/css/alerts.css">
            <link rel="stylesheet" href="../public/css/select_filter.css">

        </head>
        <body>
            <header class="container center header">
                <div class="item i-b v-middle ph12 lg2 text-center">
                    <h1 class="logo">%s</h1>
                </div>
    ';

    printf($template,
        APP_TITLE,
        APP_DESCRIPTION,
        APP_LOGO
    );

    // Crear el menú
    // $menu = new Menu();
    
    // Renderizar el menú
    // $menu->render();

    print('
        </header>
        <main class="container center main text-center">
    ');

?>