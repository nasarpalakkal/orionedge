<?php
if(isset($_COOKIE['userid'])){$username=$_COOKIE['userid'];} else {$username="admin";}
if(isset($_COOKIE['password'])){$password=$_COOKIE['password'];} else {$password="admin";}
?>
<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <title>POS</title>
   <link rel="shortcut icon" href="dist/img/favicon.ico">
   <link rel="stylesheet" href="newcss/bootstrap.min.css">   
   <script src="newcss/jquery.min.js"></script>
 <script src="newcss/bootstrap.min.js"></script>
  
<!------ Include the above in your HEAD tag ---------->
<style type="text/css">
body {
  margin: 0;
  padding: 0;
  background-image:url(dist/img/background.jpg);
  background-repeat: repeat-x;
  background-color: #605CA8;
  height: 100vh;
}
#login .container #login-row #login-column #login-box {
  margin-top: 100px;
  max-width: 400px;
  height: 300px;
  border: 1px solid #EAEAEA;
  background-color: #FFFFFF;
}
#login .container #login-row #login-column #login-box #login-form {
  padding: 30px;
}
#login .container #login-row #login-column #login-box #login-form #register-link {
  margin-top: -85px;
}
</style>
  <script language="javascript">
function frmvalid()
{
if (document.getElementById("userid").value == "")
{
alert("Enter User Name");
document.getElementById("userid").focus();
return false;
}

if (document.getElementById("password").value == "")
{
alert("Enter Password");
document.getElementById("password").focus();
return false;
}

}
</script>
</head>
<body>
    <div id="login">        
        <div class="container">
            <div id="login-row" class="row justify-content-center align-items-center">
                <div id="login-column" class="col-md-6">
                    <div id="login-box" class="col-md-12">
                        									<?php if(isset($_REQUEST['error']))
															{?>
														<div align="center"><?php print '<font color="#FF0000">Invalid User Name Or Password</font>'; ?></div>
														<?php } ?>
														<?php if(isset($_REQUEST['error1']))
															{?>
														<div align="center"><?php print '<font color="#FF0000">Login Credentials Restricted</font>'; ?></div>
														<?php } ?>	
		
						<form id="login-form" class="form" action="index_action.php" method="post">
                            <h3 class="text-center text-info">Login</h3>
                            <div class="form-group">
                                <label for="username" class="text-info">Username:</label><br>
                                <input type="text" placeholder="User Name" name="userid" id="userid" value="<?php echo $username; ?>" autocomplete="off" class="form-control">
                            </div>
                            <div class="form-group">
                                <label for="password" class="text-info">Password:</label><br>
                                <input type="password" placeholder="Password" name="password" id="password" value="<?php echo $password; ?>" autocomplete="off" class="form-control">
                            </div>
                            <div class="form-group">
                             	<input type="checkbox" name="remember_me" id="remember_me">&nbsp;<label for="password" class="text-info"> Remember me</label>
                                <input type="submit" name="submit" class="btn btn-info btn-md" style="float:right" value="Sign In" onClick="return frmvalid();">
                            </div>
                           
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>