<?php

//this is where you enter all the server / host / db credentials.
$host = '127.0.0.1';
$db   = 'test';
$user = 'root';
$pass = '';
$port = "3306";
$charset = 'utf8mb4';
$logout_destination_url = 'index.php';

$options = [
    \PDO::ATTR_ERRMODE            => \PDO::ERRMODE_EXCEPTION,
    \PDO::ATTR_DEFAULT_FETCH_MODE => \PDO::FETCH_ASSOC,
    \PDO::ATTR_EMULATE_PREPARES   => false,
];
$dsn = "mysql:host=$host;dbname=$db;charset=$charset;port=$port";
try {
     $conn = new \PDO($dsn, $user, $pass, $options);
} catch (\PDOException $e) {
     throw new \PDOException($e->getMessage(), (int)$e->getCode());
}
//To run the queries on other pages, user $conn->


function page_protect() {
	session_start();

	global $db; 

	/* Secure against Session Hijacking by checking user agent */
	if (isset($_SESSION['HTTP_USER_AGENT']))
	{
	    if ($_SESSION['HTTP_USER_AGENT'] != md5($_SERVER['HTTP_USER_AGENT']))
	    {
		logout();
		exit;
	    }
	}

	// BEFORE WE ALLOW THE SESSIONS, WE NEED TO CHECK AUTHENTICATION KEY STORED IN OUR DATABASE

	/* If session not set, check for cookies set by Remember me */
	if (!isset($_SESSION['user_id']) && !isset($_SESSION['user_name']) ) 
	{
		if(isset($_COOKIE['user_id']) && isset($_COOKIE['user_key'])){
		/* we double check cookie expiry time against stored in database */

		$cookie_user_id  = filter($_COOKIE['user_id']);

		$stmt = $conn->prepare("SELECT `ckey`,`ctime`,`user_level` FROM `members_users` WHERE `id` = :cookie_user_id");
		$stmt->bindParam(':cookie_user_id',$cookie_user_id, PDO::PARAM_STR);
		$stmt->execute();	
		$datasession = $stmt->fetchAll();

		foreach($datasession as $listitemsession){
			$ckey = $listitemsession['ckey'];
			$ctime = $listitemsession['ctime'];      

		}

		// coookie expiry
		if( (time() - $ctime) > 60*60*24*COOKIE_TIME_OUT) {

			logout();
			}

		/* Security check with untrusted cookies - dont trust value stored in cookie. 		
		/* We also do authentication check of the `ckey` stored in cookie matches that stored in database during login*/

		 if( !empty($ckey) && is_numeric($_COOKIE['user_id']) && isUserID($_COOKIE['user_name']) && $_COOKIE['user_key'] == sha1($ckey)  ) {
			  session_regenerate_id(); //against session fixation attacks.

			  $_SESSION['user_id'] = $_COOKIE['user_id'];
			  $_SESSION['user_name'] = $_COOKIE['user_name'];

			$session_user_id = $_SESSION['user_id'];

			/* query user level from database instead of storing in cookies */	
			$stmt = $conn->prepare("SELECT `user_level` FROM `members_users` WHERE `id` = :session_user_id");
			$stmt->bindParam(':session_user_id',$session_user_id, PDO::PARAM_STR);
			$stmt->execute();	
			$data_set = $stmt->fetchAll();

			foreach($babasession_set as $listitemsession_set)
			{
				$user_level = $listitemsession_set['user_level'];			
			}

			  $_SESSION['user_level'] = $user_level;
			  $_SESSION['HTTP_USER_AGENT'] = md5($_SERVER['HTTP_USER_AGENT']);

		   } else {
		   logout();
		   }

	  } else {
		header("Location: $logout_destination_url");
		exit();
		}
	}
}

function logout()
{
	global $conn;
	session_start();
	$sess_user_id = $_SESSION['user_id'];
	$cook_user_id = isset($_COOKIE['user_id']);
	if(isset($sess_user_id) || isset($cook_user_id)) {
		$sql_logout = "UPDATE members_users SET `ctime`='', `ckey` = '' WHERE `id`='$sess_user_id' OR  `id` = '$cook_user_id'";
		$stmt = $conn->prepare($sql_logout);
		$stmt->execute();	
	}		

	/************ Delete the sessions****************/
	unset($_SESSION['user_id']);
	unset($_SESSION['user_name']);
	unset($_SESSION['user_level']);
	unset($_SESSION['HTTP_USER_AGENT']);
	session_unset();
	session_destroy(); 

	/******************** Delete the cookies ********************/
	setcookie("user_id", '', time()-(60*60*24* $sessiontimeout), "/");
	setcookie("user_name", '', time()-(60*60*24* $sessiontimeout), "/");
	setcookie("user_key", '', time()-(60*60*24* $sessiontimeout), "/");
	header("Location: $logout_destination_url");
}


// ******************************************
// User Defined Functions - Start
//*******************************************


// f_checkusername - This validates alphanumeric usernames with min length of 5 chars. and max 20 chars. No Special Characters
function f_checkusername($username)
{
	if (preg_match('/^[a-z\d_]{5,20}$/i', $username)) {
		return true;
	} else {
		return false;
	}
}	
	

// f_checkemail - This validates email address syntax only.
function f_checkemail($email){
  return preg_match('/^\S+@[\w\d.-]{2,}\.[\w]{2,6}$/iU', $email) ? TRUE : FALSE;
}


// f_checkpasswordentry validates if 'password' and 're-enter password' are exactly same and also atleast 8 characters. No password complexity validation
function f_checkpasswordentry($x,$y) 
{
        if(empty($x) || empty($y) ) { return false; }
        if (strlen($x) < 8 || strlen($y) < 8) { return false; }
        if (strcmp($x,$y) != 0) {
            return false;
        } 
            return true;
}	


// f_generatepassword  - Generates a password with min 8 characters
function f_generatepassword($length = 8)
{
  $password = "";
  $possible = "0123456789bcdfghjkmnpqrstvwxyz"; 
  $i = 0; 
  while ($i < $length) {    
    $char = substr($possible, mt_rand(0, strlen($possible)-1), 1);          
        if (!strstr($password, $char)) { 
          $password .= $char;
          $i++;
        }
  }
  return $password;
}

// f_passwordhash  - Hash password @ cost 12
function f_passwordhash($pwd)
{
    $options = [
        'cost' => 12,
    ];
 	$salt = password_hash($pwd, PASSWORD_BCRYPT, $options);
    return $salt;
}


// f_passwordvefify  - verifies the supplied $pwd with the $hash stored on the db. The hash from DB must be supplied to the function.
function f_passwordvefify($pwd,$hash)
{
 	if (password_verify($pwd,$hash)) {
       return true;
    } else {
	   return false;
    }
}

// f_encodeurl  - Encodes the url
function f_encodeurl($url)
{
    $new = strtolower(ereg_replace(' ','_',$url));
    return($new);
}

//f_decodeurl - Decodes the url
function f_decodeurl($url)
{
    $new = ucwords(ereg_replace('_',' ',$url));
    return($new);
}


// f_chopstring - chops the string to a specific length. Remember the starting length is from 0 not 1.
function f_chopstring($str, $len) 
{
    if (strlen($str) < $len){
        return $str;
    }
    $str = substr($str,0,$len);
    if ($spc_pos = strrpos($str," ")){
            $str = substr($str,0,$spc_pos);
    }
    return $str . "...";
}	


// f_encryptcookie - Encrypts cookies set
function f_encryptcookie($value) {
   $key = hex2bin(openssl_random_pseudo_bytes(4));
   $cipher = "aes-256-cbc";
   $ivlen = openssl_cipher_iv_length($cipher);
   $iv = openssl_random_pseudo_bytes($ivlen);
   $ciphertext = openssl_encrypt($value, $cipher, $key, 0, $iv);
   return( base64_encode($ciphertext . '::' . $iv. '::' .$key) );
}


// f_decryptcookie - Decrypts cookies set
function decryptcookie($ciphertext) {
   $cipher = "aes-256-cbc";
   list($encrypted_data, $iv,$key) = explode('::', base64_decode($ciphertext));
   return openssl_decrypt($encrypted_data, $cipher, $key, 0, $iv);
}


// ******************************************
// User Defined Functions - End
//*******************************************

?>
