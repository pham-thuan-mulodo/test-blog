<?php

use Fuel\Core\Session;
use Fuel\Core\Controller_Rest;
use Auth\Auth;
use Fuel\Core\Input;
use Fuel\Core\Security;
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
            } 
            else {
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
}
