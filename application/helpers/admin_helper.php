<?php
function create_form_by_array($form, $data) {
    foreach ($form as $row) {

        $value = @$data[$row['name']];

        echo '<tr' . @$row['dop_tr'] . '>
			<td width="200">' . $row['descr'] . '</td>
			<td>';
        if ($row['type'] == 'text') {
            echo '<input name="data[' . $row['name'] . ']" type="text" id="' . $row['name'] . '" class="' . $row['class'] . '" value="' . $value . '" />';
        } elseif ($row['type'] == 'textarea') {
            echo '<textarea name="data[' . $row['name'] . ']" id="' . $row['name'] . '" class="' . $row['class'] . '"' . @$row['dop_style'] . '>' . $value . '</textarea>';
        } elseif ($row['type'] == 'select') {
            echo '<select class="' . $row['class'] . '" name="data[' . $row['name'] . ']">';
            addSelect($row['source'], $row['sourcename'], $value);
            echo '</select>';
        } elseif ($row['type'] == 'checkbox') {
            if (!empty($value)) $md = ' checked'; else $md = '';
            echo '<input name="data[' . $row['name'] . ']"' . $md . ' type="checkbox" id="' . $row['name'] . '" class="' . $row['class'] . '" value="1" />';
        } elseif ($row['type'] == 'file') {
            echo '<input type="file" name="' . $row['name'] . '">';
            if (!empty($value)) {
                if ($row['name'] == 'pdf_ru' || $row['name'] == 'pdf_ro') {
                    echo '<a href="/public/' . $row['path'] . '/' . $value . '" style="float:right;max-width:200px;" target="_blank">Скачать pdf</a>';
                } else {
                    $image = '/public/'.$row['path'].'/'.$value;
                    echo '<img src="' . $image . '" style="float:right;max-width:200px;"><br clear="all"><a class="myRemoveImage" data-id="'.uri(3).'" data-col="'.$row['name'].'" data-table="'.$row['path'].'" href="" style="float: right">Удалить</a>';
                }
            }
        } elseif ($row['type'] == 'catlist') {
            $cats = get_categories();
            echo '<select class="' . $row['class'] . '" name="data[' . $row['name'] . ']">';
            echo '<option value="0"></option>';
            build_cat_select($cats, 0, $value, $row['exclude']);

            echo '</select>';
        }
        echo @$row['dop_data'];
        echo '</td>
		</tr>';
    }
}

function admin_form_text($translate = false, $name = '', $descr = '', $class = '', $dop_style = '', $dop_tr = '')
{
    $ci =& get_instance();
    $langs = $ci->_langs();

    if (!empty($class)) $class = ' ' . $class;
    if (!$translate) {
        $return[] = array(
            'dop_tr' => $dop_tr,
            'dop_style' => $dop_style,
            'type' => 'text',
            'class' => 'form-control' . $class,
            'descr' => $descr,
            'name' => $name
        );
    } else {
        foreach ($langs as $lang) {
            $return[] = array(
                'dop_tr' => $dop_tr,
                'dop_style' => $dop_style,
                'type' => 'text',
                'class' => 'form-control' . $class,
                'descr' => $descr . ' ' . $lang,
                'name' => $name . '_' . $lang
            );
        }
    }

    return $return;
}

function admin_form_textarea($translate = false, $name = '', $descr = '', $class = '', $dop_style = '', $dop_tr = '')
{
    $ci =& get_instance();
    $langs = $ci->_langs();

    if (!empty($class)) $class = ' ' . $class;
    if (!$translate) {
        $return[] = array(
            'dop_tr' => $dop_tr,
            'dop_style' => $dop_style,
            'type' => 'textarea',
            'class' => 'form-control' . $class,
            'descr' => $descr,
            'name' => $name
        );
    } else {
        foreach ($langs as $lang) {
            $return[] = array(
                'dop_tr' => $dop_tr,
                'dop_style' => $dop_style,
                'type' => 'textarea',
                'class' => 'form-control' . $class,
                'descr' => $descr . ' ' . $lang,
                'name' => $name . '_' . $lang
            );
        }
    }

    return $return;
}

function admin_form_file($translate = false, $name = '', $descr = '', $path = '', $class = '', $dop_style = '', $dop_tr = '')
{
    $ci =& get_instance();
    $langs = $ci->_langs();

    if (!empty($class)) $class = ' ' . $class;
    if (!$translate) {
        $return[] = array(
            'dop_tr' => $dop_tr,
            'dop_style' => $dop_style,
            'type' => 'file',
            'path' => $path,
            'class' => 'form-control' . $class,
            'descr' => $descr,
            'name' => $name
        );
    } else {
        foreach ($langs as $lang) {
            $return[] = array(
                'dop_tr' => $dop_tr,
                'dop_style' => $dop_style,
                'type' => 'file',
                'path' => $path,
                'class' => 'form-control' . $class,
                'descr' => $descr . ' ' . $lang,
                'name' => $name . '_' . $lang
            );
        }
    }

    return $return;
}

function admin_form_select($name = '', $descr = '', $source = '', $sourcename = '', $class = '', $dop_style = '', $dop_tr = '')
{
    $ci =& get_instance();
    $langs = $ci->_langs();

    if (!empty($class)) $class = ' ' . $class;
    $return[] = array(
        'dop_tr' => $dop_tr,
        'dop_style' => $dop_style,
        'type' => 'select',
        'class' => 'form-control' . $class,
        'descr' => $descr,
        'name' => $name,
        'source' => $source,
        'sourcename' => $sourcename
    );

    return $return;
}

function convert_form($form)
{
    $form1 = array();

    foreach ($form as $ext) {
        foreach ($ext as $in) {
            $form1[] = $in;
        }
    }

    return $form1;
}

function standart_form_script($tblname)
{
    ?>
    <script type="text/javascript" src="/ckeditor/ckeditor.js"></script>
    <script type="text/javascript">
        function toggleb() {
            $("#newb").toggle();
        }

        function localsort() {
            counter = 1;
            data = '';
            $.each($('.sorthold'), function () {
                $(this).html(counter);
                if (counter < 2) breaker = ''; else breaker = '<>';
                data += breaker + $(this).attr('oid') + ':' + counter;
                counter++;
            });
            $.post('/<?=ADM_CONTROLLER?>/edit_table_order/', {data: data, table: '<?=$tblname?>'}, function (ret) {

            });
        }
    </script>
    <?
}

function addSelect($table, $sourcename = 'Name', $value = 0)
{
    $dopcond = '';
    $ci =& get_instance();
    //$q=$ci->db->order_by('Sorder ASC,ID DESC')->get($table)->result_array();
    if ($table == 'TopMenu') {
        echo '<option value="0"></option>';
        $q = $ci->db->where('ParentID', 0)->order_by('sorder ASC,id DESC')->get($table)->result_array();
    } elseif ($table == 'Gallery') {
        echo '<option value="0"></option>';
        $q = $ci->db->where('ShowInMenu', 1)->order_by('sorder ASC,id DESC')->get($table)->result_array();
    } else {
        $q = $ci->db->order_by('sorder ASC,id DESC')->get($table)->result_array();
    }
    if ($table == 'bottom_category') {
        echo '<option value="0"></option>';
    }
    if ($table == 'promo_category') {
        echo '<option value="0">Без категории</option>';
    }
    foreach ($q as $arr) {
        if ($arr['id'] == $value) $mod = ' selected'; else $mod = '';
        echo '<option' . $mod . ' value="' . $arr['id'] . '">' . $arr[$sourcename] . '</option>';
    }
}

function save_data($input = array(), $checker, $files, $tblname, $form, $block = false)
{
    $ci =& get_instance();

    $uri3 = intval($ci->uri->segment(3));

    $erflag = false;
    $err = '';
    if (!empty($checker)) {
        foreach ($checker as $val) {
            if (empty($input[$val])) $erflag = true;
        }
    }

    foreach ($form as $row) {
        if (@$row['type'] == 'checkbox') {
            if (empty($input[$row['name']])) $input[$row['name']] = 0; else $input[$row['name']] = 1;
        }
    }

    if (!$erflag) {

        $data_array = $input;
        if (empty($uri3)) {
            $ci->db->insert($tblname, $data_array);
            $id = $ci->db->insert_id();
        } else {
            $id = $uri3;
            $ci->db->where('ID', $id)->update($tblname, $data_array);
        }

        foreach ($files as $filename) {

            if (!empty($_FILES[$filename]['name'])) {

                $image = $ci->db->select($filename." as image")->where("id", $id)->get($tblname)->row()->image;
                if($image) unlink_files($tblname, [$image]);


                $config['upload_path'] = realpath("public") . '/' . $tblname;
                $config['allowed_types'] = 'svg|jpg|jpeg|pdf|png|jp2';
                $config['encrypt_name'] = TRUE;
                $ci->load->library('upload', $config);

                $ci->upload->do_upload($filename);
                $resarr = $ci->upload->data();
                $file = $resarr['file_name'];

                if (strtolower($resarr['file_ext']) == '.jpg' || strtolower($resarr['file_ext']) == '.jpeg' || strtolower($resarr['file_ext']) == '.pdf' || strtolower($resarr['file_ext']) == '.png' || strtolower($resarr['file_ext']) == '.jp2' || strtolower($resarr['file_ext']) == '.svg') {
                    $ci->db->where('ID', $id)->update($tblname, array($filename => $file));
                }
            }
        }
        if ($block) {
            return $id;
        }

    } else {
        $err .= '<div style="padding:10px 0;color:#ff0000;">Все поля отмеченные * обязательны для заполения</div>';
    }

    return $err;
}

if(!function_exists('unlink_files')) {
    function unlink_files($dir, $files) {

        if(is_array($files)){
            foreach($files as $file) {
                // delete main file
                $main = realpath('public/' . $dir) . '/' . $file;
                if(is_file($main)) unlink($main);
                $webp = realpath('public/' . $dir) . '/' . replaceWebp($file);
                if(is_file($webp)) unlink($webp);

                // delete thumbs
                $dirs = array_filter(glob('public/'.$dir.'/thumbs/*'), 'is_dir');

                foreach($dirs as $directory) {
                    $thumb = $directory.'/'.$file;
                    if (is_file($thumb)) unlink($thumb);
                }
            }
        } else {
            // delete main file
            $file = $files;
            $main = realpath('public/' . $dir) . '/' . $file;
            if(is_file($main)) unlink($main);
            $main_webp = realpath('public/' . $dir) . '/' . replaceWebp($file);
            if(is_file($main_webp)) unlink($main_webp);

            // delete thumbs
            $dirs = array_filter(glob('public/'.$dir.'/thumbs/*'), 'is_dir');

            foreach($dirs as $directory) {
                $thumb = $directory.'/'.$file;
                if (is_file($thumb)) unlink($thumb);
            }
        }
    }
}

function diverse_array($vector)
{
    $result = array();
    foreach ($vector as $key1 => $value1)
        foreach ($value1 as $key2 => $value2)
            $result[$key2][$key1] = $value2;
    return $result;
}

function getStatuses($flag = false) {

    $statuses = [
        1=>'Заявка',
        2=>'Закрыт',
        3=>'Отменен',
        4=>'Успешная транзакция',
        5=> 'Отложенный',
        6=>'В работе'
    ];

    if($flag) $statuses[0] = 'Все заказы';

    return $statuses;
}

function get_filters_for_set($lang, $category_set_id = 0, $prod_ids = array(), $attribute_id_list = array())
{
    return curl_post('cache/filters_for_set', [
        'lang' => $lang,
        'category_set_id' => $category_set_id,
        'prod_ids' => $prod_ids,
        'attribute_id_list' => $attribute_id_list,
    ], true);
}

if (!function_exists('remove_dir')) {
    function remove_dir($dir = '') {
        if (is_dir($dir)) {
            $files = new RecursiveIteratorIterator(
                new RecursiveDirectoryIterator($dir, RecursiveDirectoryIterator::SKIP_DOTS),
                RecursiveIteratorIterator::CHILD_FIRST
            );

            foreach ($files as $fileinfo) {
                $todo = ($fileinfo->isDir() ? 'rmdir' : 'unlink');
                $todo($fileinfo->getRealPath());
            }
        }
    }
}

