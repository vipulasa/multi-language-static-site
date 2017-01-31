<?php

defined('SYSTEM_ACTIVE') or die('WHERE ARE YOU GOING ?');

class App
{
    public function __construct()
    {
        // start session
        session_start();

        // load config
        global $_;
        // check if there is a language in the session and overwrite the language with the default language
        if (!isset($_SESSION['language']) && !in_array($_SESSION['language'], $_['languages'])) {
            $_SESSION['language'] = $_['default_language'];
        }
        // load router
        $this->router();
    }

    private function router()
    {
        // load routes
        require 'routes.php';
        // load config
        global $_;
        // check of router is set
        if (isset($__ROUTER)) {
            // active page
            $page = 0; // default set to home
            // get active route and load the template
            $query = filter_input(INPUT_GET, 'q');
            // home page
            if (!$query) {
                if (isset($_['default_language'])) {
                    // get default route
                    foreach ($__ROUTER as $route) {
                        if ($route['default']) {
                            $query = $_SESSION['language'] . $route['url'];
                        }
                    }
                }
            } else {
                // split the query and get the first param, it has to be a language
                $url_chunks = explode('/', $query);

                if (!empty($url_chunks) && count($url_chunks) > 1) {
                    // set language
                    if (in_array($url_chunks[0], $_['languages'])) {
                        $_SESSION['language'] = $url_chunks[0];
                    }
                    // unset language and rebuild the url
                    unset($url_chunks[0]);
                    $url = '/' . implode('/', $url_chunks);
                    // search for the url in the route list
                    $found = false;
                    foreach ($__ROUTER as $key => $route) {
                        if ($route['url'] === $url) {
                            $page = $key;
                            $found = true;
                        }
                    }
                    // if the page is 0 show the 404 page
                    if (!$found) {
                        $page = 404;
                    }

                } else {
                    if (count($url_chunks) == 1 && in_array($url_chunks[0], $_['languages'])) {
                        $_SESSION['language'] = $url_chunks[0];
                        // get default route
                        foreach ($__ROUTER as $key => $route) {
                            if ($route['default']) {
                                $page = $key;
                            }
                        }
                    }
                }
            }
            $this->render($page, $query, $__ROUTER);
        } else {
            die('Router not found.');
        }
    }

    private function render($page, $query, $__ROUTER)
    {
        // load view for the active page and template
        if(isset($__ROUTER[$page])){
            $this->view($__ROUTER[$page]['template']);
        }else{
            die('Template not found');
        }
    }

    private function view($template, $data = [])
    {
        if (file_exists($_SERVER['DOCUMENT_ROOT'] . '/templates/'. $_SESSION['language'] .'/' . $template)) {
            // load config
            global $_;
            // convert the data array to variables
            if (isset($data) && !empty($data)) {
                extract($data);
            }
            $_['language'] = $_SESSION['language'];
            $template = $_SERVER['DOCUMENT_ROOT'] . '/templates/'. $_SESSION['language'] .'/' . $template;
            require $_SERVER['DOCUMENT_ROOT'] . '/templates/master.template.php';
        } else {
            die('Template not found');
        }
    }
}