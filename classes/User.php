<?php
class User extends Base
{
    protected $name;
    protected $email;
    protected $full_name;
    protected $salt;
    protected $password_sha;
    protected $roles;
    
    public function __construct()
    {
        parent::__construct('user');
    }
}