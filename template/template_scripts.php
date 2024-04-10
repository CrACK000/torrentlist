<?php

echo '
    <!-- Essential javascripts for application to work-->
    <script src="'.URL.'/assets/js/jquery-3.2.1.min.js"></script>
    <script src="'.URL.'/assets/js/popper.min.js"></script>
    <script src="'.URL.'/assets/js/bootstrap.min.js"></script>
    <script src="'.URL.'/assets/js/main.js"></script>
    <!-- The javascript plugin to display page loading on top-->
    <script src="'.URL.'/assets/js/plugins/pace.min.js"></script>
    <!-- Ckeditor4 -->
    <script src="'.URL.'/assets/ckeditor/ckeditor.js"></script>
    <script>
        CKEDITOR.replace( \'ckeditor\' );
    </script>
    <!-- Google analytics script-->
    <script async src="https://www.googletagmanager.com/gtag/js?id=UA-114759865-1"></script>
    <script>
        window.dataLayer = window.dataLayer || [];
        function gtag(){dataLayer.push(arguments);}
        gtag(\'js\', new Date());
        
        gtag(\'config\', \'UA-114759865-1\');
    </script>';