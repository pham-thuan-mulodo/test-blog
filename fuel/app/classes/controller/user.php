<?php
use Fuel\Core\View;
use Model\User;
use Fuel\Core\Session;
use Fuel\Core\Input;
use Fuel\Core\Controller_Rest;
use Auth\Auth;
//use Fuel\Core\Date;

class Controller_User extends Controller_Rest {
    protected $format = 'json';
    public function put_list() {
        return $this->response(array(
            'foo' => Input::put('foo'),
            'baz' => array(2, 'a' => 3, 4),
            'empty' => NULL
        ));
    }
    public function post_login() {
        if(!Auth::check()) {
            if(Input::method() == 'POST') {
                if(Auth::login(Input::post('username'), Input::post('password'))) {
                    // create token
                    $token = hash_hmac('sha1', time(), uniqid(), false);
                    Session::set('token', $token);
                    return $this->response(array(
                        'message' => array(
                            'status' => 200,
                            'text' => ''
                        ),
                        'token' => $token,
                        'data' => NULL
                    ));
                }
                else {
                    return $this->response(array(
                        'message' => array(
                            'status' => 401,
                            'text' => 'Invalid Input'
                        ),
                        'data' => NULL
                    ));
                }
            }
        }
        else {
            //echo Session::get('token');
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
        Auth::logout();
        Session::destroy();
        return $this->response(array(
            'message' => array(
                'status' => 200,
                'text' => ''
            ),
            'data' => NULL
        ));
    }
    public function post_register() {
        if(Auth::check() && !empty(Session::get('token'))) {
            echo 'hello';
        }
    }
}