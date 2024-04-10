<?php

require 'app/app.php';

echo '
    <!-- Navbar-->
    <header class="app-header">
        <a class="app-header__logo" href="'.URL.'">torrentlist</a>
        <!-- Sidebar toggle button-->
        <a class="app-sidebar__toggle" href="#" data-toggle="sidebar" aria-label="Hide Sidebar"><i class="fas fa-bars"></i></a>
        <!-- Navbar Right Menu-->
        <ul class="app-nav">
        
            <form method="get" action="'.URL.'/search">
                <li class="app-search">
                    <input class="app-search__input" type="search" value="'.$_GET['search'].'" name="search" placeholder="Search">
                    <button class="app-search__button" type="submit"><i class="fas fa-search"></i></button>
                </li>
            </form>';

            if ($auth->isLoggedIn()) {

                $countnewnotify = $db->selectValue(
                    'SELECT count(*) FROM notifications WHERE owner in (?,0) AND view = 0 AND date > '.$inuser['registered'],
                    [ $auth->getUserId() ]
                );

                // user is signed in
                echo '
                <!--Notification Menu-->
                <li class="dropdown"><a class="app-nav__item" href="#" data-toggle="dropdown" aria-label="Show notifications"><i class="far fa-bell"></i>'.($countnewnotify > 0 ? '<div class="bell"></div>' : '').'</a>
                    <ul class="app-notification dropdown-menu dropdown-menu-right">
                        <li class="app-notification__title">You have '.$countnewnotify.' new notifications.</li>
                        <div class="app-notification__content">';

                            $notifications = $db->select(
                                'SELECT * FROM notifications WHERE owner in (?,0) AND date > '.$inuser['registered'].' ORDER BY date DESC LIMIT 8',
                                [ $auth->getUserId() ]
                            );

                            foreach ($notifications as $notifydata) {

                                echo '
                                <li>
                                    <a class="app-notification__item" href="'.URL.'/notify/'.$notifydata['id'].'">
                                        <span class="app-notification__icon"><span class="fa-stack fa-lg"><i class="fa fa-circle fa-stack-2x text-'.($notifydata['view'] == 0 ? $notifydata['color'] : 'muted').'"></i><i class="'.$notifydata['icon'].' fa-stack-1x fa-inverse"></i></span></span>
                                        <div>
                                            <p class="app-notification__message">'.$notifydata['title'].'</p>
                                            <p class="app-notification__meta">'.time_elapsed_string($notifydata['date']).'</p>
                                        </div>
                                    </a>
                                </li>';

                            }

                        echo '
                        </div>
                        <li class="app-notification__footer"><a href="'.URL.'/notify">See all notifications.</a></li>
                    </ul>
                </li>
                
                <!-- User Menu-->
                <li class="dropdown"><a class="app-nav__item" href="#" data-toggle="dropdown" aria-label="Open Profile Menu"><i class="fa fa-user"></i></a>
                    <ul class="dropdown-menu settings-menu dropdown-menu-right">
                        <li><a class="dropdown-item" href="'.URL.'/user/'.$auth->getUserId().'/'.strtolower(toAscii($inuser['username'])).'"><i class="fas fa-user mr-1"></i> Profile</a></li>
                        <li><a class="dropdown-item" href="'.URL.'/logout"><i class="fas fa-sign-out-alt mr-1"></i> Logout</a></li>
                    </ul>
                </li>';
            }

        echo '
        </ul>
    </header>';