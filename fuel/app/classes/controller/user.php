<?php

use Fuel\Core\View;
use Model\User;
use Fuel\Core\Session;
use Fuel\Core\Input;
use Fuel\Core\Controller_Rest;
use Fuel\Core\Security;
use Fuel\Core\Debug;
use Auth\Auth;

//use Fuel\Core\Date;

class Controller_User extends Controller_Rest {

    protected $format = 'json';

    public function before() {
        parent::before();
        if (Auth::check() && !empty(Session::get('token'))) {
            echo '';
        } else {
            return $this->response(array(
                        'message' => array(
                            'status' => 10301,
                            'text' => 'Please login'
                        ),
                        'data' => NULL
            ));
        }
    }

    public function put_list() {
        return $this->response(array(
                    'foo' => Input::put('foo'),
                    'baz' => array(2, 'a' => 3, 4),
                    'empty' => NULL
        ));
    }

    public function post_login() {
        if (!Auth::check()) {
            $username = Security::strip_tags(Security::xss_clean(Input::post('username')));
            $pass = Security::strip_tags(Security::xss_clean(Input::post('password')));
            if (Input::method() == 'POST') {
                if (Auth::login($username, $pass)) {
                    // create token
                    $token = Auth::get('login_hash');
                    Session::set('token', $token);

                    return $this->response(array(
                                'message' => array(
                                    'status' => 200,
                                    'text' => ''
                                ),
                                'token' => $token,
                                'data' => NULL
                    ));
                } else {
                    return $this->response(array(
                                'message' => array(
                                    'status' => 401,
                                    'text' => 'Invalid Input'
                                ),
                                'data' => NULL
                    ));
                }
            }
        } else {
            return $this->response(array(
                        'message' => array(
                            'status' => 200,
                            'text' => ''
                        ),
                        'token' => Session::get('token'),
                        'data' => NULL
            ));
        }
    }

    public function post_logout() {
        if (Auth::check() && !empty(Session::get('token'))) {
<<<<<<< HEAD
=======
            // xoa token trong database
            $arr_auth = Auth::instance()->get_user_id();
            $user_id = $arr_auth[1];
            $user = new User();
            $user->deleteToken($user_id);
            
>>>>>>> parent of 07aec38... custom logout API user
            Auth::logout();
            Session::destroy();
            return $this->response(array(
                        'message' => array(
                            'status' => 200,
                            'text' => ''
                        ),
                        'data' => NULL
            ));
        } else {
            return $this->response(array(
                        'message' => array(
                            'status' => 10301,
                            'text' => 'Please login'
                        ),
                        'data' => NULL
            ));
        }
    }

    public function post_register() {
        $email = Security::strip_tags(Security::xss_clean(Input::post('email')));
        $pass = Auth::instance()->hash_password(Input::post('password'));
        $username = Security::strip_tags(Security::xss_clean(Input::post('username')));
        $time = time();
        // check email format
        $result = filter_var($email, FILTER_VALIDATE_EMAIL);
        if (!empty($email) && !empty($pass) && !empty($username) && !is_bool($result)) {
            $data = array(
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

            $user = new User();
            $result = $user->register($data);

            return $this->response(array(
                        'message' => array(
                            'status' => $result['status'],
                            'text' => $result['text']
                        ),
                        'data' => $result['data']
            ));
        } else {
            return $this->response(array(
                        'message' => array(
                            'status' => 401,
                            'text' => 'Invalid Input'
                        ),
                        'data' => NULL
            ));
        }
    }

    public function put_edit() {
        if (Auth::check() && !empty(Session::get('token'))) {
            $user_id = (int) Input::put('id');
            if (empty($user_id) || $user_id <= 0) {
                return $this->response(array(
                    'message' => array(
                        'status' => 401,
                        'text' => 'Invalid Input'
                    ),
                    'data' => NULL
                ));
            }
            $user = new User();
            $result = $user->edit($user_id);
            $pass = $result['data']['password'];
            $time = time();
            // check edit account, then update to dabase and return data edited as json
            if (Input::put('id') && (!empty(Input::put('email')) || !empty(Input::put('password')) ||
                    !empty(Input::put('username')) || Input::put('profile_fields')) &&
                    !is_bool(filter_var(Input::put('email'), FILTER_VALIDATE_EMAIL))) {
                $data = array(
                    'email' => (empty(Input::put('email'))) ? $result['data']['email'] : Input::put('email'),
                    'password' => (empty(Input::put('password'))) ? $pass : Auth::instance()->hash_password(Input::put('password')),
                    'username' => (empty(Input::put('username'))) ? $result['data']['username'] : Input::put('username'),
                    'last_login' => $result['data']['last_login'],
                    'group' => $result['data']['group'],
                    'profile_fields' => (empty(Input::put('profile_fields'))) ? $result['data']['profile_fields'] : Input::put('profile_fields'),
                    'created_gmt' => $result['data']['created_gmt'],
                    'modified_gmt' => $time,
                );
                $user->updateUser($user_id, $data);
                $data['last_login'] = gmdate('Y-m-d H:i:s', $result['data']['last_login']);
                $data['created_gmt'] = gmdate('Y-m-d H:i:s', $result['data']['created_gmt']);
                $data['modified_gmt'] = gmdate('Y-m-d H:i:s', $result['data']['modified_gmt']);
                return $this->response(array(
                            'message' => array(
                                'status' => $result['status'],
                                'text' => $result['text']
                            ),
                            'data' => $data
                ));
            } else {
                return $this->response(array(
                            'message' => array(
                                'status' => $result['status'],
                                'text' => $result['text']
                            ),
                            'data' => array(
                                'id' => $result['data']['id'],
                                'email' => $result['data']['email'],
                                'password' => $pass,
                                'username' => $result['data']['username'],
                                'last_login' => gmdate('Y-m-d H:i:s', $result['data']['last_login']),
                                'group' => $result['data']['group'],
                                'profile_fields' => $result['data']['profile_fields'],
                                'created_gmt' => gmdate('Y-m-d H:i:s', $result['data']['created_gmt']),
                                'modified_gmt' => gmdate('Y-m-d H:i:s', $result['data']['modified_gmt']),
                            )
                ));
            }
        }
    }

}
