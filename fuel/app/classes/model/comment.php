<?php
namespace Model;
use Fuel\Core\DB;

class Comment extends \Orm\Model {
    protected static $_table_name = 'comment';
    protected static $_properties = array(
        'id',
        'content',
        'author_id',
        'post_id',
        'created_gmt',
        'modified_gmt'
    );
    
    public function addComment($data) {
        $entry = Comment::find('all', array(
           'where' => array(
               array('content', '=', $data['content']),
               array('author_id', '=', $data['author_id']),
               array('post_id', '=', $data['post_id'])
           )
        ));
        $comment = Comment::forge($data);
        if(count($entry) == 0) {
            $comment->save();
            $result['status'] = 200;
            $result['text'] = '';
            $result['data'] = NULL;
        }
        else {
            $result['status'] = 10401;
            $result['text'] = 'Comment Existed';
            $result['data'] = NULL;
        }
        return $result;
    }
    
    public function isExistedPost($id) {
        $post = DB::select()->from('post')->where('id', $id)->execute();
        return count($post);
    }
    
    public function deleteComment($id) {
        $comment = Comment::find($id);
        $result = $comment->delete();
        if(empty($result)) {
            $result['status'] = 10300;
            $result['text'] = 'Database Exception';
            $result['data'] = NULL;
        }
        else {
            $result['status'] = 200;
            $result['text'] = 'Delete successfully';
            $result['data'] = NULL;
        }
        return $result;
    }
}

