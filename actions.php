<?php
include 'library.php';
/*
CREATE TABLE `demo`.`fblogin` (
`id` INT( 10 ) NOT NULL AUTO_INCREMENT PRIMARY KEY ,
`fb_id` INT( 20 ) NOT NULL ,
`name` VARCHAR( 300 ) NOT NULL ,
`email` VARCHAR( 300 ) NOT NULL ,
`image` VARCHAR( 600 ) NOT NULL,
`postdate` DATETIME NOT NULL
) ENGINE = InnoDB;
*/
$action = $_REQUEST["action"];
switch($action){
	case "fblogin":
	include 'facebook.php';
	$appid 		= "1685465574999055";
	$appsecret  = "6dbed57fa8e22d030b7836da3c983c7e";
	$facebook   = new Facebook(array(
  		'appId' => $appid,
  		'secret' => $appsecret,
  		'cookie' => TRUE,
	));
$fbuser = $facebook->getUser();

	if ($fbuser) {
	  try {
	 // $json = $facebook->api('/me');
		    $user_profile = $facebook->api('/me?fields=id,name,email' );
		//	print_r($user_profile);
			
		}
		catch (Exception $e) {
			echo $e->getMessage();
			exit();
		}
		//print_r($user_profile);
					//print_r($json);
					
		$user_fbid	= $fbuser;
		$user_email = $user_profile["email"];
		$user_fnmae = $user_profile["name"];
		$user_image = "https://graph.facebook.com/".$user_fbid."/picture?type=large";
		$check_select = mysql_num_rows(mysql_query("SELECT * FROM `fblogin` WHERE email = '$user_email'"));
		if($check_select > 0){
			mysql_query("INSERT INTO `fblogin` (fb_id, name, email, image, postdate) VALUES ('$user_fbid', '$user_fnmae', '$user_email', '$user_image', '$now')");
		}
	}
	break;
}
?>
<!DOCTYPE html>
<html xmlns="">

<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title></title>
<script type="text/javascript">
window.fbAsyncInit = function() {
	FB.init({
	appId      : '1685465574999055', // replace your app id here
	channelUrl : 'http://localhost/fb/index.php', 
	status     : true, 
	cookie     : true, 
	xfbml      : true  
	});
};
(function(d){
	var js, id = 'facebook-jssdk', ref = d.getElementsByTagName('script')[0];
	if (d.getElementById(id)) {return;}
	js = d.createElement('script'); js.id = id; js.async = true;
	js.src = "//connect.facebook.net/en_US/all.js";
	ref.parentNode.insertBefore(js, ref);
}(document));

function FBLogout(){
	FB.logout(function(response) {
		window.location.href = "index.php";
	});
}
function FBLogin(){
    FB.login(function(response){
        if(response.authResponse){
            window.location.href = "actions.php?action=fblogin";
        }
    }, {scope: 'email,user_likes'});
}
FB.getLoginStatus()
</script>
<style>
body{
	font-family:Arial;
	color:#333;
	font-size:14px;
}
.mytable{
	margin:0 auto;
	width:600px;
	border:2px dashed #17A3F7;
}
a{
	color:#0C92BE;
	cursor:pointer;
}

</style>
</head>

<body>
<h1></h1>
<h3>here is the user details, for more details </h3>
<table class="mytable">
<tr>
	<td colspan="2" align="left"><h2>Hi <?php echo $user_fnmae; ?>,</h2><a onClick="FBLogout();">Logout</a></td>
</tr>
<tr>
	<td><b>Fb id:<?php echo $user_fbid; ?></b></td>
    <td valign="top" rowspan="2"><img src="<?php echo $user_image; ?>" height="100"/></td>
</tr>
<tr>
	<td><b>Email:<?php echo $user_email; ?></b></td>
</tr>
</table>
</body>
</html>