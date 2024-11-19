<?php
    class ViewController {
        private static $view_path = './views/';

        public function load_view($view) {
            if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest') {
                require_once(self::$view_path . $view . '.php');
                exit();
            }
            require_once(self::$view_path . 'header.php');
            require_once(self::$view_path . $view . '.php');
            require_once(self::$view_path . 'footer.php');
        }
    }
?>