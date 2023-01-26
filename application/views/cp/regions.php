<?
$uri3=$this->uri->segment(3);

$head1='Регионы';
$head3='titleRU';
$tblname='regions';
$headerloc='regions';

$e_path='/'.ADM_CONTROLLER.'/'.$headerloc.'/';
$delpath='/'.ADM_CONTROLLER.'/delete_row/'.$tblname.'/';
$err='';

if ($_SERVER['REQUEST_METHOD']=='POST') {

    $this->db->where("id", $uri3)->update("regions", ["slots" => json_encode($_POST['slot'])]);
}
?>

<?if (empty($uri3)) {?>
    <div class="page-bar">
        <ul class="page-breadcrumb">
            <li>
                <i class="fa fa-home"></i>
                <a href="/<?=ADM_CONTROLLER?>/menu/">Главная</a>
                <i class="fa fa-angle-right"></i>
            </li>
            <li>
                <a><?=$head1?></a>
            </li>
        </ul>
    </div>

    <? $regions=$this->db->order_by('Sorder ASC, ID DESC')->get($tblname)->result_array();?>
    <? if(!empty($regions)) { ?>
        <div class="">
            <table class="table table-striped table-bordered table-hover dataTable no-footer">
                <tr class="heading">
                    <th>Название</th>
                    <th width="220">Действия</th>
                </tr>

                <? foreach($regions as $region) { ?>
                    <tr>
                        <td>
                            <a href="<?=$e_path.$region['id']?>"><?=$region[$head3]?></a>
                        </td>
                        <td align="center">
                            <a href="<?=$e_path.$region['id']?>" class="btn blue"><i class="fa fa-pencil"></i> Редактировать</a>
                        </td>
                    </tr>
                <?}?>
            </table>
        </div>
    <? } ?>
<? } else { ?>
    <?php
    $current = date('N');
    $result = $this->db->get("weeks")->result();
    $weeks = [];
    foreach($result as $row) {
        $weeks[$row->id]['name'] = $row->name_ru;
    }

    $data=$this->db->where('id',$uri3)->get($tblname)->row_array();
    $slots = json_decode($data['slots'], true);
    ?>
    <div class="page-bar">
        <ul class="page-breadcrumb">
            <li>
                <i class="fa fa-home"></i>
                <a href="/<?=ADM_CONTROLLER?>/menu/">Главная</a>
                <i class="fa fa-angle-right"></i>
            </li>
            <li>
                <a href="/<?=ADM_CONTROLLER?>/<?=$headerloc?>/"><?=$head1?></a>
                <i class="fa fa-angle-right"></i>
            </li>
            <li>
                <a>Редактирование "<?=$data[$head3]?>"</a>
            </li>
        </ul>
    </div>
    <?=$err?>

    <h3>Тайм-слоты <?=$data[$head3]?></h3>
    <form name="form1" method="POST" action="<?=$e_path.$uri3;?>/" enctype="multipart/form-data">
        <div class="tabbable tabbable-custom">
            <ul class="nav nav-tabs">
                <? foreach($weeks as $key => $week){?>
                    <?
                    $today = date('Y-m-d');
                    switch(true) {
                        case $key == $current :
                            $date = date('d.m');
                            break;
                        case $key > $current :
                            $temp = $key - $current;
                            $date = date('d.m', strtotime($today . ' + ' . $temp . ' day'));
                            break;
                        case $key < $current :
                            $temp = ($key + (7 - $current));
                            $date = date('d.m', strtotime($today . ' + ' . $temp . ' day'));
                            break;
                    }
                    if(!isset($_SESSION['slot_tab'])) $_SESSION['slot_tab'] = 1;
                    ?>
                    <li class="<?=($key == $_SESSION['slot_tab']) ? 'active' : ''?>">
                        <a href="#tab_1_<?=$key?>" onclick="demo(<?=$key?>);" data-toggle="tab"><?=$week['name']?> <?=$date?></a>
                    </li>
                <?}?>
            </ul>
            <div class="tab-content">
                <? foreach($weeks as $key => $week){?>
                    <div class="tab-pane <?=($key == $_SESSION['slot_tab']) ? 'active' : ''?>" id="tab_1_<?=$key?>">
                        <div class="time-box">
                            <input type="hidden" name="count" value=" <?=(isset($slots[$key])) ? count($slots[$key]) : 0?>">
                            <button class="time-cell-add btn blue" data-content='<div class="time-cell">
                                <input type="text" class="form-control tstart" name="slot[<?=$key?>][__id__][start]" required>
                                <input type="text" class="form-control tend" name="slot[<?=$key?>][__id__][end]" required>
                                <input type="text" class="form-control" name="slot[<?=$key?>][__id__][price]" required>
                                <input type="text" class="form-control" name="slot[<?=$key?>][__id__][qty]" required>
                                <input type="text" class="form-control" name="slot[<?=$key?>][__id__][free]" required>
                                <button class="time-cell-remove btn red"><i class="fa fa-trash"></i></button>
                            </div>'>
                                <i class="fa fa-plus"></i>
                            </button>
                            <div class="time-cell" style="width: 465px;">
                                <div style="flex: 1 1 auto;text-align: center;">Дата <br> начала</div>
                                <div style="flex: 1 1 auto;text-align: center;">Дата <br> окончания</div>
                                <div style="flex: 1 1 auto;text-align: center;">Цена</div>
                                <div style="flex: 1 1 auto;text-align: center;">кол-во <br> доставок</div>
                                <div style="flex: 1 1 auto;text-align: center;">бесплатная <br> доставка от</div>
                            </div>
                            <? if(isset($slots[$key])) {?>
                                <? foreach($slots[$key] as $index=>$slot) {?>
                                    <div class="time-cell">
                                        <input type="text" class="form-control tstart" name="slot[<?=$key?>][<?=$index?>][start]" value="<?=$slot['start']?>" required>
                                        <input type="text" class="form-control tend" name="slot[<?=$key?>][<?=$index?>][end]" value="<?=$slot['end']?>" required>
                                        <input type="text" class="form-control" name="slot[<?=$key?>][<?=$index?>][price]" value="<?=$slot['price']?>" required>
                                        <input type="text" class="form-control" name="slot[<?=$key?>][<?=$index?>][qty]" value="<?=$slot['qty']?>" required>
                                        <input type="text" class="form-control" name="slot[<?=$key?>][<?=$index?>][free]" value="<?=$slot['free']?>" required>
                                        <button class="time-cell-remove btn red"><i class="fa fa-trash"></i></button>
                                    </div>
                                <?}?>
                            <?}?>
                        </div>
                    </div>
                <?}?>
            </div>
            <br>
            <button type="submit" class="btn green"><i class="fa fa-check"></i> Добавить</button>
        </div>
    </form>

    <script src="https://cdn.jsdelivr.net/npm/timepicker@1.13.18/jquery.timepicker.min.js"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/timepicker@1.13.18/jquery.timepicker.min.css">
    <script>
        function demo(id) {
            $.post('/<?=ADM_CONTROLLER?>/change_slot_tab/', {id: id});
        }
        $(function() {
            $('.tstart').timepicker({'timeFormat': 'H:i'});
            $('.tend').timepicker({'timeFormat': 'H:i'});
            $(document).on("click", ".time-cell-remove", function(){
                $(this).closest(".time-cell").remove();
            });
            $(document).on("click", ".time-cell-add", function(e) {
                e.preventDefault();
                let $countInput = $(this).closest(".time-box").find("input[name='count']");
                let count = parseInt($countInput.val());
                let content = $(this).data("content");
                let box = $(this).closest(".time-box");
                content = content.replace(/__id__/g, count);
                box.append(content);
                $countInput.val(count+1);

                $('.tstart').timepicker({'timeFormat': 'H:i'});
                $('.tend').timepicker({'timeFormat': 'H:i'});
            });
        });
    </script>
    <style>
        .time-box {
            display: flex;
            position: relative;
            flex-direction: column;
            align-items: start;
            min-height: 34px;
        }
        .time-cell {
            display: flex;
            justify-content: center;
            align-items: center;
            font-size: 12px;
            margin-bottom: 5px;
        }
        .time-cell input {
            width: 80px;
            margin: 0px 5px;
        }
        .time-cell-remove {
            margin-left: 5px;
        }
        .time-cell-add {
            position: absolute;
            right: 0;
            top: 0;
        }
    </style>
<?}?>
