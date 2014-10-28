<?php

use Fuel\Core\Session;
use Fuel\Core\Controller_Rest;
use Auth\Auth;
use Fuel\Core\Input;
use Fuel\Core\Security;
use Model\Post;

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
        if (Auth::check() && !empty(Session::get('token'))) 
        {
            echo '';
        } 
        else 
        {
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
        if (Auth::check() && !empty(Session::get('token'))) 
        {
            // get id of author
            $arr_auth   = Auth::instance()->get_user_id();
            $author_id  = $arr_auth[1];
            // get inputs
            $title      = Security::strip_tags(Security::xss_clean(Input::post('title')));
            $outline    = Security::strip_tags(Security::xss_clean(Input::post('outline')));
            $content    = Security::strip_tags(Security::xss_clean(Input::post('content')));
            $time       = time();

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
                return $this->response(array(
                    'message' => array(
                        'code' => 401,
                        'text' => 'Invalid Input'
                    ),
                    'data' => null
                ));
            }
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
        if (Auth::check() && !empty(Session::get('token'))) 
        {
            $post_id    = (int)Input::delete('id');
            if (empty($post_id) || $post_id <= 0) 
            {
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
    }
    
    /**
     * Do edit action when user edit a post
     * 
     * @link http://localhost/test-blog/post_edit Link to put_edit method
     * @return mixed[] Content of API response
     */
    public function put_edit() 
    {
        if (Auth::check() && !empty(Session::get('token'))) 
        {
            // Check input is valid or invalid
            $post_id    = (int)Input::put('id');
            if (empty($post_id) || $post_id <= 0) 
            {
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
            $result      = $post->get_post($post_id);
            // Get input
            $title       = Security::strip_tags(Security::xss_clean(Input::put('title')));
            $outline     = Security::strip_tags(Security::xss_clean(Input::put('outline')));
            $content     = Security::strip_tags(Security::xss_clean(Input::put('content')));
            $modify_time = time();

            // Check edited post, if post isn't edited, update post to db, then update modified time
            // if title, outline and content of post inputted is same with db, it won't be updated
            if (Input::put('id') && ((!empty($title) && $title != $result['title']) || (!empty($outline) && $outline != $result['outline']) || (!empty($content) && $content != $result['content']))) 
            {
                $data   = array(
                    'id' => $result['id'],
                    'title' => (empty($title)) ? $result['title'] : htmlspecialchars_decode($title, ENT_QUOTES),
                    'outline' => (empty($outline)) ? $result['outline'] : htmlspecialchars_decode($outline, ENT_QUOTES),
                    'content' => (empty($content)) ? $result['content'] : htmlspecialchars_decode($content, ENT_QUOTES),
                    'author_id' => $result['author_id'],
                    'created_gmt' => $result['created_gmt'],
                    'modified_gmt' => $modify_time
                );
                $post->update_post($post_id, $data);
                $data['created_gmt']    = gmdate('Y-m-d H:i:s', $result['created_gmt']);
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
                if (count($result) != 0) 
                {
                    $data   = array(
                        'id' => $result['id'],
                        'title' => $result['title'],
                        'outline' => $result['outline'],
                        'content' => $result['content'],
                        'author_id' => $result['author_id'],
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
    }
    
    /**
     * Show posts of a specified user
     * 
     * @link http://localhost/test-blog/posts Link to get_show method
     * @return mixed[] Content of API response
     */
    public function get_show() 
    {
        if (Auth::check() && !empty(Session::get('token'))) 
        {
            // Check input is valid or invalid
            $author_id  = (int)Input::get('author_id');
            if (empty($author_id) || $author_id <= 0) 
            {
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
}
