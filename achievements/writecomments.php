<?php

require '../app/app.php';

$users = $db->select(
    'SELECT * FROM users'
);

foreach ($users as $data){

    $achievements = $data['achievements'];

    //********************** Addtorrent *********************************
    $write_comments_count = $db->selectValue('SELECT count(*) FROM comments WHERE owner = ' . $data['id']);

    if (strpos($achievements, ',10,') == false) {
        $write_comments_1 = $achievements . '10,';
        if ($write_comments_count >= 1) {
            $db->update( 'users', [ 'achievements' => $write_comments_1 ], [ 'id' => $data['id'] ] );

            create_notify($data['id'],'fas fa-comment','primary','You have met the achievement','You have met the achievement: <b>Write 1 comments</b>');

        }
    }

    if (strpos($achievements, ',11,') == false) {
        $write_comments_2 = $achievements . '11,';
        if ($write_comments_count >= 10) {
            $db->update( 'users', [ 'achievements' => $write_comments_2 ], [ 'id' => $data['id'] ] );

            create_notify($data['id'],'fas fa-comment','primary','You have met the achievement','You have met the achievement: <b>Write 10 comments</b>');

        }
    }

    if (strpos($achievements, ',12,') == false) {
        $write_comments_3 = $achievements . '12,';
        if ($write_comments_count >= 20) {
            $db->update( 'users', [ 'achievements' => $write_comments_3 ], [ 'id' => $data['id'] ] );

            create_notify($data['id'],'fas fa-comment','primary','You have met the achievement','You have met the achievement: <b>Write 20 comments</b>');

        }
    }

    if (strpos($achievements, ',13,') == false) {
        $write_comments_4 = $achievements . '13,';
        if ($write_comments_count >= 50) {
            $db->update( 'users', [ 'achievements' => $write_comments_4 ], [ 'id' => $data['id'] ] );

            create_notify($data['id'],'fas fa-comment','primary','You have met the achievement','You have met the achievement: <b>Write 50 comments</b>');

        }
    }

    if (strpos($achievements, ',14,') == false) {
        $write_comments_5 = $achievements . '14,';
        if ($write_comments_count >= 100) {
            $db->update( 'users', [ 'achievements' => $write_comments_5 ], [ 'id' => $data['id'] ] );

            create_notify($data['id'],'fas fa-comment','warning','You have met the achievement','You have met the achievement: <b>Write 100 comments</b>');

        }
    }
    //*******************************************************************

}