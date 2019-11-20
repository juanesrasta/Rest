<?php

class Utilities {

    public function getPaging($page, $total_row, $records_per_page, $page_url){

        $arr_paging = array();
        $arr_paging["first"] = $page>1 ? "{$page_url}page=1":"";

        $total_pages = ceil($total_row/$records_per_page);

        $range = 2;

        $initial_num = $page - $range;

        $condition_limit_num = ($page+$range)+1;

        $arr_paging["pages"] = array();

        $page_count = 0;

    }

}