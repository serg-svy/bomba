<?
$uri3=$this->uri->segment(3);

$head1='Города';
$head2='Редактирование горда';
$head3='name_ru';
$addnew='город';
$tblname='city';
$headerloc='city';

$e_path='/'.ADM_CONTROLLER.'/'.$headerloc.'/';
$delpath='/'.ADM_CONTROLLER.'/delete_row/'.$tblname.'/';
$err='';

$form=array();
$form[]=admin_form_text(true,'name','Название');
$form[]=admin_form_text(true,'declension','Склонение города (Доставка по ...)');
$form[]=admin_form_textarea(true,'textCourier','Текст доставки курьером','ckeditor');
$form[]=admin_form_textarea(true,'textPickup','Текст самовывоза','ckeditor');

$form[]=
    array(
        array(
            'name' => "is_sector",
            'type' => 'checkbox',
            'class' => 'form-control',
            'descr' => 'Является сектором'
        )
    );
$form[]=
    array(
        array(
            'name' => "is_shown_slots",
            'type' => 'checkbox',
            'class' => 'form-control',
            'descr' => 'Показать слоты'
        )
    );
$form[]=
    array(
        array(
            'name' => "on_popup",
            'type' => 'checkbox',
            'class' => 'form-control',
            'descr' => 'Показать по дефолту в popup'
        )
    );

$form1=convert_form($form);

$files=array();
$checker=array('name_ru');

if ($_SERVER['REQUEST_METHOD']=='POST') {
    if(isset($_POST['slot'])) $_POST['data']['slots'] = json_encode($_POST['slot']);
	$err=save_data($_POST['data'],$checker,$files,$tblname,$form1);

	$this->cache->delete('cities_result_ro');
	$this->cache->delete('cities_result_ru');
}
standart_form_script($tblname);
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

	<?=$err?>

	<form name="form1" method="POST" action="<?=$e_path?>" enctype="multipart/form-data">
		<div class="portlet box">
			<div class="portlet-title">
				<div class="caption" style="color:#888;font-size:26px;">
					<?=$head1?>
				</div>
				<div class="tools" style="display:none;">
					<a href="javascript:;" class="expand"></a>
				</div>
				<div class="actions">
					<a class="btn default yellow-stripe">
					<i class="fa fa-plus"></i>
					<span class="hidden-480">
					Добавить <?=$addnew?></span>
					</a>
				</div>
			</div>
			<div class="portlet-body" style="<?if (empty($err)) echo 'display:none;';?>">
                <div class="tabbable tabbable-custom">
                    <ul class="nav nav-tabs">
                        <li class="active">
                            <a href="#tab_1_1" data-toggle="tab">Общая информация</a>
                        </li>
                        <li class="">
                            <a href="#tab_1_2" data-toggle="tab">Тайм-слоты</a>
                        </li>
                    </ul>
                    <div class="tab-content">
                        <div class="tab-pane active" id="tab_1_1">
				            <div class="">
                                <table class="table table-striped table-bordered table-hover">
                                    <?
                                    create_form_by_array($form1,@$_POST['data']);
                                    ?>
                                    <tr>
                                        <td>Кооринаты</td>
                                        <td>
                                            <script src="https://maps.googleapis.com/maps/api/js?key=<?=GOOGLE_MAPS_API?>&callback=initMap"></script>
                                            <div id="map" style="width:100%;height:400px;"></div>
                                            <script>

                                                var marker;

                                                function initMap(task) {
                                                    var map = new google.maps.Map(document.getElementById('map'), {
                                                        center: {lat: 47.0055600, lng: 28.8575000},
                                                        zoom: 14,
                                                    });

                                                    marker = new google.maps.Marker();

                                                    map.addListener('click', function (e, latLng) {
                                                        marker.setMap(null);
                                                        placeMarkerAndPanTo(e.latLng, map);
                                                    });

                                                    if (task == 'resize') {
                                                        var center = map.getCenter();
                                                        google.maps.event.trigger(map, "resize");
                                                        map.setCenter(center);
                                                    }
                                                }

                                                function placeMarkerAndPanTo(latLng, map) {
                                                    marker = new google.maps.Marker({
                                                        position: latLng,
                                                        map: map
                                                    });
                                                    let point = marker.getPosition();
                                                    $('#lat').val(point.lat());
                                                    $('#lng').val(point.lng());
                                                    map.panTo(latLng);
                                                }

                                                $(function () {
                                                    $('.portlet-title').click(function () {
                                                        initMap('resize');
                                                    })
                                                })
                                            </script>
                                            <input type="hidden" name="data[coords]" id="coordval">
                                        </td>
                                    <tr>
                                        <td>&nbsp;</td>
                                        <td><button type="submit" class="btn green"><i class="fa fa-check"></i> Добавить</button></td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                        <div class="tab-pane" id="tab_1_2">

                        </div>
                </div>
			</div>
		</div>
	</form><br />

    <form method="POST" class="chosehold">
        <a href="?store=true" class="btn green"><i class="fa fa-check"></i> города в котором есть магазин</a>
        <button type="button" class="btn red" onclick="window.location=window.location.pathname;"><i class="fa fa-check"></i> Очистить</button>
    </form>
    <br>

    <?
        $get = $this->input->get();

        if(isset($get['page'])) {
            $page = $get['page'];
            unset($get['page']);
        } else {
            $page = 1;
        }

        if(isset($_GET['store'])) {
            $this->db->join("store", "store.city_id = city.id");
            $this->db->group_by('city.id');
        }
        $total_count = $this->db->get($tblname)->num_rows();
        $limit = 100;
        $offset = ($page - 1) * $limit;
        $pages_count = ceil($total_count / $limit);


        $this->db->select("city.*");
	    if(isset($_GET['store'])) {
	        $this->db->join("store", "store.city_id = city.id");
	        $this->db->group_by('city.id');
        }
        $cities = $this->db->order_by('city.Sorder ASC, city.ID DESC')->limit($limit, $offset)->get($tblname)->result_array();
    ?>
	<? if(!empty($cities)) { ?>
		<div class="">
		    <table class="table table-striped table-bordered table-hover dataTable no-footer dragger">
		        <tr class="heading nodrop nodrag">
                    <th>Сортировка</th>
                    <th>Название</th>
                    <th width="250">Действия</th>
		        </tr>

		        <? foreach($cities as $city) { ?>
			        <tr>
				        <td width="40" celpadding="5" cellspacing="10" oid="<?=$city['id']?>" align="center" class="sorthold">
					        <?=$city['sorder'];?>
				        </td>
				        <td>
                            <a href="<?=$e_path.$city['id']?>"><?=$city['name_ru']?></a>
                        </td>
				        <td align="center">
					        <a href="<?=$e_path.$city['id']?>" class="btn blue"><i class="fa fa-pencil"></i> Редактировать</a>
					        <a href="<?=$delpath.$city['id']?>" class="btn red"><i class="fa fa-trash"></i> </a>
				        </td>
			        </tr>
		        <?}?>
		    </table>
		</div>
        <? if(isset($pages_count) && $pages_count > 1) {?>
            <div id="pagination"></div>
        <?}?>
	<? } ?>
    <script src="/dist/js/admin/jquery.simplePagination.js"></script>
    <script>
        $(function() {
            <?php $get_params = $get; unset($get_params['page']); ?>
            let get_params = <?= json_encode($get_params) ?>;
            let str = "?";
            $.each(get_params, function(i, v) {
                if (str !== "?") {
                    str += "&";
                }
                str += i + "=" + encodeURIComponent(v);
            });

            if (str !== "?") {
                str += "&page=";
            } else {
                str += "page=";
            }
            $('#pagination').pagination({
                items: <?= $total_count ?>,
                itemsOnPage: <?= $limit ?>,
                currentPage: <?= $page ?>,
                cssStyle: 'light-theme',
                hrefTextPrefix: str,
                nextText: '<i class="fa fa-angle-right"></i>',
                prevText: '<i class="fa fa-angle-left"></i>',
                listStyle: 'pagination pagination-sm',
                onPageClick: function(pageNumber, event) {
                    if(typeof event === "undefined") {
                        // $('#pagination').pagination('selectPage', pageNumber);
                    }
                },
            });
        });
    </script>
<? } else { ?>
	<?php $query=$this->db->where('id',$uri3)->get($tblname); ?>
	<?php $data=$query->row_array(); ?>
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

	<form name="form1" method="POST" action="<?=$e_path.$uri3;?>/" enctype="multipart/form-data">
        <div class="tabbable tabbable-custom">
            <ul class="nav nav-tabs">
                <li class="active">
                    <a href="#tab_1_1" data-toggle="tab">Общая информация</a>
                </li>
                <li class="">
                    <a href="#tab_1_2" data-toggle="tab">Тайм-слоты</a>
                </li>
            </ul>
            <div class="tab-content">
                <div class="tab-pane active" id="tab_1_1">
		            <div class="">
                        <table class="table table-striped table-bordered table-hover">
                            <?
                            create_form_by_array($form1,$data);
                            ?>
                            <tr>
                                <td>Кооринаты</td>
                                <td>
                                    <?
                                    $crd='47.0183674,28.8516902';
                                    if (!empty($data['coords'])) $crd=$data['coords'];
                                    $ca=explode(',',$crd);
                                    ?>
                                    <div id="map" style="width:100%;height:400px;"></div>
                                    <script>
                                        //var map;
                                        var marker;

                                        function initMap() {
                                            var uluru = {lat: <?=$ca[0]?>, lng: <?=$ca[1]?>};
                                            var map = new google.maps.Map(document.getElementById('map'), {
                                                zoom: 14,
                                                center: uluru
                                            });
                                            marker = new google.maps.Marker({
                                                position: uluru,
                                                map: map
                                            });

                                            map.addListener('click', function(e) {
                                                placeMarker(e.latLng, map);
                                                coords=e.latLng.toString().replace('(','').replace(')','').replace(' ','');
                                                $('#coordval').val(coords);
                                            });

                                        }

                                        function placeMarker(position, map) {
                                            if (marker == null) {
                                                marker = new google.maps.Marker({
                                                    position: position,
                                                    map: map
                                                });
                                            } else {
                                                marker.setPosition(position);
                                            }
                                            map.panTo(position);
                                        }

                                    </script>
                                    <script src="https://maps.googleapis.com/maps/api/js?key=<?=GOOGLE_MAPS_API?>&callback=initMap"></script>
                                    <input type="hidden" name="data[coords]" id="coordval" value="<?=$data['coords']?>">
                                </td>
                            </tr>
                            <tr>
                                <td>&nbsp;</td>
                                <td><button type="submit" class="btn green"><i class="fa fa-check"></i> Обновить</button></td>
                            </tr>
                        </table>
		            </div>
                </div>
                <div class="tab-pane" id="tab_1_2">
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
                                        <a href="#slot_1_<?=$key?>" onclick="demo(<?=$key?>);" data-toggle="tab"><?=$week['name']?> <?=$date?></a>
                                    </li>
                                <?}?>
                            </ul>
                            <div class="tab-content">
                                <? foreach($weeks as $key => $week){?>
                                    <div class="tab-pane <?=($key == $_SESSION['slot_tab']) ? 'active' : ''?>" id="slot_1_<?=$key?>">
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
                </div>
            </div>
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
