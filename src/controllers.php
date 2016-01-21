<?php 

	use Symphony\Component\HttpFoundation\Request;
	use Symphony\Component\HttpFoundation\Response;
	use Symphony\Component\HttpFoundation\JsonResponse;
	use Symphony\Component\HttpFoundation\RedirectResponse;
	use Symphony\Component\HttpKernel\Exception\NotFoundHttpException;

	/* 	
		response for '/' 

		- get/put/post/delete: INVALID
	*/
	$app->match('/', function() use ($app){
        $error = array('message' => 'This is not a correct api-call.');
        return $app->json($error, 404);
	})
	->method('GET|PUT|POST|DELETE');


	/* 	
		response for '/payment' 

		- post: prepare payment, return the created paymentID
		- get/put/delete: INVALID
	*/
	$app->match('/payment', function() use ($app){

	    $sender = $app['request']->get('sender');
	    $receiver = $app['request']->get('receiver');
	    $amount = $app['request']->get('amount');
	    $description = $app['request']->get('description');

		if ($sender == null or $receiver == null or $amount == null or $description == null or !is_numeric($amount)){
			$error = array('message' => 'Invalid params.');
			return $app->json($error, 400);
		} else {
			$sql = "SELECT * FROM account WHERE (accountnumber = ? AND balance >= ?) OR accountnumber = ?";
			$post = $app['db']->fetchAll($sql, array($sender, $amount, $receiver));	

			if (count($post) == 2){
				if ($app['db']->insert('payment', array('sender' => $sender, 'receiver' => $receiver, 'amount' => $amount, 'description' => $description, 'date' => date('Y-m-d H:i:s') )) ){
					$id = $app['db']->lastInsertId();
					
					$message = array("message" => "payment prepared.", "id" => $id);
					return $app->json($message, 201);
				} else {
					$error = array('message' => 'Database error. If this error keeps occuring, contact the database admin');
					return $app->json($error, 500);
				}
			} else {
				$error = array('message' => 'Incorrect accountnumbers or insufficient fund in account sender.');
				return $app->json($error, 400);
			}
		}
	})
	->method('POST');
	$app->match('/payment', function() use ($app){
		$error = array('message' => 'This is not a correct api-call.');
		return $app->json($error, 404);
	})
	->method('PUT|GET|DELETE');


	/*
		response for /payement/{id}

		- get: specific payment info
		- put: update payment to payed if not payed yet
		- post/delete: INVALID
	*/
	$app->get('/payment/{id}', function ($id) use ($app) {
	    $sql = "SELECT * FROM payment WHERE id = ? AND status = 1";
	    $post = $app['db']->fetchAssoc($sql, array((int)$id));

	    if ($post == null){ 
	    	$error= array('message' => 'Incorrect paymentID.');
	    	return $app->json($error, 400);
	    } else {
	    	$message= array('message' => 'Requested payment information.', 
	    					'content' => 
		    				array(
								'sender' => $post['sender'], 
								'receiver' => $post['receiver'],
								'amount' => $post['amount'],
								'description' => $post['description'],
								'date' => $post['date'],
							));
	    	return $app->json($message, 200); 
	    }
	});
	$app->put('/payment/{id}', function($id) use ($app) {
		$sql = "SELECT * FROM payment WHERE id = ? AND status = 0";
		$post = $app['db']->fetchAssoc($sql, array((int)$id));

		if ($post == null){
			$error= array('message' => 'Incorrect paymentID or already paid.');
			return $app->json($error, 400);
		} else {
			$sql1 = "SELECT * FROM account WHERE accountnumber = ? AND balance >= ?";
			$post1 = $app['db']->fetchAssoc($sql1, array($post['sender'], $post['amount']));
			$sql2 = "SELECT * FROM account WHERE accountnumber = ?";
			$post2 = $app['db']->fetchAssoc($sql2, array($post['receiver']));

			if ($post1 == null OR $post2 == null){
				$error = array('message' => 'Incorrect accountnumber sender and/or receiver or balance sender too low.');
				return $app->json($error, 400);
			} else {
				
				// Get money from sender
				$newBalance = $post1['balance']-$post['amount'];
				$app['db']->update('account', array('balance' => $newBalance), array('accountnumber' => $post['sender']));
				// Give money to receiver
				$newBalance = $post2['balance']+$post['amount'];
				$app['db']->update('account', array('balance' => $newBalance), array('accountnumber' => $post['receiver']));
				// Update payment status
				$app['db']->update('payment', array('status' => 1), array('id' => $id));

				$message= array('message' => "Payment succeeded.");
				return $app->json($message, 200);
			}
		}
	});
	$app->match('/payment/{id}', function() use ($app){
		$error= array('message' => 'This is not a correct api-call');
		return $app->json($error, 404);
	})
	->method('POST|DELETE');

?>