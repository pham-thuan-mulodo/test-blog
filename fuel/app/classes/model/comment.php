<?php
namespace Model;
use Fuel\Core\DB;
use Fuel\Core\Log;
use Exception;
/**
 * Comment
 * 
 * @package Orm\Model
 * @var Comment Class contain some method do some transaction with comment table
 */
class Comment extends \Orm\Model 
{
    /**
     *
     * @var string Name of table connected
     */
    protected static $_table_name   = 'comment';
    
    /**
    *
    * @var array array of columns in table connected
    */
    protected static $_properties   = array(
        'id',
        'content',
        'author_id',
        'post_id',
        'created_gmt',
        'modified_gmt'
    );
    
    /**
     * Insert a new comment to database
     * 
     * @param mixed[] $data Structure of comment
     * @return mixed[] Body of message which API response will return
     */
    public function add_comment($data) 
    {
        try 
        {
            $entry      = Comment::find('all', array(
                'where' => array(
                    array('content', '=', $data['content']),
                    array('author_id', '=', $data['author_id']),
                    array('post_id', '=', $data['post_id'])
                )
            ));
            $comment    = Comment::forge($data);
            if(count($entry) == 0) 
            {
                $comment->save();
                $comm_id = $comment->id;
                $comm_info = $comment->get_comment($comm_id);
                $result['data']		= array(
                		'id' => $comm_info['id'],
                		'content' => $comm_info['content'],
                		'author_id' => $comm_info['author_id'],
                		'post_id' => $comm_info['post_id'],
                		'created_gmt' => gmdate('Y-m-d H:i:s', $comm_info['created_gmt']),
                		'modified_gmt' => gmdate('Y-m-d H:i:s', $comm_info['modified_gmt'])
                );
                $result['status'] = 200;
                $result['text'] = 'Comment was added successfully';
            }
            else 
            {
                $result['status'] = 10401;
                $result['text'] = 'Comment Existed';
                $result['data'] = null;
                Log::error('Add a comment failed because comment for this post existed');
            }
            return $result;
        } 
        catch (Exception $ex) 
        {
            Log::error($ex->getMessage());
        }
    }
    
    /**
     * Check a post existed or not
     * 
     * @param int $id ID of post
     * @return int Number of records returned from post table
     */
    public function is_existed_post($id) 
    {
        try
        {
            $post   = DB::select()->from('post')->where('id', $id)->execute();
            return count($post);
        } 
        catch (Exception $ex) 
        {
            Log::error($ex->getMessage());
        }
    }
    
    /**
     * Delete a comment out of database
     *  
     * @param int $id ID of comment
     * @return mixed[] Body of message which API response will return
     */
    public function delete_comment($id) 
    {
        try
        {
            // Check comment is exist or not exist
            $entry  = Comment::find($id);
            if(count($entry) != 0) 
            {
                $comment = Comment::find($id);
                $result  = $comment->delete();
                $result['status']   = 200;
                $result['text']     = 'Delete successfully';
                $result['data']     = null;
            }
            else 
            {
                $result['status']   = 404;
                $result['text']     = 'Not Found';
                $result['data']     = null;
                Log::error('Deleting comment failed because the comment was not found');
            }
            return $result;
        } 
        catch (Exception $ex) 
        {
            Log::error($ex->getMessage());
        }
    }
    
    /**
     * Get all comments for specified post
     * 
     * @param int $post_id ID of post
     * @return mixed[] Detail information of comments for a specified post
     */
    public function get_comments_post($post_id) 
    {
        try
        {
           $entry  = Comment::find('all', array(
                'where' => array(
                    array('post_id', $post_id)
                ) 
            ));
            return $entry; 
        } 
        catch (Exception $ex) 
        {
            Log::error($ex->getMessage());
        }
    }
    
    /**
     * Get a specified comment
     * 
     * @param int $id ID of comment
     * @return mixed[] Detail information of a comment
     */
    public function get_comment($id) 
    {
        try
        {
            $result = Comment::find($id);
            return $result;
        } 
        catch (Exception $ex) 
        {
            Log::error($ex->getMessage());
        }
    }
    
    /**
     * Update comment to database
     * 
     * @param int $id ID of comment
     * @param mixed[] $data Structure of comment
     */
    public function update_comment($id, $data) 
    {
        try
        {
            $entry  = Comment::find($id);
            $entry->set($data);
            $entry->save();
        } 
        catch (Exception $ex) 
        {
            Log::error($ex->getMessage());
        }
    }
}

