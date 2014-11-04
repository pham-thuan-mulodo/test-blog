<?php
use Fuel\Core\TestCase;
use Fuel\Core\Request;
/**
 * @group User
 */

class Test_Controller_User extends TestCase 
{
    public function setUp() 
    {
        //$this->user_c = new Controller_User();
    }
    
    public function tearDown() 
    {
        //unset($this->user_c);
    }
    
    public function login_provider() 
    {
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
    /*public function test_post_register() 
    {
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
    
    public function edit_provider() 
    {
        return array(
            array(29, 'first57@mulodo.com', '123', 'Xin chao cac ban'),
            array(28, 'example_love@mulodo.com', '123', 'Happy, friendly, funny'),
        );
    }
    
    /**
     * @dataProvider edit_provider
     * 
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
     * 
     */
    /*public function test_post_logout() 
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
    }*/
}

