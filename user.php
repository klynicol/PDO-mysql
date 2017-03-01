<?php
/*
 *  Copyright (C) 2017
 *  Mark Wickline Mark@GauntInteractive.com
 *  Writen originally for EdibleIndex.com
*/



class User{

	public $error; //Holds the errors

	public $lastQuery;

	private $db;
	private $table = 'userInfo';

	function __construct($con){
		$this->db = $con;
	}

	public function register($nick, $email, $pass){ //Registers a new user on the website
		try{
			//$new_password = password_hash($pass, PASSWORD_DEFAULT); This doesnt work with PHP 5.4. Use when server get upgraded to 5.5 or later
			$query = $this->db->prepare("INSERT INTO $this->table (nick, email, pwd, rdate) VALUES (:nickName, :email, :password, NOW())");

			$query->bindParam(":nickName", $nick);
				$query->bindParam(":email", $email);
				$query->bindParam(":password", $pass); //Change $pass to $new_password after the update to 5.5

				$query->execute();
				return $query;
		}
		catch(PDOException $e){
	        $this->error .=  "<br>Error: " . $e->getMessage();
	    } 
	}

	public function login($nick, $email, $pass){
		try{
			$query = $this->db->prepare("SELECT * FROM userInfo WHERE nick = :nick OR email = :email LIMIT 1");
			$query->execute(array(':nick'=>$nick, ':email'=>$email));
			$userInQuestion = $query->fetch(PDO::FETCH_ASSOC);

			$this->lastQuery = $userInQuestion; //Store the Query as last query

			if ($query->rowCount() > 0){
				if($pass == $userInQuestion['pwd']){
					return $userInQuestion['nick'];

					/*
					******************
					TODO: handle user login
					******************
					*/
				}
				else{
					return "Nickname/Email or Password is incorrect";
				} 
			}
			else{
				return "Query returned nothing";
			}
		}
		catch(PDOException $e){
	        $this->error .=  "<br>Error: " . $e->getMessage();
	    }
	}

	public function redirect($url){
		header("Location: $url");
	}
}