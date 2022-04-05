<?php
class User{
    private $id;
    private $email;
    private $pwd;
    private $role;
    private $states;

    function __construct($id, $email, $pwd, $role, $states){
        $this->id = $id;
        $this->email = $email;
        $this->pws = $pwd;
        $this->role = $role;
        $this->states = array($states);
    }

    public function get_id(){
        return $this->id;
    }

    public function get_email(){
        return $this->email;
    }

    public function set_email($new_email, $pwd_confirm){
        $trm_new_email = trim($new_email);
        
        if($this->pwd == $pwd_confirm && !empty($trm_new_email)){
            $this->email = $trm_new_email;

            return true;
        }

        return false;
    }

    public function check_pwd($pwd_confirm){ // Check if the password is correct
        return ($this->pwd == $pwd_confirm);
    }

    public function set_pwd($new_pwd, $old_pwd){
        if($this->pwd == $old_pwd){
            $this->pwd = $new_pwd;

            return true;
        }

        return false;
    }

    public function get_role(){
        return $this->role;
    }

    public function set_role($new_role, $pwd_confirm){
        $trm_new_role = trim($new_role);

        if($this->pwd == $pwd_confirm && !empty($trm_new_role)){
            $this->role = $trm_new_role;

            return true;
        }

        return false;
    }

    public function get_states(){
        return $this->states;
    }

    public function set_states($new_states, $pwd_confirm){
        $trm_new_states = array_map(function($item){ return trim($item); }, array($new_states));

        if($this->pwd == $pwd_confirm && !empty($new_states)){
            $this->states = $trm_new_states;

            return true;
        }

        return false;
    }
}
?>