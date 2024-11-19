<?php

class Router {
    public $route;

    public function __construct() {
        $filtro = new FiltrarDatos();

        // Filtrar los datos del GET y POST para evitar ataques de inyeccion
        $datos_get = $filtro->Filtrar($_GET);
        $datos_post = $filtro->Filtrar($_POST);

        $this->route = isset($datos_get['r']) 
    				? $datos_get['r'] 
    			: (isset($datos_post['r']) && isset($datos_post['views']) 
        			? $datos_post['views'] 
        		: 'home');

        $controller = new ViewController();     // Carga la pagina

        switch ($this->route) {
            case 'home':
                $controller->load_view('home');
                break;
            case 'enlaces':
                // Generamos Selector
                if( $datos_post['r'] == 'enlaces-filter-select' )  {
                    $this->renderSelectFilter($datos_post['field']);  // Renderiza el modal de Categorías
                }

                // Filtramos la tabla
                if( $datos_post['r'] == 'enlaces-filter' )  {
                    $this->filtrarTabla($datos_post['consulta'], $datos_post['value'], $datos_post['field']);  // Filtramos la tabla
                }
                break;
            default:
                $controller->load_view('404');
                break;
        }
    }
    
    private function renderSelectFilter($field) {
        // Renderizar el <select> del formulario Filtrar
        // Implementamos proceder los datos de las llamadas AJAX
        $enlaces_controller = new EnlacesController();

        // ETIQUETA <SELECT>
        $result = [];
        $result = $enlaces_controller->getValueFields($field);

        if (empty($result)) {
            // La consulta se ejecutó correctamente pero devolvió 0 registros
            echo json_encode([
                'success' => true,
                'msg01' => 'La etiqueta SELECT de la columna ',
                'msg02' => $field,
                'msg03' => 'no tiene registros que coincidan con los filtros aplicados',
                'datos' => [] // Devuelve un array vacío
            ]);
        } else {
            if ($result) {
                // La consulta se ejecutó correctamente y devolvió registros
                echo json_encode([
                    'success' => true,
                    'msg01' => 'La etiqueta SELCT de la columna ',
                    'msg02' => $field,
                    'msg03' => 'ha sido generada correctamente',
                    'datos' => $result
                ]);
            } else {
                // Si ocurrió un error al ejecutar la consulta
                echo json_encode([
                    'success' => false,
                    'msg01' => 'Error al intentar consultar los valores únicos de la columna ',
                    'msg02' => $field,
                    'msg03' => '!'
                ]);
            }
        }
        exit();
    }

    private function filtrarTabla($consulta, $valor, $field) {
        // Filtramos la tabla
        // Instanciamos la clase de Modelo de Enlaces para consultar los datos
        $enlaces_controller = new EnlacesController();

        $enlaces = $enlaces_controller->filterEnlaces($consulta, $valor);
        $consulta_res = str_replace('?', '', $consulta) . ' %' . $valor . '%';

        if (empty($enlaces)) {
            // La consulta se ejecutó correctamente pero devolvió 0 registros
            echo json_encode([
                'success' => true,
                'msg01' => 'La etiqueta SELECT de la columna ',
                'msg02' => $field,
                'msg03' => 'no tiene registros que coincidan con los filtros aplicados',
                'datos' => [] // Devuelve un array vacío
            ]);
        } else {
            if ($enlaces) {
                // Ha consultado los valores únicos de la columna, devolvemos una respuesta JSON
                echo json_encode([
                    'success' => true,
                    'msg01' => 'La consulta ',
                    'msg02' => $consulta_res,
                    'msg03' => 'ha sido ejecutada correctamente',
                    'tablaHTML' => $this->generarTabla($enlaces)
                ]);
            } else {
                // Si ocurrió un error al intentar consultar los valores únicos de uan columna
                echo json_encode([
                    'success' => false,
                    'msg01' => 'Error al intentar ejecutar la consulta ',
                    'msg02' => $consulta_res,
                    'msg03' => '!'
                ]);
            }
        }
        exit();
    }

    function generarTabla($enlaces) {
        // Generar la tabla actualizada
        $template_enlaces = '';
        if (!empty($enlaces)) {
            // Generar cabecera de la tabla
            $template_enlaces .= '
                <table class="table-smartgrid w-100 mt-3" style="hight: 100;">
                    <thead class="bg-secondary">
                        <tr>';
            foreach ($enlaces[0] as $key_row => $row) {
                $template_enlaces .= '<th class="th-smartgrid text-center">' . ucfirst($key_row) . '</th>';
            }

            // Generar el cuerpo de la tabla
            foreach ($enlaces as $key_row => $row) {
                $template_enlaces .= '<tr class="tr-smartgrid">';
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
                        $template_enlaces .= '<td class="td-smartgrid">' . $cell . '</td>';
                    }
                }
                $template_enlaces .= '
                    </tr>';
            }
            $template_enlaces .= '</table>';
        } else {
            $template_enlaces .= '<p>No se encontraron resultados.</p>';
        }
        return $template_enlaces;
    }

}

?>