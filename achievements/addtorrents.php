<?php

require '../app/app.php';
require '../app/Functions.php';

$users = $db->select(
    'SELECT * FROM users'
);

foreach ($users as $data){

    $achievements = $data['achievements'];

    //********************** Addtorrent *********************************
    $addtorrentscount = $db->selectValue('SELECT count(*) FROM torrents WHERE owner = ' . $data['id']);

    if (strpos($achievements, ',1,') == false) {
        $add_torrent_1 = $achievements . '1,';
        if ($addtorrentscount >= 1) {
            $db->update( 'users', [ 'achievements' => $add_torrent_1 ], [ 'id' => $data['id'] ] );

            create_notify($data['id'],'fas fa-upload','primary','You have met the achievement','You have met the achievement: <b>Upload 1 torrents</b>');

        }
    }

    if (strpos($achievements, ',2,') == false) {
        $add_torrent_2 = $achievements . '2,';
        if ($addtorrentscount >= 5) {
            $db->update( 'users', [ 'achievements' => $add_torrent_2 ], [ 'id' => $data['id'] ] );

            create_notify($data['id'],'fas fa-upload','primary','You have met the achievement','You have met the achievement: <b>Upload 5 torrents</b>');

        }
    }

    if (strpos($achievements, ',3,') == false) {
        $add_torrent_3 = $achievements . '3,';
        if ($addtorrentscount >= 50) {
            $db->update( 'users', [ 'achievements' => $add_torrent_3 ], [ 'id' => $data['id'] ] );

            create_notify($data['id'],'fas fa-upload','primary','You have met the achievement','You have met the achievement: <b>Upload 50 torrents</b>');

        }
    }

    if (strpos($achievements, ',4,') == false) {
        $add_torrent_4 = $achievements . '4,';
        if ($addtorrentscount >= 500) {
            $db->update( 'users', [ 'achievements' => $add_torrent_4 ], [ 'id' => $data['id'] ] );

            create_notify($data['id'],'fas fa-upload','warning','You have met the achievement','You have met the achievement: <b>Upload 500 torrents</b>');

        }
    }
    //*******************************************************************

}