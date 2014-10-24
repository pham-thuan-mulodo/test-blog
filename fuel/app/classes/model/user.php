<?php
namespace Model;

class User extends \Orm\Model {
    protected static $_properties = array('id', 'email', 'password', 'username', 'last_login', 'login_hash', 'group', 'profile_fields', 'created_gmt', 'modified_gmt');
    protected static $_table_name = 'user';
    //protected static $_connection = 'trainning';
    
    public static function register($data) {
        $user = User::forge($data);
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
    public function edit($user_id) {
        $user = User::forge();
        $entry = $user->find('all', array(
            'where' => array(
                array('id', $user_id)
            )
        ));
        if(count($entry) == 0) {
            $result['status'] = 404;
            $result['text'] = 'Not Found';
            $arr_msg = array(
                'message' => array(
                    'code' => $result['status'],
                    'text' => $result['text'],
                ),
                'data' => NULL
            );
            die(json_encode($arr_msg));
        }
        else {
            $result['status'] = 200;
            $result['text'] = '';
            foreach($entry as $item) {
                $result['data'] = $item;
            }
        }
        return $result;
    }
    public function updateUser($user_id, $data) {
        $entry = User::find($user_id);
        $entry->set($data);
        $entry->save();
    }
    public function deleteToken($user_id) {
        $entry = User::find($user_id);
        $entry->login_hash = '';
        $entry->save();
    }
}


