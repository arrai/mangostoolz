<?php
//
// these are some examples how to use MangosSRP. Note that these are only snipptes, the script alone does nothing but producing errors :)
//
error_reporting(E_ALL);
require_once("MangosSRP.class.php");

//////
// first: Registering a new user
// assumption: you made a form and the user sent his desired username/password
//
$databaseValues = MangosSRP::registerNewUser($_POST['username'], $_POST['password']);

$query = "INSERT INTO account(username, v, s) VALUES ('".mysql_real_escape_string($_POST['username'])."', '".$databaseValues['v']."', '".$databaseValues['s']."')";

//////
// second: Verifying a user's password
// this might be used to authenticate a password change for example
// assumption: you made a form and the user sent his username and password. Furthermore, you've looked up v and s for this account from the database.
//
$valid = MangosSRP::isValidPassword($_POST['username'], $_POST['password'], $databse_s, $database_v);
if($valid)
    echo "User authenticated successfully";
else
    echo "User authentication failed";

//////
// third: changing users password
// assumption: you made a form and the user sent his username and password.
// of course, you should authentify him with his old data first. see above therefore
//

$databaseValues = MangosSRP::registerNewUser($_POST['username'], $_POST['new_password']);
$query = "UPDATE account v='".$databaseValues['v']."', s='".$databaseValues['s']."' WHERE username='".mysql_real_escape_string($_POST['username'])."'";
echo $query;

?>
