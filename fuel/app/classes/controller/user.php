<?php
use Model\User;
use Fuel\Core\Session;
use Fuel\Core\Input;
use Fuel\Core\Controller_Rest;
use Fuel\Core\Security;
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
                            'code' => 10301,
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
            // Check user login or not login
            if(Auth::check()) {
                return $this->response(array(
                            'message' => array(
                                'code' => 200,
                                'text' => ''
                            ),
                            'token' => Session::get('token'),
                            'data' => NULL
                ));
            }
            
            $username = Security::strip_tags(Security::xss_clean(Input::post('username')));
            $pass = Security::strip_tags(Security::xss_clean(Input::post('password')));
            if (Input::method() == 'POST') {
                if (Auth::login($username, $pass)) {
                    // create token
                    $token = Auth::get('login_hash');
                    Session::set('token', $token);

                    return $this->response(array(
                                'message' => array(
                                    'code' => 200,
                                    'text' => ''
                                ),
                                'token' => $token,
                                'data' => NULL
                    ));
                } else {
                    return $this->response(array(
                                'message' => array(
                                    'code' => 401,
                                    'text' => 'Invalid Input'
                                ),
                                'data' => NULL
                    ));
                }
            }
    }

    public function post_logout() {
        if (Auth::check() && !empty(Session::get('token'))) {
            // Delete token in database
            $arr_auth = Auth::instance()->get_user_id();
            $user_id = $arr_auth[1];
            $user = new User();
            $user->deleteToken($user_id);
            
            Auth::logout();
            Session::destroy();
            return $this->response(array(
                        'message' => array(
                            'code' => 200,
                            'text' => ''
                        ),
                        'data' => NULL
            ));
        } else {
            return $this->response(array(
                        'message' => array(
                            'code' => 10301,
                            'text' => 'Please login'
                        ),
                        'data' => NULL
            ));
        }
    }

    public function post_register() {
        // Get inputs
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
                            'code' => $result['status'],
                            'text' => $result['text']
                        ),
                        'data' => $result['data']
            ));
        } else {
            return $this->response(array(
                        'message' => array(
                            'code' => 401,
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
                        'code' => 401,
                        'text' => 'Invalid Input'
                    ),
                    'data' => NULL
                ));
            }
            $user = new User();
            $result = $user->edit($user_id);
            $pass = $result['data']['password'];
            // Get inputs
            $email = Input::put('email');
            $newPass = Input::put('password');
            $username = Input::put('username');
            $profile = Input::put('profile_fields');
            $time = time();
            // check editted account, then update to dabase and return data edited as json
            if (Input::put('id') && ((!empty($email) && !is_bool(filter_var($email, FILTER_VALIDATE_EMAIL))) || !empty($newPass) || !empty($username) || !empty($profile))) {
                $data = array(
                    'email' => (empty($email)) ? $result['data']['email'] : $email,
                    'password' => (empty($newPass)) ? $pass : Auth::instance()->hash_password($newPass),
                    'username' => (empty($username)) ? $result['data']['username'] : $username,
                    'last_login' => $result['data']['last_login'],
                    'group' => $result['data']['group'],
                    'profile_fields' => (empty($profile)) ? $result['data']['profile_fields'] : $profile,
                    'created_gmt' => $result['data']['created_gmt'],
                    'modified_gmt' => $time,
                );
                $user->updateUser($user_id, $data);
                $data['last_login'] = gmdate('Y-m-d H:i:s', $result['data']['last_login']);
                $data['created_gmt'] = gmdate('Y-m-d H:i:s', $result['data']['created_gmt']);
                $data['modified_gmt'] = gmdate('Y-m-d H:i:s', $result['data']['modified_gmt']);
                return $this->response(array(
                            'message' => array(
                                'code' => $result['status'],
                                'text' => $result['text']
                            ),
                            'data' => $data
                ));
            } else {
                return $this->response(array(
                            'message' => array(
                                'code' => $result['status'],
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
