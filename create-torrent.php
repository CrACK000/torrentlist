<?php

require 'app/app.php';
require 'app/Functions.php';

$title = 'Add torrent';

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
                    <h1><i class="fas fa-cubes"></i> How to create a torrent</h1>
                    <p>Instruction how to create your own torrent.</p>
                </div>
                
                <ul class="app-breadcrumb breadcrumb">
                    <li class="breadcrumb-item"><i class="fas fa-home"></i></li>
                    <li class="breadcrumb-item"><a href="' . URL . '/create-torrent">create torrent</a></li>
                </ul>
                
            </div>
            
            <div class="row">
                <div class="col-md-12">
                
                    <div class="tile tile-body">
                
                        <p><a href="http://www.utorrent.com/"><strong>uTorrent</strong></a></p>

                        <p><a href="http://www.utorrent.com/"><img alt="utorrent bit torrent" src="http://www.torrentfreak.com/images/utorrent.png" /></a></p>
                        
                        <p><strong>1.&nbsp;</strong>File &gt; Create new Torrent&nbsp;<em>(or CTRL + N)</em><br />
                        <strong>2.&nbsp;</strong>Select the files and or directories</p>
                        
                        <p><strong>3.&nbsp;</strong>Trackers: This is probably the hard part for most people. But it&rsquo;s pretty easy, just put in one of the popular public trackers. You can use one or more trackers, but in general one is enough.</p>
                        
                        <p>Here are some good trackers you can use:</p>
                        
                        <div style="background:#eeeeee;border:1px solid #cccccc;padding:5px 10px;"><samp>http://open.tracker.thepiratebay.org/announce<br />
                        http://www.torrent-downloads.to:2710/announce<br />
                        http://denis.stalker.h3q.com:6969/announce<br />
                        udp://denis.stalker.h3q.com:6969/announce<br />
                        http://www.sumotracker.com/announce</samp></div>
                        
                        <p>Put one of these in the tracker box</p>
                        
                        <p><strong>4.</strong>&nbsp;Do NOT tick the private torrent box (unless you&rsquo;re using a private tracker)</p>
                        
                        <p><strong>5.</strong>&nbsp;Save the torrent</p>
                        
                        <hr>
                        
                        <p><a href="http://www.bitcomet.com/"><strong>Bitcomet</strong></a></p>
                        
                        <p><a href="http://www.bitcomet.com/"><img alt="bitcomet bit torrent" src="http://www.torrentfreak.com/images/bitcomet.gif" /></a></p>
                        
                        <p><strong>1.&nbsp;</strong>File &gt; Create Torrent&nbsp;<em>(or CTRL + M)</em></p>
                        
                        <p><strong>2.&nbsp;</strong>Select the files and or directories</p>
                        
                        <p><strong>3.&nbsp;</strong>Select &ldquo;enable public DHT network&rdquo; from the dropdown box<br />
                        This way you can be your own tracker if the public tracker goes down.</p>
                        
                        <p><strong>4.&nbsp;</strong>Tracker server and DHT node list<br />
                        Again, This is probably the hard part for most people. But it&rsquo;s pretty easy, just put in one of the popular public trackers. You can use one or more trackers, but in general one is enough.</p>
                        
                        <p>Here are some of the most popular trackers at the moment:</p>
                        
                        <div style="background:#eeeeee;border:1px solid #cccccc;padding:5px 10px;"><samp>http://open.tracker.thepiratebay.org/announce<br />
                        http://www.torrent-downloads.to:2710/announce<br />
                        http://denis.stalker.h3q.com:6969/announce<br />
                        udp://denis.stalker.h3q.com:6969/announce<br />
                        http://www.sumotracker.com/announce</samp></div>
                        
                        <p>Put one of these in the tracker box</p>
                        
                        <p><strong>5.</strong>&nbsp;Save the torrent</p>
                        
                        <hr>
                        
                        <p><a href="http://azureus.sourceforge.net/"><strong>Azureus</strong></a></p>
                        
                        <p><a href="http://azureus.sourceforge.net/"><img alt="azureus bit torrent" src="http://www.torrentfreak.com/images/azureus.png" /></a></p>
                        
                        <p><strong>1.&nbsp;</strong>File &gt; New Torrent&nbsp;<em>(or CTRL + N)</em></p>
                        
                        <p><strong>2.&nbsp;</strong>Tick &ldquo;use an external tracker&rdquo;.<br />
                        And again, This is probably the hard part for most people. But it&rsquo;s pretty easy, just put in one of the popular public trackers.</p>
                        
                        <p>Here are some of the most popular trackers at the moment:</p>
                        
                        <div style="background:#eeeeee;border:1px solid #cccccc;padding:5px 10px;"><samp>http://tracker.prq.to/announce<br />
                        http://inferno.demonoid.com:3389/announce<br />
                        http://tracker.bt-chat.com/announce<br />
                        http://tracker.zerotracker.com:2710/announce</samp></div>
                        
                        <p>Put one of these in the tracker box</p>
                        
                        <p><strong>3.&nbsp;</strong>Select single file or dicectory, click NEXT and point to the file or directory you want to share, and click NEXT</p>
                        
                        <p><strong>4.&nbsp;</strong>Do NOT tick &ldquo;private torrent&rdquo;</p>
                        
                        <p><strong>5.&nbsp;</strong>Do tick &ldquo;allow decentralized tracking&rdquo;</p>
                        
                        <p><strong>6.</strong>&nbsp;Save the torrent</p>
                        
                        <small class="text-muted"><a href="https://torrentfreak.com/how-to-create-a-torrent/" target="_blank">Source</a></small>
                        
                    </div>
                
                </div>
            </div>
            
            '; require 'template/template_footer.php'; echo '
            
        </main>
        
        '; require 'template/template_scripts.php'; echo '
        
    </body>
</html>';