<?php
    class EnlacesController extends Model_crud {
        protected $table = 'vista_enlaces';              // Especifica la tabla a usar
        protected $column_id = 'rowid';                         // Especifica la columna clave a usar

        // Funciones específicas
        // Consultamos la lista de los valores únicos de la columna indicada
        public function getValueFields($field) {
            $query = "CALL enlaces_field_DISTINCT_START(?)";
            return $this->execute_prepared_select($query, 's', [$field]);
        }

        // Verifica si la filla EXISTE
        public function filterEnlaces($query, $valor) {

            return $this->execute_prepared_select($query, 's', ['%' . $valor . '%']);
        }

    }
?>