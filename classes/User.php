<?php
class User extends Base
{
    protected $name;
    protected $email;
    protected $full_name;
    protected $password;
    protected $roles;
    
    public function __construct()
    {
        parent::__construct('User');
    }
    
    public function signup($username, $password) {
        $bones = new Bones();
        $this->roles = array();
        $this->name = $username;
        $this->password = $password;
        //Use CouchAdmin calls to create new user
        try {
            $response = $bones->couchAdm->createUserExtended($this->full_name,$this->email,$this->name,$this->password);
        } catch (Exception $e) {
            $bones->set('error', 'ERROR: '.$e->getCode().' creating user, '.$e->getMessage());
            $bones->render('user/signup');
            exit;
        }
        
    }
}