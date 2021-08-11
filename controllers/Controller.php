<?php
    //Initial controller
    class Controller extends RESTBlaze {
        public function init() {
            $this->view("main");
            $this->DB->query("SELECT * FROM users", ['sddfsfd']);
        }
    }
?>