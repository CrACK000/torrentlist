<?php

require 'app/app.php';
require 'app/Functions.php';
require 'app/paginator.php';

if ($_GET['id']) {

    $notification = $db->selectRow(
        'SELECT * FROM notifications WHERE id = ?',
        [ $_GET['id'] ]
    );

    if ($notification['owner'] != 0) {
        if ($notification['owner'] != $auth->getUserId()) {
            header('Location: ' . URL);
        }

        if ($notification['view'] == 0) {
            $db->update(
                'notifications',
                [
                    // set
                    'view' => 1
                ],
                [
                    // where
                    'id' => $_GET['id']
                ]
            );
        }

    }

    $title = $notification['title'] . ' - notification';

} else {

    $title = 'Notifications';

    $pages = new Paginator('25','p');

    $countnotify = $db->selectValue(
        'SELECT count(*) FROM notifications WHERE owner in (?,0) AND date > '.$inuser['registered'],
        [ $auth->getUserId() ]
    );

    $pages->set_total($countnotify);

    $notification = $db->select(
        'SELECT * FROM notifications WHERE owner in (?,0) AND date > '.$inuser['registered'].' ORDER BY date DESC '.$pages->get_limit(),
        [ $auth->getUserId() ]
    );

}

echo '
<!DOCTYPE html>
<html lang="en">

    <head>'; require 'template/template_headtags.php'; echo '</head>

    <body class="app sidebar-mini rtl">
    
        '; require 'template/template_head.php';

           require 'template/template_sidebar.php'; echo '
        
        
        
        <main class="app-content">';

            if ($_GET['id']) {

                echo '
                <div class="app-title">
                
                    <div>
                        <h1><i class="fas fa-flag"></i> Notification: ' . $notification['title'] . '</h1>
                    </div>
                    
                    <ul class="app-breadcrumb breadcrumb">
                        <li class="breadcrumb-item"><i class="fas fa-home"></i></li>
                        <li class="breadcrumb-item">notification</li>
                        <li class="breadcrumb-item"><a href="' . URL . '/notify/' . $notification['id'] . '">' . strtolower($notification['title']) . '</a></li>
                    </ul>
                    
                </div>
                
                <div class="row">
                    <div class="col-md-12">
                    
                        <div class="tile">
                        
                            <h4><span class="fa-stack fa-lg mr-3"><i class="fa fa-circle fa-stack-2x text-' . $notification['color'] . '"></i><i class="' . $notification['icon'] . ' fa-stack-1x fa-inverse"></i></span> ' . $notification['title'] . '</h4>
                            
                            <div><small class="text-muted">' . time_elapsed_string($notification['date']) . '</small></div>
                            
                            ' . $notification['text'] . '
                        
                        </div>
                    
                    </div>
                </div>';

            } else {

                echo '
                <div class="app-title">
                
                    <div>
                        <h1><i class="fas fa-flag"></i> Your notifications</h1>
                    </div>
                    
                    <ul class="app-breadcrumb breadcrumb">
                        <li class="breadcrumb-item"><i class="fas fa-home"></i></li>
                        <li class="breadcrumb-item"><a href="'.URL.'/notify">Notifications</a></li>
                    </ul>
                    
                </div>
                
                <div class="row">
                    <div class="col-md-12">
                    
                        <div class="tile">';

                            foreach ($notification as $data){

                                echo '
                                <div class="row">
                                    <div class="col-md-1 text-center">
                                        <span class="fa-stack fa-lg mr-3"><i class="fa fa-circle fa-stack-2x text-'.($data['view'] == 0 ? $data['color'] : 'muted').'"></i><i class="' . $data['icon'] . ' fa-stack-1x fa-inverse"></i></span>
                                    </div>
                                    <div class="col-md-3">
                                        <h4 class="mt-2 mb-0">' . $data['title'] . '</h4>
                                    </div>
                                    <div class="col-md-5">
                                        <div class="mt-2 small text-muted">' . date('d.m.Y H:i:s', $data['date']) . '<br>
                                        ' . htmlcode(substrwords($data['text'], 150)) . '</div>
                                    </div>
                                    <div class="col-md-2">
                                        <a href="'.URL.'/notify/' . $data['id'] . '">More details</a>
                                    </div>
                                </div>
                                
                                <hr>';

                            }

                            echo $pages->page_links();
                            
                        echo '
                        </div>
                    
                    </div>
                </div>';

            }
            
            require 'template/template_footer.php'; echo '
            
        </main>
        
        '; require 'template/template_scripts.php'; echo '
        
    </body>
</html>';