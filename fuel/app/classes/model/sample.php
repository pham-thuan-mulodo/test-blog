<?php

class Model_Sample extends \Orm\Model {
	/**
	 *
	 * @var array array of columns in table connected
	 */
	protected static $_properties   = array('id', 'email', 'password', 'username', 'last_login', 'login_hash', 'group', 'profile_fields', 'created_gmt', 'modified_gmt');
	
	/**
	 *
	 * @var string Name of table connected
	*/
	protected static $_table_name   = 'user';
	
	public static function get_users() {
		$entry = Model_Sample::find(3);
		return $entry;
	}
}