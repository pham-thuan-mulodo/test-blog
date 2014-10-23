<?php
namespace Model;

class Post extends \Orm\Model {
    protected static $_table_name = 'post';
    protected static $_properties = array(
        'id',
        'title',
        'outline',
        'content',
        'author_id',
        'created_gmt',
        'modified_gmt'
    );
    
    public function createPost($data) {
        $entry = Post::find('all', array(
           'where' => array(
               array('title', '=', $data['title']),
               array('content', '=', $data['content'])
           )
        ));
        $post = Post::forge($data);
        if(count($entry) == 0) {
            $post->save();
            $result['status'] = 200;
            $result['text'] = '';
            $result['data'] = NULL;
        }
        else {
            $result['status'] = 10301;
            $result['text'] = 'Post Existed';
            $result['data'] = NULL;
        }
        return $result;
    }
    
    public function deletePost($id) {
        $entry = Post::find($id);
        if(count($entry) != 0) {
            $post = Post::find($id);
            $result = $post->delete();
            $result['status'] = 200;
            $result['text'] = 'Delete successfully';
            $result['data'] = NULL;   
        }
        else {
            $result['status'] = 404;
            $result['text'] = 'Not Found';
            $result['data'] = NULL;  
        }
        return $result;
    }
    
    public function getPost($id) {
        $result = Post::find($id);
        return $result;
    }
    
    public function updatePost($id, $data) {
        $entry = Post::find($id);
        $entry->set($data);
        $entry->save();
    }
    
    public function getPostOfSpecificUser($id) {
        $entry = Post::find('all', array(
           'where' => array(
               array('author_id', $id)
           ) 
        ));
        return $entry;
    }
}

