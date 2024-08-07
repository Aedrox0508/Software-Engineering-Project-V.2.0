
<?php
    class Database{

        public $db_name = 'blink_db';
        public $db_host = 'localhost';
        public $db_user = 'root';
        public $db_pass = '';

        protected $connection;
        public function connect(){
            try {
                $this->connection = new PDO("mysql:host=$this->db_host;dbname=$this->db_name", 
                                            $this->db_user, $this->db_pass);
                $this->connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            } catch (PDOException $e) {
                echo "Connection error: " . $e->getMessage();
            }
            return $this->connection;
        }


    }
?>
