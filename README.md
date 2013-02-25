codeigniter-loadules
====================

## About Loadules

Loadules is a portmanteau word that blends the words `load` and `modules`.
In miiiCasa, it means a set of tools for loading JavaScript and CSS module files.

codeigniter-loadules is a CodeIgniter library that helps to load CSS and JavaScript (YUI) files.

## Installation

For CodeIgniter 2.x versions:

    ```php
    $this->load->add_package_path(APPPATH . "third_party/loadules/");        
    ```

For CodeIgniter 1.x versions:

    ```    
    cd <codeigniter_app_folder>;
    git clone <this_repository> third_party/loadules;
    cd libraries;
    ln ../third_party/loadules/libraries/Loadules.php;
    ```

## Usage

1. Use loadules library to load JavaScript and CSS modules.

    ```php
    $this->load->library("loadules");
    $this->loadules->set("welcome/_notification", "common/_sidebar");
    echo $this->loadules->load();
    ```

1. And it will output appropriate link and script tags.

    ```html
    <link rel="stylesheet" type="text/css" href="/combo?f=index/welcome/_notification.css,index/common/_sidebar.css">
    <script src="/combo?g=js"></script><!-- YUI Seed file and customized meta-data -->
    <script>
    YUI().use("welcome/_notifiation"); // Loads module according to YUI meta-data.
    </script>
    ```
