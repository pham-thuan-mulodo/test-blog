<?php
use Fuel\Core\View;
use Model\User;
use Fuel\Core\Session;
use Fuel\Core\Input;
//use Fuel\Core\Date;

class Controller_User extends Controller {
    public function action_index() {
        //echo Session::get('token');
        return View::forge('user/index');
    }
    public function action_register() {
        //Date::display_timezone('Asia/Ho_Chi_Minh');
        $pass = md5('123@');
        $time = time();
        //echo date("Y-m-d H:i:s");
        $data = array(
            'email' => 'first@gmail.com',
            'password' => $pass,
            'username' => 'first',
            'created_gmt' => $time,
            'modified_gmt' => 0
        );
        $user = new User();
        $result = $user->register($data);
        $response = array(
            'message' => array(
                'status' => $result['status'],
                'text' => $result['text']
            ),
            'data' => $result['data']
        );
        echo json_encode($response);
    }
    public function action_login() {
        $email = 'example@gmail.com';
        $pass = md5('123@');
 
        $user = new User();
        $result = $user->login($email, $pass);
        if($result['status'] == 200) {
            $response = array(
                'message' => array(
                    'status' => $result['status'],
                    'text' => $result['text']
                ),
                'data' => NULL
            );
            // create token
            $token = hash_hmac('sha1', time(), uniqid(), false);
            // store data into session
            Session::set('user_id', $result['data']->id)
                    ->set('email', $result['data']->email)
                    ->set('username', $result['data']->username)
                    ->set('token', $token);
            echo Session::get('welcome');
        }
        else {
            $response = array(
                'message' => array(
                    'status' => $result['status'],
                    'text' => $result['text']
                ),
                'data' => NULL
            );
        }
        echo json_encode($response);
        //echo count($result);
    }
    public function action_edit($id) {
        // faa609ff9b7f4a0c95fded15d0688349ce1a446e
       $user_id = (int)$id;
       //$user_id = Input::put('user_id', 21);
       $token = Input::put('token', 'faa609ff9b7f4a0c95fded15d0688349ce1a446e');
       $token_id = Session::get('token');
       //$user_id = Session::get('user_id');
       //$email = Session::get('email');
       
       //echo $token_id; exit;
       if($token != $token_id || $user_id < 0) {
           $response = array(
                'message' => array(
                    'status' => '404',
                    'text' => 'Not found'
                ),
                'data' => NULL
            );
           echo json_encode($response);
       }
       else {
           $user = new User();
           $result = $user->edit($user_id);
           switch($result['status']) {
               case 401: 
                   $response = array(
                       'message' => array(
                           'status' => $result['status'],
                           'text' => $result['text']
                       ),
                       'data' => $result['data']
                   );
                   echo json_encode($response);
                   break;
               case 200:
                   $response = array(
                       'message' => array(
                           'status' => $result['status'],
                           'text' => $result['text']
                       ),
                       'data' => array(
                           'id' => $result['data']->id,
                           'email' => $result['data']->email,
                           'password' => $result['data']->password,
                           'username' => $result['data']->username,
                           'created_gmt' => gmdate('Y-m-d H:i:s', $result['data']->created_gmt),
                           'modified_gmt' => gmdate('Y-m-d H:i:s', $result['data']->modified_gmt)
                       )
                   );
                   echo json_encode($response);
                   break;
           }
       }
    }
}