<?
$uri3 = $this->uri->segment(3);
$head1 = 'Отзывы';
$tblname = 'product_feedback';
$delpath = '/' . ADM_CONTROLLER . '/delete_row/' . $tblname . '/';

$config = array();
$config["base_url"] = '/'.ADM_CONTROLLER.'/product_feedback/';
$config["total_rows"] =  $this->db->count_all($tblname);;
$config["per_page"] = 50;
$config["uri_segment"] = 3;
$config['full_tag_open'] = "<ul class='pagination'>";
$config['full_tag_close'] ="</ul>";
$config['num_tag_open'] = '<li>';
$config['num_tag_close'] = '</li>';
$config['cur_tag_open'] = "<li class='disabled'><li class='active'><a href='#'>";
$config['cur_tag_close'] = "<span class='sr-only'></span></a></li>";
$config['next_tag_open'] = "<li>";
$config['next_tagl_close'] = "</li>";
$config['prev_tag_open'] = "<li>";
$config['prev_tagl_close'] = "</li>";
$config['first_tag_open'] = "<li>";
$config['first_tagl_close'] = "</li>";
$config['last_tag_open'] = "<li>";
$config['last_tagl_close'] = "</li>";
$config['last_link'] = 'Последняя';
$config['first_link'] = 'Первая';

$this->pagination->initialize($config);

$page = ($this->uri->segment(3)) ? $this->uri->segment(3) : 0;

$pagination_links = $this->pagination->create_links();

$this->db->limit($config["per_page"], $page);
$query = $this->db->where("parent_id", 0)->order_by('ID DESC')->get($tblname);
$result = $query->result_array();

?>
<div class="page-bar">
    <ul class="page-breadcrumb">
        <li>
            <i class="fa fa-home"></i>
            <a href="/<?= ADM_CONTROLLER ?>/menu/">Главная</a>
            <i class="fa fa-angle-right"></i>
        </li>
        <li>
            <a><?= $head1 ?></a>
        </li>
    </ul>
</div>

<h2><?= $head1 ?> <a href="/<?=ADM_CONTROLLER?>/product_feedback/download" class="btn blue">Скачать</a></h2>
<br>


<? if (!empty($result)) { ?>
    <div class="">
        <table class="table table-striped table-bordered table-hover dataTable no-footer">
            <tr class="heading">
                <th width="10"><i class="fa fa-eye"></i></th>
                <th width="120">Имя</th>
                <th width="120">Email</th>
                <th width="120">Телефон</th>
                <th>Товар</th>
                <th>Комментарий</th>
                <th>Изображений</th>
                <th width="280">Действия</th>
            </tr>

            <? foreach ($result as $row) { ?>
                <tr>
                    <? if (!empty($row['isShown'])) $c_mod = ' checked';
                    else $c_mod = ''; ?>
                    <td><input <?= $c_mod ?> type="checkbox" data-id="<?= $row['id'] ?>" class="form-control changer" value="1"></td>
                    <td><?= $row['first_name'] ?> <?= $row['last_name'] ?></td>
                    <td><?= $row['email'] ?></td>
                    <td><?= $row['phone'] ?></td>
                    <td><?= $row['product_id'] ?></td>
                    <td><?= $row['text'] ?></td>
                    <td>
                        <? if ($row['img']) { ?>
                            <? $images = explode(' ', trim($row['img'])); ?>
                            <? foreach ($images as $img) { ?>
                                <img src="/public/product_feedback/<?= $row['product_id'] ?>/<?= $img ?>" alt="IMG" width="50">
                            <? } ?>
                        <? } ?>
                    </td>
                    <td>
                        <a href="/<?=ADM_CONTROLLER?>/product_feedback_edit/<?= $row['id'] ?>/" class="btn blue"><i class="fa fa-pencil"></i> добавить ответ</a>
                        <a href="<?= $delpath . $row['id'] ?>/" class="btn red"><i class="fa fa-trash"></i> </a>
                    </td>
                </tr>
            <? } ?>
        </table>
        <div class="pagination_links">
            <?php echo $pagination_links; ?> </div>
        </div>
        <div>
            <strong>Количество отзывов: <?= $config["total_rows"] ?></strong>
        </div>
    </div>

<?php } ?>

<script>
    $(document).ready(function() {
        $('.changer').change(function() {
            let id=$(this).data('id');
            let val;
            if ($(this).is(':checked')) {
                val=1;
            } else {
                val=0;
            }
            $.post('/<?=ADM_CONTROLLER?>/checkboxChange/', { "table": "<?=$tblname?>", "col": "isShown", "id":id, "val":val } );
        });
    });
</script>
