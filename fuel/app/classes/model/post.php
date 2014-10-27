<?php
namespace Model;

/**
 * Post
 * 
 * @package Orm\Model 
 * @var Post Class contain some method do some transaction with post table
 */
class Post extends \Orm\Model 
{
    /**
     *
     * @var string Name of table connected
     */
    protected static $_table_name   = 'post';
    /**
     *
     * @var array array of columns in table connected
     */
    protected static $_properties   = array(
        'id',
        'title',
        'outline',
        'content',
        'author_id',
        'created_gmt',
        'modified_gmt'
    );
    
    /**
     * Insert a new post to database
     * 
     * @param mixed[] $data Structure of a post
     * @return mixed[] Body of message which API will return
     */
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
    
    /**
     * Delete a post out of database
     * 
     * @param int $id ID of post
     * @return mixed[] Body of message which API will return
     */
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
    
    /**
     * Get detail of a specific post
     * 
     * @param int $id ID of post
     * @return mixed[] Array information of a specific post
     */
    public function get_post($id) 
    {
        $result = Post::find($id);
        return $result;
    }
    
    /**
     * Update post to database
     * 
     * @param int $id ID of post
     * @param mixed[] $data Structure of post
     */
    public function update_post($id, $data) 
    {
        $entry  = Post::find($id);
        $entry->set($data);
        $entry->save();
    }
    
    /**
     * Get list posts of a specific user
     * 
     * @param int $id ID of user
     * @return mixed[] Array posts of a specific user
     */
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

