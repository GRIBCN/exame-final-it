<?php
    abstract class Model_crud {
        private static $db_host = DB_HOST;
        private static $db_user = DB_USER;
        private static $db_pass = DB_PASS;
        private static $db_name = DB_NAME;
        private static $db_charset = DB_CHARSET;
        private $conn;

        // Protegerá el nombre de la tabla
        protected $table = '';

        // Protegerá el nombre de columna clave
        protected $column_id = '';

        // Establecer la conexión a la base de datos
        private function db_open() {
            $this->conn = new mysqli(
                self::$db_host,
                self::$db_user,
                self::$db_pass,
                self::$db_name
            );
            
            if ($this->conn->connect_error) {
                die('Error de conexión: ' . $this->conn->connect_error);
            }

            $this->conn->set_charset(self::$db_charset);
        }
        
        // Cerrar la conexión a la base de datos
        private function db_close() {
            $this->conn->close();
        }

        // Método general para ejecutar consultas preparadas con varios tipos de datos
        // Se utiliza para consultas de modificación de datos como INSERT, UPDATE o DELETE
        protected function execute_prepared_query($query, $types, $params) {
            $this->db_open();

            // Preparar la consulta
            // El método prepare() de MySQLi para preparar la consulta SQL.
            // Devuelve un objeto statement ($stmt).
            $stmt = $this->conn->prepare($query);

            // Verificar si la preparación de la consulta fue exitosa
            if ($stmt === false) {
                error_log("Error en la preparación de la consulta: ".$this->conn->error);
                $this->db_close();
                return false;
            }

            // Vincula los parámetros a la consulta preparada (según el tipo)
            // $types = 'ss' y $params = ['John Doe', 'john@example.com']
            // 's' para 'John Doe' (tipo string).
            // 's' para 'john@example.com' (tipo string).
            $stmt->bind_param($types,...$params);
            
            // Ejecutar la consulta
            $success = $stmt->execute();
            
            $stmt->close();
            $this->db_close();
            
            return $success;    // Retornar true o false para saber si la consulta fue exitosa
        }

        // Método para ejecutar consultas SELECT
        // Se utiliza específicamente para manejar consultas SELECT
        protected function execute_prepared_select($query, $types = '', $params = []) {
            $this->db_open();

            // Preparar la consulta
            $stmt = $this->conn->prepare($query);

            // Verificar si la preparación de la consulta fue exitosa
            if ($stmt === false) {
                error_log("Error en la preparación de la consulta: ".$this->conn->error);
                $this->db_close();
                return false;
            }

            // Vincula los parámetros a la consulta preparada (según el tipo)
            if ($types && $params) {
                $stmt->bind_param($types,...$params);
            }
            
            
            // Crear una representación legible de la consulta para debuggeo
            $debug_query = $query;
            foreach ($params as $param) {
                $param = is_string($param) ? "'$param'" : $param;
                $debug_query = preg_replace('/\?/', $param, $debug_query, 1);
            }

/*
            if (isset($_POST['r']) && ($_POST['r'] == 'tienda-filter' && isset($_POST['crud']))) {
                echo json_encode([
                    'success' => false,
                    'msg01' => 'El nombre de la canción',
                    'msg02' => $debug_query,  // Aquí añadimos la consulta debugeada
                    'msg03' => ' ya existe y no se puede utilizar! ',
                    'msg04' => $params,
                    'msg05' => ' ya existe y no se puede utilizar! '
                ]);
                exit();
            }
*/

            // Ejecutar la consulta
            $stmt->execute();
            
            // Obtiene el resultado de la consulta
            // Se usa en consultas SELECT para obtener los datos devueltos por la consulta.
            $result = $stmt->get_result();
            
            // Almacena los resultados en un array
            // Se transforma en un array asociativo utilizando
            // fetch_all(MYSQLI_ASSOC) - Convierte todos los resultados de la consulta en un array asociativo, donde cada fila es un array de claves y valores
            $data = $result->fetch_all(MYSQLI_ASSOC);

            $stmt->close();
            $this->db_close();
            
            return $data;
        }

        // Métodos CRUD Generalizados
        // INSERT - inserta una nueva fila en la base de datos.
        public function insert($data = array()) {
            // convierte las claves (nombres de las columnas) del array $data en una cadena separada por comas.
            // $data = ['name' => 'John', 'email' => 'john@example.com'];
            // $columns = 'name, email'
            $columns = implode(", ", array_keys($data));

            // Genera una cadena de marcadores de posición (?) para los valores que se van a insertar, separados por comas
            // $values = ['John', 'john@example.com'];
            // $placeholders = '?,?'
            $placeholders = implode(", ", array_fill(0, count($data), "?"));
           
            // Extrae los valores del array $data
            // $values = ['John', 'john@example.com']
            $values = array_values($data);

            // Identificar los tipos de datos de los valores
            // $types = 'ss' (para dos strings)
            $types = $this->get_types_from_values($values);

            // Crea la consulta SQL de inserción, reemplazando $columns y $placeholders con los nombres de las columnas y los marcadores de posición.
            // $query = "INSERT INTO users (name, email) VALUES (?,?)"
            $query = "INSERT INTO $this->table ($columns) VALUES ($placeholders)";

            // Ejecuta la consulta preparada utilizando la función execute_prepared_query
            return $this->execute_prepared_query($query, $types, $values);
        }

        // UPDATE - actualiza una fila existente en la base de datos.
        public function update($id, $data = array()) {
            // Genera la parte de la consulta SQL que actualiza las columnas,
            // enlazando cada columna con un marcador de posición ( = ?).
            // $data = ['name' => 'John', 'email' => 'john@example.com'];
            // $columns = 'name = ?, email = ?'
            $columns = implode(" = ?, ", array_keys($data)) . " = ?";       // 'columna1 = ?, columna2 = ?'

            // Extrae los valores del array $data, es decir, los datos que queremos actualizar.
            // $values = ['John', 'john@example.com']
            $values = array_values($data);                                  // Los valores reales para el update

            // Identifica los tipos de datos de los valores y añade 'i' porque el $id es un entero.
            // $types = 'ssi' (dos strings y un entero)
            $types = $this->get_types_from_values($values) . 'i';           // 'ss', 'ii', etc.

            // Añade el $id al final del array de valores.
            // Esto es necesario para ejecutar la cláusula WHERE que identifica qué fila actualizar.
            // $values = ['John', 'john@example.com', 5] (5 es el ID del registro)
            $values[] = $id;                                                // El valor del id para el update
            
            // Crea la consulta SQL de actualización.
            // UPDATE users SET name = ?, email = ? WHERE id = ?
            $query = "UPDATE $this->table SET $columns WHERE $this->column_id = ?";

            // Ejecuta la consulta preparada utilizando la función execute_prepared_query
            return $this->execute_prepared_query($query, $types, $values);
        }

        // DELETE - elimina una fila de la base de datos.
        public function delete($id, $types = 'i') {
            // Genera la consulta SQL para eliminar una fila según el ID.
            // DELETE FROM users WHERE id = ?
            $query = "DELETE FROM $this->table WHERE $this->column_id = ?";

            // Ejecuta la consulta preparada. El tipo es 'i' porque el ID es un entero, y el valor es el $id
            return $this->execute_prepared_query($query, $types, [$id]);       // 'i' para el ID (entero)
        }
        
        // SELECT - selecciona (obtiene) una fila o varias filas de la base de datos.
        public function get($id = null) {
            if ($id) {
                // Si se pasa un ID, selecciona una fila específica de la tabla.
                // Genera la consulta SQL para seleccionar una fila específica según el ID.
                // SELECT * FROM users WHERE id = ?
                $query = "SELECT * FROM $this->table WHERE $this->column_id = ?";

                // Ejecuta la consulta preparada. El tipo es 'i' porque el ID es un entero, y el valor es el $id
                return $this->execute_prepared_select($query, 'i', [$id]);       // 'i' para el ID (entero)
            } else {
                // Si no se pasa un ID, selecciona todas las filas de la tabla.
                // Genera la consulta SQL para seleccionar todas las filas.
                // SELECT * FROM users
                $query = "SELECT * FROM $this->table";

                // Ejecuta la consulta preparada sin pasar parámetros y devuelve todas las filas.
                return $this->execute_prepared_select($query);
            }
        }
        
        // Obtener los tipos de datos de los valores
        private function get_types_from_values($values) {
            $types = '';
            foreach ($values as $value) {
                if (is_int($value)) {
                    $types.= 'i';           // integer
                } elseif (is_float($value) || is_double($value)) {
                    $types.='d';            // decimal
                } elseif (is_string($value)) {
                    $types.='s';            // string
                } else {
                    $types .= 'b';          // boolean
                }
            }
            return $types;
        }
    }
?>