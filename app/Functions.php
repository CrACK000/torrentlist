<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

function formatSizeUnits($bytes): string
{

    if ($bytes >= 1073741824) {
        $bytes = number_format($bytes / 1073741824, 2) . ' GB';
    } elseif ($bytes >= 1048576) {
        $bytes = number_format($bytes / 1048576, 2) . ' MB';
    } elseif ($bytes >= 1024) {
        $bytes = number_format($bytes / 1024, 2) . ' KB';
    } elseif ($bytes > 1) {
        $bytes = $bytes . ' bytes';
    } elseif ($bytes == 1) {
        $bytes = $bytes . ' byte';
    } else {
        $bytes = '0 bytes';
    }

    return $bytes;

}

function checkRemoteFile($url): bool
{

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_NOBODY, 1);
    curl_setopt($ch, CURLOPT_FAILONERROR, 1);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

    if(curl_exec($ch)!==FALSE) {

        return true;

    } else {

        return false;

    }

}

function toAscii($str, $replace=array(), $delimiter='-') {

    if( !empty($replace) ) {
        $str = str_replace((array)$replace, ' ', $str);
    }
    $clean = iconv('UTF-8', 'ASCII//TRANSLIT', $str);
    $clean = preg_replace("/[^a-zA-Z0-9\/_|+ -]/", '', $clean);
    $clean = trim($clean, '-');
    return preg_replace("/[\/_|+ -]+/", $delimiter, $clean);

}

function generateRandomString($length = 10): string
{

    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $charactersLength = strlen($characters);
    $randomString = '';

    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[rand(0, $charactersLength - 1)];
    }

    return $randomString;

}

function substrwords($text, $maxchar, $end='...') {

    if (strlen($text) > $maxchar || $text == '') {

        $words = preg_split('/\s/', $text);
        $output = '';
        $i      = 0;

        while (1) {
            $length = strlen($output)+strlen($words[$i]);

            if ($length > $maxchar) {
                break;
            } else {
                $output .= " " . $words[$i];
                ++$i;
            }

        }
        $output .= $end;

    } else {

        $output = $text;

    }

    return $output;

}

function phpmailer($for, $hisname, $subject, $body, $nohtmlbody){

    $mail = new PHPMailer(true);                       // Passing `true` enables exceptions
    try {
        //Server settings
        $mail->SMTPDebug = 0;                                   // Enable verbose debug output
        $mail->isSMTP();                                        // Set mailer to use SMTP
        $mail->Host = $_ENV['SMTP_HOST'];                       // Specify main and backup SMTP servers
        $mail->SMTPAuth = true;                                 // Enable SMTP authentication
        $mail->Username = $_ENV['SMTP_USERNAME'];               // SMTP username
        $mail->Password = $_ENV['SMTP_PASSWORD'];               // SMTP password
        $mail->SMTPSecure = 'ssl';                              // Enable TLS encryption, `ssl` also accepted
        $mail->Port = $_ENV['SMTP_PORT'];                       // TCP port to connect to

        //Recipients
        $mail->setFrom($_ENV['SMTP_FROM'], 'Torrentlist');
        $mail->addAddress($for, $hisname);                      // Add a recipient

        //Content
        $mail->isHTML(true);                              // Set email format to HTML
        $mail->Subject = $subject;
        $mail->Body    = $body;
        $mail->AltBody = $nohtmlbody;

        $mail->send();

        return true;

    } catch (Exception $e) {
        return false;
    }

}

function htmlcode($text) {

    $search = array(
        '<',
        '>',
        '=',
        "'",
        '"'
    );

    $replace = array(
        '&#60;',
        '&#62;',
        '&#61;',
        '&#39;',
        '&#34;'
    );

    return str_replace($search, $replace, $text);

}

function showBBcodes($text) {

    // BBcode array
    $find = array(
        '~\[b\](.*?)\[/b\]~s',
        '~\[i\](.*?)\[/i\]~s',
        '~\[u\](.*?)\[/u\]~s',
        '~\[quote\](.*?)\[/quote\]~s',
        '~\[size=(.*?)\](.*?)\[/size\]~s',
        '~\[color=(.*?)\](.*?)\[/color\]~s',
        '~\[url\]((?:ftp|https?)://.*?)\[/url\]~s',
        '~\[img\](https?://.*?\.(?:jpg|jpeg|gif|png|bmp))\[/img\]~s'
    );

    // HTML tags to replace BBcode
    $replace = array(
        '<b>$1</b>',
        '<i>$1</i>',
        '<span style="text-decoration:underline;">$1</span>',
        '<pre>$1</'.'pre>',
        '<span style="font-size:$1px;">$2</span>',
        '<span style="color:$1;">$2</span>',
        '<a href="$1">$1</a>',
        '<img class="img-thumbnail" style="max-width: 100%;" src="$1" alt="" />'
    );

    // Replacing the BBcodes with corresponding HTML tags
    return preg_replace($find,$replace,$text);

}

function create_notify($owner,$icon,$color,$title,$text){

    global $db;

    if ($owner == 0){
        $view = 1;
    } else {
        $view = 0;
    }

    $db->insert(
        'notifications',
        [
            // set
            'owner' => $owner,
            'icon'  => $icon,
            'color' => $color,
            'title' => $title,
            'view'  => $view,
            'text'  => $text,
            'date'  => time()
        ]
    );

}

function time_elapsed_string($ptime)
{
    $etime = time() - $ptime;

    if ($etime < 1)
    {
        return '0 seconds';
    }

    $a = array( 365 * 24 * 60 * 60  =>  'year',
        30 * 24 * 60 * 60  =>  'month',
        24 * 60 * 60  =>  'day',
        60 * 60  =>  'hour',
        60  =>  'minute',
        1  =>  'second'
    );
    $a_plural = array( 'year'   => 'years',
        'month'  => 'months',
        'day'    => 'days',
        'hour'   => 'hours',
        'minute' => 'minutes',
        'second' => 'seconds'
    );

    foreach ($a as $secs => $str)
    {
        $d = $etime / $secs;
        if ($d >= 1)
        {
            $r = round($d);
            return $r . ' ' . ($r > 1 ? $a_plural[$str] : $str) . ' ago';
        }
    }
}

function userinfo($id,$row){

    global $db;

    $userinfo = $db->selectRow(
        'SELECT * FROM users WHERE id = ?',
        [ $id ]
    );

    return $userinfo[$row];

}