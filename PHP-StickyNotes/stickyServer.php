<?php
	//Missing Comments sorry!
	session_start();
	session_regenerate_id();
	require_once('stickyDAO.php');
	$respose = null;

	if ($_SERVER['REQUEST_METHOD'] == 'POST')
	{
		$database = new StickyDAO();

		//Ajax message contains text = add new sticky to db.
		if (isset($_POST['text']))
		{	
			//Prevent XSS attack by cleaning user input.
			$text = htmlentities($_POST['text']);
			$userId = $_SESSION['userId'];

			$response[] = $database->createSticky($text,$userId);

			echo json_encode($response);
		}
		//Ajax message contains login = validate username and password match in db.
		if (isset($_POST['login']))
		{
			//Prevent XSS attack by cleaning user input.
			$email = htmlentities($_POST['email']);
			$password = htmlentities($_POST['password']);

			$result = $database->loginUser($email,$password);

			if ($result == false)
			{
				echo json_encode("not match");
			}
			else{
				echo json_encode("match");
				session_regenerate_id();
				$_SESSION['userId']=$result; 
				//TODO create a cookie to pass username around for welcome message.	
			}	

		}
		//AJax message contains register = check if name already exists otherwise add user.
		if (isset($_POST['register']))
		{
			//Prevent XSS attack by cleaning user input.
			$email = htmlentities($_POST['email']);
			$password = htmlentities($_POST['password']);

			//Check if user is in db.
			$resultCount = $database->checkIfUserExists($email);

			if ($resultCount > 0 )
			{
				echo json_encode($resultCount);	//Will return 1 signifying already existing user.	
			}
			else
			{
				$id = $database->createUser($email,$password);

				$_SESSION['userId']=$id;	
				//TODO create a cookie to pass username around for welcome message.
				echo json_encode("match");
				session_regenerate_id();		
			}

		}

		//Ajax message contains update = change position of sticky in db.
		if (isset($_POST['update']))
		{
			$id = $_POST['id'];
			$leftpos = $_POST['left'];
			$toppos = $_POST['top'];
			$zindex = $_POST['zindex'];

			$response = $database->updateStickyPosition($id,$leftpos,$toppos,$zindex);

			//currently just for testing
			echo json_encode($response);
		}

		//Ajax message contains delete = remove specified sticky from the db.
		if (isset($_POST['delete']))
		{
			$id = $_POST['id'];

			$response = $database->deleteSticky($id);

			echo json_encode($response);
		}
		//Ajax message contains getStickies = retrieve all stickies from db for the logged in user.
		if (isset($_POST['getStickies']))
		{	
			if (!isset($_SESSION['userId']))
			{
				echo json_encode("NO"); //Signifying not authorized as no session was found.
			}
			else
			{
				$id = $_SESSION['userId'];

				$response = $database->getStickiesForUser($id);

				echo json_encode($response);
			}		
		}

		if (isset($_POST['logout']))
		{	
			//"Better to be paranoid"-Jaya 

			// Unset all of the session variables.
			$_SESSION = array();

			// If it's desired to kill the session, also delete the session cookie.
			// Note: This will destroy the session, and not just the session data!
			if (ini_get("session.use_cookies")) {
			    $params = session_get_cookie_params();
			    setcookie(session_name(), '', time() - 42000,
			        $params["path"], $params["domain"],
			        $params["secure"], $params["httponly"]
			    );
			}

			// Finally, destroy the session.
			session_destroy();

			//Inform Javascript this you are not authorized to be on this page.
			echo json_encode("NO");

		}
		
	}