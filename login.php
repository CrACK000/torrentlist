<?php

require 'app/app.php';

if (isset($_POST['login'])) {
    try {
        $auth->login($_POST['email'], $_POST['password']);
        // user is logged in
        header('Location: '.URL);
    } catch (\Delight\Auth\InvalidEmailException $e) {
        // wrong email address
        $err = 1;
        $msg = 'wrong email address';
    } catch (\Delight\Auth\InvalidPasswordException $e) {
        // wrong password
        $err = 1;
        $msg = 'wrong password';
    } catch (\Delight\Auth\EmailNotVerifiedException $e) {
        // email not verified
        $err = 1;
        $msg = 'email not verified';
    } catch (\Delight\Auth\TooManyRequestsException $e) {
        // too many requests
        $err = 1;
        $msg = 'too many requests';
    }
}

if ($auth->isLoggedIn()) {
    // user is signed in
    header('Location: '.URL);
}

$title = 'Login';

echo '
<!DOCTYPE html>
<html lang="en">

    <head>'; require 'template/template_headtags.php'; echo '</head>

    <body>
    
        <a href="'.URL.'" class="position-absolute text-light m-3" style="top:0;left: 0;"><i class="fas fa-home mr-2"></i> back to home</a>
    
        <section class="material-half-bg">
        
            <div class="cover"></div>
            
        </section>
        
        <section class="login-content">
        
            <div class="logo">
                <h1>torrentlist</h1>
            </div>
            
            <div class="login-box">
            
                <form class="login-form" method="post" action="">
                
                    <h3 class="login-head"><i class="fa fa-lg fa-fw fa-user"></i>SIGN IN</h3>
                    
                    <div class="form-group">
                        <label class="control-label">USERNAME</label>
                        <input class="form-control" type="email" name="email" value="'.$_POST['email'].'" placeholder="Email" required autofocus>
                    </div>
                    
                    <div class="form-group">
                        <label class="control-label">PASSWORD</label>
                        <input class="form-control" type="password" name="password" placeholder="Password" required>
                    </div>
                    
                    <div class="form-group">
                        <div class="utility">
                            <p class="semibold-text mb-2"><a href="#" data-toggle="flip">Forgot Password ?</a></p>
                        </div>
                    </div>
                    
                    <div class="form-group btn-container">
                        <button class="btn btn-primary btn-block" type="submit" name="login"><i class="fa fa-sign-in fa-lg fa-fw"></i>SIGN IN</button>
                    </div>
                    
                </form>
                
                <form class="forget-form" action="index.html">
                
                    <h3 class="login-head"><i class="fas fa-lg fa-fw fa-lock"></i>Forgot Password ?</h3>
                    
                    <div class="form-group">
                        <label class="control-label">EMAIL</label>
                        <input class="form-control" type="text" placeholder="Email">
                    </div>
                    
                    <div class="form-group btn-container">
                        <button class="btn btn-primary btn-block"><i class="fas fa-unlock fa-lg fa-fw"></i>RESET</button>
                    </div>
                    
                    <div class="form-group mt-3">
                        <p class="semibold-text mb-0"><a href="#" data-toggle="flip"><i class="fa fa-angle-left fa-fw"></i> Back to Login</a></p>
                    </div>
                    
                </form>
                
            </div>
            
        </section>
    
    
    
    
        '; require 'template/template_scripts.php'; echo '
        <script type="application/javascript" src="'.URL.'/assets/js/plugins/bootstrap-notify.min.js"></script>

        <script type="text/javascript">
            $(\'.login-content [data-toggle="flip"]\').click(function() {
                $(\'.login-box\').toggleClass(\'flipped\');
                return false;
            });
        </script>';

        if ($err == 1) {
            echo '
            <script>
                $.notify({
                    title: "Bug : ",
                    message: "'.$msg.'"
                },{
                    type: "danger",
                    placement: {
                        from: "top",
                        align: "right"
                    }
                });
            </script>';
        }

    echo '
    </body>
</html>';