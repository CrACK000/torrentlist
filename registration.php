<?php

require 'app/app.php';
require 'app/Functions.php';

try {

    if (isset($_POST['register'])){

        if ($_POST['password'] == $_POST['repassword']){

            $userId = $auth->register($_POST['email'], $_POST['password'], $_POST['username'], function ($selector, $token) {

                $body = '<!DOCTYPE html> <html> <head> <meta charset="UTF-8"> <title>Confirm your email address</title> </head> <body> <div style="background-color:#fff;margin:0 auto 0 auto;padding:30px 0 30px 0;color:#4f565d;font-size:13px;line-height:20px;font-family:\'Helvetica Neue\',Arial,sans-serif;text-align:left;"> <div style="width: 100%;text-align: center;"> <table style="width:550px;text-align:center" align="center"> <tbody> <tr> <td style="padding:0 0 20px 0;border-bottom:1px solid #e9edee;"> <a href="'.URL.'" style="font-size: 50px;color: #009688;text-decoration:none;display:block; margin:0 auto;" target="_blank"> <img src="'.URL.'/assets/images/logotxt.png" style="border: 0;"> </a> </td></tr><tr> <td colspan="2" style="padding:30px 0;"> <p style="color:#1d2227;line-height:28px;font-size:22px;margin:12px 10px 20px 10px;font-weight:400;">Hi Really Good Email, it\'s great to meet you.</p><p style="margin:0 10px 10px 10px;padding:0;">We\'d like to make sure we got your email address right.</p><p> <a style="display:inline-block;text-decoration:none;padding:15px 20px;background-color:#009688;border:1px solid #009688;border-radius:3px;color:#FFF;font-weight:bold;" href="'.URL.'/verification/'.$selector.'/'.$token.'" target="_blank">Yes, it\'s me - let\'s get started</a> </p></td></tr><tr> <td colspan="2" style="padding:30px 0 0 0;border-top:1px solid #e9edee;color:#9b9fa5"> If you have any questions you can contact us at <a style="color:#666d74;text-decoration:none;" href="mailto:info@pallax.systems" target="_blank">info@pallax.systems</a> </td></tr></tbody> </table> </div></div></body> </html>';

                $nohtmlbody = "Torrentlist\n\nHi Really Good Email, it's great to meet you.\nWe'd like to make sure we got your email address right.\n\nTo verify your email address, click the link below.\n".URL."/verification/".$selector."/".$token."\n\nIf you have any questions you can contact us at info@pallax.systems";

                phpmailer($_POST['email'],$_POST['username'],'Registration',$body,$nohtmlbody);

            });

            $msg = '<div class="alert alert-success">Registration was successful. Your confirmation message has been sent to your email, verify your account.</div>';

        } else {
            $msg = '<div class="alert alert-danger">your passwords do not match</div>';
        }

    }

} catch (\Delight\Auth\InvalidEmailException $e) {
    // invalid email address
    $msg = '<div class="alert alert-danger">invalid email address</div>';
} catch (\Delight\Auth\InvalidPasswordException $e) {
    // invalid password
    $msg = '<div class="alert alert-danger">invalid password</div>';
} catch (\Delight\Auth\UserAlreadyExistsException $e) {
    // user already exists
    $msg = '<div class="alert alert-danger">user already exists</div>';
} catch (\Delight\Auth\TooManyRequestsException $e) {
    // too many requests
    $msg = '<div class="alert alert-danger">too many requests</div>';
}

if ($auth->isLoggedIn()) {
    // user is signed in
    header('Location: '.URL);
}

$title = 'Registration';

echo '
<!DOCTYPE html>
<html lang="en">

    <head>'; require 'template/template_headtags.php'; echo '</head>

    <body class="app sidebar-mini rtl">
    
        '; require 'template/template_head.php';

           require 'template/template_sidebar.php'; echo '
        
        
        
        <main class="app-content">
        
            <div class="app-title">
            
                <div>
                    <h1><i class="fas fa-server"></i> Registration</h1>
                    <p>If you are already registered, you can log in to the <a href="'.URL.'/login">Login</a> page.</p>
                </div>
                
                <ul class="app-breadcrumb breadcrumb">
                    <li class="breadcrumb-item"><i class="fas fa-home"></i></li>
                    <li class="breadcrumb-item"><a href="'.URL.'">list of torrents</a></li>
                    <li class="breadcrumb-item"><a href="'.URL.'/registration">registration</a></li>
                </ul>
                
            </div>
            
            <div class="row">
                <div class="col-md-12">
                
                    '.$msg.'
                
                    <div class="tile">
                        <h3 class="tile-title">Registration</h3>
                        <div class="tile-body">
                            
                            <form action="" method="POST">
                            
                                <div class="form-group">
                                    <label for="email">Email address</label>
                                    <input class="form-control" id="email" name="email" type="email" value="'.$_POST['email'].'" placeholder="Enter email" required autofocus>
                                </div>
                                
                                <div class="form-group">
                                    <label for="username">Username</label>
                                    <input class="form-control" id="username" name="username" type="text" value="'.$_POST['username'].'" placeholder="Enter username" minlength="4" maxlength="15" required>
                                </div>
                                
                                <div class="form-group">
                                    <label for="password">Password</label>
                                    <input class="form-control" id="password" name="password" type="password" placeholder="Password" required>
                                </div>
                                
                                <div class="form-group">
                                    <label for="repeatpassword">Repeat password</label>
                                    <input class="form-control" id="repeatpassword" name="repassword" type="password" placeholder="Repeat password" required>
                                </div>
                                
                                <div class="form-group">
                                    <button class="btn btn-primary mr-3" name="register" type="submit"><i class="fa fa-fw fa-lg fa-check-circle"></i>Register</button>
                                    <a class="btn btn-secondary" href="'.URL.'"><i class="fa fa-fw fa-lg fa-times-circle"></i>Cancel</a>
                                </div>
                                
                            </form>
                            
                        </div>
                    </div>
                
                </div>
            </div>
            
            '; require 'template/template_footer.php'; echo '
            
        </main>
        
        '; require 'template/template_scripts.php'; echo '
        
    </body>
</html>';