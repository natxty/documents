<?php

//this is the navigation bar that goes across the top
function nav_menu($args) {
    $logged_in = $args['logged_in'];
    $is_admin = $args['is_admin'];
    $menu = @$args['menu'];
    $nav = array();

    $nav[] = array(site_url('home'),'Home');
    if ($logged_in) {
        $nav[] = array(site_url('view'),'View Lots');
        $nav[] = array(site_url('edit'),'Edit Lots');
        $nav[] = array(site_url('view/documents'),'Documents');
        if ($is_admin) { $nav[] = array(site_url('auth'),'Admin'); }
        else { $nav[] = array(site_url('auth/change_password'),'Change Password'); }
        $nav[] = array(site_url('auth/logout'),'Logout');
    }
    else { $nav[] = array(site_url('auth/login'),'Login'); }

    if (count($nav) > 0) {
        $div = '';
        $sep = "<div class=\"nav_menu\">";
        foreach ($nav as $n) {
            $div .= $sep."<a href=\"".$n[0]."\">".$n[1]."</a>";
            $sep = ' | ';
        }
        $div .= "</div>";
        return $div;
    } else { return false; }
}

//this is the full screen menu
function full_menu($args) {
    $items = $args['items'];
    $menu = "<ul class=\"full_menu\">";
        foreach ($items as $url => $caption) {
            $menu .= "<li><a href=\"$url\">$caption</li>";
        }
    $menu .= "</ul>";
    return $menu;
}

//print error message in pretty HTML
function customDie($error) {
    $base_url = BASE_URL;
    $sitetitle = SITETITLE;
    $title = 'Error';
    $content = "<p class=\"alert\">$error</p>";
    $nav_menu = nav_menu(array('logged_in'=>false,'menu'=>null));
    $base = str_replace('system/','',BASEPATH);
    $view_url = $base.'application/views/admin_page.php';
    require $view_url;
    die();
}

?>