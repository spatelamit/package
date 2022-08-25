<?php

class Files extends Controller {

    function Files() {
        parent::Controller();
        /* Set your own path here */
        echo $this->_path = "./Assets/";
    }

    function index() {
        echo "";
    }

    function js() {
        $segs = $this->uri->segment_array();
        foreach ($segs as $segment) {
            $filepath = $this->_path . $segment . '.js';
            if (file_exists($filepath)) {
                readfile($filepath);
            }
        }
    }

    function css() {
        $segs = $this->uri->segment_array();
        foreach ($segs as $segment) {
            $filepath = $this->_path . $segment . '.css';
            if (file_exists($filepath)) {
                readfile($filepath);
            }
        }
    }

}

?>