<?php

use Model\User;
use Fuel\Core\Session;
use Fuel\Core\Input;
use Fuel\Core\Controller_Rest;
use Fuel\Core\Security;
use Auth\Auth;
use Fuel\Core\Log;
use Fuel\Core\Cookie;

/**
 * Controller_User Controller class for user endpoint. This class contains all API methods related user.
 * About routing config. See routing.php 
 * 
 * @package Fuel\Core\Controller_Rest
 * @var Controller_User Class contains methods to resolve transactions of user
 */
class Controller_User extends Controller_Rest 
{
    /**
     *
     * @var string Set type of data of API Response Data
     */
    protected $format   = 'json';
   
    /**
     * Check user logged in or not
     * 
     * @return mixed[] Content of API response
     */
    public function before() 
    {
        parent::before();
        if (Auth::check()) 
        {
            echo '';
        } 
        else 
        {
            Log::error('You are not login yet.');
            return $this->response(array(
                'message'  => array(
                    'code' => 10301,
                    'text' => 'Please login'
                ),
                'data'     => null
            ));
        }
    }
    
    /**
     * Do login action for user 
     *
     * @link http://localhost/test-blog/user Link to post_login method
     * @return mixed[] Content of API response
     */
    public function post_login() 
    {
        // Check user login or not login
        if (Auth::check()) 
        {
            return $this->response(array(
                'message' => array(
                    'code' => 200,
                    'text' => ''
                ),
                'token'   => Session::get('token'),
                'data'    => null
            ));
        }

        $username   = Security::strip_tags(Security::xss_clean(Input::post('username')));
        $pass       = Security::strip_tags(Security::xss_clean(Input::post('password')));
        Log::debug('Username inputted now is "'.$username.'"');
        Log::debug('Password inputted now is "'.Auth::instance()->hash_password($pass).'"');
        if (Input::method() == 'POST') 
        {
            $token = '';
            if (Auth::login($username, $pass) || empty($token)) 
            {
                // create token
                $token  = Auth::get('login_hash');
                Session::set('token', $token);
                Session::set('user_id', Auth::get('id'));
                Session::set('username', Auth::get('username'));
                Cookie::set('token', $token, 60*60*24);
                Cookie::set('user_id', Auth::get('id'), 60*60*24);
                Cookie::set('username', Auth::get('username'), 60*60*24);
                return $this->response(array(
                    'message' => array(
                        'code' => 200,
                        'text' => ''
                    ),
                    'token'   => $token,
                    'data'    => null
                ));
            } 
            else 
            {
                Log::debug('Login failed because username or password is not valid');
                return $this->response(array(
                    'message' => array(
                        'code' => 401,
                        'text' => 'Invalid Input'
                    ),
                    'data'    => null
                ));
            }
        }
    }
    
    /**
     * Do logout action for user
     * 
     * @link http://localhost/test-blog/user_logout Link to post_logout method
     * @return mixed[] Content of API response
     */
    public function post_logout() 
    {
        $token      = Security::strip_tags(Security::xss_clean(Input::post('token')));
        $user = new User();
        $user_info = $user->check_token_exist($token);
        $token_db = '';
        foreach($user_info as $item)
        {
            $token_db = $item['login_hash'];
            $user_id = $item['id'];
        }

        if (!empty($token) && $token == $token_db) 
        {
            // Delete cookie
            Cookie::delete('token');
            // Delete session
            Session::delete('token');
            // Delete token in database
            $user->delete_token($user_id);
            Log::info('User'.$user_id.' logged out. Token of user'.$user_id.' was deleted in database and session');

            Auth::logout();
            return $this->response(array(
                'message' => array(
                    'code' => 200,
                    'text' => 'Logout Successfully'
                ),
                'data'    => null
            ));
        } 
        else 
        {
            Log::debug('You cannot logout because invalid token');
            return $this->response(array(
                'message' => array(
                    'code' => 10303,
                    'text' => 'Permission denied'
                ),
                'data'    => null
            ));
        }
    }
    
    /**
     * Do register action for user
     * 
     * @link http://localhost/test-blog/user_register Link to post_register method
     * @return mixed[] Content of API Response
     */
    public function post_register() 
    {
        // Get inputs
        $email      = Security::strip_tags(Security::xss_clean(Input::post('email')));
        $pass       = Auth::instance()->hash_password(Input::post('password'));
        $username   = Security::strip_tags(Security::xss_clean(Input::post('username')));
        $time       = time();
        Log::debug('Inputted Email: "'.$email.'"');
        Log::debug('Inputted pass: "'.$pass.'"');
        Log::debug('Inputted Username: "'.$username.'"');
        // check email format
        $result     = filter_var($email, FILTER_VALIDATE_EMAIL);
        if (!empty($email) && !empty($pass) && !empty($username) && !is_bool($result)) 
        {
            $data   = array(
                'email' => $email,
                'password' => $pass,
                'username' => $username,
                'last_login' => 0,
                'login_hash' => '',
                'group' => 0,
                'profile_fields' => '',
                'created_gmt' => $time,
                'modified_gmt' => 0
            );

            $user   = new User();
            $result = $user->register($data);

            return $this->response(array(
                'message' => array(
                    'code' => $result['status'],
                    'text' => $result['text']
                ),
                'data' => $result['data']
            ));
        } 
        else 
        {
            Log::debug('Register failed because of invalid input');
            return $this->response(array(
                'message' => array(
                    'code' => 401,
                    'text' => 'Invalid Input'
                ),
                'data' => null
            ));
        }
    }
    
    /**
     * Do edit action for user
     * 
     * @link http://localhost/test-blog/user_edit Link to put_edit method
     * @return mixed[] Content of API response
     */
    public function put_edit() 
    {   
        $token      = Input::put('token');
        $user = new User();
        $user_info = $user->check_token_exist($token);
        $token_db = '';
        foreach($user_info as $item)
        {
            $token_db = $item['login_hash'];
        }

        if (!empty($token) && $token == $token_db) 
        {
            $user_id    = (int)Input::put('id');
            Log::debug('ID of user now is: '.$user_id);
            if (empty($user_id) || $user_id <= 0) 
            {
                Log::debug('ID of user is not valid');
                return $this->response(array(
                    'message' => array(
                        'code' => 401,
                        'text' => 'Invalid Input'
                    ),
                    'data' => null
                ));
            }
            $user       = new User();
            $user_info  = $user->get_user_info($user_id);
            $pass       = $user_info['data']['password'];
            // Get inputs
            $email      = Input::put('email');
            $newPass    = Input::put('password');
            $username   = Input::put('username');
            $profile    = Input::put('profile_fields');
            $time       = time();
            // check editted account, then update to dabase and return data edited as json
            if (Input::put('id') && ((!empty($email) && !is_bool(filter_var($email, FILTER_VALIDATE_EMAIL))) || !empty($newPass) || !empty($username) || !empty($profile))) 
            {
                $data   = array(
                    'email' => (empty($email)) ? $user_info['data']['email'] : $email,
                    'password' => (empty($newPass)) ? $pass : Auth::instance()->hash_password($newPass),
                    'username' => (empty($username)) ? $user_info['data']['username'] : $username,
                    'last_login' => $user_info['data']['last_login'],
                    'group' => $user_info['data']['group'],
                    'profile_fields' => (empty($profile)) ? $user_info['data']['profile_fields'] : $profile,
                    'created_gmt' => $user_info['data']['created_gmt'],
                    'modified_gmt' => $time,
                );
                $user->update_user($user_id, $data);
                $data['last_login']     = gmdate('Y-m-d H:i:s', $user_info['data']['last_login']);
                $data['created_gmt']    = gmdate('Y-m-d H:i:s', $user_info['data']['created_gmt']);
                $data['modified_gmt']   = gmdate('Y-m-d H:i:s', $user_info['data']['modified_gmt']);
                return $this->response(array(
                    'message' => array(
                        'code' => 10302,
                        'text' => 'Updated Successfully'
                    ),
                    'data' => $data
                ));
            } 
            else 
            {
                return $this->response(array(
                    'message' => array(
                        'code' => $user_info['status'],
                        'text' => $user_info['text']
                    ),
                    'data' => array(
                        'id' => $user_info['data']['id'],
                        'email' => $user_info['data']['email'],
                        'password' => $pass,
                        'username' => $user_info['data']['username'],
                        'last_login' => gmdate('Y-m-d H:i:s', $user_info['data']['last_login']),
                        'group' => $user_info['data']['group'],
                        'profile_fields' => $user_info['data']['profile_fields'],
                        'created_gmt' => gmdate('Y-m-d H:i:s', $user_info['data']['created_gmt']),
                        'modified_gmt' => gmdate('Y-m-d H:i:s', $user_info['data']['modified_gmt']),
                    )
                ));
            }
        }
        else 
        {
            Log::debug('You cannot edit user because of invalid token');
            return $this->response(array(
                'message' => array(
                    'code' => 10303,
                    'text' => 'Permission denied'
                ),
                'data' => null
            ));
        }
    }
}
