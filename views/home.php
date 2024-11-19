<?php
    // Instanciamos la clase de FiltrarDatos para filtrar los datos del GET
    $filtro = new FiltrarDatos();
    $datos_get = $filtro->Filtrar($_GET);
    

    print('
        <div
            id="idEnlacesPage"
            data-id-page = "' . isset($datos_get['r']) . '"
            data-filter-consulta = "' . (isset($datos_get['consulta']) ? $datos_get['consulta'] : '') . '"
            data-filter-valor = "' . (isset($datos_get['valor']) ? $datos_get['valor'] : '') . '"
        >
            <h2 class="text-center">'
                . APP_LOGO_HOME .
            '</h2>
        </div>

    ');

    // Instanciamos la clase de Modelo de Enlaces para consultar los datos
    $enlaces_controller = new EnlacesController();
 
    if (isset($datos_get['consulta']) && isset($datos_get['valor']) && !empty($datos_get['consulta']) && !empty($datos_get['valor'])) {
        $enlaces = $enlaces_controller->filterEnlaces($datos_get['consulta'], $datos_get['valor']);
    } else {
        $enlaces = $enlaces_controller->get();
    }

    if( empty($enlaces) ) {
        print( '
            <div class="container">
                <p class="item  error">No hay regisytos</p>
            </div>
        ');
    } else {
	    $template_enlaces = '
		    <div class="card card-smartgrid">
                <div class="card-header card-header-smartgrid bg-dark text-white text-center">
                    <h3 class="card-title">SOFTWARE</h3>
                </div>
                <div 
                    class="card-body card-body-smartgrid p-3 d-flex justify-content-between align-items-center w-100"
                        id="idActionButtonsTopEnlaces"
                        name="actionButtonsTopEnlaces"
                >
                    <!-- Inputs Radio de la izquierda -->
                    <div class="form-group text-start style="justify-content: flex-left;"">
                        <form>
                            <div class="form-check">
                                <input 
                                    class="form-check-input" 
                                    type="radio" 
                                    name="searchOption" 
                                    id="searchCategoria" 
                                    value="categoria"
                                    data-sql-consult="SELECT * FROM vista_enlaces WHERE categoria LIKE ?"
                                    data-sql-select="SELECT DISTINCT categoria FROM vista_enlaces ORDER BY categoria"
                                    checked
                                >
                                <label class="form-check-label" for="searchCategoria">
                                    Búsqueda por la Categoria
                                </label>
                            </div>
                            <div class="form-check">
                                <input 
                                    class="form-check-input" 
                                    type="radio" 
                                    name="searchOption" 
                                    id="searchTipo" 
                                    value="tipo"
                                    data-sql-consult="SELECT * FROM vista_enlaces WHERE tipo LIKE ?"
                                    data-sql-select="SELECT DISTINCT tipo FROM vista_enlaces ORDER BY tipo"
                                >
                                <label class="form-check-label" for="searchTipo">
                                    Búsqueda por Lenguaje de Programación
                                </label>
                            </div>
                            <div class="form-check">
                                <input 
                                    class="form-check-input" 
                                    type="radio" 
                                    name="searchOption" 
                                    id="searchTitulo" 
                                    value="titulo"
                                    data-sql-consult="SELECT * FROM vista_enlaces WHERE titulo LIKE ?"
                                    data-sql-select="SELECT DISTINCT titulo FROM vista_enlaces ORDER BY titulo"
                                >
                                <label class="form-check-label" for="searchTitulo">
                                    Búsqueda por Palabras contenidas en el Título
                                </label>
                            </div>

                        </form>
                    </div>
                    <!-- Selectores de la parte central -->
                    <div class="form-group text-center" id="idDynamicDivSelectFilter" style="justify-content: flex-center;">
                    </div>
                    <!-- Botones FILTRAR y RESET de la parte derecha -->
                    <div class="close-button" id="idActionButtonsTopRight" style="justify-content: flex-end;">
                        <div class="button-container" id="idActionEnlacesButtonsFilterContainer">
                            <button class="btn-secondary-cancel" type="reset" value="NO" id="idResetEnlacesFilter">
                                Reset
                            </button>
                            <button 
                                id="idEjecutarEnlacesFilter"
                                class="btn-primary-confirm" 
                                type="submit" 
                                value="SI"
                                onclick="filterEnlaces(this)"
                            >
                                Filtrar
                            </button>
                            <input type="hidden" name="crud" value="filter">
                            <input type="hidden" name="r" value="enlaces-filter">
                            <input type="hidden" name="rowid" value="">
                        </div>
                    </div>
                </div>
                <div id="tablaContainer">
                <table class="table-smartgrid w-100 mt-3" style="hight: 100;">
					<thead class="bg-secondary">
						<tr>
        ';

        // LA CABECERA DE LA TABLA
        foreach ($enlaces[0] as $key_row => $row) {
            $template_enlaces .= '
                <th class="th-smartgrid text-center">
                    '. ucfirst($key_row). '
                </th>
            ';
        }
        $template_enlaces .= '
						</tr>
					</thead>
        ';

        // EL CUERPO DE LA TABLA
        foreach ($enlaces as $key_row => $row) { 
            $template_enlaces .= '
                <tr class="tr-smartgrid">';
                foreach ($row as $key_cell => $cell) {
                    if($key_cell == 'enlace') {
                        // Si es la columna "enlace", generamos un enlace o un botón
                        $template_enlaces .= '
                            <td class="td-smartgrid">
                                <a href="' . $cell . '" target="_blank" class="btn btn-sm btn-secondary">
                                    <i class="fa-solid fa-link"></i> Ver Pagina
                                </a>
                            </td>
                        ';
                    } else {
                        $template_enlaces .= '<td class="td-smartgrid">'. $cell. '</td>';
                    }
                }
                $template_enlaces .= '
                </tr>
            ';
        }

        $template_enlaces .= '
                </table>
                </div>
            </div>
        ';

        print($template_enlaces);
    }
?>