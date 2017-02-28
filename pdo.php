<?php
/*
 *  Copyright (C) 2017
 *  Mark Wickline Mark@GauntInteractive.com
 *  Writen originally for EdibleIndex.com
*/

class Mysql
{
	public $lastError;		//Holds the last error
	public $lastQuerry;		//Holds the last query
	public $result;			//Holds the MySWL query result
	public $records;		//Holds the total number of records returned
	public $affected;		//Holds the total number of recoreds affected 
	public $rayResults;		//Holds raw 'arrayed' results
	public $arrayedResult;	//Holds an array of the result

	private $hostName;		
	private $userName;		
	private $password;
	private $database;
	private $port = 3306;

	private $persistant; 	//Boolean -> is the conncection persistant or not. Set in the contstucutor
	private $pdo;			//Holds the connection object


	/* *******************
	 * Class Constructor *
	 * *******************/
	
	function __construct($database, $userName, $password, $hostName, $persistant){
		$this->databse = $database;
		$this->userName = $userName;
		$this->password = $password;
		$this->hostName = $hostName;
		$this->persistant = $persistant;

		//conncet to the database
		$this->connect();
	}

	/* *******************
	 * Class Destructor  *
	 * *******************/

	function __destruct(){
		$this->pdo = null;
	}

	/* *******************
	 * Private Functions *
	 * *******************/

	private function connect(){
		if(!$this->pdo){
			if($this->persistant){
				try{
					$this->$pdo = new PDO("mysql:host={$this->hostName};port={$this->port};dbname={$this->database};charset=utf8", $this->userName, $this->password, array(PDO::ATTR_PERSISTENT => true));
					return true;
				} catch(PDOException $e){
					$message = 'Error: ' . $e->getMessage() . "\n";
					$this->lastError = $message;
					print $messsage;
					die($message);
					return false;
				}
			}
			else{
				try{
					$this->$pdo = new PDO("mysql:host={$this->hostName};port={$this->port};dbname={$this->database};charset=utf8", $this->userName, $this->password);
					return true;
				} catch(PDOException $e){
					$message = 'Error: ' . $e->getMessage() . "\n";
					$this->lastError = $message;
					print $messsage;
					die($message);
					return false;
				}	
			}
		}
		else{
				$this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_WARNING);
				return true;
		}
	}



}




?>