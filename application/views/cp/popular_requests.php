<?
$uri3 = $this->uri->segment(3);
$head1 = 'Популярные запросы';
$tblname = 'popular_requests';
$delpath = '/' . ADM_CONTROLLER . '/delete_row/' . $tblname . '/';

$config = array();
$config["base_url"] = '/'.ADM_CONTROLLER.'/popular_requests/';
$config["total_rows"] =  $this->db->count_all($tblname);
$config['page_query_string'] = TRUE;
$config["per_page"] = 50;
$config["uri_segment"] = null;
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

$page = ($this->input->get('per_page')) ? $this->input->get('per_page') : 0;

$pagination_links = $this->pagination->create_links();

$this->db->limit($config["per_page"], $page);
$query = $this->db->order_by('count DESC')->get($tblname);
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

<h2><?= $head1 ?></h2>
<br>


<? if (!empty($result)) { ?>
    <div class="">
        <table class="table table-striped table-bordered table-hover dataTable no-footer">
            <tr class="heading">
                <th width="120">Имя</th>
                <th width="120">Count</th>
            </tr>

            <? foreach ($result as $row) { ?>
                <tr>
                    <td><?= $row['query'] ?></td>
                    <td><?= $row['count'] ?></td>
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
