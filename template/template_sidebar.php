<?php

require 'app/app.php';

echo '
    <!-- Sidebar menu-->
    <div class="app-sidebar__overlay" data-toggle="sidebar"></div>
    
    <aside class="app-sidebar">';

        if ($auth->isLoggedIn()) {
            // user is signed in
            echo '
            <div class="app-sidebar__user"><img class="app-sidebar__user-avatar" src="'.URL.$inuser['avatar'].'" alt="'.$inuser['username'].'">
                <div>
                    <p class="app-sidebar__user-name">'.$inuser['username'].' '.($inuser['important'] == 1 ? '<i class="far fa-check-circle ml-2 important-check" title="Verified user"></i>' : '').'</p>
                    <p class="app-sidebar__user-designation text-muted">@'.strtolower(toAscii($inuser['username'])).'</p>
                </div>
            </div>';
        }

        echo '
        <ul class="app-menu">
        
            <li>
                <a class="app-menu__item '.($_SERVER['PHP_SELF'] == '/index.php' ? 'active' : '').($_SERVER['PHP_SELF'] == '/torrent.php' ? 'active' : '').'" href="'.URL.'"><i class="app-menu__icon fas fa-list-ul"></i><span class="app-menu__label">List of torrents</span></a>
            </li>
            
            <li>
                <a class="app-menu__item '.($_SERVER['PHP_SELF'] == '/create-torrent.php' ? 'active' : '').'" href="'.URL.'/create-torrent"><i class="app-menu__icon fas fa-cubes"></i><span class="app-menu__label">Create torrent</span></a>
            </li>
            
            <li>
                <a class="app-menu__item '.($_SERVER['PHP_SELF'] == '/search.php' ? 'active' : '').'" href="'.URL.'/search"><i class="app-menu__icon fas fa-search"></i><span class="app-menu__label">Search</span></a>
            </li>';

            if ($auth->isLoggedIn()){
                echo '
                <li>
                    <a class="app-menu__item '.($_SERVER['PHP_SELF'] == '/add.php' ? 'active' : '').'" href="'.URL.'/add"><i class="app-menu__icon fas fa-plus-circle"></i><span class="app-menu__label">Add torrent</span></a>
                </li>';
            }

            if (!$auth->isLoggedIn()) {
                echo '
                <li class="treeview ' . ($_SERVER['PHP_SELF'] == '/registration.php' ? 'is-expanded' : '') . '"><a class="app-menu__item" href="#" data-toggle="treeview"><i class="app-menu__icon fas fa-user-circle"></i><span class="app-menu__label">User</span><i class="treeview-indicator fa fa-angle-right"></i></a>
                    <ul class="treeview-menu">
                        <li><a class="treeview-item" href="' . URL . '/login"><i class="icon far fa-circle mr-2"></i> Login</a></li>
                        <li><a class="treeview-item ' . ($_SERVER['PHP_SELF'] == '/registration.php' ? 'active' : '') . '" href="' . URL . '/registration"><i class="icon far fa-circle mr-2"></i> Registration</a></li>
                    </ul>
                </li>';
            }

        echo '
        </ul>
        
    </aside>';