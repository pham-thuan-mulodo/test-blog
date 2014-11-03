<?php

use Fuel\Core\Session;
use Fuel\Core\Controller_Rest;
use Auth\Auth;
use Fuel\Core\Input;
use Fuel\Core\Security;
use Model\Comment;
use Fuel\Core\Log;
use Model\User;

/**
 * Controller_Comment Controller class for comment endpoint. This class contains all API methods related comment.
 * About routing config. See routing.php 
 * 
 * @package Fuel\Core\Controller_Rest
 * @var Controller_Comment Class contains methods to resolve transactions of comment
 */
class Controller_Comment extends Controller_Rest 
{
    /**
     *
     * @var string Set type of data of API Response Data
     */
    protected $format = 'json';
    
    /**
     * Check user logged in or not
     *
     * @return mixed[] Content of API response
     */
    public function before() 
    {
        parent::before();
        if (Auth::check()) 
        {
            echo '';
        } 
        else 
        {
            Log::error('You are not login yet.');
            return $this->response(array(
                'message' => array(
                    'code' => 10301,
                    'text' => 'Please login'
                ),
                'data' => null
            ));
        }
    }
    
    /**
     * Do add action when user add a comment
     * 
     * @link http://localhost/test-blog/comment Link to post_add method
     * @return mixed[] Content of API response
     */
    public function post_add() 
    {
        $token      = Input::post('token');
        $user = new User();
        $user_info = $user->check_token_exist($token);
        $token_db = '';
        foreach($user_info as $item)
        {
            $token_db = $item['login_hash'];
            $user_id = $item['id'];
        }
        
        if (!empty($token) && $token == $token_db) 
        {
            // get id of author
            $author_id = $user_id;
            // get inputs
            $post_id    = (int) Input::post('post_id');
            $content    = htmlspecialchars_decode(Security::strip_tags(Security::xss_clean(Input::post('content'))), ENT_QUOTES);
            $time       = time();
            $comment    = new Comment();
            Log::debug('ID of post commented now = '.$post_id);
            Log::debug('Content of comment now: '.$content);

            if ($post_id > 0 && !empty($post_id) && !empty($content)) 
            {
                $flag   = $comment->is_existed_post($post_id);
                // Check post existed, if post existed then add new comment
                if ($flag == 1) 
                {
                    $data   = array(
                        'content' => $content,
                        'author_id' => $author_id,
                        'post_id' => $post_id,
                        'created_gmt' => $time,
                        'modified_gmt' => 0
                    );
                    $result = $comment->add_comment($data);
                    return $this->response(array(
                        'message' => array(
                            'code' => $result['status'],
                            'text' => $result['text']
                        ),
                        'data' => null
                    ));
                } 
                else 
                {
                    Log::error('Add comment failed because the post commented was not existed');
                    return $this->response(array(
                        'message' => array(
                            'code' => 10300,
                            'text' => 'Database Exception'
                        ),
                        'data' => null
                    ));
                }
            } 
            else 
            {
                Log::debug('Add comment failed because of invalid input');
                return $this->response(array(
                    'message' => array(
                        'code' => 401,
                        'text' => 'Invalid Input'
                    ),
                    'data' => null
                ));
            }
        }
        else
        {
            Log::debug('You cannot add comment because of invalid token');
            return $this->response(array(
                'message' => array(
                    'code' => 10303,
                    'text' => 'Permission denied'
                ),
                'data' => null
            ));
        }
    }
    
    /**
     * Do remove action when user delete a comment
     * 
     * @link http://localhost/test-blog/comment Link to delete_remove method
     * @return mixed[] Content of API response
     */
    public function delete_remove() 
    {
        $token      = Input::delete('token');
        $user = new User();
        $user_info = $user->check_token_exist($token);
        $token_db = '';
        foreach($user_info as $item)
        {
            $token_db = $item['login_hash'];
        }
        
        if (!empty($token) && $token == $token_db) 
        {
            $comm_id = (int)Input::delete('id');
            Log::debug('ID of comment to delete now = '.$comm_id);
            if (empty($comm_id) || $comm_id <= 0) 
            {
                Log::debug('Deleting comment failed because ID of comment is invalid');
                return $this->response(array(
                    'message' => array(
                        'code' => 401,
                        'text' => 'Invalid Input'
                    ),
                    'data' => null
                ));
            } 
            else 
            {
                $comment    = new Comment();
                $result     = $comment->delete_comment($comm_id);
                return $this->response(array(
                    'message' => array(
                        'code' => $result['status'],
                        'text' => $result['text']
                    ),
                    'data' => $result['data']
                ));
            }
        }
        else
        {
            Log::debug('You cannot delete this comment because of invalid token');
            return $this->response(array(
                'message' => array(
                    'code' => 10303,
                    'text' => 'Permission denied'
                ),
                'data' => null
            ));
        }
    }
    
    /**
     * Show comments of a specified post
     * 
     * @link http://localhost/test-blog/comments Link to get_show method
     * @return mixed[] Content of API response
     */
    public function get_show() 
    {
        $token      = Input::get('token');
        $user = new User();
        $user_info = $user->check_token_exist($token);
        $token_db = '';
        foreach($user_info as $item)
        {
            $token_db = $item['login_hash'];
        }
        
        if (!empty($token) && $token == $token_db) 
        {
            // Check input is valid or invalid
            $post_id    = (int)Input::get('post_id');
            Log::debug('ID of specified post now = '.$post_id);
            if (empty($post_id) || $post_id <= 0) 
            {
                Log::debug('Get comments of specified post failed because of invalid input');
                return $this->response(array(
                    'message' => array(
                        'code' => 401,
                        'text' => 'Invalid Input'
                    ),
                    'data' => null
                ));
            }
            $comment    = new Comment();
            $result     = $comment->get_comments_post($post_id);
            if (count($result) != 0) 
            {
                $data   = array();
                $i      = 0;
                // create data[] array
                foreach ($result as $item) 
                {
                    $data[$i]['id']             = $item['id'];
                    $data[$i]['content']        = $item['content'];
                    $data[$i]['author_id']      = $item['author_id'];
                    $data[$i]['post_id']        = $item['post_id'];
                    $data[$i]['created_gmt']    = gmdate('Y-m-d H:i:s', $item['created_gmt']);
                    $data[$i]['modified_gmt']   = gmdate('Y-m-d H:i:s', $item['modified_gmt']);
                    $i++;
                }
                return $this->response(array(
                    'message' => array(
                        'code' => 200,
                        'text' => ''
                    ),
                    'data' => $data
                ));
            } 
            else 
            {
                Log::error('Get comments of specified post failed because this post was not found in database');
                return $this->response(array(
                    'message' => array(
                        'code' => 404,
                        'text' => 'Not Found'
                    ),
                    'data' => null
                ));
            }
        }
        else
        {
            Log::debug('You cannot get comments because of invalid token');
            return $this->response(array(
                'message' => array(
                    'code' => 10303,
                    'text' => 'Permission denied'
                ),
                'data' => null
            ));
        }
    }
    
    /**
     * Do edit action when user edit a comment
     * 
     * @link http://localhost/test-blog/comment_edit Link to put_edit method
     * @return mixed[] Content of API response
     */
    public function put_edit() 
    {
        $token      = Input::put('token');
        $user = new User();
        $user_info = $user->check_token_exist($token);
        $token_db = '';
        foreach($user_info as $item)
        {
            $token_db = $item['login_hash'];
            $user_id = $item['id'];
        }
        
        if (!empty($token) && $token == $token_db) 
        {
            // Check input is valid or invalid
            $comm_id    =  (int)Input::put('id');
            Log::debug('ID of comment to edit now = '.$comm_id);
            if (empty($comm_id) || $comm_id <= 0) 
            {
                Log::debug('Edit comment failed because of invalid input');
                return $this->response(array(
                    'message' => array(
                        'code' => 401,
                        'text' => 'Invalid Input'
                    ),
                    'data' => null
                ));
            }

            // Get post
            $comment        = new Comment();
            $result         = $comment->get_comment($comm_id);
            // Get input
            $content        = htmlspecialchars_decode(Security::strip_tags(Security::xss_clean(Input::put('content'))), ENT_QUOTES);
            $modify_time    = time();

            if (Input::put('id') && !empty($content) && $content != $result['content']) 
            {
                $data = array(
                    'id' => $result['id'],
                    'content' => (empty($content)) ? $result['content'] : htmlspecialchars_decode($content, ENT_QUOTES),
                    'author_id' => $result['author_id'],
                    'post_id' => $result['post_id'],
                    'created_gmt' => $result['created_gmt'],
                    'modified_gmt' => $modify_time
                );
                $comment->update_comment($comm_id, $data);
                $data['created_gmt']    = gmdate('Y-m-d H:i:s', $result['created_gmt']);
                $data['modified_gmt']   = gmdate('Y-m-d H:i:s', $result['modified_gmt']);
                return $this->response(array(
                    'message' => array(
                        'code' => 10302,
                        'text' => 'Updated Successfully'
                    ),
                    'data' => $data
                ));
            } 
            else 
            {
                if (count($result) != 0) 
                {
                    $data = array(
                        'id' => $result['id'],
                        'content' => $result['content'],
                        'author_id' => $result['author_id'],
                        'post_id' => $result['post_id'],
                        'created_gmt' => gmdate('Y-m-d H:i:s', $result['created_gmt']),
                        'modified_gmt' => gmdate('Y-m-d H:i:s', $result['modified_gmt'])
                    );
                    return $this->response(array(
                        'message' => array(
                            'code' => 200,
                            'text' => ''
                        ),
                        'data' => $data
                    ));
                } 
                else 
                {
                    Log::error('Edit comment failed because this comment was not found');
                    return $this->response(array(
                        'message' => array(
                            'code' => 404,
                            'text' => 'Not Found'
                        ),
                        'data' => null
                    ));
                }
            }
        }
        else
        {
            Log::debug('You cannot edit this comment because of invalid token');
            return $this->response(array(
                'message' => array(
                    'code' => 10303,
                    'text' => 'Permission denied'
                ),
                'data' => null
            ));
        }
    }
}
