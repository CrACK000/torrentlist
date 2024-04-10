<?php

require '../app/app.php';
require '../app/Functions.php';

$users = $db->select(
    'SELECT * FROM users'
);

foreach ($users as $data){

    $achievements = $data['achievements'];

    //********************** Addtorrent *********************************
    $rating_torrents_count = $db->selectValue('SELECT count(*) FROM rating WHERE owner = ' . $data['id']);

    if (strpos($achievements, ',15,') == false) {
        $rating_torrents_1 = $achievements . '15,';
        if ($rating_torrents_count >= 1) {
            $db->update( 'users', [ 'achievements' => $rating_torrents_1 ], [ 'id' => $data['id'] ] );

            create_notify($data['id'],'fas fa-star','primary','You have met the achievement','You have met the achievement: <b>Rate 1 torrents</b>');

        }
    }

    if (strpos($achievements, ',16,') == false) {
        $rating_torrents_2 = $achievements . '16,';
        if ($rating_torrents_count >= 10) {
            $db->update( 'users', [ 'achievements' => $rating_torrents_2 ], [ 'id' => $data['id'] ] );

            create_notify($data['id'],'fas fa-star','primary','You have met the achievement','You have met the achievement: <b>Rate 10 torrents</b>');

        }
    }

    if (strpos($achievements, ',17,') == false) {
        $rating_torrents_3 = $achievements . '17,';
        if ($rating_torrents_count >= 20) {
            $db->update( 'users', [ 'achievements' => $rating_torrents_3 ], [ 'id' => $data['id'] ] );

            create_notify($data['id'],'fas fa-star','primary','You have met the achievement','You have met the achievement: <b>Rate 20 torrents</b>');

        }
    }

    if (strpos($achievements, ',18,') == false) {
        $rating_torrents_4 = $achievements . '18,';
        if ($rating_torrents_count >= 50) {
            $db->update( 'users', [ 'achievements' => $rating_torrents_4 ], [ 'id' => $data['id'] ] );

            create_notify($data['id'],'fas fa-star','primary','You have met the achievement','You have met the achievement: <b>Rate 50 torrents</b>');

        }
    }
    //*******************************************************************

}