<?php
namespace Model;

class User extends \Orm\Model {
    protected static $_properties = array('id', 'email', 'password', 'username', 'created_gmt', 'modified_gmt');
    protected static $_table_name = 'user';
    //protected static $_connection = 'trainning';
    
    public function register($data) {
        $user = new User($data);
        // Check Existing Account
        $entry = $user->find('all', array(
            'where' => array(
                array('email', $data['email']),
                array('username', $data['username'])
            )
        ));
        if(count($entry) == 0) {
            $user->save();
            $result['status'] = 200;
            $result['text'] = '';
            $result['data'] = NULL;
        }
        else {
            $result['status'] = 402;
            $result['text'] = 'Existing Account';
            $result['data'] = NULL;
        }
        return $result;
    }
    public function login($email, $pass) {
        $user = new User();
        $entry = $user->find('all', array(
            'where' => array(
                array('email', $email),
                array('password', $pass)
            )
        ));
        if(count($entry) == 1) {
            $result['status'] = 200;
            $result['text'] = '';
            foreach($entry as $item) {
                $result['data'] = $item;
            }
        }
        else {
            $result['status'] = 401;
            $result['text'] = 'Invalid Input';
            $result['data'] = NULL;
        }
        return $result;
    }
}


