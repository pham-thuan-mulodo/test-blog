<?php
namespace Model;

class Post extends \Orm\Model 
{
    protected static $_table_name   = 'post';
    protected static $_properties   = array(
        'id',
        'title',
        'outline',
        'content',
        'author_id',
        'created_gmt',
        'modified_gmt'
    );
    
    public function create_post($data) 
    {
        $entry  = Post::find('all', array(
           'where' => array(
               array('title', '=', $data['title']),
               array('content', '=', $data['content'])
           )
        ));
        $post   = Post::forge($data);
        if(count($entry) == 0) 
        {
            $post->save();
            $result['status']   = 200;
            $result['text']     = '';
            $result['data']     = null;
        }
        else 
        {
            $result['status']   = 10301;
            $result['text']     = 'Post Existed';
            $result['data']     = null;
        }
        return $result;
    }
    
    public function delete_post($id) 
    {
        $entry  = Post::find($id);
        if(count($entry) != 0) 
        {
            $post   = Post::find($id);
            $result = $post->delete();
            $result['status']   = 200;
            $result['text']     = 'Delete successfully';
            $result['data']     = null;   
        }
        else 
        {
            $result['status']   = 404;
            $result['text']     = 'Not Found';
            $result['data']     = null;  
        }
        return $result;
    }
    
    public function get_post($id) 
    {
        $result = Post::find($id);
        return $result;
    }
    
    public function update_post($id, $data) 
    {
        $entry  = Post::find($id);
        $entry->set($data);
        $entry->save();
    }
    
    public function get_post_of_specific_user($id) 
    {
        $entry  = Post::find('all', array(
           'where' => array(
               array('author_id', $id)
           ) 
        ));
        return $entry;
    }
}

