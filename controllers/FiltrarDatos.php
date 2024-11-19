<?php
    class FiltrarDatos {
        public function Filtrar($datos) {
            // Sanitizamos los datos y evitamos algunos problemas de seguridad como Cross-Site Scripting (XSS)

            $passwordKeys = ['pass', 'contraseña']; // Array con nombres de claves que podrían contener contraseñas
    
            if (is_array($datos)) {
                foreach ($datos as $key => $value) {
                    if (in_array($key, $passwordKeys)) {
                        // No aplicamos htmlspecialchars ni otras limpiezas a las contraseñas
                        $datos[$key] = $this->ProcesarContrasena($value);
                    } else {
                        $datos[$key] = $this->LimpiarDatos($value);
                    }
                }
            } else {
                $datos = $this->LimpiarDatos($datos);
            }
            return $datos;
        }
    
        private function LimpiarDatos($datos) {
            $datos = trim($datos); // Eliminar espacios en blanco al inicio y final
            $datos = stripslashes($datos); // Eliminar barras invertidas
            $datos = htmlspecialchars($datos, ENT_QUOTES, 'UTF-8'); // Convertir caracteres especiales
            return $datos;
        }
    
        private function ProcesarContrasena($pass) {
            // Almacenar las contraseñas usando password_hash() en lugar de MD5
            return password_hash($pass, PASSWORD_DEFAULT);
        }
    }
?>