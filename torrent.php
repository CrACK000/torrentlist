<?php

require 'app/app.php';
require 'app/Functions.php';
require 'app/Torrent.php';

$select = $db->selectRow(
    'SELECT * FROM torrents WHERE id = ?',
    [ $_GET['id'] ]
);

$category = $db->selectRow(
    'SELECT * FROM categories WHERE id = ?',
    [ $select['category'] ]
);

$commCount = $db->selectValue(
    'SELECT count(*) FROM comments WHERE torrent = ?',
    [ $select['id'] ]
);

$torrent = new Torrent( 'uploads/torrents/'.$select['file'] );

$title = $select['title'].' - torrent details';

$ratingcount = $db->select(
    'SELECT * FROM rating WHERE torrent = ?',
    [
        $select['id']
    ]
);

$ratingcoutall = $db->selectValue(
    'SELECT count(*) FROM rating WHERE torrent = ?',
    [
        $select['id']
    ]
);

$ratingmax = $ratingcoutall * 5;

foreach ($ratingcount as $data){
    $coutrat[] = $data['value'];
}

$cout_2 = array_sum($coutrat);

// percent
$percent_sample = (100 * $cout_2) / $ratingmax;
$percent = ((round($percent_sample)*5) / 100);

$percent_show = str_replace(".", ",", $percent);

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
                    <h1 title="'.$select['title'].'"><i class="fas fa-server"></i> ' . substrwords($select['title'], 85, '..') . '</h1>
                    <p>Do you want to create your own torrent? Go to the <a href="'.URL.'/create-torrent">Create torrent</a> page.</p>
                </div>
                
                <ul class="app-breadcrumb breadcrumb">
                    <li class="breadcrumb-item"><i class="fas fa-home"></i></li>
                    <li class="breadcrumb-item"><a href="' . URL . '">list of torrents</a></li>
                    <li class="breadcrumb-item"><a href="' . URL . '/torrent/' . $select['id'] . '/' . toAscii($select['title'], '_').'">torrent</a></li>
                    <li class="breadcrumb-item" title="'.$select['title'].'"><a href="' . URL . '/torrent/' . $select['id'] . '/' . toAscii($select['title'], '_').'">' . substrwords($select['title'], 40, '..') . '</a></li>
                </ul>
                
            </div>
            
            <div class="row">
                <div class="col-md-12">
                
                    <div class="tile">
                        <h3 class="tile-title">torrent details <button type="button" onclick="location.href=\''.URL.'/download/'.$select['id'].'\'" class="btn btn-primary float-right"><i class="fas fa-cloud-download-alt mr-1"></i> Download</button></h3>
                        <div class="tile-body">
                            
                            <div class="row no-gutters">
                                
                                <div class="col-sm-2 text-right">
                                    <span class="mr-2" style="color: #009688">Title:</span>
                                </div>
                                <div class="col-sm-4">
                                    '.$select['title'].'
                                </div>
                                
                                <div class="col-sm-2 text-right">
                                    <span class="mr-2" style="color: #009688">Rating:</span>
                                </div>
                                <div class="col-sm-4">
                                    '.($ratingcoutall ? $percent_show : '<small class="text-muted">nobody voted</small>').' <i style="color: #009688" class="fas fa-star"></i>
                                </div>
                                
                                <div class="col-sm-2 text-right">
                                    <span class="mr-2" style="color: #009688">Category:</span>
                                </div>
                                <div class="col-sm-4">
                                    '.$category['title'].'
                                </div>
                                
                                <div class="col-sm-2 text-right">
                                    <span class="mr-2" style="color: #009688">Size:</span>
                                </div>
                                <div class="col-sm-4">
                                    '.formatSizeUnits($torrent->size()).'
                                </div>
                                
                                <div class="col-sm-2 text-right">
                                    <span class="mr-2" style="color: #009688">Date added:</span>
                                </div>
                                <div class="col-sm-4">
                                    '.Date('d.m.Y H:i',$select['date_added']).'
                                </div>
                                
                                <div class="col-sm-2 text-right">
                                    <span class="mr-2" style="color: #009688">Number of downloads:</span>
                                </div>
                                <div class="col-sm-4">
                                    '.$select['number_of_downloads'].'x
                                </div>
                                
                            </div>
                            
                            <p class="text-center my-3 small">The server operator does not assume any responsibility for uploaded files. Responsibility always carries the file uploader.</p>
                            
                            <p class="float-right">';

                                if ($auth->isLoggedIn()) {

                                    $already_voted = $db->selectValue(
                                        'SELECT * FROM rating WHERE owner = ? AND torrent = ?',
                                        [
                                            $auth->getUserId(),
                                            $select['id']
                                        ]
                                    );

                                    if (!$already_voted) {

                                        if (isset($_POST['stars'])) {

                                            $db->insert(
                                                'rating',
                                                [
                                                    // set
                                                    'owner' => $auth->getUserId(),
                                                    'torrent' => $select['id'],
                                                    'value' => $_POST['stars']
                                                ]
                                            );

                                            header('Location: ' . URL . '/torrent/' . $select['id'] . '/' . toAscii($select['title'], '_'));

                                        }

                                        echo ' 
                                        <form method="POST" id="submitRating">
                                    
                                            <div class="rating float-right">
                                                <input name="stars" value="5" id="e5" type="radio"></a><label for="e5" data-toggle="tooltip" data-placement="top" title="Very good" data-original-title="Very good"><i class="far fa-star"></i></label>
                                                <input name="stars" value="4" id="e4" type="radio"></a><label for="e4" data-toggle="tooltip" data-placement="top" title="Good" data-original-title="Good"><i class="far fa-star"></i></label>
                                                <input name="stars" value="3" id="e3" type="radio"></a><label for="e3" data-toggle="tooltip" data-placement="top" title="Ok" data-original-title="Ok"><i class="far fa-star"></i></label>
                                                <input name="stars" value="2" id="e2" type="radio"></a><label for="e2" data-toggle="tooltip" data-placement="top" title="Useful" data-original-title="Useful"><i class="far fa-star"></i></label>
                                                <input name="stars" value="1" id="e1" type="radio"></a><label for="e1" data-toggle="tooltip" data-placement="top" title="Nothing much" data-original-title="Nothing much"><i class="far fa-star"></i></label>
                                            </div>
                                        
                                        </form>';

                                    } else {

                                        if (round($percent) >= 1){
                                            $star_one = '-o';
                                        } if(round($percent) >= 2) {
                                            $star_two = '-o';
                                        } if(round($percent) >= 3) {
                                            $star_three = '-o';
                                        } if(round($percent) >= 4) {
                                            $star_four = '-o';
                                        } if(round($percent) == 5) {
                                            $star_five = '-o';
                                        }

                                        if (round($percent) == 1){
                                            $star_text = 'Nothing much';
                                        } elseif(round($percent) == 2) {
                                            $star_text = 'Useful';
                                        } elseif(round($percent) == 3) {
                                            $star_text = 'Ok';
                                        } elseif(round($percent) == 4) {
                                            $star_text = 'Good';
                                        } elseif(round($percent) == 5) {
                                            $star_text = 'Very good';
                                        }

                                        $your_rating = $db->selectRow(
                                            'SELECT * FROM rating WHERE owner = ? AND torrent = ?',
                                            [
                                                $auth->getUserId(),
                                                $select['id']
                                            ]
                                        );

                                        if ($your_rating['value'] == 1){
                                            $your_rating_text = 'Nothing much';
                                        } elseif($your_rating['value'] == 2) {
                                            $your_rating_text = 'Useful';
                                        } elseif($your_rating['value'] == 3) {
                                            $your_rating_text = 'Ok';
                                        } elseif($your_rating['value'] == 4) {
                                            $your_rating_text = 'Good';
                                        } elseif($your_rating['value'] == 5) {
                                            $your_rating_text = 'Very good';
                                        }

                                        echo '
                                        <div class="rating float-right">
                                            <i class="star'.$star_five.' fa'.($star_five ? '' : 'r').' fa-star" title="Very good"></i>
                                            <i class="star'.$star_four.' fa'.($star_four ? '' : 'r').' fa-star" title="Good"></i>
                                            <i class="star'.$star_three.' fa'.($star_three ? '' : 'r').' fa-star" title="Ok"></i>
                                            <i class="star'.$star_two.' fa'.($star_two ? '' : 'r').' fa-star" title="Useful"></i>
                                            <i class="star'.$star_one.' fa'.($star_one ? '' : 'r').' fa-star" title="Nothing much"></i>
                                        </div>
                                        <div class="position-absolute pr-4" style="right: 0;">
                                            <span class="small text-muted float-right">Your rating: '.$your_rating_text.' | Rating: '.$star_text.'</span>
                                        </div>';
                                    }

                                } else {

                                    if (round($percent) >= 1){
                                        $star_one = '-o';
                                    } if(round($percent) >= 2) {
                                        $star_two = '-o';
                                    } if(round($percent) >= 3) {
                                        $star_three = '-o';
                                    } if(round($percent) >= 4) {
                                        $star_four = '-o';
                                    } if(round($percent) == 5) {
                                        $star_five = '-o';
                                    }

                                    if (round($percent) == 1){
                                        $star_text = 'Nothing much';
                                    } elseif(round($percent) == 2) {
                                        $star_text = 'Useful';
                                    } elseif(round($percent) == 3) {
                                        $star_text = 'Ok';
                                    } elseif(round($percent) == 4) {
                                        $star_text = 'Good';
                                    } elseif(round($percent) == 5) {
                                        $star_text = 'Very good';
                                    }

                                    echo '
                                    <div class="rating float-right">
                                        <i class="star'.$star_five.' fa'.($star_five ? '' : 'r').' fa-star" title="Very good"></i>
                                        <i class="star'.$star_four.' fa'.($star_four ? '' : 'r').' fa-star" title="Good"></i>
                                        <i class="star'.$star_three.' fa'.($star_three ? '' : 'r').' fa-star" title="Ok"></i>
                                        <i class="star'.$star_two.' fa'.($star_two ? '' : 'r').' fa-star" title="Useful"></i>
                                        <i class="star'.$star_one.' fa'.($star_one ? '' : 'r').' fa-star" title="Nothing much"></i>
                                    </div>
                                    <div class="position-absolute pr-4" style="right: 0;">
                                        <span class="small text-muted float-right">Rating: '.$star_text.'</span>
                                    </div>';

                                }

                            echo '
                            </p>
                            
                        </div>
                    </div>
                    
                    <div class="tile">
                        <h3 class="tile-title">description</h3>
                        <div class="tile-body">
                            
                            '.$select['description'].'
                            
                        </div>
                    </div>
                    
                    <div class="tile">
                        <h3 class="tile-title">file list <small class="text-muted">all files</small></h3>
                        <div class="tile-body">
                            <ul class="list-unstyled text-black bg-light p-3">';

                                foreach ($torrent->content() as $uf => $af) {

                                    $a = pathinfo($uf);

                                    $type = substr($a['basename'], strrpos($a['basename'], '.') + 1);

                                    $url = URL . '/assets/images/icons/'.$type.'.png';

                                    $check = checkRemoteFile($url);

                                    if ($check == true) {
                                        $icon = '<img alt="' . $type . '" src="' . URL . '/assets/images/icons/'.$type.'.png" class="mr-1" />';
                                    } else {
                                        $icon = '<img alt="' . $type . '" src="' . URL . '/assets/images/icons/other.png" class="mr-1" />';
                                    }

                                    echo '<li><span class="mr-2">'.$icon.$a['basename'].'</span> <small class="text-muted"><span class="mr-1">~</span> '.formatSizeUnits($af).'</small></li>';

                                }

                            echo '
                            </ul>
                        </div>
                    </div>
                    
                    <div class="tile">
                        <h3 class="tile-title">comments</h3>
                        <div class="tile-body">';

                            $comments = $db->select(
                                'SELECT * FROM comments WHERE torrent = ? LIMIT 10',
                                [ $select['id'] ]
                            );

                            if ($commCount > 0) {

                                echo '<form method="post">';

                                foreach ($comments as $com) {

                                    $autor = $db->selectRow(
                                        'SELECT * FROM users WHERE id = ?',
                                        [$com['owner']]
                                    );

                                    echo '
                                    <div class="row" id="com-'.$com['id'].'">
                                        <div class="col-sm-2 text-right">';

                                            if ($com['owner'] != $select['owner']) {
                                                echo '<a href="' . URL . '/user/' . $com['owner'] . '/' . strtolower(toAscii($autor['username'])) . '" class="font-weight-bold">' . $autor['username'] . '</a><br>';
                                            } else {
                                                echo '<span class="font-weight-bold text-light bg-primary rounded">Uploader</span><br>';
                                            }

                                            echo '
                                            <span class="text-muted">' . Date('d.m.Y H:i:s', $com['date']) . '</span><br>';

                                            if ($auth->getUserId() == $com['owner']) {

                                                if (isset($_POST['delete-com-'.$com['id']])){

                                                    $db->delete(
                                                        'comments',
                                                        [
                                                            // where
                                                            'id' => $com['id']
                                                        ]
                                                    );

                                                    header('Location: ' . URL . '/torrent/' . $select['id'] . '/' . toAscii($select['title'], '_'));

                                                }

                                                echo '<button type="submit" name="delete-com-'.$com['id'].'" class="btn btn-link btn-sm p-0">Delete</button> | <button type="submit" name="edit-com-'.$com['id'].'" class="btn btn-link btn-sm p-0">Edit</button>';
                                            }

                                        echo '
                                        </div>
                                        <div class="col-sm-10">';

                                            if ($auth->getUserId() == $com['owner']) {
                                                if (isset($_POST['editcombtn' . $com['id']])) {
                                                    $db->update(
                                                        'comments',
                                                        [
                                                            // set
                                                            'text' => $_POST['edit-com-text']
                                                        ],
                                                        [
                                                            // where
                                                            'id' => $com['id']
                                                        ]
                                                    );
                                                    header('Location: ' . URL . '/torrent/' . $select['id'] . '/' . toAscii($select['title'], '_'));
                                                }
                                            }

                                            if (isset($_POST['edit-com-'.$com['id']])){
                                                if ($auth->getUserId() == $com['owner']) {

                                                    $script = '
                                                    <script>
                                                        var elem = $(\'#com-'.$com['id'].'\');
                                                        if(elem) {
                                                            $(window).scrollTop(elem.offset().top);
                                                        }
                                                    </script>';

                                                    echo '
                                    
                                                    <textarea name="edit-com-text" class="form-control col-md-12" rows="4" placeholder="Type here the text..." required>'.$com['text'].'</textarea>
                                                    
                                                    <div class="text-right">
                                                        <a href="' . URL . '/torrent/' . $select['id'] . '/' . toAscii($select['title'], '_').'" class="btn btn-link btn-sm my-1">Cancel</a>
                                                        <button type="submit" name="editcombtn'.$com['id'].'" class="btn btn-primary btn-sm my-1"><i class="fas fa-pencil-alt mr-2"></i> Edit a comment</button>
                                                    </div>';

                                                }
                                            } else {
                                                echo nl2br(showBBcodes(htmlcode($com['text'])));
                                            }

                                        echo '
                                        </div>
                                    </div>
                                    
                                    <hr>';

                                }

                                echo '</form>';

                            } else {
                                echo '<p class="text-center">No comments have been added yet</p>';
                            }

                            if ($auth->isLoggedIn()) {
                                if (isset($_POST['addcom'])) {
                                    if ($_POST['text']) {

                                        $db->insert(
                                            'comments',
                                            [
                                                // set
                                                'owner' => $auth->getUserId(),
                                                'torrent' => $select['id'],
                                                'text' => $_POST['text'],
                                                'date' => time()
                                            ]
                                        );

                                        echo '<div class="alert alert-success">Comment successfully added</div>';

                                        if ($auth->getUserId() != $select['owner']) {
                                            $notifytext = '<a href="' . URL . '/user/' . userinfo($auth->getUserId(), 'id') . '/' . strtolower(toAscii(userinfo($auth->getUserId(), 'username'))) . '">' . userinfo($auth->getUserId(), 'username') . '</a> commented on your torrent (<a href="' . URL . '/torrent/' . $select['id'] . '/' . toAscii($select['title'], '_') . '">' . $select['title'] . '</a>)';
                                            create_notify($select['owner'], 'fas fa-comments', 'primary', 'Commenting on torrent', $notifytext);
                                        }

                                        header('Location: ' . URL . '/torrent/' . $select['id'] . '/' . toAscii($select['title'], '_'));

                                    } else {
                                        echo '<div class="alert alert-danger">You have to type some text</div>';
                                        header('Location: ' . URL . '/torrent/' . $select['id'] . '/' . toAscii($select['title'], '_'));
                                    }

                                }
                            }

                            if ($auth->isLoggedIn()) {

                                echo '

                                <div class="modal fade" id="help" tabindex="-1" role="dialog" aria-labelledby="help" aria-hidden="true">
                                    <div class="modal-dialog modal-dialog-centered" role="document">
                                        <div class="modal-content">
                                            <div class="modal-body">
                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                                
                                                <p>[b] <b> Strong text </b> [/b]</p>
                                                <p>[i] <i> Italics text </i> [/i]</p>
                                                <p>[u] <u> Underlined text </u> [/u]</p>
                                                <p>[quote] <pre>Quote text</pre> [/quote]</p>
                                                <p>[size=18] <span style="font-size: 18px;"> larger text </span> [/size]</p>
                                                <p>[color=yellow] <span style="color: yellow"> Color text </span> [/color]</p>
                                                <p>[url] <a href="#"> url address </a> [/url]</p>
                                                <p>[img] url image address [/img]</p>
                                                
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <form method="POST" action="">
                                    
                                    <textarea name="text" class="form-control col-md-12" rows="4" placeholder="Type here the text..." required></textarea>
                                    
                                    <div class="text-right">
                                        <button type="button" class="btn btn-link" data-toggle="modal" data-target="#help"><i class="far fa-question-circle"></i></button>
                                        <button type="submit" name="addcom" class="btn btn-primary my-2"><i class="fas fa-plus-circle"></i> Add a comment</button>
                                    </div>
                                    
                                </form>';

                            } else {
                                echo '<p class="text-center">You must be logged in to add comment.</p>';
                            }

                            echo '
                        </div>
                    </div>
                
                </div>
            </div>
            
            '; require 'template/template_footer.php'; echo '
            
        </main>
        
        '; require 'template/template_scripts.php'; echo '

        <script>
        
            $(\'[data-toggle="tooltip"]\').tooltip();
        
            $(\'input[name=stars]\').on(\'change\', function() {
                $("#submitRating").submit();
            });
            
        </script>
        
        '.$script.'
        
    </body>
</html>';