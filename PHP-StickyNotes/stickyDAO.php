<?php
	require_once('sticky.php');
	//Missing Comments sorry!
	/**
	 * Class StickyDAO
	 * This is the Database Access class for the stickydb database, used to create DAO Objects.
	 * This class will make modifications to both the stickynotes and stickyuser tables.
	 * @todo This class should implement an interface in the future.
	 * This class will establish a connection with the database and perform query/update/delete commands against it.
	 * This class makes use of the default root user and empty password in mysql -> fields can be changed.
	 * This class also makes use of PDO interface - PHP Data Objects for connections, attributes, and preprared statements.
	 * All queries made against the database are prepared to prevent against SQL Injection attacks.
	 * @author Christopher Dufort
	 * @version 1.0.0 Release
	 * @since 5.5.X PHP
	 */
	class StickyDAO {

		//Private fields.
		private $connectString; //Connection string including DB type, host, and db name.
		private $user;			//Username to connect to the db.
		private $password;		//Password associated with user to connect to db.

		/**
		 * This constructor is specifically tailored to a particular language/table/user credentials.
		 * @see create_stickydb.sql - an associated script used to create the database and tables.
		 */
		function __construct() {
			$this->connectString ="mysql:host=localhost;dbname=stickydb";
			$this->user = "root";
			$this->password = "";
		}

		public function createSticky($text, $owner){
			//Try-Catch-Finally block used to ensure the release of connection resources and error handling.
			try{
				$pdo = new PDO($this->connectString, $this->user, $this->password);
				$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

				//Prevent SQL Injection attacks
				$stmt = $pdo->prepare("INSERT INTO stickynotes(text,owner) VALUES(:text,:owner);");

				$stmt->bindValue(':text', $text);
				$stmt->bindValue(':owner', $owner); 
				$stmt->execute();

				//Retrieve the unique id of the record that was just entered.
				$id = $pdo->lastInsertId();

				//Make a new sticky object.
				$sticky = new Sticky($id, $text);

				//Make an associative array of a sticky
			    $responseRow["id"]=$sticky->getId(); 
			    $responseRow["text"]=$sticky->getText();
			    $responseRow["leftpos"]=$sticky->getLeftpos();
			    $responseRow["toppos"]=$sticky->getToppos();
			    $responseRow["zindex"]=$sticky->getZindex();

				return $response[] = $responseRow;

			} catch (PDOException $e){
				echo($e->getMessage()); 
			} finally {
				unset($pdo);
			}		
		}

		public function checkIfUserExists($email){
			//Try-Catch-Finally block used to ensure the release of connection resources and error handling.
			try{
				$pdo = new PDO($this->connectString, $this->user, $this->password);
				$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

				//Prevent SQL Injection attacks
				$stmt = $pdo->prepare("SELECT id FROM stickyuser WHERE email = :email;");

				$stmt->bindValue(':email', $email);
				$stmt->execute();

				$resultCount = $stmt->rowCount();

				return $resultCount;

			} catch (PDOException $e){
				echo($e->getMessage()); 
			} finally {
				unset($pdo);
			}
		}

		public function createUser($email, $password){
			//Try-Catch-Finally block used to ensure the release of connection resources and error handling.
			try{
				$pdo = new PDO($this->connectString, $this->user, $this->password);
				$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

				//Prevent SQL Injection attacks
				$stmt = $pdo->prepare("INSERT INTO stickyuser(email,password) VALUES(:email, :pass);");

				$stmt->bindValue(':email', $email);
				$stmt->bindValue(':pass', password_hash($password, PASSWORD_DEFAULT));

				$stmt->execute();

				$id = $pdo->lastInsertId();

				return $id;	

			}catch (PDOException $e){
				echo($e->getMessage()); 
			} finally {
				unset($pdo);
			}
		}

		public function loginUser($email,$password){
			//Try-Catch-Finally block used to ensure the release of connection resources and error handling.
			try{
				$pdo = new PDO($this->connectString, $this->user, $this->password);
				$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

				//Prevent SQL Injection attacks
				$stmt = $pdo->prepare("SELECT password, id FROM stickyuser WHERE email = :email");

				$stmt->bindValue(':email', $email);
				
				$stmt->execute();

				//Fetch an associative array of password and id.
				$row= $stmt->fetch(PDO::FETCH_ASSOC);

				//If passwords match return the id for session.
				if (password_verify($password, $row['password'] )){
					return $row['id']; ;
					//reset login counter and time.
				}
				else{
					return false;
					//add login counter and update time.
				}

			}catch (PDOException $e){
				echo($e->getMessage()); 
			} finally {
				unset($pdo);
			}
		}

		public function updateStickyPosition($id,$leftpos,$toppos,$zindex){
			//Try-Catch-Finally block used to ensure the release of connection resources and error handling.
			try{
				$pdo = new PDO($this->connectString, $this->user, $this->password);
				$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

				//Prevent SQL Injection attacks
				$stmt = $pdo->prepare("UPDATE stickynotes 
					SET leftpos=:left, toppos=:top, zindex=:zindex WHERE id=:id");

				$stmt->bindValue(':id', $id);
				$stmt->bindValue(':left', $leftpos);
				$stmt->bindValue(':top', $toppos);
				$stmt->bindValue(':zindex', $zindex);
				$stmt->execute();
				$rows = $stmt->rowCount();

				//following just for testing
				$response["id"]=$id;
			    $response["leftpos"]=$leftpos;
			    $response["toppos"]=$toppos;
			    $response["zindex"]=$zindex;
			    $response["rowsInserted"]=$rows;

			    return $response;

			} catch (PDOException $e){
				echo($e->getMessage()); 
			} finally {
				unset($pdo);
			}
		}

		public function deleteSticky($id){
			//Try-Catch-Finally block used to ensure the release of connection resources and error handling.
			try{
				$pdo = new PDO($this->connectString, $this->user, $this->password);
				$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

				//Prevent SQL Injection attacks
				$stmt = $pdo->prepare("DELETE FROM stickynotes WHERE id=:id;");

				$stmt->bindValue(':id', $id);
				$good = $stmt->execute();

				//following just for testing
				$response["success"]=$good;

				return $response;	

			} catch (PDOException $e){
				echo($e->getMessage()); 
			} finally {
				unset($pdo);
			}
		}

		public function getStickiesForUser($id){
			//Try-Catch-Finally block used to ensure the release of connection resources and error handling.
			try{
				$pdo = new PDO($this->connectString, $this->user, $this->password);
				$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

				//Prevent SQL Injection attacks
				$stmt = $pdo->prepare("SELECT * FROM stickynotes WHERE owner=:ownerid");

				$stmt->bindValue(':ownerid', $id);
				$stmt->execute();


				$stmt->setFetchMode(PDO::FETCH_CLASS|PDO::FETCH_PROPS_LATE, 'Sticky');

				while ($sticky = $stmt->fetch())
				{
					//Make an associative array of a sticky
				    $responseRow["id"] = $sticky->getId(); 
				    $responseRow["text"] = $sticky->getText();
				    $responseRow["leftpos"] = $sticky->getLeftpos()."px";
				    $responseRow["toppos"] = $sticky->getToppos()."px";
				    $responseRow["zindex"] = $sticky->getZindex();
				    //apend array to an array of arrays
				    $response[] = $responseRow;
				}

				//This will return null if there are no existing stickies for user(no affect to user).
				return $response;

			} catch (PDOException $e){
				echo($e->getMessage()); 
			} finally {
				unset($pdo);
			}
		}
	}