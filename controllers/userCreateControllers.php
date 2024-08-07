
<?php
    require_once "databaseController.php";

    class User{
        public $user_id;
        public $username;
        public $email;
        public $password;
        public $birthdate;
        public $phone;

        protected $db;
        public function __construct(){
            $this->db = new Database();
        }

        function addUser(){
            $sql = "INSERT INTO users (username, email, phone, password, joinDate)
            VALUES (:username, :email, :phone, :password, CURDATE());";

            $query = $this->db->connect()->prepare($sql);
            $query->bindParam(':username', $this->username);
            $query->bindParam(':email', $this->email);
            $query->bindParam(':phone', $this->phone);
            $hashedPassword = password_hash($this->password, PASSWORD_DEFAULT);
            $query->bindParam(':password', $hashedPassword);

            if ($query->execute()) {
                return true;
            } else {
                return false;
            }
        }
    }

?>