<?php

require 'app/app.php';
require 'app/Functions.php';
require 'app/Torrent.php';
require 'app/paginator.php';

$title = 'List of torrents';

//create new object pass in number of pages and identifier
$pages = new Paginator('25','p');

//get number of total records
$total = $db->selectValue('SELECT count(*) FROM torrents WHERE disabled = 0');

//pass number of records to
$pages->set_total($total);

$torrents = $db->select(
    'SELECT * FROM torrents WHERE disabled = 0 ORDER BY date_added DESC '.$pages->get_limit()
);

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
                    <h1><i class="fas fa-list-ul"></i> Latest torrents</h1>
                    <p>If you are looking for something we do not know much about, it will help you <a href="'.URL.'/search">advanced search</a>.</p>
                </div>
                
                <ul class="app-breadcrumb breadcrumb">
                    <li class="breadcrumb-item"><i class="fas fa-home"></i></li>
                    <li class="breadcrumb-item"><a href="' . URL . '">list of torrents</a></li>
                </ul>
                
            </div>
            
            <div class="row">
                <div class="col-md-12">';

                    foreach ($torrents as $data) {

                        $torrent = new Torrent( 'uploads/torrents/'.$data['file'] );

                        $category = $db->selectRow(
                            'SELECT * FROM categories WHERE id = ?',
                            [ $data['category'] ]
                        );

                        $commCount = $db->selectValue(
                            'SELECT count(*) FROM comments WHERE torrent = ?',
                            [ $data['id'] ]
                        );

                        //Rating
                        $ratingcount = $db->select(
                            'SELECT * FROM rating WHERE torrent = ?',
                            [
                                $data['id']
                            ]
                        );

                        $ratingcoutall = $db->selectValue(
                            'SELECT count(*) FROM rating WHERE torrent = ?',
                            [
                                $data['id']
                            ]
                        );

                        $ratingmax = $ratingcoutall * 5;

                        $coutrat = 0;

                        foreach ($ratingcount as $datar){
                            $coutrat+= $datar['value'];
                        }

                        $cout_2 = $coutrat;

                        $percent_sample = (100 * $cout_2) / $ratingmax;
                        $percent = ((round($percent_sample)*5) / 100);

                        $percent_show = str_replace(".", ",", $percent);

                        echo '
                        <div class="tile rounded-0 p-3 mb-3">
                            <div class="row">
                            
                                <div class="col-sm-1">
                                    <i class="fas fa-file mr-1"></i>
                                    <small class="text-muted mt-1">' . Date('d.m.Y', $data['date_added']) . '</small>
                                </div>
                                
                                <div class="col-sm-5">
                                    <a href="' . URL . '/torrent/' . $data['id'] . '/' . toAscii($data['title'], '_') . '">' . substrwords($data['title'], 65, '') . '</a> <small class="text-muted mt-1">/ Size: '.formatSizeUnits($torrent->size()).'</small>
                                </div>
                                
                                <div class="col-sm-4">
                                    <small class="text-muted mt-1 mr-4">Category: <a href="'.URL.'/search?c'.$data['category'].'=1"><strong>' . $category['title'] . '</strong></a></small>
                                    <small class="text-muted mt-1 mr-4">Rating: '.($ratingcoutall ? $percent_show : $ratingcoutall).' <i style="color: #009688" class="fas fa-star"></i></small>
                                    <small class="text-muted mt-1">Comments: <strong>'.$commCount.'</strong>x</small>
                                </div>
                                
                                <div class="col-sm-2 text-right">
                                    <a href="'.URL.'/download/'.$data['id'].'"><i class="fas fa-cloud-download-alt mr-1"></i> download quickly</a>
                                </div>
                                
                            </div>
                        </div>';

                    }

                    echo $pages->page_links();

                echo '
                </div>
            </div>
            
            '; require 'template/template_footer.php'; echo '
            
        </main>
        
        '; require 'template/template_scripts.php'; echo '
        
    </body>
</html>';