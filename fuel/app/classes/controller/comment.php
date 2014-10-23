<?php
use Fuel\Core\Session;
use Fuel\Core\Controller_Rest;
use Auth\Auth;
use Fuel\Core\Input;
use Fuel\Core\Security;
use Fuel\Core\Date;
use Model\Comment;

class Controller_Comment extends Controller_Rest {
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
    
    public function post_add() {
        if(Auth::check() && !empty(Session::get('token'))) {
            // get id of author
            $arr_auth = Auth::instance()->get_user_id();
            $author_id = $arr_auth[1];
            // get inputs
            $post_id = (int)Input::post('post_id');
            $content = Security::strip_tags(Security::xss_clean(Input::post('content')));
            $time = time();
            $comment = new Comment();
            
            if ($post_id > 0 && !empty($post_id) && !empty($content)) {
                $flag = $comment->isExistedPost($post_id);
                if($flag == 1) {
                   $data = array(
                        'content' => $content,
                        'author_id' => $author_id,
                        'post_id' => $post_id,
                        'created_gmt' => $time,
                        'modified_gmt' => 0
                    );
                    $result = $comment->addComment($data);
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
                            'status' => 10300,
                            'text' => 'Database Exception'
                        ),
                        'data' => NULL
                    ));
                }
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
    
    public function delete_remove() {
        if(Auth::check() && !empty(Session::get('token'))) {
            $comm_id = (int) Input::delete('id');
            if (empty($comm_id) || $comm_id <= 0) {
                return $this->response(array(
                    'message' => array(
                        'status' => 404,
                        'text' => 'Not Found'
                    ),
                    'data' => NULL
                ));
            } 
            else {
                $comment = new Comment();
                $result = $comment->deleteComment($comm_id);
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
    
    public function get_show() {
        if(Auth::check() && !empty(Session::get('token'))) {
            // Check input is valid or invalid
            $post_id = (int)Input::get('post_id');
            if(empty($post_id) || $post_id <= 0) {
                return $this->response(array(
                    'message' => array(
                        'status' => 401,
                        'text' => 'Invalid Input'
                    ),
                    'data' => NULL
                ));
            }
            $comment = new Comment();
            $result = $comment->getCommentsOfSpecifiedPost($post_id);
            if(count($result) != 0) {
                $data = array();
                $i = 0;
                foreach($result as $item) {
                    $data[$i]['id'] = $item['id'];
                    $data[$i]['content'] = $item['content'];
                    $data[$i]['author_id'] = $item['author_id'];
                    $data[$i]['post_id'] = $item['post_id'];
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

