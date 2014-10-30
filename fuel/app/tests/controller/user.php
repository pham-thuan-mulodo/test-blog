<?php
use Fuel\Core\TestCase;
use Fuel\Core\Request;
/**
 * @group User
 */

class Test_Controller_User extends TestCase {
    protected $user_c;
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
        $response       = $curl->response();
        // Convert json response to array
        $arr_msg        = json_decode($response, true);
        $tokens[] = $arr_msg['token'];
        // Get API Response code
        $status_actual  = $arr_msg['message']['code'];
        $status_expected= 200;
        $this->assertEquals($status_expected, $status_actual);
        return $tokens;
    }
    
    /**
     * Test controller post_register
     * 
     * @dataProvider register_provider
     * @param string $email Email of user to test
     * @param string $password Password of user to test
     * @param string $username Username of user to test
     */
    /*public function test_post_register($email, $password, $username) {
        $curl   = Request::forge('http://localhost/test-blog/user_register', 'curl');
        $curl->set_method('post');
        $user_info  = array(
            'email' => $email,
            'password' => $password,
            'username' => $username
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
    
    public function register_provider() {
        return array(
//            array('steve@mulodo.com', '123', 'steve'),
//            array('andrew@mulodo.com', '456', 'andrew45'),
        );
    }
    
    /**
     * @depends test_post_login
     */
    /*public function test_post_logout($tokens) { 
        if(isset($tokens)) {
            $curl   = Request::forge('http://localhost/test-blog/user_logout', 'curl');
            $curl->set_method('post');
            $curl->execute();
            echo $curl->response();exit;
        }
        else {
            echo 'Failfggg';
        }
    }*/
}

