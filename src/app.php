<?php
	
	use Silex\Application;
	use Silex\Extension\DoctrineExtension;

	$app = new Application();

	$app->register(new Silex\Provider\DoctrineServiceProvider(), array(
	    'db.options' => array(
	        'driver'   	=> 'pdo_mysql',
	      	'dbname'	=> 'bank', 
	      	'host'		=> 'localhost', 
	      	'user'		=> 'root',
	      	'password' 	=> null,
	      	'charset'   => 'utf8mb4',
    	),
	));

	return $app;

?>