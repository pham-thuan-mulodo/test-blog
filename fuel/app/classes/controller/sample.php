<?php
use Fuel\Core\Controller_Rest;

class Controller_Sample extends Controller_Rest {
	public function post_users() {
		$result = Model_Sample::get_users();
		print_r($result);exit;
	}
}