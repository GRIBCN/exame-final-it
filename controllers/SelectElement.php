<?php
    // La clase que genera dinámicamente un elemento HTML <select>
    class SelectElement {
        private $id;               // ID del elemento <select>
        private $name;             // Nombre del elemento <select>
        private $class;            // Clase CSS opcional del <select>
        private $options = [];     // Array que contendrá las opciones del <select>
        private $defaultOption;    // Opción por defecto (texto y valor)
        private $required;         // Booleano: si el campo es requerido o no
        private $multiple;         // Booleano: si el select permite selección múltiple
        private $attributes = [];  // Array para otros atributos personalizados
        
        // Constructor para inicializar el select con los parámetros más comunes
        public function __construct($id, $name, $class = '', $required = false, $multiple = false) {
            $this->id = $id;
            $this->name = $name;
            $this->class = $class;
            $this->required = $required;
            $this->multiple = $multiple;
        }

        // Método para establecer una opción por defecto, como "Seleccionar..."
        public function setDefaultOption($text, $value = '') {
            $this->defaultOption = ['text' => $text, 'value' => $value];
        }

        // Método para añadir una opción al select, con posibilidad de marcarla como seleccionada
        public function addOption($value, $text, $selected = false) {
            $this->options[] = [
                'value' => $value,          // Valor de la opción
                'text' => $text,            // Texto que mostrará la opción
                'selected' => $selected     // Booleano que indica si la opción está seleccionada
            ];
        }

        // Método para añadir atributos personalizados al <select> (como data-* o aria-* o onchange)
        public function addAttribute($key, $value) {
            $this->attributes[$key] = $value;
        }

        // Método para generar y devolver el código HTML del <select>
        public function render() {
            // Iniciar el código del <select> con los atributos básicos
            $select = "<select id='{$this->id}' name='{$this->name}' class='{$this->class}'";
            
            // Si el campo es obligatorio, añadimos el atributo "required"
            if ($this->required) {
                $select .= " required";
            }

            // Si se permite seleccionar múltiples opciones, añadimos el atributo "multiple"
            if ($this->multiple) {
                $select .= " multiple";
            }

            // Añadimos cualquier atributo personalizado que el usuario haya definido
            foreach ($this->attributes as $key => $value) {
                $select .= " {$key}='{$value}'";
            }

            // Cerramos la etiqueta de apertura del <select>
            $select .= ">";

            // Si se ha definido una opción por defecto, la añadimos primero
            if ($this->defaultOption) {
                $select .= "<option value='{$this->defaultOption['value']}'>{$this->defaultOption['text']}</option>";
            }

            // Añadimos todas las opciones que se han añadido previamente con el método addOption
            foreach ($this->options as $option) {
                // Si la opción está marcada como seleccionada, añadimos el atributo "selected"
                $selected = $option['selected'] ? 'selected' : '';
                $select .= "<option value='{$option['value']}' {$selected}>{$option['text']}</option>";
            }

            // Cerramos el select
            $select .= "</select>";

            // Devolvemos el HTML generado
            return $select;
        }
    }

?>