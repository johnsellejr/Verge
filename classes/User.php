<?php

class User extends Base
{
    protected $_id;
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
        
        //Set the DB Connection before calling CouchAdmin
        $bones->set_db($bones->config->db_server.":".$bones->config->db_port,$bones->config->db_database,["username"=>$bones->config->db_admin_user,"password"=>$bones->config->db_admin_password]);
        //Use CouchAdmin calls to create new user
        try {
            $response = $bones->couchAdm->createUserExtended($this->full_name,$this->email,$this->name,$this->password);
        } catch (Exception $e) {
            if($e->getCode() == "409") {
                $bones->set('error', 'A user with this name already exists.');
                $bones->render('user/signup');
        } else {
            $bones->error500($e);
            }
        }
    }
    
    public function login() {
        $bones = new Bones();
        try {
            $bones->set_db($bones->config->db_server.":".$bones->config->db_port,$bones->config->db_database,['username'=>$this->name,'password'=>$this->password,'cookie_auth'=>TRUE]);
            session_start();
            $_SESSION["username"] = $this->name;
            $_SESSION["cookie"] = $bones->couch->getSessionCookie();
            session_commit();
        }
        catch(Exception $e) {
            if($e->getCode() == "401") {
                $bones->set('error', ' Incorrect login credentials.');
                $bones->render('user/login');
                exit;
            } else {
                $bones->error500($e);
            }
        }
    }
    
    public static function logout() {
        //$bones = new Bones();
        //$bones->couch->login(null, null);
        session_start();
        session_unset();
        session_destroy();
    }
    
    public static function current_user() {
        session_start();
        return $_SESSION['username'];
        session_commit();
    }
    
    public static function is_authenticated() {
        if (self::current_user()) {
            return true;
        } else {
            return false;
        }
    }
    
    public static function get_by_username($username = null) {
        $bones = new Bones();
        $user = new User();
        
        //Set the DB Connection before calling CouchAdmin
        $bones->set_db($bones->config->db_server.":".$bones->config->db_port,$bones->config->db_database,["username"=>$bones->config->db_admin_user,"password"=>$bones->config->db_admin_password]);
        //Use CouchAdmin calls to retrieve user
        try {
            $response = $bones->couchAdm->getUser($username);
            $user->_id = $response->_id;
            $user->name = $response->name;
            $user->email = $response->email;
            $user->full_name = $response->full_name;
            
            return $user;
        } catch (Exception $e) {
            if($e->getCode() == "404") {
                $bones->error404();
            } else {
                $bones->error500($e);
            }
        }        
    }
    
    public function gravatar($size='50') {
        return
        'http://www.gravatar.com/avatar/?gravatar_id='
        .md5(strtolower($this->email)).'&size='.$size;
    }
}