<?php
    class connection {
        public $host = "localhost";
        public $user = "root";
        public $password = '';
        public $db_name = "ahorrando";
        public $port = '3306';

        public function conectar() {
            $connection = mysqli_connect(
            $this ->host,
            $this->user,
            $this->password,
            $this->db_name,
            $this->port

        );
        if(!$connection){
            throw new Exception("Error de Conexion: " . mysqli_connect_error());
        }
        return ($connection);
        }
 
    }
