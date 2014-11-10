<?php

namespace Model;
use Fuel\Core\Log;
use Exception;
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
        try 
        {
            $user   = User::forge($data);
            // Check Existing Account
            $entry  = User::find('all', array(
                'where' => array(
                    array('email', '=', $data['email']),
                    array('username', '=', $data['username'])
                )
            ));
            if (count($entry) == 0) 
            {
                $user->save();
                // get id of user inserted currently
                $user_id = $user->id;
           		$user_info			= $user->get_user_info($user_id);
           		$result['data']		= $user_info['data'];
                $result['status']   = 200;
                $result['text']     = 'Register Successfully';
            } 
            else 
            {
                $result['status']   = 402;
                $result['text']     = 'Existing Account';
                $result['data']     = null;
                Log::warning('Register user failed because there is an existed account');
            }
            return $result;
        } 
        catch (Exception $ex) {
            Log::error($ex->getMessage());
        }
    }

    /**
     * Get detail information of a specific user in user table
     * 
     * @param int $user_id ID of user
     * @return mixed[] Body of message which API response will return
     */
    public function get_user_info($user_id) 
    {
        try {
            $user   = User::forge();
            $entry  = User::find('all', array(
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
                Log::error('Get information of user failed. Because ID of user was not found in user table');
                die(json_encode($arr_msg));
            } 
            else 
            {
                $result['status']   = 200;
                $result['text']     = 'Get user information successfully';
                foreach ($entry as $item) 
                {
                    $result['data'] = $item;
                }
            }
            return $result;
        } 
        catch (Exception $ex) 
        {
            Log::error($ex->getMessage());
        }
    }
    
    /**
     * Update data in user table
     * 
     * @param int $user_id ID of user
     * @param mixed[] $data Contain content of fiels user edited
     */
    public function update_user($user_id, $data) 
    {
        try 
        {
            $entry  = User::find($user_id);
            $entry->set($data);
            $entry->save();
        } 
        catch (Exception $ex) 
        {
            Log::error($ex->getMessage());
        }
    }
    
    /**
     * Delete token stored in user table when user log out
     * 
     * @param int $user_id ID of user logout
     */
    public function delete_token($user_id) 
    {
        try
        {
            $entry              = User::find($user_id);
            $entry->login_hash  = '';
            $entry->save();
        } 
        catch (Exception $ex) 
        {
            Log::error($ex->getMessage());
        }
    }
    
    public function get_token_user($user_id) {
        try
        {
            $entry = User::find($user_id);
            return $entry;
        } 
        catch (Exception $ex) 
        {
            Log::error($ex->getMessage());
        }
    }
    
    public function check_token_exist($token) {
        try 
        {
            $entry  = User::find('all', array(
                'where' => array(
                    array('login_hash', $token)
                )
            ));
            return $entry;
        } 
        catch (Exception $ex) 
        {
            Log::error($ex->getMessage());
        }
    }
    
    public function is_existed_pass($user_id, $curr_pass) {
    	try
    	{
    		$entry	= User::find('all', array(
    			'where' => array(
    				array('id', '=', $user_id),
    				array('password', '=', $curr_pass)
    			)	
    		));
    		return $entry;
    	}
    	catch(Exception $ex) {
    		Log::error($ex->getMessage());
    	}
    }
    public function update_pass($user_id, $new_pass) {
    	try
    	{
    		$user = User::find($user_id);
    		$user->password = $new_pass;
    		$user->save();
    	}
    	catch(Exception $ex) {
    		echo $ex->getMessage();
    	}
    }
}
