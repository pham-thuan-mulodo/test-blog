<?php
use Fuel\Core\View;
use Model\User;
//use Fuel\Core\Date;

class Controller_User extends Controller {
    public function action_index() {
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
        
    }
}