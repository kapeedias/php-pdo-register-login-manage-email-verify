<?php

//this is where you enter all the server / host / db credentials.
$host = '127.0.0.1';
$db   = 'test';
$user = 'root';
$pass = '';
$port = "3306";
$charset = 'utf8mb4';


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


// f_chopstring - chops the string to a specific length. Remember the starting length is form 0 not 1.
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

// ******************************************
// User Defined Functions - End
//*******************************************

?>
