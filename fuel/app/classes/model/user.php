<?php

namespace Model;

/**
 * User
 * 
 * @package \Orm\Model
 * @var User Class contain some method do some transaction with user table
 */
class User extends \Orm\Model 
{
   /**
    *
    * @var array array of columns in table connected
    */
    protected static $_properties   = array('id', 'email', 'password', 'username', 'last_login', 'login_hash', 'group', 'profile_fields', 'created_gmt', 'modified_gmt');
    
    /**
     *
     * @var string Name of table connected
     */
    protected static $_table_name   = 'user';
    
    /**
     * Insert data of user to user table
     * 
     * @param mixed[] $data Array of information of user inputted
     * @return mixed[] Body of message which API response will return
     */
    public static function register($data) 
    {
        $user   = User::forge($data);
        // Check Existing Account
        $entry  = $user->find('all', array(
            'where' => array(
                array('email', $data['email']),
                array('username', $data['username'])
            )
        ));
        if (count($entry) == 0) 
        {
            $user->save();
            $result['status']   = 200;
            $result['text']     = 'Register Successfully';
            $result['data']     = null;
        } 
        else 
        {
            $result['status']   = 402;
            $result['text']     = 'Existing Account';
            $result['data']     = null;
        }
        return $result;
    }

    /*public function login($email, $pass) {
        $user = new User();
        $entry = $user->find('all', array(
            'where' => array(
                array('email', $email),
                array('password', $pass)
            )
        ));
        if (count($entry) == 1) {
            $result['status'] = 200;
            $result['text'] = '';
            foreach ($entry as $item) {
                $result['data'] = $item;
            }
        } else {
            $result['status'] = 401;
            $result['text'] = 'Invalid Input';
            $result['data'] = NULL;
        }
        return $result;
    }*/

    /**
     * Get detail information of a specific user in user table
     * 
     * @param int $user_id ID of user
     * @return mixed[] Body of message which API response will return
     */
    public function edit($user_id) 
    {
        $user   = User::forge();
        $entry  = $user->find('all', array(
            'where' => array(
                array('id', $user_id)
            )
        ));
        if (count($entry) == 0) 
        {
            $result['status']   = 404;
            $result['text']     = 'Not Found';
            $arr_msg            = array(
                'message' => array(
                    'code' => $result['status'],
                    'text' => $result['text'],
                ),
                'data' => null
            );
            die(json_encode($arr_msg));
        } 
        else 
        {
            $result['status']   = 10302;
            $result['text']     = 'Updated Successfully';
            foreach ($entry as $item) 
            {
                $result['data'] = $item;
            }
        }
        return $result;
    }
    
    /**
     * Update data in user table
     * 
     * @param int $user_id ID of user
     * @param mixed[] $data Contain content of fiels user edited
     */
    public function update_user($user_id, $data) 
    {
        $entry  = User::find($user_id);
        $entry->set($data);
        $entry->save();
    }
    
    /**
     * Delete token stored in user table when user log out
     * 
     * @param int $user_id ID of user logout
     */
    public function delete_token($user_id) 
    {
        $entry              = User::find($user_id);
        $entry->login_hash  = '';
        $entry->save();
    }
}
