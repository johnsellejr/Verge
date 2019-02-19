<?php
class Post extends Base
{
    protected $date_created;
    protected $content;
    protected $user;
    
    public function __construct() {
        parent::__construct('post');
    }
    
    public function create() {
        $bones = new Bones();
        session_start();
        //Set the DB Connection before calling CouchAdmin
        $bones->set_db($bones->config->db_server.":".$bones->config->db_port,$bones->config->db_database);
        $bones->couch->setSessionCookie($_SESSION["cookie"]);
        session_commit();
        /*
        $new_doc = new stdClass();
        $new_doc->_id = $bones->couch->getUuids(1)[0];
        $new_doc->date_created = date('r');
        $new_doc->user = User::current_user();
        $new_doc->content = $this->content;
        */
        
        $this->_id = $bones->couch->getUuids(1)[0];
        $this->date_created = date('r');
        $this->user = User::current_user();
        
        try {
            $response = $bones->couch->storeDoc(json_decode($this->to_json()));
        }
        catch(Exception $e) {
            $bones->error500($e);
        }
    }
    
    public function get_posts_by_user($username, $skip = 0, $limit = 10) {
        $bones = new Bones();
        $posts = array();
        session_start();
        //Set the DB Connection before calling CouchAdmin
        $bones->set_db($bones->config->db_server.":".$bones->config->db_port,$bones->config->db_database);
        $bones->couch->setSessionCookie($_SESSION["cookie"]);
        session_commit();
        
        try {
            $response = $bones->couch->key($username)->descending(true)->reduce(false)->skip($skip)->limit($limit)->getView('application', 'posts_by_user');
        } catch (Exception $e) {
            $bones->error500($e);
        }
        for ($i = 0; $i < sizeof($response->rows); $i++) {
            array_push($posts, $response->rows[$i]->value);
        }
        return $posts;
    }
    
    public function get_post_count_by_user($username) {
        $bones = new Bones();
        session_start();
        //Set the DB Connection before calling CouchAdmin
        $bones->set_db($bones->config->db_server.":".$bones->config->db_port,$bones->config->db_database);
        $bones->couch->setSessionCookie($_SESSION["cookie"]);
        session_commit();
        
        try {
            $response = $bones->couch->key('jsellejr')->reduce(true)->getView('application', 'posts_by_user');
        } catch (Exception $e) {
            $bones->error500($e);
        }
        if ($response->rows) {
            return $rows = $response->rows[0]->value;
        } else {
            return 0;
        }
    }
    
    public function delete() {
        $bones = new Bones();
        session_start();
        
        //Set the DB Connection before calling CouchAdmin
        $bones->set_db($bones->config->db_server.":".$bones->config->db_port,$bones->config->db_database);
        $bones->couch->setSessionCookie($_SESSION["cookie"]);
        session_commit();
        
        try {                
            $doc = $bones->couch->rev($this->_rev)->getDoc($this->_id);
        }
        catch(Exception $e) {
            $bones->error500($e);
        }
        
        try {
            $bones->couch->deleteDoc($doc);
        }
        catch(Exception $e) {
            $bones->error500($e);
        }
        
    }    
}