<?php

require 'app/app.php';
require 'app/Functions.php';

$data = $db->selectRow(
    'SELECT * FROM users WHERE id = ?',
    [ $_GET['id'] ]
);

$addedtorrentscount     = $db->selectValue('SELECT count(*) FROM torrents WHERE owner = ?', [ $_GET['id'] ]);
$writtencommentscount   = $db->selectValue('SELECT count(*) FROM comments WHERE owner = ?', [ $_GET['id'] ]);

$status = array("0" => "Normal", "1" => "Archived", "2" => "Banned", "3" => "Locked", "4" => "Pending review", "5" => "Suspended");

if ($auth->isLoggedIn()) {

    if ($auth->getUserId() == $_GET['id']) {

        //Edit Settings
        if (isset($_POST['settings'])) {

            if ($data['username'] == $_POST['username']) {
                $username_check = 0;
            } else {
                $username_check = $db->selectValue('SELECT count(*) FROM users WHERE username = ?', [$_POST['username']]);
            }

            if (!$username_check) {

                $db->update(
                    'users',
                    [
                        // set
                        'username' => $_POST['username'],
                        'aboutme' => $_POST['aboutme']
                    ],
                    [
                        // where
                        'id' => $auth->getUserId()
                    ]
                );

                $achievements = $data['achievements'];

                if ($_POST['aboutme']){
                    if (strpos($achievements, ',20,') == false) {
                        $about_me = $achievements . '20,';
                        $db->update( 'users', [ 'achievements' => $about_me ], [ 'id' => $data['id'] ] );
                        create_notify($data['id'],'fas fa-male','primary','You have met the achievement','You have met the achievement: <b>Add something about your profile to your profile</b>');
                    }
                }

                header('Location: '.URL.'/user/'.$auth->getUserId().'/'.strtolower(toAscii($data['username'])));

            } else {
                $msg = '<div class="alert alert-danger mt-4">username already exists</div>';
                header('Refresh:2; url='.URL.'/user/'.$auth->getUserId().'/'.strtolower(toAscii($data['username'])));
            }

        }
        //END *Edit Settings

        //CHANGE Password
        if (isset($_POST['password'])){
            if ($_POST['newPassword'] == $_POST['RenewPassword']){

                try {
                    $auth->changePassword($_POST['oldPassword'], $_POST['newPassword']);
                    // password has been changed
                    $msg = '<div class="alert alert-success mt-4">Password has been changed successfully</div>';
                    header('Refresh:2; url='.URL.'/user/'.$auth->getUserId().'/'.strtolower(toAscii($data['username'])));
                }
                catch (\Delight\Auth\NotLoggedInException $e) {
                    // not logged in
                    $msg = '<div class="alert alert-danger mt-4">not logged in</div>';
                    header('Refresh:2; url='.URL.'/user/'.$auth->getUserId().'/'.strtolower(toAscii($data['username'])));
                }
                catch (\Delight\Auth\InvalidPasswordException $e) {
                    // invalid password(s)
                    $msg = '<div class="alert alert-danger mt-4">invalid password(s)</div>';
                    header('Refresh:2; url='.URL.'/user/'.$auth->getUserId().'/'.strtolower(toAscii($data['username'])));
                }
                catch (\Delight\Auth\TooManyRequestsException $e) {
                    // too many requests
                    $msg = '<div class="alert alert-danger mt-4">too many requests</div>';
                    header('Refresh:2; url='.URL.'/user/'.$auth->getUserId().'/'.strtolower(toAscii($data['username'])));
                }

            } else {
                $msg = '<div class="alert alert-danger mt-4">passwords do not match</div>';
                header('Refresh:2; url='.URL.'/user/'.$auth->getUserId().'/'.strtolower(toAscii($data['username'])));
            }
        }
        //END *CHANGE Password

        //Upload avatar
        if (isset($_POST['upload'])){

            $storage = new \Upload\Storage\FileSystem('uploads/avatars');
            $file = new \Upload\File('file', $storage);

            $new_filename = strtolower(toAscii($data['username'])).'-'.generateRandomString(25);
            $file->setName($new_filename);

            $file->addValidations(array(
                new \Upload\Validation\Mimetype(array('image/png', 'image/gif', 'image/jpeg')),
                new \Upload\Validation\Size('3M')
            ));

            try {

                $width = $file->getDimensions();

                if ($width['width'] <= 500){
                    if ($width['height'] <= 500){
                        // Success!
                        $file->upload();

                        $db->update(
                            'users',
                            [
                                // set
                                'avatar' => '/uploads/avatars/'.$new_filename.'.'.$file->getExtension()
                            ],
                            [
                                // where
                                'id' => $auth->getUserId()
                            ]
                        );

                        $achievements = $data['achievements'];

                        if (strpos($achievements, ',19,') == false) {
                            $upload_avatar = $achievements . '19,';
                            $db->update( 'users', [ 'achievements' => $upload_avatar ], [ 'id' => $data['id'] ] );
                            create_notify($data['id'],'fas fa-user-secret','primary','You have met the achievement','You have met the achievement: <b>Upload your first avatar</b>');
                        }

                        header('Location: '.URL.'/user/'.$auth->getUserId().'/'.strtolower(toAscii($data['username'])));
                    } else {
                        $msg = '<div class="alert alert-danger">Bad image size. Max is 500x500 pixels</div>';
                        header('Refresh:2; url='.URL.'/user/'.$auth->getUserId().'/'.strtolower(toAscii($data['username'])));
                    }
                } else {
                    $msg = '<div class="alert alert-danger">Bad image size. Max is 500x500 pixels</div>';
                    header('Refresh:2; url='.URL.'/user/'.$auth->getUserId().'/'.strtolower(toAscii($data['username'])));
                }

            } catch (\Exception $e) {
                // Fail!
                $msg = '<div class="alert alert-danger">Bad picture</div>';
                header('Refresh:2; url='.URL.'/user/'.$auth->getUserId().'/'.strtolower(toAscii($data['username'])));
            }

        }
        //END *Upload avatar
    }

}

$achievements_count_1 = explode(',', $data['achievements']);
$zero = 0;
foreach ($achievements_count_1 as $achievements_sum){

    $zero++;

}

$achievements_count = $zero - 2;

$title = $data['username'].' - Profile';

echo '
<!DOCTYPE html>
<html lang="en">

    <head>'; require 'template/template_headtags.php'; echo '</head>

    <body class="app sidebar-mini rtl">
    
        '; require 'template/template_head.php';

           require 'template/template_sidebar.php'; echo '
        
        
        
            <main class="app-content">
            <div class="row user">
                <div class="col-md-12">
                    <div class="profile">
                        <div class="info"><img class="user-img" src="'.URL.$data['avatar'].'">
                            <h4>'.$data['username'].' '.($data['important'] == 1 ? '<i class="far fa-check-circle ml-2 important-check" title="Verified user"></i>' : '').'</h4>
                            <p>@'.strtolower(toAscii($data['username'])).'</p>
                        </div>
                        <div class="cover-image" style="background-image: url(\''.URL.'/assets/images/title_images/no.png\');"></div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="tile p-0">
                        <ul class="nav flex-column nav-tabs user-tabs">
                            <li class="nav-item"><a class="nav-link active" href="#user-profile" data-toggle="tab">Profil</a></li>
                            <li class="nav-item"><a class="nav-link" href="#user-statistics" data-toggle="tab">Statistics</a></li>
                            <li class="nav-item"><a class="nav-link" href="#user-achievements" data-toggle="tab">Achievements</a></li>';

                            if ($auth->isLoggedIn()) {

                                if ($auth->getUserId() == $_GET['id']) {

                                    echo '
                                    <li class="nav-item"><a class="nav-link" href="#user-settings" data-toggle="tab">Main settings</a></li>
                                    <li class="nav-item"><a class="nav-link" href="#user-password" data-toggle="tab">Change password</a></li>
                                    <li class="nav-item"><a class="nav-link" href="#user-avatar" data-toggle="tab">Change avatar</a></li>';

                                }

                            }

                        echo '
                        </ul>
                    </div>
                </div>
                <div class="col-md-9">
                
                    '.$msg.'
                
                    <div class="tab-content">
                    
                        <div class="tab-pane fade active show" id="user-profile">
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="widget-small primary coloured-icon"><i class="icon fas fa-download fa-3x"></i>
                                        <div class="info">
                                            <h5 class="font-weight-normal">Downloaded torrents</h5>
                                            <p><b>'.$data['number_of_downloaded_torrents'].'</b></p>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="widget-small info coloured-icon"><i class="icon fas fa-upload fa-3x"></i>
                                        <div class="info">
                                            <h5 class="font-weight-normal">Added torrents</h5>
                                            <p><b>'.$addedtorrentscount.'</b></p>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="widget-small danger coloured-icon"><i class="icon fa fa-star fa-3x"></i>
                                        <div class="info">
                                            <h5 class="font-weight-normal">Last login</h5>
                                            <p><b>'.date('d.m.Y H:i', $data['last_login']).'</b></p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="timeline-post">
                            
                                <h4>About me</h4>';

                                if ($data['aboutme']){
                                    echo showBBcodes(htmlcode($data['aboutme']));
                                } else {
                                    echo '<p class="text-center">User not to write anything about himself at the moment</p>';
                                }

                            echo '
                            </div>
                            
                        </div>
                        
                        <div class="tab-pane fade" id="user-statistics">
                            <div class="timeline-post">
                                <h4>Statistics</h4>
                            
                                    <div class="form-group row mb-0 no-gutters">
                                        <label class="control-label col-md-3 text-right text-primary pr-2">Meno:</label>
                                        <div class="col-md-8">
                                            '.$data['username'].'
                                        </div>
                                    </div>
                                    
                                    <div class="form-group row mb-0 no-gutters">
                                        <label class="control-label col-md-3 text-right text-primary pr-2">Rank:</label>
                                        <div class="col-md-8">
                                            '.$data['roles_mask'].'
                                        </div>
                                    </div>
                                    
                                    <div class="form-group row mb-0 no-gutters">
                                        <label class="control-label col-md-3 text-right text-primary pr-2">ID:</label>
                                        <div class="col-md-8">
                                            '.$data['id'].'
                                        </div>
                                    </div>
                                    
                                    <div class="form-group row mb-0 no-gutters">
                                        <label class="control-label col-md-3 text-right text-primary pr-2">Status:</label>
                                        <div class="col-md-8">
                                            '.$status[$data['status']].'
                                        </div>
                                    </div>
                                    
                                    <div class="form-group row mb-0 no-gutters">
                                        <label class="control-label col-md-3 text-right text-primary pr-2">Registration date:</label>
                                        <div class="col-md-8">
                                            '.date('d.m.Y H:i', $data['registered']).'
                                        </div>
                                    </div>
                                    
                                    <div class="form-group row mb-0 no-gutters">
                                        <label class="control-label col-md-3 text-right text-primary pr-2">Last login:</label>
                                        <div class="col-md-8">
                                            '.date('d.m.Y H:i', $data['last_login']).'
                                        </div>
                                    </div>
                                    
                                    <div class="form-group row mb-0 no-gutters">
                                        <label class="control-label col-md-3 text-right text-primary pr-2">Downloaded torrents:</label>
                                        <div class="col-md-8">
                                            '.$data['number_of_downloaded_torrents'].'
                                        </div>
                                    </div>
                                    
                                    <div class="form-group row mb-0 no-gutters">
                                        <label class="control-label col-md-3 text-right text-primary pr-2">Added torrents:</label>
                                        <div class="col-md-8">
                                            '.$addedtorrentscount.'
                                        </div>
                                    </div>
                                    
                                    <div class="form-group row mb-0 no-gutters">
                                        <label class="control-label col-md-3 text-right text-primary pr-2">Achieved achievements:</label>
                                        <div class="col-md-8">
                                            '.$achievements_count.' of 20
                                        </div>
                                    </div>
                                    
                                    <div class="form-group row mb-0 no-gutters">
                                        <label class="control-label col-md-3 text-right text-primary pr-2">Written Comments:</label>
                                        <div class="col-md-8">
                                            '.$writtencommentscount.'
                                        </div>
                                    </div>
                            
                            </div>
                        </div>
                        
                        <div class="tab-pane fade" id="user-achievements">
                        
                            <div class="timeline-post">
                            
                                <h4>Achievements <small class="text-muted">'.$achievements_count.' of 20</small></h4>';

                                $achievements = $data['achievements'];

                                echo '
                                
                                <hr>
                                
                                <div class="row">
                                    <div class="col-md-1 text-center">';
                                        if (strpos($achievements, ',1,') !== false) {echo '<div><i class="fas fa-upload fa-3x" style="opacity: 0.4;"></i></div><div style="margin-top: -41px;"><i class="far fa-check-circle fa-3x text-primary"></i></div>';
                                        } else {echo '<i class="fas fa-upload fa-3x"></i>';}
                                    echo '
                                    </div>
                                    <div class="col-md-11">
                                        <b>Upload 1 torrents</b>
                                    </div>
                                </div>
                                
                                <hr>
                                
                                <div class="row">
                                    <div class="col-md-1 text-center">';
                                        if (strpos($achievements, ',2,') !== false) {echo '<div><i class="fas fa-upload fa-3x" style="opacity: 0.4;"></i></div><div style="margin-top: -41px;"><i class="far fa-check-circle fa-3x text-primary"></i></div>';
                                        } else {echo '<i class="fas fa-upload fa-3x"></i>';}
                                    echo '
                                    </div>
                                    <div class="col-md-11">
                                        <b>Upload 5 torrents</b>
                                    </div>
                                </div>
                                
                                <hr>
                                
                                <div class="row">
                                    <div class="col-md-1 text-center">';
                                        if (strpos($achievements, ',3,') !== false) {echo '<div><i class="fas fa-upload fa-3x" style="opacity: 0.4;"></i></div><div style="margin-top: -41px;"><i class="far fa-check-circle fa-3x text-primary"></i></div>';
                                        } else {echo '<i class="fas fa-upload fa-3x"></i>';}
                                    echo '
                                    </div>
                                    <div class="col-md-11">
                                        <b>Upload 50 torrents</b>
                                    </div>
                                </div>
                                
                                <hr>
                                
                                <div class="row">
                                    <div class="col-md-1 text-center">';
                                        if (strpos($achievements, ',4,') !== false) {echo '<div><i class="fas fa-upload fa-3x" style="opacity: 0.4;"></i></div><div style="margin-top: -41px;"><i class="far fa-check-circle fa-3x text-primary"></i></div>';
                                        } else {echo '<i class="fas fa-upload fa-3x"></i>';}
                                    echo '
                                    </div>
                                    <div class="col-md-11">
                                        <b>Upload 500 torrents <span class="badge badge-warning">Rare</span></b>
                                    </div>
                                </div>
                                
                                <hr>
                            
                                <div class="row">
                                    <div class="col-md-1 text-center">';
                                        if (strpos($achievements, ',5,') !== false) {echo '<div><i class="fas fa-download fa-3x" style="opacity: 0.4;"></i></div><div style="margin-top: -41px;"><i class="far fa-check-circle fa-3x text-primary"></i></div>';
                                        } else {echo '<i class="fas fa-download fa-3x"></i>';}
                                    echo '
                                    </div>
                                    <div class="col-md-11">
                                        <b>Download 1 torrents</b>
                                    </div>
                                </div>
                                
                                <hr>
                                
                                <div class="row">
                                    <div class="col-md-1 text-center">';
                                        if (strpos($achievements, ',6,') !== false) {echo '<div><i class="fas fa-download fa-3x" style="opacity: 0.4;"></i></div><div style="margin-top: -41px;"><i class="far fa-check-circle fa-3x text-primary"></i></div>';
                                        } else {echo '<i class="fas fa-download fa-3x"></i>';}
                                    echo '
                                    </div>
                                    <div class="col-md-11">
                                        <b>Download 50 torrents</b>
                                    </div>
                                </div>
                                
                                <hr>
                                
                                <div class="row">
                                    <div class="col-md-1 text-center">';
                                        if (strpos($achievements, ',7,') !== false) {echo '<div><i class="fas fa-download fa-3x" style="opacity: 0.4;"></i></div><div style="margin-top: -41px;"><i class="far fa-check-circle fa-3x text-primary"></i></div>';
                                        } else {echo '<i class="fas fa-download fa-3x"></i>';}
                                    echo '
                                    </div>
                                    <div class="col-md-11">
                                        <b>Download 500 torrents</b>
                                    </div>
                                </div>
                                
                                <hr>

                                <div class="row">
                                    <div class="col-md-1 text-center">';
                                        if (strpos($achievements, ',8,') !== false) {echo '<div><i class="fas fa-download fa-3x" style="opacity: 0.4;"></i></div><div style="margin-top: -41px;"><i class="far fa-check-circle fa-3x text-primary"></i></div>';
                                        } else {echo '<i class="fas fa-download fa-3x"></i>';}
                                    echo '
                                    </div>
                                    <div class="col-md-11">
                                        <b>Download 1,000 torrents</b>
                                    </div>
                                </div>
                                
                                <hr>

                                <div class="row">
                                    <div class="col-md-1 text-center">';
                                        if (strpos($achievements, ',9,') !== false) {echo '<div><i class="fas fa-download fa-3x" style="opacity: 0.4;"></i></div><div style="margin-top: -41px;"><i class="far fa-check-circle fa-3x text-primary"></i></div>';
                                        } else {echo '<i class="fas fa-download fa-3x"></i>';}
                                    echo '
                                    </div>
                                    <div class="col-md-11">
                                        <b>Download 2,000 torrents <span class="badge badge-warning">Rare</span></b>
                                    </div>
                                </div>
                                
                                <hr>
                                        
                                <div class="row">
                                    <div class="col-md-1 text-center">';
                                        if (strpos($achievements, ',10,') !== false) {echo '<div><i class="fas fa-comment fa-3x" style="opacity: 0.4;"></i></div><div style="margin-top: -41px;"><i class="far fa-check-circle fa-3x text-primary"></i></div>';
                                        } else {echo '<i class="fas fa-comment fa-3x"></i>';}
                                    echo '
                                    </div>
                                    <div class="col-md-11">
                                        <b>Write 1 comments</b>
                                    </div>
                                </div>
                                
                                <hr>
                                
                                <div class="row">
                                    <div class="col-md-1 text-center">';
                                        if (strpos($achievements, ',11,') !== false) {echo '<div><i class="fas fa-comment fa-3x" style="opacity: 0.4;"></i></div><div style="margin-top: -41px;"><i class="far fa-check-circle fa-3x text-primary"></i></div>';
                                        } else {echo '<i class="fas fa-comment fa-3x"></i>';}
                                    echo '
                                    </div>
                                    <div class="col-md-11">
                                        <b>Write 10 comments</b>
                                    </div>
                                </div>
                                
                                <hr>
                                
                                <div class="row">
                                    <div class="col-md-1 text-center">';
                                        if (strpos($achievements, ',12,') !== false) {echo '<div><i class="fas fa-comment fa-3x" style="opacity: 0.4;"></i></div><div style="margin-top: -41px;"><i class="far fa-check-circle fa-3x text-primary"></i></div>';
                                        } else {echo '<i class="fas fa-comment fa-3x"></i>';}
                                    echo '
                                    </div>
                                    <div class="col-md-11">
                                        <b>Write 20 comments</b>
                                    </div>
                                </div>
                                
                                <hr>

                                <div class="row">
                                    <div class="col-md-1 text-center">';
                                        if (strpos($achievements, ',13,') !== false) {echo '<div><i class="fas fa-comment fa-3x" style="opacity: 0.4;"></i></div><div style="margin-top: -41px;"><i class="far fa-check-circle fa-3x text-primary"></i></div>';
                                        } else {echo '<i class="fas fa-comment fa-3x"></i>';}
                                    echo '
                                    </div>
                                    <div class="col-md-11">
                                        <b>Write 50 comments</b>
                                    </div>
                                </div>
                                
                                <hr>

                                <div class="row">
                                    <div class="col-md-1 text-center">';
                                        if (strpos($achievements, ',14,') !== false) {echo '<div><i class="fas fa-comment fa-3x" style="opacity: 0.4;"></i></div><div style="margin-top: -41px;"><i class="far fa-check-circle fa-3x text-primary"></i></div>';
                                        } else {echo '<i class="fas fa-comment fa-3x"></i>';}
                                    echo '
                                    </div>
                                    <div class="col-md-11">
                                        <b>Write 100 comments <span class="badge badge-warning">Rare</span></b>
                                    </div>
                                </div>
                                
                                <hr>
                                
                                <div class="row">
                                    <div class="col-md-1 text-center">';
                                        if (strpos($achievements, ',15,') !== false) {echo '<div><i class="fas fa-star fa-3x" style="opacity: 0.4;"></i></div><div style="margin-top: -41px;"><i class="far fa-check-circle fa-3x text-primary"></i></div>';
                                        } else {echo '<i class="fas fa-star fa-3x"></i>';}
                                    echo '
                                    </div>
                                    <div class="col-md-11">
                                        <b>Rate 1 torrents</b>
                                    </div>
                                </div>
                                
                                <hr>
                                
                                <div class="row">
                                    <div class="col-md-1 text-center">';
                                        if (strpos($achievements, ',16,') !== false) {echo '<div><i class="fas fa-star fa-3x" style="opacity: 0.4;"></i></div><div style="margin-top: -41px;"><i class="far fa-check-circle fa-3x text-primary"></i></div>';
                                        } else {echo '<i class="fas fa-star fa-3x"></i>';}
                                    echo '
                                    </div>
                                    <div class="col-md-11">
                                        <b>Rate 10 torrents</b>
                                    </div>
                                </div>
                                
                                <hr>
                                
                                <div class="row">
                                    <div class="col-md-1 text-center">';
                                        if (strpos($achievements, ',17,') !== false) {echo '<div><i class="fas fa-star fa-3x" style="opacity: 0.4;"></i></div><div style="margin-top: -41px;"><i class="far fa-check-circle fa-3x text-primary"></i></div>';
                                        } else {echo '<i class="fas fa-star fa-3x"></i>';}
                                    echo '
                                    </div>
                                    <div class="col-md-11">
                                        <b>Rate 20 torrents</b>
                                    </div>
                                </div>
                                
                                <hr>

                                <div class="row">
                                    <div class="col-md-1 text-center">';
                                        if (strpos($achievements, ',18,') !== false) {echo '<div><i class="fas fa-star fa-3x" style="opacity: 0.4;"></i></div><div style="margin-top: -41px;"><i class="far fa-check-circle fa-3x text-primary"></i></div>';
                                        } else {echo '<i class="fas fa-star fa-3x"></i>';}
                                    echo '
                                    </div>
                                    <div class="col-md-11">
                                        <b>Rate 50 torrents</b>
                                    </div>
                                </div>
                                
                                <hr>
                                
                                <div class="row">
                                    <div class="col-md-1 text-center">';
                                        if (strpos($achievements, ',19,') !== false) {echo '<div><i class="fas fa-user-secret fa-3x" style="opacity: 0.4;"></i></div><div style="margin-top: -41px;"><i class="far fa-check-circle fa-3x text-primary"></i></div>';
                                        } else {echo '<i class="fas fa-user-secret fa-3x"></i>';}
                                    echo '
                                    </div>
                                    <div class="col-md-11">
                                        <b>Upload your first avatar</b>
                                    </div>
                                </div>
                                
                                <hr>
                                
                                <div class="row">
                                    <div class="col-md-1 text-center">';
                                        if (strpos($achievements, ',20,') !== false) {echo '<div><i class="fas fa-male fa-3x" style="opacity: 0.4;"></i></div><div style="margin-top: -41px;"><i class="far fa-check-circle fa-3x text-primary"></i></div>';
                                        } else {echo '<i class="fas fa-male fa-3x"></i>';}
                                    echo '
                                    </div>
                                    <div class="col-md-11">
                                        <b>Add something about your profile to your profile</b>
                                    </div>
                                </div>
                                
                                <hr>
                            
                            </div>
                        
                        </div>';

                        if ($auth->isLoggedIn()) {

                            if ($auth->getUserId() == $_GET['id']) {

                                echo '
                                <div class="tab-pane fade" id="user-settings">
                                    <div class="tile user-settings">
                                        <h4 class="line-head">Settings</h4>
                                        
                                        <form method="post" action="">
                                        
                                            <div class="form-group row">
                                                <label class="control-label col-md-3" for="e">Email</label>
                                                <div class="col-md-8">
                                                    <input class="form-control col-md-8" type="email" name="email" id="e" disabled required value="' . $data['email'] . '" placeholder="Enter email address">
                                                </div>
                                            </div>
                                        
                                            <div class="form-group row">
                                                <label class="control-label col-md-3" for="u">Username</label>
                                                <div class="col-md-8">
                                                    <input class="form-control col-md-8" type="text" name="username" required placeholder="Username" id="u" value="' . $data['username'] . '">
                                                </div>
                                            </div>
                                            
                                            <div class="form-group row">
                                                <label class="control-label col-md-3" for="a">About me</label>
                                                <div class="col-md-8">
                                                    <textarea class="form-control" rows="4" id="a" name="aboutme" placeholder="Lorem ipsum dol...">' . $data['aboutme'] . '</textarea>
                                                </div>
                                            </div>
                                            
                                            <div class="row mb-10">
                                                <div class="col-md-12">
                                                    <button class="btn btn-primary" type="submit" name="settings"><i class="fa fa-fw fa-lg fa-check-circle"></i> Save</button>
                                                </div>
                                            </div>
                                            
                                        </form>
                                        
                                    </div>
                                </div>
                                
                                <div class="tab-pane fade" id="user-password">
                                    <div class="tile user-settings">
                                        <h4 class="line-head">Change password</h4>
                                        
                                        <form method="POST" action="">
                                        
                                            <div class="form-group row">
                                                <label class="control-label col-md-3">Old password</label>
                                                <div class="col-md-8">
                                                    <input class="form-control col-md-8" name="oldPassword" type="password" placeholder="********">
                                                </div>
                                            </div>
                                            
                                            <div class="form-group row">
                                                <label class="control-label col-md-3">New password</label>
                                                <div class="col-md-8">
                                                    <input class="form-control col-md-8" name="newPassword" type="password" placeholder="********">
                                                </div>
                                            </div>
                                            
                                            <div class="form-group row">
                                                <label class="control-label col-md-3">RE New password</label>
                                                <div class="col-md-8">
                                                    <input class="form-control col-md-8" name="RenewPassword" type="password" placeholder="********">
                                                </div>
                                            </div>
                                            
                                            <div class="row mb-10">
                                                <div class="col-md-12">
                                                    <button class="btn btn-primary" type="submit" name="password"><i class="fa fa-fw fa-lg fa-check-circle"></i> Save</button>
                                                </div>
                                            </div>
                                            
                                        </form>
                                        
                                    </div>
                                </div>
                                
                                <div class="tab-pane fade" id="user-avatar">
                                    <div class="tile user-settings">
                                        <h4 class="line-head">Change avatar</h4>
                                        
                                        
                                        <div class="text-left">
                                            
                                            <form method="post" action="" enctype="multipart/form-data">
                                        
                                                <img class="rounded float-left mr-3" style="width: 150px;height: 150px;" src="'.URL.$data['avatar'].'">
                                                <span class="text-muted">Click Browse to upload an image<br>
                                                Max. file size: 3 MB / Max. size: 500x500 pixels</span><br>
                                                <input type="file" name="file" class="mt-4" accept="image/*"><br>
                                                <button type="submit" name="upload" class="btn btn-primary mt-3">Upload</button>
                                            
                                            </form>
                                            
                                        </div>
                                        
                                    </div>
                                </div>';

                            }

                        }

                    echo '
                    </div>
                </div>
            </div>
          
            '; require 'template/template_footer.php'; echo '
          
        </main>
        
        '; require 'template/template_scripts.php'; echo '
        
    </body>
</html>';