<?php

namespace Controller\Admin;

use \Model;
use \Mysqli;
use \Model\User;

class Home extends \Controller {
    private $user;
    
    public function _before() {
        Model::setMysqli(
            new Mysqli('localhost', 'root', '12345', 'test')
        );
        
        $this->user = new User;
    }
    
    public function Action() {
        return [$this, 'login'];
        echo __METHOD__;
    }
    
    public function loginAction() {
        echo $this->user->get(2)->one;
    }
    
    public function editArticleAction() {
        echo '<a href="' . url('article', ['id' => 1]) . '">Click Here!</a>';
    }
}