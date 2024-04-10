<?php

require '../app/app.php';
require '../app/Functions.php';

$users = $db->select(
    'SELECT * FROM users'
);

foreach ($users as $data){

    $achievements = $data['achievements'];

    //********************** Downloads torrents **************************
    $downloads_torrents_count = $data['number_of_downloaded_torrents'];

    if (strpos($achievements, ',5,') == false) {
        $downloads_torrent_1 = $achievements . '5,';
        if ($downloads_torrents_count >= 1) {
            $db->update( 'users', [ 'achievements' => $downloads_torrent_1 ], [ 'id' => $data['id'] ] );

            create_notify($data['id'],'fas fa-download','primary','You have met the achievement','You have met the achievement: <b>Download 1 torrents</b>');

        }
    }

    if (strpos($achievements, ',6,') == false) {
        $downloads_torrent_2 = $achievements . '6,';
        if ($downloads_torrents_count >= 50) {
            $db->update( 'users', [ 'achievements' => $downloads_torrent_2 ], [ 'id' => $data['id'] ] );

            create_notify($data['id'],'fas fa-download','primary','You have met the achievement','You have met the achievement: <b>Download 50 torrents</b>');

        }
    }

    if (strpos($achievements, ',7,') == false) {
        $downloads_torrent_3 = $achievements . '7,';
        if ($downloads_torrents_count >= 500) {
            $db->update( 'users', [ 'achievements' => $downloads_torrent_3 ], [ 'id' => $data['id'] ] );

            create_notify($data['id'],'fas fa-download','primary','You have met the achievement','You have met the achievement: <b>Download 500 torrents</b>');

        }
    }

    if (strpos($achievements, ',8,') == false) {
        $downloads_torrent_4 = $achievements . '8,';
        if ($downloads_torrents_count >= 1000) {
            $db->update( 'users', [ 'achievements' => $downloads_torrent_4 ], [ 'id' => $data['id'] ] );

            create_notify($data['id'],'fas fa-download','primary','You have met the achievement','You have met the achievement: <b>Download 1,000 torrents</b>');

        }
    }

    if (strpos($achievements, ',9,') == false) {
        $downloads_torrent_5 = $achievements . '9,';
        if ($downloads_torrents_count >= 2000) {
            $db->update( 'users', [ 'achievements' => $downloads_torrent_5 ], [ 'id' => $data['id'] ] );

            create_notify($data['id'],'fas fa-download','warning','You have met the achievement','You have met the achievement: <b>Download 2,000 torrents</b>');

        }
    }
    //*******************************************************************

}