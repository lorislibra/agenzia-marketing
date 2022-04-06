<?php

require_once "manager.php";
require_once "src/entities/user.php";

class UserRepo extends DbManager {

    public function get_by_id(int $id): User {
        $query = $this->get_connection()->prepare("SELECT * FROM user WHERE id=:id");

        if ($query->execute(["id" => $id])) {
            return User::from_array($query->fetch());
        }
    }

    public function get_by_email(string $email): User {
        $query = $this->get_connection()->prepare("SELECT * FROM user WHERE email=:email");

        if ($query->execute(["email" => $email])) {
            return User::from_array($query->fetch());
        }
    }

    public function get_by_email_password(string $email, string $password): User {
        $query = $this->get_connection()->prepare("SELECT * FROM user WHERE email=:email AND password=:password");

        if ($query->execute(["email" => $email, "password" => $password])){
            return User::from_array($query->fetch());
        }
    }
}

?>