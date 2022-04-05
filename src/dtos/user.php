<?php
class User{
    private $id;
    private $email;
    private $pwd;
    private $role;
    private $states;

    function __construct(int $id, string $email, string $pwd, string $role, array $states){
        $this->id = $id;
        $this->email = $email;
        $this->pws = $pwd;
        $this->role = $role;
        $this->states = array($states);
    }

    public function get_id(): int{
        return $this->id;
    }

    public function get_email(): string{
        return $this->email;
    }

    public function set_email(string $new_email, string $pwd_confirm): bool{
        $trm_new_email = trim($new_email);
        
        if($this->pwd == $pwd_confirm && !empty($trm_new_email)){
            $this->email = $trm_new_email;

            return true;
        }

        return false;
    }

    // Check if the password is correct
    public function check_pwd(string $pwd_confirm): bool{
        return ($this->pwd == $pwd_confirm);
    }

    public function set_pwd(string $new_pwd, string $old_pwd): bool{
        if($this->pwd == $old_pwd){
            $this->pwd = $new_pwd;

            return true;
        }

        return false;
    }

    public function get_role(): string{
        return $this->role;
    }

    public function set_role(string $new_role, string $pwd_confirm): bool{
        $trm_new_role = trim($new_role);

        if($this->pwd == $pwd_confirm && !empty($trm_new_role)){
            $this->role = $trm_new_role;

            return true;
        }

        return false;
    }

    public function get_states(): array{
        return $this->states;
    }

    public function set_states(array $new_states, string $pwd_confirm): bool{
        $trm_new_states = array_map(function($item){ return trim($item); }, array($new_states));

        if($this->pwd == $pwd_confirm && !empty($new_states)){
            $this->states = $trm_new_states;

            return true;
        }

        return false;
    }
}
?>