<?php

require_once("manager.php");
require_once("src/entities/user.php");
require_once("src/entities/region.php");

class UserRepo extends DbManager
{

    function parse_fetch(PDOStatement $statement): array
    {
        $list = array();
        // metadata of the query result
        $metadata = new QueryMetadata($statement);

        // iterate over rows
        while ($row = $statement->fetch(PDO::FETCH_NUM)) {
            // build the temp user from the row
            var_dump($row);
            $user = User::build_from_row($metadata, $row);

            try {
                // build the temp region from the row
                $region = Region::build_from_row($metadata, $row);
            } catch (MissingColumnError $e) { 
                $region = null;
            }
            
            // check if the user is in the list
            if (array_key_exists($user->id, $list)){
                // get the user from the list
                $user = $list[$user->id];
            } else {
                // add the user in the list
                $list[$user->id] = $user;
            }

            // if the region is in the row add it to the user region list
            if ($region) {
                array_push($user->regions, $region);
            }
        }

        return $list;
    }

    // get a user by its id
    function get_by_id(int $id): ?User
    {
        $stmt = $this->get_connection()->prepare("
        SELECT * FROM user
        LEFT JOIN user_region ON user.id=user_region.user_id 
        LEFT JOIN region ON user_region.region_id=region.id
        WHERE id=:id;
        ");

        if ($stmt->execute(["id" => $id])) {
            $users = $this->parse_fetch($stmt);
            $this->get_first_element($users);
        }
        
        return null;
    }

    // get a user by its email
    function get_by_email(string $email): ?User
    {
        $stmt = $this->get_connection()->prepare("
        SELECT * FROM user
        LEFT JOIN user_region ON user.id=user_region.user_id 
        LEFT JOIN region ON user_region.region_id=region.id 
        WHERE email=:email;
        ");

        if ($stmt->execute(["email" => $email])) {
            $users = $this->parse_fetch($stmt);
            $this->get_first_element($users);
        }

        return null;
    }

    // get a user by its email and password
    function get_by_email_password(string $email, string $password): ?User
    {
        $stmt = $this->get_connection()->prepare("
        SELECT * FROM user  
        LEFT JOIN user_region ON user.id=user_region.user_id 
        LEFT JOIN region ON user_region.region_id=region.id
        WHERE email=:email AND password=:password;
        ");

        if ($stmt->execute(["email" => $email, "password" => $password])) {
            $users = $this->parse_fetch($stmt);
            return $this->get_first_element($users);
        }

        return null;
    }

    // get all users
    function get_all(): ?array
    {
        $stmt = $this->get_connection()->prepare("
        SELECT * FROM user
        LEFT JOIN user_region ON user.id=user_region.user_id 
        LEFT JOIN region ON user_region.region_id=region.id;
        ");

        if ($stmt->execute()) {
            $users = $this->parse_fetch($stmt);
            return $users;
        }

        return null;
    }

    function count(): int
    {
        $stmt = $this->get_connection()->prepare("
        SELECT COUNT(id) FROM user;
        ");
        if ($stmt->execute()) {
            return $stmt->fetchColumn(0);
        }

        return 0;
    }
}

?>