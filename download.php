<?php

    require 'app/app.php';

    $select = $db->selectRow(
        'SELECT * FROM torrents WHERE id = ?',
        [ $_GET['id'] ]
    );

    $count = $select['number_of_downloads'] + 1;

    $db->update(
        'torrents',
        [
            'number_of_downloads' => $count
        ],
        [
            // where
            'id' => $_GET['id']
        ]
    );

    if ($auth->getUserId()) {

        $user_count_d = $inuser['number_of_downloaded_torrents'] + 1;

        $db->update(
            'users',
            [
                'number_of_downloaded_torrents' => $user_count_d
            ],
            [
                // where
                'id' => $auth->getUserId()
            ]
        );

    }

    $file = "uploads/torrents/".$select['file'];

    if(!file_exists($file)) { header('Location: '.URL.''); die("I'm sorry, the file doesn't seem to exist."); }

    $type = filetype($file);
    // Get a date and timestamp
    $today = date("F j, Y, g:i a");
    $time = time();
    // Send file headers
    header("Content-type: $type");
    header("Content-Disposition: attachment;filename={$select['file']}");
    header("Content-Transfer-Encoding: binary");
    header('Pragma: no-cache');
    header('Expires: 0');
    // Send the file contents.
    set_time_limit(0);
    readfile($file);