<?php
return array(
	'_root_'  => 'welcome/index',  // The default route
	'_404_'   => 'welcome/404',    // The main 404 route
	'hello(/:name)?' => array('welcome/hello', 'name' => 'hello'),
//        Config router for user controller
        'user' => 'user/login',
        'user_logout' => 'user/logout',
        'user_register' => 'user/register',
        'user_edit' => 'user/edit'
);