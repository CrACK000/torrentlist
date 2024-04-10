<?php

require 'app/app.php';

$auth->logOutAndDestroySession();

header("Refresh:2; url=".URL);

$title = 'Checking out';

echo '
<!DOCTYPE html>
<html lang="en">

    <head>'; require 'template/template_headtags.php'; echo '</head>

    <body class="app rtl">
    
        <div style="position: absolute;top: 50%;left: 50%;transform: translateX(-50%) translateY(-50%);text-align: center">
            <span class="verify-span">Checking out is running</span>
            <span class="verify-span l-1"></span>
            <span class="verify-span l-2"></span>
            <span class="verify-span l-3"></span>
            <span class="verify-span l-4"></span>
            <span class="verify-span l-5"></span>
            <span class="verify-span l-6"></span>
        </div>
                
        '; require 'template/template_scripts.php'; echo '
        
    </body>
</html>';