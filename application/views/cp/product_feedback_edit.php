<?
$uri3 = $this->uri->segment(3);
$head1 = 'Отзывы';
$head2 = 'Отзыв';
$tblname = 'product_feedback';

$product_feedback = $this->db->where("id", $uri3)->get($tblname)->row();

if ($_SERVER['REQUEST_METHOD']=='POST') {

    $ins = array(
        'first_name' => $_POST['first_name'],
        'last_name' => $_POST['last_name'],
        'date' => $_POST['date'],
        'isShown' => $_POST['isShown'],
        'product_id' => $_POST['product_id'],
        'parent_id' => $_POST['parent_id'],
        'text' => $_POST['text'],
        'email' => '',
        'phone' => '',
        'score' => 0,
        'img' => '',
    );

    $this->db->insert($tblname, $ins);
}

$result = $this->db->where("parent_id", $uri3)->order_by('ID DESC')->get($tblname)->result_array();

?>
<div class="page-bar">
    <ul class="page-breadcrumb">
        <li>
            <i class="fa fa-home"></i>
            <a href="/<?= ADM_CONTROLLER ?>/menu/">Главная</a>
            <i class="fa fa-angle-right"></i>
        </li>
        <li>
            <a href="/<?=ADM_CONTROLLER?>/product_feedback/"><?= $head1 ?></a>
            <i class="fa fa-angle-right"></i>
        </li>
        <li>
            <a><?= $head2 ?> <?=$uri3?></a>
        </li>
    </ul>
</div>

<h2><?= $head2 ?> <?=$uri3?></h2>
<br>


<? if (!empty($result)) { ?>
    <div class="">
        <table class="table table-striped table-bordered table-hover dataTable no-footer">
            <tr class="heading">
                <th width="120">Дата</th>
                <th width="120">Имя</th>
                <th>Комментарий</th>
                <th width="220">Действия</th>
            </tr>

            <? foreach ($result as $row) { ?>
                <tr>
                    <td><?= date('d.m.Y', strtotime($row['date'])) ?></td>
                    <td><?= $row['first_name'] ?> <?= $row['last_name'] ?></td>
                    <td><?= $row['text'] ?></td>
                    <td>
                        <a href="/<?=ADM_CONTROLLER?>/delete_row/product_feedback/<?= $row['id'] ?>/" class="btn red"><i class="fa fa-trash"></i> </a>
                    </td>
                </tr>
            <? } ?>
        </table>
    </div>

<?php } ?>

<form method="POST" action="/<?=ADM_CONTROLLER?>/product_feedback_edit/<?=$uri3?>/">
    <table class="table table-striped table-bordered table-hover">
        <input type="hidden" name="first_name" value="Expert">
        <input type="hidden" name="last_name" value="Bomba">
        <input type="hidden" name="date" value="<?=date('Y-m-d')?>">
        <input type="hidden" name="isShown" value="1">
        <input type="hidden" name="parent_id" value="<?=$product_feedback->id?>">
        <input type="hidden" name="product_id" value="<?=$product_feedback->product_id?>">
        <tr>
            <td width="200">Ответ эксперта по бомбе</td>
            <td><textarea class="form-control" name="text" id="" cols="30" rows="5"></textarea></td>
        </tr>
        <tr>
            <td colspan="2"><button type="submit" class="btn green"><i class="fa fa-check"></i> Добавить</button></td>
        </tr>
    </table>
</form>

