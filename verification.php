<?php

require 'app/app.php';

try {
    $auth->confirmEmail($_GET['selector'], $_GET['token']);

    $msg = 'email address has been verified';
    header("Refresh:7; url=".URL."/login");
}
catch (\Delight\Auth\InvalidSelectorTokenPairException $e) {
    // invalid token
    $msg = 'invalid token';
    header("Refresh:7; url=".URL);
}
catch (\Delight\Auth\TokenExpiredException $e) {
    // token expired
    $msg = 'token expired';
    header("Refresh:7; url=".URL);
}
catch (\Delight\Auth\UserAlreadyExistsException $e) {
    // email address already exists
    $msg = 'email address already exists';
    header("Refresh:7; url=".URL);
}
catch (\Delight\Auth\TooManyRequestsException $e) {
    // too many requests
    $msg = 'too many requests';
    header("Refresh:7; url=".URL);
}

if ($auth->isLoggedIn()) {
    // user is signed in
    header('Location: '.URL);
}

$title = 'Verification';

echo '
<!DOCTYPE html>
<html lang="en">

    <head>'; require 'template/template_headtags.php'; echo '</head>

    <body class="app rtl">
    
        <div style="position: absolute;top: 50%;left: 50%;transform: translateX(-50%) translateY(-50%);text-align: center">
            <div id="loading">
                <span class="verify-span">Authentication is running</span>
                <span class="verify-span l-1"></span>
                <span class="verify-span l-2"></span>
                <span class="verify-span l-3"></span>
                <span class="verify-span l-4"></span>
                <span class="verify-span l-5"></span>
                <span class="verify-span l-6"></span>
            </div>
            <div id="result" style="display: none;">
                <span>'.$msg.'</span>
            </div>
        </div>
                
        '; require 'template/template_scripts.php'; echo '

        <script>
            $("#loading").delay(5550).hide(0);
            $("#result").delay(5500).show(0);
        </script>
        
    </body>
</html>';