<?php

use Fuel\Core\Session;
use Fuel\Core\Controller_Rest;
use Auth\Auth;
use Fuel\Core\Input;
use Fuel\Core\Security;
use Fuel\Core\Date;
use Model\Post;

class Controller_Post extends Controller_Rest {

    protected $format = 'json';

    public function before() {
        parent::before();
        if (Auth::check() && !empty(Session::get('token'))) {
            echo '';
        } else {
            return $this->response(array(
                'message' => array(
                    'status' => 10301,
                    'text' => 'Please login'
                ),
                'data' => NULL
            ));
        }
    }

    public function post_create() {
        if (Auth::check() && !empty(Session::get('token'))) {
            // get id of author
            $arr_auth = Auth::instance()->get_user_id();
            $author_id = $arr_auth[1];
            // get inputs
            $title = Security::strip_tags(Security::xss_clean(Input::post('title')));
            $outline = Security::strip_tags(Security::xss_clean(Input::post('outline')));
            $content = Security::strip_tags(Security::xss_clean(Input::post('content')));
            $time = time();
          
            if (!empty($title) && !empty($content)) {
                $data = array(
                    'title' => $title,
                    'outline' => $outline,
                    'content' => $content,
                    'author_id' => $author_id,
                    'created_gmt' => $time,
                    'modified_gmt' => 0
                );
                $post = new Post();
                $result = $post->createPost($data);
                return $this->response(array(
                    'message' => array(
                        'status' => $result['status'],
                        'text' => $result['text']
                    ),
                    'data' => NULL
                ));
            } else {
                return $this->response(array(
                    'message' => array(
                        'status' => 401,
                        'text' => 'Invalid Input'
                    ),
                    'data' => NULL
                ));
            }
        }
    }

    public function delete_delete() {
        if (Auth::check() && !empty(Session::get('token'))) {
            $post_id = (int) Input::delete('id');
            if (empty($post_id) || $post_id <= 0) {
                return $this->response(array(
                    'message' => array(
                        'status' => 404,
                        'text' => 'Not Found'
                    ),
                    'data' => NULL
                ));
            } 
            else {
                $post = new Post();
                $result = $post->deletePost($post_id);
                return $this->response(array(
                    'message' => array(
                        'status' => $result['status'],
                        'text' => $result['text']
                    ),
                    'data' => $result['data']
                ));
            }
        }
    }

    public function put_edit() {
        if (Auth::check() && !empty(Session::get('token'))) {
            // Check input is valid or invalid
            $post_id = (int) Input::put('id');
            if (empty($post_id) || $post_id <= 0) {
                return $this->response(array(
                    'message' => array(
                        'status' => 401,
                        'text' => 'Invalid Input'
                    ),
                    'data' => NULL
                ));
            }

            // Get post
            $post = new Post();
            $result = $post->getPost($post_id);
            // Get input
            $title = Security::strip_tags(Security::xss_clean(Input::put('title')));
            $outline = Security::strip_tags(Security::xss_clean(Input::put('outline')));
            $content = Security::strip_tags(Security::xss_clean(Input::put('content')));
            $modify_time = time();

            if (Input::put('id') && (!empty($title) || !empty($outline) || !empty($content))) {
                $data = array(
                    'id' => $result['id'],
                    'title' => (empty($title)) ? $result['title'] : $title,
                    'outline' => (empty($outline)) ? $result['outline'] : $outline,
                    'content' => (empty($content)) ? $result['content'] : e($content),
                    'author_id' => $result['author_id'],
                    'created_gmt' => $result['created_gmt'],
                    'modified_gmt' => $modify_time
                );
                $post->updatePost($post_id, $data);
                $data['created_gmt'] = gmdate('Y-m-d H:i:s', $result['created_gmt']);
                $data['modified_gmt'] = gmdate('Y-m-d H:i:s', $modify_time);
                return $this->response(array(
                    'message' => array(
                        'status' => 200,
                        'text' => 'Updated Successfully'
                    ),
                    'data' => $data
                ));
            } 
            else {
                if (count($result) != 0) {
                    $data = array(
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
                            'status' => 200,
                            'text' => ''
                        ),
                        'data' => $data
                    ));
                }
                else {
                    return $this->response(array(
                        'message' => array(
                            'status' => 404,
                            'text' => 'Not Found'
                        ),
                        'data' => NULL
                    ));
                }
            }
        }
    }
    
    public function get_show() {
        if(Auth::check() && !empty(Session::get('token'))) {
            // Check input is valid or invalid
            $author_id = (int)Input::get('author_id');
            if(empty($author_id) || $author_id <= 0) {
                return $this->response(array(
                    'message' => array(
                        'status' => 401,
                        'text' => 'Invalid Input'
                    ),
                    'data' => NULL
                ));
            }
            $post = new Post();
            $result = $post->getPostOfSpecificUser($author_id);
            if(count($result) != 0) {
                $data = array();
                $i = 0;
                foreach($result as $item) {
                    $data[$i]['id'] = $item['id'];
                    $data[$i]['title'] = $item['title'];
                    $data[$i]['outline'] = $item['outline'];
                    $data[$i]['content'] = $item['content'];
                    $data[$i]['author_id'] = $item['author_id'];
                    $data[$i]['created_gmt'] = gmdate('Y-m-d H:i:s', $item['created_gmt']);
                    $data[$i]['modified_gmt'] = gmdate('Y-m-d H:i:s', $item['modified_gmt']);
                    $i++;
                }
                return $this->response(array(
                    'message' => array(
                        'status' => 200,
                        'text' => ''
                    ),
                    'data' => $data
                ));
            }
            else {
                return $this->response(array(
                    'message' => array(
                        'status' => 404,
                        'text' => 'Not Found'
                    ),
                    'data' => NULL
                ));
            }
            exit; 
        }     
    }
}
