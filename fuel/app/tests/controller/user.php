<?php
use Fuel\Core\TestCase;
use Fuel\Core\Request;
use Model\User;
/**
 * @group User
 */

class Test_Controller_User extends TestCase {
    public function setUp() {
        //$this->user_c = new Controller_User();
    }
    
    public function tearDown() {
        //unset($this->user_c);
    }
    /**
     * Test Controller post_login
     * 
     * @dataProvider login_provider
     * @param string $username Username of user to test
     * @param string $password Password of user to test
     */
    public function test_post_login($username, $password) {
        $curl       = Request::forge('http://localhost/test-blog/user', 'curl');
        $curl->set_method('post');
        $user_info  = array(
            'username' => $username,
            'password' => $password
        );
        $curl->set_params($user_info);
        $curl->execute();
        // Get API Response
        $response       = $curl->response()->body();
        // Convert json response to array
        $arr_msg        = json_decode($response, true);
        // Get API Response code
        $status_actual  = $arr_msg['message']['code'];
        $status_expected= 200;
        $this->assertEquals($status_expected, $status_actual);
        return $arr_msg['token'];
    }
    
    /**
     * Test controller post_register
     * 
     * @param string $email Email of user to test
     * @param string $password Password of user to test
     * @param string $username Username of user to test
     */
    /*public function test_post_register() {
        $curl   = Request::forge('http://localhost/test-blog/user_register', 'curl');
        $curl->set_method('post');
        $user_info  = array(
            'email' => 'misa@mulodo.com',
            'password' => '123',
            'username' => 'misa'
        );
        $curl->set_params($user_info);
        $curl->execute();
        
        // Get API Response
        $response       = $curl->response();
        // Convert json response to array
        $arr_msg        = json_decode($response, true);
        // Get API Response code
        $status_actual  = $arr_msg['message']['code'];
        $status_expected= 200;
        $this->assertEquals($status_expected, $status_actual);
    }*/
    
    /**
     * 
     */
    public function test_post_logout() {
        $token = $this->test_post_login('richard', '123');
        $curl   = Request::forge('http://localhost/test-blog/user_logout', 'curl');
        $curl->set_method('post');
        $curl->set_params($token);
        $curl->execute();

        // Get API Response
        $response       = $curl->response();
        // Convert json response to array
        $arr_msg        = json_decode($response->body(), true);
        // Get API Response code
        $status_actual  = $arr_msg['message']['code'];
        $status_expected= 200;
        $this->assertEquals($status_expected, $status_actual);       
    }
    
    public function login_provider() {
        return array(
            array('albert', '123'),
            array('richard', '123'),
            array('nhat123', '123'),
            array('utest2', '123'),
            array('anna231', '123'),
            array('example57', '123'),
            array('firstlove123', '123'),
            array('shinichi', '123'),
            array('tuan_pham123', '123'),
            array('loc', '123'),
        );
    }
}

