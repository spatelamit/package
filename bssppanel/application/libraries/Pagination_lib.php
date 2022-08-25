<?php

/**
 * Initialize the pagination rules for cities page
 * @return Pagination
 */
class Pagination_lib {

//put your code here
    function __construct() {
        $this->ci = & get_instance();
    }

    public function initPagination($base_url, $per_page, $total_rows, $url_segment) {
        $config['per_page'] = $per_page;
        $config['uri_segment'] = $url_segment;
        $config['base_url'] = base_url() . $base_url;
        $config['total_rows'] = $total_rows;

        // First Links
        $config['first_link'] = 'First';
        $config['first_tag_open'] = '<li>';
        $config['first_tag_close'] = '</li>';

        // Last Links
        $config['last_link'] = 'Last';
        $config['last_tag_open'] = '<li>';
        $config['last_tag_close'] = '</li>';

        // Next Link
        //$config['next_link'] = '&raquo;';
        $config['next_link'] = 'Next';
        $config['next_tag_open'] = '<li>';
        $config['next_tag_close'] = '</li>';

        // Previous Link
        //$config['prev_link'] = '&laquo;';
        $config['prev_link'] = 'Previous';
        $config['prev_tag_open'] = '<li>';
        $config['prev_tag_close'] = '</li>';

        // Current Link
        $config['cur_tag_open'] = '<li class="active"><a>';
        $config['cur_tag_close'] = '</a></li>';

        // Digit Link
        $config['num_tag_open'] = '<li>';
        $config['num_tag_close'] = '</li>';

        /* $config['use_page_numbers'] = TRUE;
          $config['first_tag_open'] = $config['last_tag_open'] = $config['next_tag_open'] = $config['prev_tag_open'] = $config['num_tag_open'] = '';
          $config['first_tag_close'] = $config['last_tag_close'] = $config['next_tag_close'] = $config['prev_tag_close'] = $config['num_tag_close'] = '';
          $config['cur_tag_open'] = "";
          $config['cur_tag_close'] = "";
         */

        $this->ci->pagination->initialize($config);
        return $config;

        /*
          $config = array();
          $config["base_url"] = base_url() . "user/delivery_reports";
          $config["total_rows"] = $this->user_data_model->countDeliveryReports($user_id);
          $config["per_page"] = 20;
          $config["uri_segment"] = 3;

          $this->pagination->initialize($config);
          $page = ($this->uri->segment(3)) ? $this->uri->segment(3) : 0;
          $data['delivery_reports'] = $this->user_data_model->getDeliveryReports($user_id, $config["per_page"], $page);
          $data["links"] = $this->pagination->create_links();
         */
    }

}

?>