<?php
use Fuel\Core\TestCase;
use Fuel\Core\Request;
/**
 * Test_Controller_Post Class contains all test methods for methods in class Controller_Post
 * 
 * @group Post
 * @package Fuel\Core\TestCase
 * @var Test_Controller_Post Object contains methods to test methods in class Controller_Post
 */
class Test_Controller_Post extends TestCase 
{
    private $user;
    public function setUp() 
    {
        parent::setUp();
        $this->user = new Test_Controller_User();
    }
    
    public function tearDown() 
    {
        parent::tearDown();
        unset($this->user);
    }
    
    /**
     * create_provider
     * 
     * @return mixed Set of data to test
     */
    public function create_provider() 
    {
        return array(
            array('Test title 5', 'Sample outline testing', 'Sample Content testing'),
            array('Test title 6', '', 'This is a sample content'),
            array('Test title 7', '', 'Today is a beautiful day. There is a little rain.'),
            array('Test title 8', 'It is a rainny day.', 'It is a raiiny day. I hope there will be sunny tomorrow.'),
        );
    }
    
    /**
     * test_post_create Test post_create method in post controller
     * 
     * @dataProvider create_provider
     * @param string $title Title of post to test
     * @param string $outline Outline of post to test
     * @param string $content Content of post to test
     */
    /*public function test_post_create($title, $outline, $content) 
    {
        $token = $this->user->test_post_login('albert', '123');
        if(!empty($token))
        {
            $curl       = Request::forge('http://localhost/test-blog/post', 'curl');
            $curl->set_method('post');
            $post_info  = array(
                'title' => $title,
                'outline' => $outline,
                'content' => $content,
                'token' => $token
            );
            $curl->set_params($post_info);
            $curl->execute();
            // Get API Response
            $response       = $curl->response()->body();
            // Convert json response to array
            $arr_msg        = json_decode($response, true);
            // Get API Response code
            $status_actual  = $arr_msg['message']['code'];
            $status_expected= 200;
            $this->assertEquals($status_expected, $status_actual);
        }
    }*/
    
    /**
     * edit_post_provider
     * 
     * @return mixed Set of data to test
     */
    public function edit_post_provider() 
    {
        return array(
            array(30, 'A little love', 'Make me smile', 'This is a sample content'),
            array(31, 'Little rain', 'Sample outline testing', 'Today is a beautiful day. There is a little rain.'),
            array(32, 'Rainny day', 'It is a rainny day.', 'It is a raiiny day. I hope there will be sunny tomorrow.'),
        );
    }
    
    /**
     * test_put_edit Test put_edit method in post controller
     * 
     * @dataProvider edit_post_provider
     * @param int $post_id ID of post to test
     * @param string $title Title of post to test
     * @param string $outline Outline of post to test
     * @param string  $content Content of post to test
     */
    public function test_put_edit($post_id, $title, $outline, $content) 
    {
        $token = $this->user->test_post_login('albert', '123');
        if(!empty($token))
        {
            $curl       = Request::forge('http://localhost/test-blog/post_edit', 'curl');
            $curl->set_method('put');
            $post_info  = array(
                'id' => $post_id,
                'title' => $title,
                'outline' => $outline,
                'content' => $content,
                'token' => $token
            );
            $curl->set_params($post_info);
            $curl->execute();
            // Get API Response
            $response       = $curl->response()->body();
            // Convert json response to array
            $arr_msg        = json_decode($response, true);
            // Get API Response code
            $status_actual  = $arr_msg['message']['code'];
            $status_expected= 10302;
            $this->assertEquals($status_expected, $status_actual);
        }
    }
    
    /**
     * delete_post_provider
     * 
     * @return mixed Set of data to test
     */
    public function delete_post_provider() 
    {
        return array(
            array(27),
            array(28),
            array(29)
        );
    }
    
    /**
     * test_delete_delete Test delete_delete method in post controller
     * 
     * @dataProvider delete_post_provider
     * @param int $post_id ID of post to test
     */
    public function test_delete_delete($post_id) 
    {
        $token = $this->user->test_post_login('albert', '123');
        if(!empty($token))
        {
            $curl       = Request::forge('http://localhost/test-blog/post_delete', 'curl');
            $curl->set_method('delete');
            $post_info  = array(
                'id' => $post_id,
                'token' => $token
            );
            $curl->set_params($post_info);
            $curl->execute();
            // Get API Response
            $response       = $curl->response()->body();
            // Convert json response to array
            $arr_msg        = json_decode($response, true);
            // Get API Response code
            $status_actual  = $arr_msg['message']['code'];
            $status_expected= 200;
            $this->assertEquals($status_expected, $status_actual);
        }
    }
    
    /**
     * delete_post_provider
     * 
     * @return mixed Set of data to test
     */
    public function get_post_provider() 
    {
        return array(
            array(2)
        );
    }
    
    /**
     * test_get_show Test get_show method in post controller
     * 
     * @dataProvider get_post_provider
     * @param int $author_id ID of user to test
     */
    public function test_get_show($author_id) 
    {
        $token = $this->user->test_post_login('albert', '123');
        if(!empty($token))
        {
            $curl       = Request::forge('http://localhost/test-blog/posts', 'curl');
            $curl->set_method('get');
            $post_info  = array(
                'author_id' => $author_id,
                'token' => $token
            );
            $curl->set_params($post_info);
            $curl->execute();
            // Get API Response
            $response       = $curl->response()->body();
            // Convert json response to array
            $arr_msg        = json_decode($response, true);
            // Get API Response code
            $status_actual  = $arr_msg['message']['code'];
            print_r($arr_msg['data']);
            $status_expected= 200;
            $this->assertEquals($status_expected, $status_actual);
        }
    }
}
