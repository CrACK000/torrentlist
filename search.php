<?php

require 'app/app.php';
require 'app/Functions.php';
require 'app/Torrent.php';
require 'app/paginator.php';

$title = 'Search results';

//create new object pass in number of pages and identifier
$pages = new Paginator('20','p');

$search = $_GET['search'];

$foreachcategory = $db->select(
    'SELECT * FROM categories'
);

foreach ($foreachcategory as $catdb){
    if ($_GET['c'.$catdb['id']] == 1) {
        $catarray[] = $catdb['id'];
    }
}
if ($catarray) {

    $wherecat = implode(',', $catarray);

    $whereandin = 'AND category in (' . $wherecat . ')';

}

if (!$_GET['disabled'])    { $disabled = ' AND disabled = 0 '; }
if ($_GET['disabled'] == 1){ $disabled = ' AND disabled = 0 '; }
if ($_GET['disabled'] == 2){ $disabled = ' '; }
if ($_GET['disabled'] == 3){ $disabled = ' AND disabled = 1 '; }


//get number of total records
$total = $db->selectValue('SELECT count(*) FROM torrents WHERE title LIKE "%'.$search.'%"'.$disabled.$whereandin);

//pass number of records to
$pages->set_total($total);

$torrents = $db->select('SELECT * FROM torrents WHERE title LIKE "%'.$search.'%"'.$disabled.$whereandin.' '.$pages->get_limit());


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
                    <h1><i class="fas fa-search"></i> Search results</h1>
                    <p>If you are looking for something we do not know much about, it will help you <a href="#">advanced search</a>.</p>
                </div>
                
                <ul class="app-breadcrumb breadcrumb">
                    <li class="breadcrumb-item"><i class="fas fa-home"></i></li>
                    <li class="breadcrumb-item"><a href="' . URL . '/search">search results</a></li>
                </ul>
                
            </div>
            
            <div class="row">
                <div class="col-md-12">
                
                    <div class="tile mb-4 text-center">
                        
                        <form method="get">
                        
                            <div class="row col-md-8 mx-auto mb-3">';

                                foreach ($foreachcategory as $catdat) {

                                    echo '
                                    <div class="col-md-3 text-left">
                                        <div class="form-check">
                                            <label class="form-check-label">
                                                <input class="form-check-input" '.($_GET['c'.$catdat['id']] == 1 ? 'checked' : '').' type="checkbox" name="c'.$catdat['id'].'" value="1"> '.$catdat['title'].'
                                            </label>
                                        </div>
                                    </div>';

                                }

                            echo '
                            </div>
                        
                            <div class="form-group mt-0">
                                
                                <input class="form-control form-control-lg col-md-5 mx-auto" value="'.$search.'" style="border:1px solid #ccc;" type="search" name="search" placeholder="Search for an expression..">
                                
                                <select name="disabled" class="form-control form-control-sm col-1 mx-auto mt-1" style="border:1px solid #ccc;">
                                    <option'.($_GET['disabled'] == 1 ? ' selected' : '').' value="1">Approved</option>
                                    <option'.($_GET['disabled'] == 2 ? ' selected' : '').' value="2">All of them</option>
                                    <option'.($_GET['disabled'] == 3 ? ' selected' : '').' value="3">Disapprove</option>
                                </select>
                                
                                <button type="submit" class="btn btn-dark btn-sm mt-1"><i class="fas fa-search mr-1"></i> Search</button>
                                
                            </div>
                            
                            <p class="text-center small text-muted mb-0">Number of torrents found: '.$total.'</p>
                        
                        </form>
                        
                    </div>';

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