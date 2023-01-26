<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Cron extends CI_Controller
{
    var $glob_path = 'public';

    public function removeOldImages($table) {
        switch ($table) {
            case 'slider':
                $this->removeOldImagesSlider();
                break;
            case 'category':
                $this->removeOldImagesCategory();
                break;
            case 'category_banners':
                $this->removeOldImagesCategoryBanners();
                break;
            case 'news':
                $this->removeOldImagesNews();
                break;
            case 'promotions':
                $this->removeOldImagesPromotions();
                break;
            case 'product_block':
                $this->removeOldImagesProductBlock();
                break;
            default:
                echo "nu ati commpletat numele tabelei";
        }
    }

    public function removeOldImagesSlider() {
        $result = $this->db->select("image_ru, image_ro, image_mobile_ru, image_mobile_ro, image_terminal_ru, image_terminal_ro, image_terminal_sleep_ru, image_terminal_sleep_ro")->get("slider")->result();

        $images = [];

        foreach($result as $row) {
            if(!empty($row->image_ru)) $images[] = $row->image_ru;
            if(!empty($row->image_ro)) $images[] = $row->image_ro;
            if(!empty($row->image_mobile_ru)) $images[] = $row->image_mobile_ru;
            if(!empty($row->image_mobile_ro)) $images[] = $row->image_mobile_ro;
            if(!empty($row->image_terminal_ru)) $images[] = $row->image_terminal_ru;
            if(!empty($row->image_terminal_ro)) $images[] = $row->image_terminal_ro;
            if(!empty($row->image_terminal_sleep_ru)) $images[] = $row->image_terminal_sleep_ru;
            if(!empty($row->image_terminal_sleep_ro)) $images[] = $row->image_terminal_sleep_ro;
        }

        $this->_remove_files("slider", $images);
    }

    public function removeOldImagesCategory() {
        $result = $this->db->select("image, image_terminal, image_size_ru, image_size_ro")->get("category")->result();

        $images = [];

        foreach($result as $row) {
            if(!empty($row->image)) $images[] = $row->image;
            if(!empty($row->image_terminal)) $images[] = $row->image_terminal;
            if(!empty($row->image_size_ru)) $images[] = $row->image_size_ru;
            if(!empty($row->image_size_ro)) $images[] = $row->image_size_ro;
        }

        $this->_remove_files("category", $images);
    }

    public function removeOldImagesCategoryBanners() {
        $result = $this->db->select("image_ru, image_ro")->get("category_banners")->result();

        $images = [];

        foreach($result as $row) {
            if(!empty($row->image_ru)) $images[] = $row->image_ru;
            if(!empty($row->image_ro)) $images[] = $row->image_ro;
        }

        $this->_remove_files("category_banners", $images);
    }

    public function removeOldImagesNews() {
        $result = $this->db->select("image_head_ru, image_head_ro, image_list_ru, image_list_ro")->get("news")->result();

        $images = [];

        foreach($result as $row) {
            if(!empty($row->image_head_ru)) $images[] = $row->image_head_ru;
            if(!empty($row->image_head_ro)) $images[] = $row->image_head_ro;
            if(!empty($row->image_list_ru)) $images[] = $row->image_list_ru;
            if(!empty($row->image_list_ro)) $images[] = $row->image_list_ro;
        }

        $this->_remove_files("news", $images);
    }

    public function removeOldImagesPromotions() {
        $result = $this->db->select("image_list_ru, image_list_ro")->get("promotions")->result();

        $images = [];

        foreach($result as $row) {
            if(!empty($row->image_list_ru)) $images[] = $row->image_list_ru;
            if(!empty($row->image_list_ro)) $images[] = $row->image_list_ro;
        }

        $this->_remove_files("promotions", $images);
    }

    public function removeOldImagesProductBlock() {
        $result = $this->db->select("img")->get("product_block")->result();

        $images = [];

        foreach($result as $row) {
            if(!empty($row->img)) $images[] = $row->img;
        }

        $this->_remove_files("product_block", $images);
    }

    function _remove_files($dir, $images) {
        if (empty($dir)) return false;

        $prev = realpath($this->glob_path).'/';
        $dir = $prev.$dir;
        if (is_dir($dir)) {
            $files = new RecursiveIteratorIterator(
                new RecursiveDirectoryIterator($dir, RecursiveDirectoryIterator::SKIP_DOTS),
                RecursiveIteratorIterator::CHILD_FIRST
            );

            foreach ($files as $fileinfo) {
                if(is_file($fileinfo)) {
                    if(!in_array($fileinfo->getFileName(), $images)) {
                        unlink($fileinfo->getRealPath());
                    }
                }
            }
        }
    }

    public function populate_first_color() {

        ini_set('memory_limit', '2048M');
        ini_set('max_execution_time', '0');

        $query = $this->db->from('articol_color')
            ->join("product", "product.id = articol_color.product_id")
            ->get()->result();

        foreach ($query as $item) {
            if(empty($item->color_ru)) {
                $first_color = 'NO_COLOR';
            } else {
                $colors = explode(',', $item->color_ro);
                $first_color = $colors[0];
            }

            $this->db->where('articol', $item->articol)->update('product', array('first_color' => $first_color));
        }

        echo 'done populate first color';
    }
}
