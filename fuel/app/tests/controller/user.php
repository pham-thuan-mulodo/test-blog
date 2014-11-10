<?php
use Fuel\Core\TestCase;
use Fuel\Core\Request;
use Fuel\Core\DB;
/**
 * Test_Controller_User Class contains all test methods for methods in class Controller_User
 * 
 * @group User
 * @package Fuel\Core\TestCase
 * @var Test_Controller_User Object contains methods to test methods in class Controller_User
 */
class Test_Controller_User extends TestCase 
{
	private $_user_id;
	
	public function set_user_id($user_id) 
	{
		$this->_user_id = $user_id;
	}
	
	public function get_user_id() 
	{
		return $this->_user_id;
	}
	
    public function setUp() 
    {
        //$this->user_c = new Controller_User();
    }
    
    public function tearDown() 
    {
        //unset($this->user_c);
        $this->delete_user_test($this->get_user_id());
        unset($this->_user_id);
    }
    
    /**
     * login_provider
     * 
     * @return mixed Set of data to test
     */
    public function login_provider() 
    {
        return array(
            array('albert', '123'),
            array('richard', '123'),
            array('nhat123', '123'),
            array('utest2', '123'),
            array('anna231', '123'),
            array('example57', '123'),
            array('firstlove123', '123456'),
            array('shinichi', '123'),
            array('tuan_pham123', '123'),
            array('loc', '123'),
        );
    }
    
    /**
     * Test Controller post_login
     * 
     * @dataProvider login_provider
     * @param string $username Username of user to test
     * @param string $password Password of user to test
     */
    public function test_post_login($username, $password) 
    {
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
    public function test_post_register() 
    {
        $curl   = Request::forge('http://localhost/test-blog/user_register', 'curl');
        $curl->set_method('post');
        $user_info  = array(
            'email' => 'alex@mulodo.com',
            'password' => '123456',
            'username' => 'alex'
        );
        $curl->set_params($user_info);
        $curl->execute();
        
        // Get API Response
        $response       = $curl->response();
        // Convert json response to array
        $arr_msg        = json_decode($response, true);
        $this->set_user_id($arr_msg['data']['id']);
        // Get API Response code
        $status_actual  = $arr_msg['message']['code'];
        $status_expected= 200;
        $this->assertEquals($status_expected, $status_actual);
    }
    
    /**
     * edit_provider
     * 
     * @return mixed Set of data to test
     */
    public function edit_provider() 
    {
        return array(
            array(29, 'first57@mulodo.com', '123', 'Xin chao cac ban'),
            array(28, 'example_love@mulodo.com', '123', 'Happy, friendly, funny'),
        );
    }
    
    /**
     * Test put_edit method in user controller
     * 
     * @dataProvider edit_provider
     * @param int $user_id ID of user to test
     * @param string $email Email of user to test
     * @param string $password Password of user to test
     * @param string $profile Profile of user to test  
     */
    public function test_put_edit($user_id, $email, $password, $profile) 
    {
        $token = $this->test_post_login('albert', '123');
        if(!empty($token)) 
        {
            $curl   = Request::forge('http://localhost/test-blog/user_edit', 'curl');
            $curl->set_method('put');
            $user_info = array(
                'token' => $token,
                'email' => $email,
                'password' => $password,
                'profile_fields' => $profile,
                'id' => $user_id
            );
            $curl->set_params($user_info);
            $curl->execute();
            // Get API Response
            $response       = $curl->response();
            //echo $response;exit;
            // Convert json response to array
            $arr_msg        = json_decode($response->body(), true);
            // Get API Response code
            $status_actual  = $arr_msg['message']['code'];
            $status_expected= 10302;
            $this->assertEquals($status_expected, $status_actual);  
        }
    }
    
    /**
     * Test post_logout method in user controller
     */
    public function test_post_logout() 
    {
        $token = $this->test_post_login('albert', '123');
        if(!empty($token)) 
        {
            $curl   = Request::forge('http://localhost/test-blog/user_logout', 'curl');
            $curl->set_method('post');
            $token_info = array(
                'token' => $token
            );
            $curl->set_params($token_info);
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
    }
    
    public function delete_user_test($user_id) 
    {
    	$query = DB::query('DELETE FROM user WHERE id = :user_id');
    	$query->bind('user_id', $user_id);
    	$query->execute();
    }
}

