<?php
defined('BASEPATH') OR exit('No direct script access allowed');

if (!function_exists('newthumbs')) {
    function newthumbs($photo = '', $dir = '', $width = 0, $height = 0, $version = 0, $zc = 0)
    {
        require_once(realpath('application') . '/third_party/imres/phpthumb.class.php');

        $result = is_dir(realpath('public/' . $dir) . '/thumbs');
        if ($result) {
            $prevdir = $dir . '/thumbs';
        } else {
            $dir = createPath($dir);
            if (mkdir(realpath('public/' . $dir) . '/thumbs')) {
                $prevdir = $dir . '/thumbs';
            } else {
                return 'error 1';
            }
        }

        $timg = realpath('public/' . $dir) . '/' . $photo;

        $pizza = explode(".", $photo);
        if(in_array(end($pizza) , ["svg"])) return '/public/' . $dir . '/' . $photo;

        if (!is_file($timg) || end($pizza) == 'jp2') {
            $source = realpath('public/i') . '/no_image.jpg';
            $dest = realpath('public/i') . '/no_image_' . $width . '_' . $height . '_' . $zc . '.jpg';
            //return $dest;
            $dest2 = '/public/i/no_image_' . $width . '_' . $height . '_' . $zc . '.jpg';
            if (is_file($dest)) return $dest2;
            $phpThumb = new phpThumb();
            $phpThumb->setSourceFilename($source);
            if (!empty($width)) $phpThumb->setParameter('w', $width);
            if (!empty($height)) $phpThumb->setParameter('h', $height);
            $phpThumb->setParameter('q', 99);
            $phpThumb->setParameter('f', 'jpeg');
            if (!empty($zc)) {
                $phpThumb->setParameter('zc', '1');
            }
            $img = '';
            if ($phpThumb->GenerateThumbnail()) {
                if ($phpThumb->RenderToFile($dest)) {
                    return $dest2;
                }
            }
        }

        if (!empty($version)) {
            $result = is_dir(realpath('public/' . $dir) . '/thumbs/version_' . $version);
            if ($result) {
                $prevdir = $dir . '/thumbs/version_' . $version;
            } else {
                if (mkdir(realpath('public/' . $dir) . '/thumbs/version_' . $version)) {
                    $prevdir = $dir . '/thumbs/version_' . $version;
                } else {
                    return 'error 1';
                }
            }
        }
        $va1 = explode('.', $photo);
        $ext = end($va1);

        $timg = realpath('public/' . $dir) . '/' . $photo;
        $catimg = realpath('public/' . $prevdir) . '/' . $photo;

        if (is_file($timg) && !is_file($catimg)) {
            $opath1 = realpath('public/' . $dir) . '/';
            $opath2 = realpath('public/' . $prevdir) . '/';
            $dest = $opath2 . $photo;
            $source = $opath1 . $photo;

            $phpThumb = new phpThumb();
            $phpThumb->setSourceFilename($source);

            if (!empty($width)) $phpThumb->setParameter('w', $width);
            if (!empty($height)) $phpThumb->setParameter('h', $height);
            if (!empty($height)) $phpThumb->setParameter('q', 100);
            if ($ext == 'png') $phpThumb->setParameter('f', 'png');
            if (!empty($zc)) {
                $phpThumb->setParameter('zc', '1');
            }
            $phpThumb->setParameter('q', 100);
            if ($phpThumb->GenerateThumbnail()) {
                if ($phpThumb->RenderToFile($dest)) {
                    $img = '/public/' . $prevdir . '/' . $photo;
                } else {
                    return 'error 3';
                }
            }

        } elseif (is_file($catimg)) {
            $img = '/public/' . $prevdir . '/' . $photo;
        } else {
            return 'error 2';
        }

        return $img;
    }
}

if(!function_exists('product_image')) {
    function product_image($img, $articol, $color_path, $width=0, $height=0): string
    {
        $prevdiv=substr($articol,-2);
        $dir = 'products/'.$prevdiv.'/'.$articol.'/'.$color_path;

        return newthumbs($img, $dir, $width, $height,$width.'x'.$height.'x0',0);
    }
}


if(!function_exists('unlink_files')) {
    function unlink_files($dir, $files) {

        if(is_array($files)){
            foreach($files as $file) {
                $main = realpath('public/' . $dir) . '/' . $file;
                if(is_file($main)) unlink($main);
                $webp = realpath('public/' . $dir) . '/' . replaceWebp($file);
                if(is_file($webp)) unlink($webp);

                $dirs = array_filter(glob('public/'.$dir.'/thumbs/*'), 'is_dir');

                foreach($dirs as $directory) {
                    $thumb = $directory.'/'.$file;
                    if (is_file($thumb)) unlink($thumb);
                }
            }
        } else {
            $file = $files;
            $main = realpath('public/' . $dir) . '/' . $file;
            if(is_file($main)) unlink($main);
            $main_webp = realpath('public/' . $dir) . '/' . replaceWebp($file);
            if(is_file($main_webp)) unlink($main_webp);

            $dirs = array_filter(glob('public/'.$dir.'/thumbs/*'), 'is_dir');

            foreach($dirs as $directory) {
                $thumb = $directory.'/'.$file;
                if (is_file($thumb)) unlink($thumb);
            }
        }
    }
}

if(!function_exists('replaceWebp')) {
    function replaceWebp($file) {
        return str_replace(['.jpg', '.png'], '.webp', $file);
    }
}

if (!function_exists('verify_img_extension')) {
    function verify_img_extension($ext) {
        $file_types = array('.jpg', '.jpeg', '.gif', '.png', '.svg');
        if (in_array(strtolower($ext), $file_types)) {return true;} else {return false;}
    }
}

if (!function_exists('createPath')) {
    function createPath($path) {

        if (is_dir(realpath('public') . '/' . $path)) return $path;

        $items = explode("/", $path);
        $dir = "";

        foreach($items as $item) {
            $dir .= "/" . $item;

            if (!is_dir(realpath('public') . '/' . $dir)) mkdir(realpath('public') . $dir);
        }

        return $dir;
    }
}

if (!function_exists('init_load_img')) {
    function init_load_img($path, $first=true) {

        $dir = createPath($path);

        $CI =& get_instance();
        $config['upload_path'] = realpath("public") . '/' . $dir;
        $config['allowed_types'] = 'jpg|jpeg|gif|png|svg';
        $config['max_size'] = 2048;
        $config['encrypt_name'] = TRUE;
        if($first) {
            $CI->load->library('upload', $config);
        } else {
            $CI->upload->initialize($config);
        }
    }
}

if (!function_exists('init_load_files')) {
    function init_load_files($path, $first=true) {

        $dir = createPath($path);

        $CI =& get_instance();
        $config['upload_path'] = realpath("public") . '/' . $dir;
        $config['allowed_types'] = 'pdf|doc|docx';
        $config['encrypt_name'] = TRUE;
        if($first) {
            $CI->load->library('upload', $config);
        } else {
            $CI->upload->initialize($config);
        }
    }
}

if (!function_exists('dump')) {
    function dump($data, $continue = false)
    {
        echo '<pre>';
        print_r($data);
        echo '</pre>';
        if (!$continue) {
            die();
        }
        return true;
    }
}

if (!function_exists('dd')) {
    function dd($data)
    {
        echo '<pre>';
        print_r($data);
        echo '</pre>';
        die();
    }
}

if (!function_exists('throw_on_404')) {
    function throw_on_404()
    {
        header("HTTP/1.0 404 Not Found");
        show_404();
        exit();
    }
}

if (!function_exists('check_if_POST')) {
    function check_if_POST()
    {
        if ($_SERVER['REQUEST_METHOD'] != 'POST') {
            throw_on_404();
        }
    }
}

if (!function_exists('check_if_GET')) {
    function check_if_GET()
    {
        if (empty($_GET)) {
            throw_on_404();
        }
    }
}

if(!function_exists('language')) {
    function language($param=false) {
        $language = array(
            'ro' => 'Româna',
            'ru' => 'Руский',
        );
        if($param){
            $keys = array();
            foreach($language as $key => $name){
                $keys[] = $key;
            }
            return $keys;
        } else {
            return $language;
        }
    }
}

if (!function_exists('get_prefered_language')) {
    function get_prefered_language() {
        $langs = language(true);
        $browserlang = isset($_SERVER["HTTP_ACCEPT_LANGUAGE"]) ? substr($_SERVER["HTTP_ACCEPT_LANGUAGE"],0,2) : '';

        if(in_array($browserlang, $langs )) {
            $_SESSION['lang'] = $browserlang;
        } else {
            $_SESSION['lang'] = 'ro';
        }
    }
}

if (!function_exists('assign_language')) {
    function assign_language($lang = FALSE) {

        $langs = language(true);
        if(in_array($lang, $langs )) {
            $_SESSION['lang'] = $lang;
        } else {
            $_SESSION['lang'] = 'ro';
        }
    }
}

if (!function_exists('get_language')) {
    function get_language($up = FALSE)
    {
        return ($up) ? strtoupper($_SESSION['lang']) : strtolower($_SESSION['lang']);
    }
}

if (!function_exists('get_language_for_admin')) {
    function get_language_for_admin($up = FALSE) {
        return ($up) ? 'RO' : 'ro';
    }
}

if (!function_exists('select_language')) {
    function select_language($lang = FALSE, $langs_array = FALSE)
    {
        if (empty($lang) || empty($langs_array)) {
            throw_on_404();
        }

        $CI = &get_instance();
        $protocol = (isset($_SERVER['HTTPS']) ? "https" : "http");
        $host = '://' . $_SERVER['HTTP_HOST'];
        $get_data = $_SERVER['QUERY_STRING'];

        $current_lang = strtolower($lang);

        //unset($langs_array[$current_lang]);

        $CI->load->view('layouts/pages/langs',
            array(  'langs_array' => $langs_array,
                'protocol' => $protocol,
                'host' => $host,
                'get_data' => $get_data,
                'current_lang' => $current_lang));
    }
}

if (!function_exists('alternate')) {
    function alternate($lang = FALSE, $langs_array = FALSE)
    {
        if (empty($lang) || empty($langs_array)) {
            throw_on_404();
        }

        $CI = &get_instance();
        $protocol = (isset($_SERVER['HTTPS']) ? "https" : "http");
        $host = '://' . $_SERVER['HTTP_HOST'];
        $get_data = $_SERVER['QUERY_STRING'];

        $current_lang = strtolower($lang);

        $CI->load->view('layouts/pages/alternate',
            array(  'langs_array' => $langs_array,
                'protocol' => $protocol,
                'host' => $host,
                'get_data' => $get_data,
                'current_lang' => $current_lang));
    }
}


if (!function_exists('reArrayFiles')) {
    function reArrayFiles($file)
    {
        $file_ary = array();
        $file_count = count($file['name']);
        $file_key = array_keys($file);

        for ($i = 0; $i < $file_count; $i++) {
            foreach ($file_key as $val) {
                if (isset($file[$val][$i])) {
                    $file_ary[$i][$val] = $file[$val][$i];
                }
            }
        }
        return $file_ary;
    }
}

if (!function_exists('transliteration')) {
    function transliteration($str)
    {
        $tr = array(
            "А" => "A", "Б" => "B", "В" => "V", "Г" => "G",
            "Д" => "D", "Е" => "E", "Ё" => "E", "Ж" => "J", "З" => "Z", "И" => "I",
            "Й" => "Y", "К" => "K", "Л" => "L", "М" => "M", "Н" => "N", 'â' => 'a', 'Â'=> 'A', 'ă' => 'a', 'Ă' => 'A', 'ţ' => 't', 'Ţ' => 'T', 'ş' => 's', 'Ş' => 'S',
            "О" => "O", "П" => "P", "Р" => "R", "С" => "S", "Т" => "T",
            "У" => "U", "Ф" => "F", "Х" => "H", "Ц" => "TS", "Ч" => "CH", "î" => "i", "Î" => "I", "," => "", "№" => "", "ț" => "t", "Ț" => "T", "ș" => "s", "Ș" => "S",
            "Ш" => "SH", "Щ" => "SCH", "Ъ" => "", "Ы" => "YI", "Ь" => "",
            "Э" => "E", "Ю" => "YU", "Я" => "YA", "а" => "a", "б" => "b",
            "в" => "v", "г" => "g", "д" => "d", "е" => "e", "ё" => "e", "ж" => "j",
            "з" => "z", "и" => "i", "й" => "y", "к" => "k", "л" => "l",
            "м" => "m", "н" => "n", "о" => "o", "п" => "p", "р" => "r",
            "с" => "s", "т" => "t", "у" => "u", "ф" => "f", "х" => "h",
            "ц" => "ts", "ч" => "ch", "ш" => "sh", "щ" => "sch", "ъ" => "y",
            "ы" => "yi", "ь" => "", "э" => "e", "ю" => "yu", "я" => "ya", " " => "_", " (" => "", ")" => "",
            "." => "", "\\" => "", "/" => "-", "'" => "", "»" => "", "«" => "", "&quot;" => "", "\"" => "", "&" => "i", "%" => "",
            "$" => "usd", "€" => "eur", "!" => "", "?" => "", "+" => "plus",
        );

        $str = strtolower(strtr($str, $tr));
        $str = preg_replace('/\s+/u', '-', $str);
        $str = preg_replace('/[^a-zA-Z0-9_-]+/', '', $str);

        return $str;
    }
}

if (!function_exists('cyrillic')) {
    function cyrillic($str)
    {
        $str = strtolower($str);
        $tr = array(
            "а" => "a", "е" => "e", "о" => "o",
            "в" => "b", "с" => "c", "н" => "h", "т" => "t", "м" => "m", "р" => "p", "к" => "k",
            "х" => "x", "у" => "y"
        );

        return strtr($str, $tr);
    }
}

if (!function_exists('get_url_pattern')) {
    function get_url_pattern()
    {
        $url = preg_replace('/\&page=.*/', '', $_SERVER["REQUEST_URI"]);
        /* Заносим переменную page в ГЕТ */
        if (!preg_match('/\?/', $_SERVER["REQUEST_URI"])) {
            $urlPattern = $url . '?&page=(:num)';
        } else {
            $urlPattern = $url . '&page=(:num)';
        }
        return $urlPattern;
    }
}

if (!function_exists('is_assoc')) {
    function is_assoc($var)
    {
        return is_array($var) && array_diff_key($var, array_keys(array_keys($var)));
    }
}

if (!function_exists('get_youtube_id')) {
    function get_youtube_id($url)
    {
        preg_match('%(?:youtube(?:-nocookie)?\.com/(?:[^/]+/.+/|(?:v|e(?:mbed)?)/|.*[?&]v=)|youtu\.be/)([^"&?/ ]{11})%i', $url, $match);
        $youtube_id = $match[1];
        return $youtube_id;
    }
}

if (!function_exists('formatSizeUnits')) {
    function formatSizeUnits($bytes)
    {
        if ($bytes >= 1073741824) {
            $bytes = number_format($bytes / 1073741824, 2) . ' GB';
        } elseif ($bytes >= 1048576) {
            $bytes = number_format($bytes / 1048576, 2) . ' MB';
        } elseif ($bytes >= 1024) {
            $bytes = number_format($bytes / 1024, 2) . ' KB';
        } elseif ($bytes > 1) {
            $bytes = $bytes . ' bytes';
        } elseif ($bytes == 1) {
            $bytes = $bytes . ' byte';
        } else {
            $bytes = '0 bytes';
        }

        return $bytes;
    }
}

if (!function_exists('numberFormat')) {
    function numberFormat($number, $zecimal=0) {
        return number_format((float)$number, $zecimal, '.', ' ');
    }
}

if (!function_exists('str_replace_nth')) {
    function str_replace_nth($search, $replace, $subject, $nth=1) {
        $nth = $nth-1;
        $found = preg_match_all('/'.preg_quote($search).'/', $subject, $matches, PREG_OFFSET_CAPTURE);
        if (false !== $found && $found > $nth) {
            return substr_replace($subject, $replace, $matches[0][$nth][1], strlen($search));
        }
        return $subject;
    }
}

function zerofill ($num, $zerofill = 5) {
    return str_pad($num, $zerofill, '0', STR_PAD_LEFT);
}

if (!function_exists('ilabCrypt')) {
    function ilabCrypt($string, $action = true)
    {
        // Инициализируем даныне
        $output = false;
        $encrypt_method = "AES-256-CBC";
        $secret_key = '0jWLPvrkm0bASR}L_UpWi:TWzV3<%|(4~OglNl;+Z+G3C@l>AC)mW!g6w4C[LgMz';
        $secret_iv = 'dq+;{dB-Wa8R61(`UK5FglVP`P>MW+y!T{f-gc-k#Gu#~gxu1GYmzNA].P~@h&:,';
        // Преобразуем данные
        $key = hash('sha256', $secret_key);
        $iv = substr(hash('sha256', $secret_iv), 0, 16);
        // Основной скрипт
        if ($action) {
            $output = openssl_encrypt($string, $encrypt_method, $key, 0, $iv);
            $output = base64_encode($output);
        } else {
            $output = openssl_decrypt(base64_decode($string), $encrypt_method, $key, 0, $iv);
        }
        return $output;
    }
}

if (!function_exists('generatePassword')) {
    function generatePassword()
    {
        return str_shuffle(bin2hex(openssl_random_pseudo_bytes(4)));
    }
}

if (!function_exists('uri')) {
    function uri($num){
        $CI  = &get_instance();
        return clear($CI->uri->segment($num));
    }
}

if (!function_exists('clear')) {
    function clear($str, $type = '0')
    {
        $str = trim($str);
        if ($type == 'email') {
            if (filter_var($str, FILTER_VALIDATE_EMAIL) === false) {
                $str = "";
            }
        } else if ($type == 1 or $type == 'int') {
            $str = intval($str);
        } else if ($type == 2 or $type == 'float') {
            $str = str_replace(",", ".", $str);
            $str = floatval($str);
        } else if ($type == 3 or $type == 'regx') {
            $str = preg_replace("/[^a-zA-ZА-Яа-я0-9.,!\s]/", "", $str);
        } else if ($type == 'alias') {
            $str = preg_replace("/[^a-zA-Z0-9_-\s]/", "", $str);
        } else if ($type == 4 or $type == 'text') {
            $str = str_replace("'", "&#8242;", $str);
            $str = str_replace("\"", "&#34;", $str);
            $str = stripslashes($str);
            $str = htmlspecialchars($str);
        } else {
            $str = strip_tags($str);
            $str = str_replace("\n", " ", $str);
            $str = str_replace("'", "&#8242;", $str);
            $str = str_replace("\"", "&#34;", $str);
            $str = preg_replace('!\s+!', ' ', $str);
            $str = stripslashes($str);
            $str = htmlspecialchars($str);
        }
        return $str;
    }
}

if(!function_exists('getRealCatChilds')) {
    function getRealCatChilds($id_parent){
        $CI      =&get_instance();
        $dataset = $CI->db->select('id, parent_id')->get('category')->result_array();
        $dataset = key_to_id($dataset);
        $ids     = array();
        foreach ($dataset as $id => &$node) {
            if ($id_parent==$id) {
                $ids[] = $id;
                $id_parent=$id;
            } else {
                $dataset[$node['parent_id']]['childs'][$id] =& $node;
                if ($id_parent==$node['parent_id'] or in_array($node['parent_id'], $ids)) {
                    $ids[] = $id;
                    $id_parent=$id;
                }
            }
        }
        return $ids;
    }
}

if (!function_exists('key_to_id')) {
    function key_to_id($array) {
        if (empty($array)) {
            return array();
        }
        $new_arr = array();
        foreach ($array as $id => &$node) {
            $new_arr[$node['id']] =& $node;
        }
        return $new_arr;
    }
}

if (!function_exists('getRealIpAddr')) {
    function getRealIpAddr()
    {
        if (!empty($_SERVER['HTTP_CLIENT_IP'])) // Определяем IP
        {
            $ip = $_SERVER['HTTP_CLIENT_IP'];
        } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
            $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
        } else {
            $ip = $_SERVER['REMOTE_ADDR'];
        }
        return $ip;
    }
}

if (!function_exists('GUID')) {
    function GUID() {
        if (function_exists('com_create_guid') === true)
        {
            return trim(com_create_guid(), '{}');
        }

        return sprintf('%04X%04X-%04X-%04X-%04X-%04X%04X%04X', mt_rand(0, 65535), mt_rand(0, 65535), mt_rand(0, 65535), mt_rand(16384, 20479), mt_rand(32768, 49151), mt_rand(0, 65535), mt_rand(0, 65535), mt_rand(0, 65535));
    }
}

if (!function_exists('user_login')) {
    function user_login($user) {
        $_SESSION['user_id'] = $user->id;
        $_SESSION['user_name'] = $user->fullname;
        $_SESSION['user_key'] = hash('sha512', $user->email . $user->password . $user->id );
    }
}

if (!function_exists('user_is_logged_in')) {
    function user_is_logged_in()
    {
        $user_id = @$_SESSION['user_id'];
        $name = @$_SESSION['user_name'];
        $key = @$_SESSION['user_key'];

        $lang = strtolower($_SESSION['lang']);
        if (empty($user_id) || empty($name) || empty($key)) {
            unset($_SESSION['user_id']);
            unset($_SESSION['user_name']);
            unset($_SESSION['user_key']);
            return false;
        }
        return true;
    }
}

if (!function_exists('user_is_auth')) {
    function user_is_auth($lang)
    {
        $CI = &get_instance();
        $user_id = @$_SESSION['user_id'];
        $user_name = @$_SESSION['user_name'];
        $user_key = @$_SESSION['user_key'];

        try {
            if(empty($user_id) || empty($user_name) || empty($user_key)) {
                throw new Exception('USER ERROR');
            }

            $user = $CI->db->where('ID', $user_id)->get('users')->row();

            if (empty($user)) {
                throw new Exception('USER ERROR');
            }

            $key_check = hash('sha512', $user->email . $user->password . $user->id );

            if ($user_key != $key_check) {
                throw new Exception('Key ERROR');
            }
        } catch (Exception $e) {
            unset($_SESSION['user_id']);
            unset($_SESSION['user_name']);
            unset($_SESSION['user_key']);
            redirect('/');
        }
    }
}

if (!function_exists('menu_map')) {
    function menu_map($objects, $parent = 0) {

        $result = array();
        foreach ($objects as $object) {

            if ($object->parent_id == $parent) {
                $result[$object->id] = (object) array();
                $result[$object->id]->id = $object->id;
                $result[$object->id]->parent_id = $object->parent_id;
                $result[$object->id]->menu_category_id = $object->menu_category_id;
                $result[$object->id]->title = $object->title;
                $result[$object->id]->uri = $object->uri;
                $result[$object->id]->isShown = $object->isShown;
                $result[$object->id]->onTop = $object->onTop;
                $result[$object->id]->onBottom = $object->onBottom;
                $result[$object->id]->children = menu_map($objects, $object->id, false);
            }

        }

        return $result;
    }
}

if (!function_exists('categories_map')) {
    function categories_map($objects, $parent = 0, $clear=true) {

        $result = array();
        if($clear) unset($_SESSION['all_cat_children']);
        foreach ($objects as $object) {

            if ($object->parent_id == $parent) {
                $_SESSION['all_cat_children'][] = $object->id;
                $result[$object->id] = (object) array();
                $result[$object->id]->id = $object->id;
                $result[$object->id]->parent_id = $object->parent_id;
                $result[$object->id]->title = $object->title;
                $result[$object->id]->uri = $object->uri;
                $result[$object->id]->onMain = $object->onMain;
                $result[$object->id]->min_price = $object->min_price;
                $result[$object->id]->img = $object->img;
                $result[$object->id]->children = categories_map($objects, $object->id, false);
            }

        }

        return $result;
    }
}

if (!function_exists('categories_cat_ids')) {
    function categories_cat_ids($objects, $id = 0) {

        foreach ($objects as $object) {
            if(is_array($object)) $object = (object) $object;

            if ($object->id == $id && $id !=0 ) {
                $_SESSION['cat_ids'][$object->level] = $object->id;
                categories_cat_ids($objects, $object->parent_id);
            }
        }
    }
}

if (!function_exists('admin_categories_tree')) {
    function admin_categories_tree($objects, $e_path, $del_path, $parent_id = 0)
    {
        foreach ($objects as $item) {
            if ($item->parent_id == $parent_id) {
                $cmod = (!empty($item->isShown)) ? 'checked' : '';
                $cmod2 = (!empty($item->onMain)) ? 'checked' : '';
                $self_class = 'treegrid-' . $item->id;
                $parent_class = ($parent_id != 0) ? 'treegrid-parent-' . $parent_id : '';
                echo '
                    <tr class="' . $self_class . ' ' . $parent_class . '" style="height: 51px;">
                        <td class="align-middle td-flex">
                        <input style="width:50px;margin-right:15px" type="text" onkeyup="this.value=this.value.replace(/[^\d]/,\'\')" min="1"
                        class="form-control text-center sorder" value="' . $item->sorder . '"
                        name="so[' . $item->id . ']">
                        <a style="font-weight: 900;"
                            href="' . $e_path . $item->id . '">' . $item->{'title'.get_language_for_admin(true)} . '</a></td>
                        <td class="align-middle" >
                            <label class="mt-checkbox mt-checkbox-outline">
                                <input type="checkbox" ' . $cmod . ' value="' . $item->id . '" data-table="categories" data-col="isShown"
                                class="mine_change_check"> '  . lang('Show on site'). '
                                <span></span>
                            </label>
                        </td> 
                        <td class="align-middle" >
                            <label class="mt-checkbox mt-checkbox-outline">
                                <input type="checkbox" ' . $cmod2 . ' value="' . $item->id . '" data-table="categories" data-col="onMain"
                                class="mine_change_check"> '  . lang('Show on main'). '
                                <span></span>
                            </label>
                        </td>
                        <td class="align-middle">
                            <a href="' . $e_path . $item->id . '/' . '"
                            class="btn blue">
                                <i class="fa fa-pencil"></i> '  . lang('Edit'). '
                            </a>
                                <a href="' . $del_path . $item->id . '/' . '"
                                class="btn red mine_delete_row">
                                    <i class="fa fa-trash"></i> '  . lang('Add'). '
                                </a>
                        </td>
                    </tr>';
                admin_categories_tree($objects, $e_path, $del_path, $item->id);
            }
        }
    }
}

if(!function_exists('percentage')) {
    function percentage($NumberFour, $Everything) {
        $percentage = ( $NumberFour / $Everything ) * 100;
        $percentage = number_format($percentage, 0);
        $discount = (100 -$percentage) * -1;
        $display = $discount.'%';
        return $display;
    }
}

if (!function_exists('admin_categories_tree_with_products')) {
    function admin_categories_tree_with_products($categories, $table, $ids, $objects, $e_path, $del_path, $parent_id = 0)
    {
        foreach ($categories as $item) {
            if ($item->parent_id == $parent_id) {
                $self_class = 'treegrid-' . $item->id;
                $parent_class = ($parent_id != 0) ? 'treegrid-parent-' . $parent_id : '';

                if(!in_array($item->id, $ids)){
                    echo '
                    <tr class="' . $self_class . ' ' . $parent_class . '">
                        <td colspan="7" class="align-middle"><span class="caption-subject bold font-grey-gallery uppercase">' . $item->{'title'.get_language_for_admin(true)}.'</span></td>
                    </tr>';
                } else {
                    echo '
                    <tr class="' . $self_class . ' ' . $parent_class . '">
                        <td colspan="7" class="align-middle"><span class="caption-subject bold font-grey-gallery uppercase">' . $item->{'title'.get_language_for_admin(true)}.'</span>
                            <a style="float:right" data-id="'.$item->id.'" class="category_ajax" href="/"><i class="fa fa-plus"></i>&nbsp;<span>'  . lang('Show products'). '</span></a>
                        
                            <table class="table table-hover table-hide" style="margin-top:10px;">
                                <tr>
                                    <th>Название</th>
                                    <th width="80" class="mine-center-item">on Toate produsele</th>
                                    <th width="80" class="mine-center-item">'.lang('On site').'</th>
                                    <th width="80" class="mine-center-item">'.lang('Popular').'</th>
                                    <th width="80" class="mine-center-item">'.lang('New').'</th>
                                    <th width="110" class="mine-center-item">'.lang('Product day').'</th>
                                    <th width="320" class="mine-center-item">'.lang('Action').'</th>
                                </tr>';

                    foreach($objects as $object) {
                        $cmod5 = (!empty($object->toateHainele)) ? 'checked' : '';
                        $cmod1 = (!empty($object->isShown)) ? 'checked' : '';
                        $cmod2 = (!empty($object->isPopular)) ? 'checked' : '';
                        $cmod3 = (!empty($object->isNew)) ? 'checked' : '';
                        $cmod4 = (!empty($object->isToday)) ? 'checked' : '';
                        if($object->category_id == $item->id) {
                            echo '<tr>
                                        <td class="align-middle td-flex">
                                        <input style="width:50px;margin-right:15px" type="text" onkeyup="this.value=this.value.replace(/[^\d]/,\'\')" min="1"
                                                class="form-control text-center sorder" value="' . $object->sorder . '"
                                                name="so[' . $object->id . ']">
                                        <a style="font-weight: 900;"
                                            href="' . $e_path . $object->id . '">' . $object->{'title'.get_language_for_admin(true)} . '</a></td>
                                        <td class="align-middle">
                                                <label class="mt-checkbox mt-checkbox-outline">
                                                    <input data-col="toateHainele" data-table="' . $table . '" type="checkbox" ' . $cmod5 . ' value="' . $object->id . '"
                                                        class="mine_change_check">&nbsp;
                                                    <span></span>
                                                </label>
                                            </td>
                                        <td class="align-middle">
                                                <label class="mt-checkbox mt-checkbox-outline">
                                                    <input data-col="isShown" data-table="'.$table.'" type="checkbox" '.$cmod1.' value="'.$object->id.'"
                                                        class="mine_change_check">&nbsp;
                                                    <span></span>
                                                </label>
                                            </td>
                                        <td class="align-middle">
                                                <label class="mt-checkbox mt-checkbox-outline">
                                                    <input data-col="isPopular" data-table="'.$table.'" type="checkbox" '.$cmod2.' value="'.$object->id.'"
                                                        class="mine_change_check">&nbsp;
                                                    <span></span>
                                                </label>
                                            </td>
                                        <td class="align-middle">
                                                <label class="mt-checkbox mt-checkbox-outline">
                                                    <input data-col="isNew" data-table="'.$table.'" type="checkbox" '.$cmod3.' value="'.$object->id.'"
                                                        class="mine_change_check">&nbsp;
                                                    <span></span>
                                                </label>
                                            </td>
                                        <td class="align-middle">
                                                <label class="mt-checkbox mt-checkbox-outline">
                                                    <input data-col="isToday" data-table="'.$table.'" type="checkbox" '.$cmod4.' value="'.$object->id.'"
                                                        class="mine_change_check">&nbsp;
                                                    <span></span>
                                                </label>
                                            </td>
                                        <td width="320" class="align-middle">
                                            <a href="' . $e_path . $object->id . '/' . '"
                                            class="btn blue">
                                                <i class="fa fa-pencil"></i> '  . lang('Edit'). '
                                            </a>
                                            <a href="' . $del_path . $object->id . '/' . '"
                                            class="btn red mine_delete_row">
                                                <i class="fa fa-trash"></i> '  . lang('Delete'). '
                                            </a>
                                        </td>
                                    </tr>';
                        }
                    }
                    echo '
                            </table>

                        </td>
                    </tr>';
                }

                admin_categories_tree_with_products($categories, $table, $ids, $objects, $e_path, $del_path,  $item->id);
            }
        }
    }
}

if (!function_exists('admin_categories_map')) {
    function admin_categories_map($objects, $parent = 0) {

        $result = array();
        foreach ($objects as $object) {

            if ($object->parent_id == $parent) {
                $result[$object->id] = (object) array();
                $result[$object->id]->id = $object->id;
                $result[$object->id]->parent_id = $object->parent_id;
                $result[$object->id]->{'title'.get_language_for_admin(true)} = $object->{'title'.get_language_for_admin(true)};
                $result[$object->id]->children = admin_categories_map($objects, $object->id);
            }
        }

        return $result;
    }
}

if (!function_exists('admin_categories_json')) {
    function admin_categories_json($objects, $parent = 0, $parent_id = null, $id = null) {

        $result = '';
        foreach ($objects as $object) {
            if ($object->parent_id == $parent) {
                $child = admin_categories_json($objects, $object->id, $parent_id, $id);

                $selected = ($object->id == $parent_id) ? true : false;
                $disabled = ($object->id == $id) ? true : false;

                $result .= "{text:'".$object->{'title'.get_language_for_admin(true)}."',state:{selected:'".$selected."',disabled:'".$disabled."'}, id:".$object->id.", children:[".json_encode($child, JSON_UNESCAPED_UNICODE)."]},";
            }
        }

        $result = str_replace('"', '', $result);
        $result = str_replace('\\', '', $result);

        return $result;
    }
}

if (!function_exists('updateChar')) {
    function updateChar($str, $char, $offset) {
        if(!is_array($offset)){
            if ( ! isset($str[$offset])) {
                return FALSE;
            }
            $str[$offset] = $char;
        } else {
            foreach($offset as $item){
                if ( ! isset($str[$item])) {
                    return FALSE;
                }
                $str[$item] = $char;
            }
        }
        return $str;
    }
}

if (!function_exists('get_youtube_video_ID')) {
    function get_youtube_video_ID($youtube_video_url) {
        /**
         * Pattern matches
         * http://youtu.be/ID
         * http://www.youtube.com/embed/ID
         * http://www.youtube.com/watch?v=ID
         * http://www.youtube.com/?v=ID
         * http://www.youtube.com/v/ID
         * http://www.youtube.com/e/ID
         * http://www.youtube.com/user/username#p/u/11/ID
         * http://www.youtube.com/leogopal#p/c/playlistID/0/ID
         * http://www.youtube.com/watch?feature=player_embedded&v=ID
         * http://www.youtube.com/?feature=player_embedded&v=ID
         */
        $pattern = '%(?:youtube(?:-nocookie)?\.com/(?:[^/]+/.+/|(?:v|e(?:mbed)?)/|.*[?&]v=)|youtu\.be/)([^"&?/ ]{11})%i';
        if (preg_match($pattern, $youtube_video_url, $match)) {
            return $match[1];
        }
        // if no match return false.
        return false;
    }
}

function send_notification($to,$subject,$message, $attach=false): bool
{
    $CI =& get_instance();

    $CI->load->library('email');
    $config['charset'] = 'utf-8';
    $config['mailtype'] = 'html';
    $CI->email->initialize($config);
    $CI->email->from('no-reply@'.$_SERVER['HTTP_HOST'], $_SERVER['HTTP_HOST']);
    $CI->email->to($to);
    $CI->email->reply_to('no-reply@'.$_SERVER['HTTP_HOST']);
    $CI->email->subject($subject);
    $CI->email->message($message);
    if($attach) $CI->email->attach($attach);
    if ($CI->email->send()) {
        return true;
    } else {
        return false;
    }
}

function category_link($lang, $category): string
{
    if(is_array($category)) $category = (object) $category;

    $link = '/'.$lang.'/'.CATEGORY_URI.'/';
    $link .= (empty($child->fixed_link))  ?  (empty($category->uri)) ? $category->id : $category->uri :  $category->fixed_link;

    return $link.'/';
}

function calculateCredit($price, $month, $credit): string
{
    $percent = $credit[array_search($month, array_column($credit, 'months'))]['percent'];
    $num = ($price * ($percent/100) + $price) / $month;
    return number_format($num, 0, '.', ' ');
}

function generateFeedbackText($count, $onlyCount=false): string
{
    if($onlyCount) return $count;

    /*$result = $count . " ";
    if($count == 1){
        $result .= SPACE_FEEDBACKS;
    } else if (in_array($count, array(2,3,4))) {
        $result .= A_FEEDBACKS;
    } else {
        $result .= OV_FEEDBACKS;
    }*/

    return OV_FEEDBACKS . ': ' . $count;
}

if(!function_exists('generate_new_name')) {
    function generate_new_name($product_id) {
        $CI =& get_instance();

        $categories = array(
            679641 => array('attributes' => array(7297,2819)),
            679643 => array('attributes' => array(7297,2819)),
            679644 => array('attributes' => array(7297,2819)),
            679645 => array('attributes' => array(7297,2819)),
            634602 => array('attributes' => array(7297,2819)),
            679646 => array('attributes' => array(7297,2819)),
            679647 => array('attributes' => array(7297,2819)),
            679648 => array('attributes' => array(7297,2819)),
            679649 => array('attributes' => array(7297,2819)),
            679651 => array('attributes' => array(7297,2819)),
            679652 => array('attributes' => array(7297,2819)),
            679653 => array('attributes' => array(7297,2819)),
            679655 => array('attributes' => array(7297,2819)),
            679654 => array('attributes' => array(7297,2819)),
            348593 => array('attributes' => array(2219, 720, 1415, 4353)),
            348595 => array('attributes' => array(2250, 720, 7691, 3906)),
            348603 => array('attributes' => array(2250, 720, 1415, 3906)),
            634554 => array('attributes' => array(6954, 720, 1415, 4353)),
            672541 => array('attributes' => array(1498, 1625, 4055, 4353)),
            672575 => array('attributes' => array(1498, 1625, 4055, 4353)),
            633548 => array('attributes' => array(1515, 1732, 4055, 3907)),
            348602 => array('attributes' => array(1771, 1541, 4055, 1415, 3907)),
            348607 => array('attributes' => array(2228, 1415, 7817)),
            352931 => array('attributes' => array(1498, 3906)),
            352932 => array('attributes' => array(1498, 3906)),
            348609 => array('attributes' => array(1515, 1732, 4055, 3907)),
            348610 => array('attributes' => array(1771, 1541, 4055, 1415, 3907)),
            348611 => array('attributes' => array(2219, 720, 1415, 4353)),
            348612 => array('attributes' => array(1723, 1757, 1415, 4353)),
            374372 => array('attributes' => array(2250, 720, 4055, 7691, 3906)),
            348613 => array('attributes' => array(2233, 1981, 1973, 3906)),
            633560 => array('attributes' => array(2238, 2648, 3907)),
            693312 => array('attributes' => array(2233, 1971, 3906)),
            693313 => array('attributes' => array(2233, 1981, 1973, 3906)),
            633563 => array('attributes' => array(2257, 2648, 1550, 3907)),
            633565 => array('attributes' => array(1938, 2648, 3906)),
            633568 => array('attributes' => array(2648, 1550, 3907)),
            348636 => array('attributes' => array(3084, 2648, 3907)),
            633571 => array('attributes' => array(2734, 2648, 3907)),
            348638 => array('attributes' => array(2648, 3906)),
            348631 => array('attributes' => array(1809, 2648, 3906)),
            633580 => array('attributes' => array(2246, 2648, 1550, 3907)),
            679298 => array('attributes' => array(1809, 2648, 3906)),
            364411 => array('attributes' => array(2229, 1778, 1550, 3907)),
            364424 => array('attributes' => array(2229, 1778, 1550, 3907)),
            364425 => array('attributes' => array(2229, 1778, 1550, 3907)),
            633592 => array('attributes' => array(8943, 1971, 1460, 3907)),
            633562 => array('attributes' => array(2648, 3907)),
            348634 => array('attributes' => array(1808, 3907)),
            348635 => array('attributes' => array(1770, 2648, 3907)),
            348633 => array('attributes' => array(1525, 2648, 3907)),
            693314 => array('attributes' => array(1498, 1778, 3906)),
            634110 => array('attributes' => array(7781, 2647, 3738, 3907)),
            634115 => array('attributes' => array(8777, 2647, 3738, 3907)),
            634116 => array('attributes' => array(7781, 2647, 3738, 3907)),
            634121 => array('attributes' => array(1723, 1757, 1415, 4353)),
            672182 => array('attributes' => array(1723, 1757, 1415, 4353)),
            634130 => array('attributes' => array(1723, 1757, 1415, 4353)),
            634117 => array('attributes' => array(7377, 1074, 1415, 3907)),
            634112 => array('attributes' => array(2648, 7819, 2255, 3906)),
            634125 => array('attributes' => array(1468, 3907)),
            634126 => array('attributes' => array(2648, 7819, 2255, 3906)),
            757561 => array('attributes' => array(2257, 2648, 1550, 3906)),
            634579 => array('attributes' => array(2813, 4239, 2282, 9040, 9039, 5934, 3907),),
            634581 => array('attributes' => array(2813, 4239, 2282, 9040, 9039, 5934, 3907),),
            634580 => array('attributes' => array(2813, 4239, 2282, 9040, 9039, 5934, 3907),),
        );

        $attributes_type = array(
            7297 => array('name' => 'Режим HDR', 'ro' => 'Regim HDR', 'ru' => 'Режим HDR'),
            2819 => array('name' => 'Размер диагонали экрана', 'ro' => ' cm', 'ru' => ' см'),
            2219 => array('name' => 'Общий полезный объем',  'ro' => ' l', 'ru' => ' л'),
            720  => array('name' => 'Высота', 'ro' => ' cm', 'ru' => ' см'),
            1415 => array('name' => 'Класс энергопотребления', 'ro' => '', 'ru' => ''),
            4353 => array('name' => 'цвет', 'ro' => '', 'ru' => ''),
            2250 => array('name' => 'Объем морозильной камеры', 'ro' => ' l', 'ru' => ' л'),
            7691 => array('name' => 'Класс энергопотребления', 'ro' => '', 'ru' => ''),
            3906 => array('name' => 'цвет', 'ro' => '', 'ru' => ''),
            6954 => array('name' => 'Кол-во хранимых бутылок', 'ro' => ' sticle', 'ru' => ' бутылок'),
            1498 => array('name' => 'Количество конфорок', 'ro' => ' arzatoare', 'ru' => ' конфорок'),
            1625 => array('name' => 'Материал варочной поверхности', 'ro' => '', 'ru' => ''),
            4055 => array('name' => 'Ширина', 'ro' => ' cm', 'ru' => ' см'),
            1515 => array('name' => 'Количество моторов', 'ro' => ' motoare', 'ru' => ' моторов'),
            1732 => array('name' => 'Макс. производ', 'ro' => ' m3/h', 'ru' => ' m3/ч'),
            3907 => array('name' => 'цвет', 'ro' => '', 'ru' => ''),
            1771 => array('name' => 'Максимальная вместимость', 'ro' => ' seturi', 'ru' => ' комплектов'),
            1541 => array('name' => 'Количество программ мойки', 'ro' => ' programe', 'ru' => 'программы'),
            2228 => array('name' => 'Объем духовки', 'ro' => ' l', 'ru' => ' л'),
            7817 => array('name' => 'цвет', 'ro' => '', 'ru' => ''),
            1723 => array('name' => 'Макс. загрузка (хлопок)', 'ro' => ' kg', 'ru' => ' кг'),
            1757 => array('name' => 'Макс. скорость отжима', 'ro' => ' rot/min', 'ru' => ' об/мин'),
            2233 => array('name' => 'Объем', 'ro' => ' l', 'ru' => ' л'),
            1981 => array('name' => 'Мощность микроволн', 'ro' => ' W', 'ru' => ' Вт'),
            1973 => array('name' => 'Мощность гриля', 'ro' => ' W', 'ru' => ' Вт'),
            2238 => array('name' => 'Объем контейнера для воды', 'ro' => ' l', 'ru' => ' л'),
            2648 => array('name' => 'Потребляемая мощность', 'ro' => ' W', 'ru' => ' Вт'),
            2257 => array('name' => 'Объем чаши', 'ro' => ' ml', 'ru' => ' мл'),
            1550 => array('name' => 'Количество скоростей', 'ro' => ' trepte viteza', 'ru' => ' скоростей'),
            1938 => array('name' => 'Минимальный', 'ro' => ' gr', 'ru' => ' гр'),
            3084 => array('name' => 'Резервуар для воды', 'ro' => ' l', 'ru' => ' л'),
            2734 => array('name' => 'Резервуар для воды', 'ro' => ' kg/min', 'ru' => ' кг/мин'),
            1809 => array('name' => 'Максимальный объём', 'ro' => ' l', 'ru' => ' л'),
            2229 => array('name' => 'Объем емкости для сока', 'ro' => ' l', 'ru' => ' л'),
            1778 => array('name' => 'Мощность', 'ro' => ' W', 'ru' => ' Вт'),
            1971 => array('name' => 'Мощность', 'ro' => ' W', 'ru' => ' Вт'),
            1460 => array('name' => 'Количество автопрограмм', 'ro' => ' programe', 'ru' => ' программ'),
            1808 => array('name' => 'Максимальный вес', 'ro' => ' kg', 'ru' => ' кг'),
            1770 => array('name' => 'Макс.вместимость', 'ro' => ' kg', 'ru' => ' кг'),
            1525 => array('name' => 'Количество отделений', 'ro' => '', 'ru' => ''),
            7781 => array('name' => 'Объем пылесборника', 'ro' => '', 'ru' => ''),
            2647 => array('name' => 'Мощность', 'ro' => ' W', 'ru' => ' Вт'),
            8777 => array('name' => 'Объем пылесборника', 'ro' => '', 'ru' => ''),
            3738 => array('name' => 'Уровень шума', 'ro' => ' dB', 'ru' => ' дБ'),
            7377 => array('name' => 'Тип сушки', 'ro' => '', 'ru' => ''),
            1074 => array('name' => 'Загрузка при сушке(хлопок)', 'ro' => ' kg', 'ru' => ' кг'),
            7819 => array('name' => 'Паровой удар', 'ro' => ' g/min', 'ru' => ' г/мин'),
            2255 => array('name' => 'Объем резервуара', 'ro' => ' ml', 'ru' => ' мл'),
            1468 => array('name' => 'Количество видов строчек', 'ro' => ' programe', 'ru' => ' программ'),
            8943 => array('name' => 'Объем чаши', 'ro' => ' l', 'ru' => ' л'),
            2246 => array('name' => 'Объем', 'ro' => ' ml', 'ru' => ' мл'),
            2813 => array('name' => 'Размер диагонали экрана', 'ro' => ' inch', 'ru' => ' дюйма'),
            4239 => array('name' => 'Тип процессора', 'ro' => '', 'ru' => ''),
            2282 => array('name' => 'Оперативная память', 'ro' => ' GB', 'ru' => ' ГБ'),
            9040 => array('name' => 'Объем HDD', 'ro' => '', 'ru' => ''),
            9039 => array('name' => 'Объем SSD', 'ro' => '', 'ru' => ''),
            5934 => array('name' => 'Операционная система', 'ro' => '', 'ru' => ''),
        );

        $yes_no_array = array(7297);

        $CI->db->select('*');
        $CI->db->from('product');
        $CI->db->where('id', $product_id);
        $product = $CI->db->get()->row_array();

        if ($product) {
            $CI->db->select('*');
            $CI->db->from('category_product');
            $CI->db->where('product_id', $product_id);
            $result = $CI->db->get()->result_array();

            $category_ids = array_map(function($item){return $item['category_id'];}, $result);

            $response = array();
            if (is_array($category_ids)) {
                foreach ($category_ids as $category_id) {
                    if (isset($categories[$category_id])) {
                        $response = $categories[$category_id];
                    }
                }
            }

            if (count($response)) {
                $name_ru = !$product['old_name_ru'] ? $product['name_ru'] : $product['old_name_ru'];
                $name_ro = !$product['old_name_ro'] ? $product['name_ro'] : $product['old_name_ro'];

                $CI->db->select('*');
                $CI->db->from('product_attribute_value');
                $CI->db->where('product_id', $product_id);
                $result2 = $CI->db->get()->result_array();

                if ($result2) {

                    $db_attributes = array();
                    foreach ($result2 as $row2) {
                        $db_attributes[$row2['attribute_id']] = $row2;
                    }


                    $features_array = array();

                    foreach ($response['attributes'] as $code) {
                        if (isset($db_attributes[$code]) && $db_attributes[$code]['value_ro'] != '\N') {

                            // adauga valoarea din db
                            if (!in_array($db_attributes[$code]['value_ro'], array('?', '0'))) {
                                $features_array['ro'][$code] = $db_attributes[$code]['value_ro'];
                            }

                            if (!in_array($db_attributes[$code]['value_ru'], array('?', '0'))) {
                                $features_array['ru'][$code] = $db_attributes[$code]['value_ru'];
                            }

                            // adauga tipul valorii
                            if (isset($attributes_type[$code])) {
                                if(in_array($code, $yes_no_array)) {
                                    if(in_array(trim($db_attributes[$code]['value_ro']), ['Da', 'DA', 'da', 1])) {
                                        $features_array['ru'][$code] = $attributes_type[$code]['ru'];
                                        $features_array['ro'][$code] = $attributes_type[$code]['ro'];
                                    }
                                } else {
                                    $features_array['ru'][$code] .= $attributes_type[$code]['ru'];
                                    $features_array['ro'][$code] .= $attributes_type[$code]['ro'];
                                }
                            }
                        }
                    }

                    $name_ro = $name_ro . ', ' . implode(', ', $features_array['ro']);
                    $name_ru = $name_ru . ', ' . implode(', ', $features_array['ru']);

                    $upd = [
                        'old_name_ru' => !$product['old_name_ru'] ? $product['name_ru'] : $product['old_name_ru'],
                        'name_ru' => $name_ru,

                        'old_name_ro' => !$product['old_name_ro'] ? $product['name_ro'] : $product['old_name_ro'],
                        'name_ro' => $name_ro,

                    ];

                    generate_url($product_id, $name_ro);

                    $CI->db->where('id', $product_id)->update('product', $upd);
                }
            }
        }
    }
}

if(!function_exists('generate_url')) {
    function generate_url($id, $name, $flag = false) {
        if (empty($id) or empty($name)) return false;

        $uri = transliteration($name . '-' . $id);

        if($flag) {
            $ci = &get_instance();
            $ci->db->where('id', $id)->update('product', ['uri' => $uri]);
        }

        return $uri;
    }
}

if(!function_exists('getCourierDelivery')) {
    function getCourierDelivery($lang, $city_id): array
    {
        $ci = &get_instance();

        $response = [];

        $city = $ci->db->select("region_id, slots, name_$lang AS title")->where("id", $city_id)->get('city')->row();

        if($city->slots) {
            $slots = json_decode($city->slots, true);
        } else {
            $region = $ci->db->where("id", $city->region_id)->get('regions')->row();
            $slots = json_decode($region->slots, true);
        }
        $currentDay = date('N');
        $currentTime = date('H:i');

        $response['city'] = $city->title;
        $response['day'] = TOMORROW;
        $response['free'] = 500;
        if(isset($slots[$currentDay])) {
            foreach($slots[$currentDay] as $slot){
                if($currentTime < $slot['end']) {
                    $response['day'] = TODAY;
                    $response['free'] = $slot['free'];
                }
            }
        }

        return $response;
    }
}

if (!function_exists('order_double')) {
    function order_double($id = false) {
        if($id) {
            $ci =& get_instance();

            $anvelope = [657513, 657560, 657562, 691809];
            $check_double = $ci->db->where("product_id", $id)->where_in("category_id", $anvelope)->get("category_product")->result();
            return !empty($check_double);
        } else {
            return false;
        }

    }
}

if (!function_exists('array_search_partial')) {
    function array_search_partial($arr, $keyword) {
        if(!$arr) return false;
        foreach($arr as $index => $string) {
            if (strpos($index, $keyword) !== FALSE)
                return $index;
        }
    }
}

if (!function_exists('curl_get')) {
    function curl_get($method, $associative = false)
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_POST, 0);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 0);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_URL, API_URI . $method);
        $result = curl_exec($ch);
        curl_close($ch);

        return json_decode($result, $associative);
    }
}

if (!function_exists('curl_post')) {
    function curl_post($method, $data, $associative = false)
    {
        //if($method == 'product/find') dd(json_encode($data, true));

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 0);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_URL, API_URI . $method);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type:application/json'));
        $result = curl_exec($ch);
        curl_close($ch);

        return json_decode($result, $associative);
    }
}

if (!function_exists('wrapCatName')) {
    function wrapCatName($str)
    {
        $str = trim($str);
        $str = str_replace(' для ', '<br>для ', $str);
        $str = str_replace(' и ', '<br>и ', $str);
        $str = str_replace(' de ', '<br>de ', $str);
        return str_replace(' si ', '<br>si ', $str);
    }
}

if (!function_exists('switcher_ru')) {
    function switcher_ru($value)
    {
        $converter = array(
            'f' => 'а', ',' => 'б', 'd' => 'в', 'u' => 'г', 'l' => 'д', 't' => 'е', '`' => 'ё',
            ';' => 'ж', 'p' => 'з', 'b' => 'и', 'q' => 'й', 'r' => 'к', 'k' => 'л', 'v' => 'м',
            'y' => 'н', 'j' => 'о', 'g' => 'п', 'h' => 'р', 'c' => 'с', 'n' => 'т', 'e' => 'у',
            'a' => 'ф', '[' => 'х', 'w' => 'ц', 'x' => 'ч', 'i' => 'ш', 'o' => 'щ', 'm' => 'ь',
            's' => 'ы', ']' => 'ъ', "'" => "э", '.' => 'ю', 'z' => 'я',

            'F' => 'А', '<' => 'Б', 'D' => 'В', 'U' => 'Г', 'L' => 'Д', 'T' => 'Е', '~' => 'Ё',
            ':' => 'Ж', 'P' => 'З', 'B' => 'И', 'Q' => 'Й', 'R' => 'К', 'K' => 'Л', 'V' => 'М',
            'Y' => 'Н', 'J' => 'О', 'G' => 'П', 'H' => 'Р', 'C' => 'С', 'N' => 'Т', 'E' => 'У',
            'A' => 'Ф', '{' => 'Х', 'W' => 'Ц', 'X' => 'Ч', 'I' => 'Ш', 'O' => 'Щ', 'M' => 'Ь',
            'S' => 'Ы', '}' => 'Ъ', '"' => 'Э', '>' => 'Ю', 'Z' => 'Я',

            '@' => '"', '#' => '№', '$' => ';', '^' => ':', '&' => '?', '/' => '.', '?' => ',',
        );

        return strtr($value, $converter);
    }
}

if (!function_exists('switcher_en')) {
    function switcher_en($value)
    {
        $converter = array(
            'а' => 'f', 'б' => ',', 'в' => 'd', 'г' => 'u', 'д' => 'l', 'е' => 't', 'ё' => '`',
            'ж' => ';', 'з' => 'p', 'и' => 'b', 'й' => 'q', 'к' => 'r', 'л' => 'k', 'м' => 'v',
            'н' => 'y', 'о' => 'j', 'п' => 'g', 'р' => 'h', 'с' => 'c', 'т' => 'n', 'у' => 'e',
            'ф' => 'a', 'х' => '[', 'ц' => 'w', 'ч' => 'x', 'ш' => 'i', 'щ' => 'o', 'ь' => 'm',
            'ы' => 's', 'ъ' => ']', 'э' => "'", 'ю' => '.', 'я' => 'z',

            'А' => 'F', 'Б' => '<', 'В' => 'D', 'Г' => 'U', 'Д' => 'L', 'Е' => 'T', 'Ё' => '~',
            'Ж' => ':', 'З' => 'P', 'И' => 'B', 'Й' => 'Q', 'К' => 'R', 'Л' => 'K', 'М' => 'V',
            'Н' => 'Y', 'О' => 'J', 'П' => 'G', 'Р' => 'H', 'С' => 'C', 'Т' => 'N', 'У' => 'E',
            'Ф' => 'A', 'Х' => '{', 'Ц' => 'W', 'Ч' => 'X', 'Ш' => 'I', 'Щ' => 'O', 'Ь' => 'M',
            'Ы' => 'S', 'Ъ' => '}', 'Э' => '"', 'Ю' => '>', 'Я' => 'Z',

            '"' => '@', '№' => '#', ';' => '$', ':' => '^', '?' => '&', '.' => '/', ',' => '?',
        );

        return strtr($value, $converter);
    }
}
