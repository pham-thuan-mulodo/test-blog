<?php

use Fuel\Core\Session;
use Fuel\Core\Controller_Rest;
use Auth\Auth;
use Fuel\Core\Input;
use Fuel\Core\Security;
use Model\Post;
use Fuel\Core\Log;
use Model\User;

/**
 * Controller_Post Controller class for post endpoint. This class contains all API methods related post.
 * About routing config. See routing.php 
 * 
 * @package Fuel\Core\Controller_Rest
 * @var Controller_Post Class contains methods to resolve transactions of post
 */
class Controller_Post extends Controller_Rest 
{
    /**
     *
     * @var string Set type of data of API Response Data
     */
    protected $format   = 'json';
    
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
     * Do create action when user create a post
     * 
     * @link http://localhost/test-blog/post Link to post_create method
     * @return mixed[] Content of API response
     */
    public function post_create() 
    {
        $arr_auth   = Auth::instance()->get_user_id();
        $user_id    = $arr_auth[1];
        $user       = new User();
        $token      = Input::post('token');
        $user_token = $user->get_token_user($user_id);
        $sstoken    = $user_token['login_hash'];
        if (!empty($token) && $token == $sstoken) 
        {
            // get id of author
            $author_id  = $arr_auth[1];
            // get inputs
            $title      = Security::strip_tags(Security::xss_clean(Input::post('title')));
            $outline    = Security::strip_tags(Security::xss_clean(Input::post('outline')));
            $content    = Security::strip_tags(Security::xss_clean(Input::post('content')));
            $time       = time();
            
            Log::debug('Inputted title of post now is: "'.$title.'"');
            Log::debug('Inputted content of post now is: "'.$content.'"');
            
            if (!empty($title) && !empty($content)) 
            {
                $data   = array(
                    'title' => $title,
                    'outline' => $outline,
                    'content' => $content,
                    'author_id' => $author_id,
                    'created_gmt' => $time,
                    'modified_gmt' => 0
                );
                $post   = new Post();
                $result = $post->create_post($data);
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
                Log::debug('Create post failed because of invalid input');
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
            Log::debug('You cannot create post because of invalid token');
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
     * Do delete action when user delete a post
     * 
     * @license http://localhost/test-blog/post_delete Link to delete_delete method
     * @return mixed[] Content of API response
     */
    public function delete_delete() 
    {
        $arr_auth   = Auth::instance()->get_user_id();
        $user_id    = $arr_auth[1];
        $user       = new User();
        $token      = Input::delete('token');
        $user_token = $user->get_token_user($user_id);
        $sstoken    = $user_token['login_hash'];
        if (!empty($token) && $token == $sstoken) 
        {
            $post_id    = (int)Input::delete('id');
            Log::debug('Inputted ID of post deleted now = '.$post_id);
            
            if (empty($post_id) || $post_id <= 0) 
            {
                Log::debug('Delete post failed because ID of post is invalid');
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
                $post   = new Post();
                $result = $post->delete_post($post_id);
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
            Log::debug('You cannot delete post because of invalid token');
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
     * Do edit action when user edit a post
     * 
     * @link http://localhost/test-blog/post_edit Link to put_edit method
     * @return mixed[] Content of API response
     */
    public function put_edit() 
    {
        $arr_auth   = Auth::instance()->get_user_id();
        $user_id    = $arr_auth[1];
        $user       = new User();
        $token      = Input::put('token');
        $user_token = $user->get_token_user($user_id);
        $sstoken    = $user_token['login_hash'];
        if (!empty($token) && $token == $sstoken) 
        {
            // Check input is valid or invalid
            $post_id    = (int)Input::put('id');
            Log::debug('Inputted ID of post edited now = '.$post_id);
            if (empty($post_id) || $post_id <= 0) 
            {
                Log::debug('Edit post failed because ID of post is not valid');
                return $this->response(array(
                    'message' => array(
                        'code' => 401,
                        'text' => 'Invalid Input'
                    ),
                    'data' => null
                ));
            }

            // Get post
            $post        = new Post();
            $post_info      = $post->get_post_info($post_id);
            // Get input
            $title       = Security::strip_tags(Security::xss_clean(Input::put('title')));
            $outline     = Security::strip_tags(Security::xss_clean(Input::put('outline')));
            $content     = Security::strip_tags(Security::xss_clean(Input::put('content')));
            $modify_time = time();

            // Check edited post, if post isn't edited, update post to db, then update modified time
            // if title, outline and content of post inputted is same with db, it won't be updated
            if (Input::put('id') && ((!empty($title) && $title != $post_info['title']) || (!empty($outline) && $outline != $post_info['outline']) || (!empty($content) && $content != $post_info['content']))) 
            {
                $data   = array(
                    'id' => $post_info['id'],
                    'title' => (empty($title)) ? $post_info['title'] : htmlspecialchars_decode($title, ENT_QUOTES),
                    'outline' => (empty($outline)) ? $post_info['outline'] : htmlspecialchars_decode($outline, ENT_QUOTES),
                    'content' => (empty($content)) ? $post_info['content'] : htmlspecialchars_decode($content, ENT_QUOTES),
                    'author_id' => $post_info['author_id'],
                    'created_gmt' => $post_info['created_gmt'],
                    'modified_gmt' => $modify_time
                );
                $post->update_post($post_id, $data);
                $data['created_gmt']    = gmdate('Y-m-d H:i:s', $post_info['created_gmt']);
                $data['modified_gmt']   = gmdate('Y-m-d H:i:s', $modify_time);
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
                if (count($post_info) != 0) 
                {
                    $data   = array(
                        'id' => $post_info['id'],
                        'title' => $post_info['title'],
                        'outline' => $post_info['outline'],
                        'content' => $post_info['content'],
                        'author_id' => $post_info['author_id'],
                        'created_gmt' => gmdate('Y-m-d H:i:s', $post_info['created_gmt']),
                        'modified_gmt' => gmdate('Y-m-d H:i:s', $post_info['modified_gmt'])
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
                    Log::error('Getting information of post to edit failed because the post was not found');
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
            Log::debug('You cannot edit this post because of invalid token');
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
     * Show posts of a specified user
     * 
     * @link http://localhost/test-blog/posts Link to get_show method
     * @return mixed[] Content of API response
     */
    public function get_show() 
    {
        $arr_auth   = Auth::instance()->get_user_id();
        $user_id    = $arr_auth[1];
        $user       = new User();
        $token      = Input::get('token');
        $user_token = $user->get_token_user($user_id);
        $sstoken    = $user_token['login_hash'];
        if (!empty($token) && $token == $sstoken) 
        {
            // Check input is valid or invalid
            $author_id  = (int)Input::get('author_id');
            Log::debug('Inputted ID of user now = '.$author_id);
            
            if (empty($author_id) || $author_id <= 0) 
            {
                Log::debug('Get posts of specified user failed because of invalid ID of user');
                return $this->response(array(
                    'message' => array(
                        'code' => 401,
                        'text' => 'Invalid Input'
                    ),
                    'data' => null
                ));
            }
            $post   = new Post();
            $result = $post->get_user_posts($author_id);
            if (count($result) != 0) 
            {
                $data   = array();
                $i      = 0;
                // create data[] array
                foreach ($result as $item) 
                {
                    $data[$i]['id']             = $item['id'];
                    $data[$i]['title']          = $item['title'];
                    $data[$i]['outline']        = $item['outline'];
                    $data[$i]['content']        = $item['content'];
                    $data[$i]['author_id']      = $item['author_id'];
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
                Log::error('Get posts of specified user failed because this user was not found in database');
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
            Log::debug('You cannot get posts because of invalid token');
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
