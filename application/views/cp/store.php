<?php
$uri3 = $this->uri->segment(3);

$head1 = 'Магазины';
$head2 = 'Редактирование магазина';
$head3 = 'name_ru';
$addnew = 'магазин';
$tblname = 'store';
$headerloc = 'store';

$photoTable = 'store_images';
$photoDel='/'.ADM_CONTROLLER.'/delStoreImg/';
$photoSort='/'.ADM_CONTROLLER.'/updStoreImgSort/';

$e_path = '/' . ADM_CONTROLLER . '/' . $headerloc . '/';
$delpath = '/' . ADM_CONTROLLER . '/delete_row/' . $tblname . '/';
$err = '';

$form = array();
$form[] = admin_form_select('city_id', 'Город', 'city', 'name_ru');
$form[] = admin_form_text(true, 'name', 'Заголовок');
$form[] = admin_form_text(true, 'address', 'Адрес');
$form[] = admin_form_textarea(true, 'workhours', 'Часы работы');
$form[] = admin_form_textarea(true, 'troleibus', 'Троллейбусы');
$form[] = admin_form_textarea(true, 'parking', 'Парковка');
$form[] = admin_form_text(false, 'phone', 'Телефон');

$form1 = convert_form($form);

$checker = array('name_ru');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
	$err = save_data($_POST['data'], $checker, [], $tblname, $form1);

    if (!empty($_FILES['media']['name'][0])) {
        $files = $_FILES['media'];

        $this->load->library('upload');
        $config['upload_path'] = realpath('public/store');
        $config['allowed_types'] = 'jpg|jpeg|gif|png';
        $config['encrypt_name'] = true;
        $this->upload->initialize($config);

        $cpt = count($_FILES['media']['name']);
        for ($i = 0; $i < $cpt; $i++) {
            $_FILES['media']['name'] = $files['name'][$i];
            $_FILES['media']['type'] = $files['type'][$i];
            $_FILES['media']['tmp_name'] = $files['tmp_name'][$i];
            $_FILES['media']['error'] = $files['error'][$i];
            $_FILES['media']['size'] = $files['size'][$i];

            $this->upload->do_upload('media');
            $file_data = $this->upload->data();
            $file = $file_data['file_name'];

            if (strtolower($file_data['file_ext']) == '.jpg' || strtolower($file_data['file_ext']) == '.jpeg' || strtolower($file_data['file_ext']) == '.gif' || strtolower($file_data['file_ext']) == '.png') {
                $this->db->insert($photoTable, ['store_id' => $uri3, 'img' => $file]);
            }
        }
    }
}
standart_form_script($tblname);
?>

<?php if (empty($uri3)) { ?>
    <script src="https://maps.googleapis.com/maps/api/js?key=<?=GOOGLE_MAPS_API?>"></script>
    <script>
        $(document).ready(function () {
            $('.actions').click(function () {
                initMap();
            });
        });
    </script>
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

	<?= $err ?>

    <form name="form1" method="POST" action="<?= $e_path ?>" enctype="multipart/form-data">
        <div class="portlet box">
            <div class="portlet-title">
                <div class="caption" style="color:#888;font-size:26px;">
					<?= $head1 ?>
                </div>
                <div class="tools" style="display:none;">
                    <a href="javascript:;" class="expand"></a>
                </div>
                <div class="actions">
                    <a class="btn default yellow-stripe">
                        <i class="fa fa-plus"></i>
                        <span class="hidden-480">
					Добавить <?= $addnew ?></span>
                    </a>
                </div>
            </div>
            <div class="portlet-body" style="<?php if (empty($err)) echo 'display:none;'; ?>">
                <div class="">
                    <table class="table table-striped table-bordered table-hover">
                        <?php
						create_form_by_array($form1, @$_POST['data']);
						?>
                        <tr>
                            <td>Кооринаты</td>
                            <td>

                                <div id="map" style="width:100%;height:400px;"></div>
                                <script>
                                    //var map;
                                    var marker;

                                    function initMap() {
                                        var uluru = {lat: 47.0183674, lng: 28.8516902};
                                        var map = new google.maps.Map(document.getElementById('map'), {
                                            zoom: 12,
                                            center: uluru
                                        });
                                        /*var marker = new google.maps.Marker({
                                          position: uluru,
                                          map: map
                                        });*/

                                        map.addListener('click', function (e) {
                                            placeMarker(e.latLng, map);
                                            coords = e.latLng.toString().replace('(', '').replace(')', '').replace(' ', '');
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


                                <input type="hidden" name="data[coords]" id="coordval">
                            </td>
                        </tr>
                        <tr>
                            <td>&nbsp;</td>
                            <td>
                                <button type="submit" class="btn green"><i class="fa fa-check"></i> Добавить</button>
                            </td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
    </form><br/>
    <?php
	$city = $this->db->order_by('id asc')->get('city')->result_array();
	foreach ($city as $row) {
		$checkb = $this->db->where('city_id', $row['id'])->order_by('Sorder ASC, ID DESC')->get($tblname)->result_array();
		if (!empty($checkb)) {
		    echo '<h1>' . $row['name_ru'] . '</h1>';
			echo '<div class="">
			<table class="table table-striped table-bordered table-hover dataTable no-footer dragger">';
			echo '<tr class="heading nodrop nodrag">
				<th>Сортировка</th>
				<th>Название</th>
				<th width="250">Действия</th>
			</tr>';

			foreach ($checkb as $barr) {
				echo "<tr>";
				echo "<td width=\"40\" celpadding=\"5\" cellspacing=\"10\" oid=\"" . $barr['id'] . "\" align=\"center\" class=\"sorthold\">";
				echo $barr['sorder'];
				echo "</td>";
				echo "<td><a href=\"$e_path" . $barr['id'] . "/\">" . $barr['name_ru'] . "</a>";
				echo "</td>";
				echo "<td align=\"center\">";
				echo '<a href="' . $e_path . $barr['id'] . '/" class="btn blue"><i class="fa fa-pencil"></i> Редактировать</a>';
				echo '<a href="' . $delpath . $barr['id'] . '/" class="btn red"><i class="fa fa-trash"></i> </a>';
				echo "</td>";
				echo "</tr>";
			}
			echo "</table>";
			echo '</div>';
		}
	}
} else {
    $data = $this->db->where('id', $uri3)->get($tblname)->row_array();
    $photos = $this->db->where("store_id", $uri3)->order_by("sorder asc, id desc")->get($photoTable)->result_array();
	?>
    <div class="page-bar">
        <ul class="page-breadcrumb">
            <li>
                <i class="fa fa-home"></i>
                <a href="/<?= ADM_CONTROLLER ?>/menu/">Главная</a>
                <i class="fa fa-angle-right"></i>
            </li>
            <li>
                <a href="/<?= ADM_CONTROLLER ?>/<?= $headerloc ?>/"><?= $head1 ?></a>
                <i class="fa fa-angle-right"></i>
            </li>
            <li>
                <a>Редактирование "<?= $data[$head3] ?>"</a>
            </li>
        </ul>
    </div>
	<?= $err ?>

    <form name="form1" method="POST" action="<?= $e_path . $uri3; ?>/" enctype="multipart/form-data">
        <div class="">
            <table class="table table-striped table-bordered table-hover">
                <?php
				create_form_by_array($form1, $data);
				?>
                <tr>
                    <td>Фотографии</td>
                    <td>
                        <input type="file"
                               multiple
                               name="media[]" id="file"
                               class="form-control">
                    </td>
                </tr>
                <tr>
                    <td>Кооринаты</td>
                    <td>
                        <?php
						$crd = '47.0183674,28.8516902';
						if (!empty($data['coords'])) $crd = $data['coords'];
						$ca = explode(',', $crd);
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

                                map.addListener('click', function (e) {
                                    placeMarker(e.latLng, map);
                                    coords = e.latLng.toString().replace('(', '').replace(')', '').replace(' ', '');
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


                        <input type="hidden" name="data[coords]" id="coordval" value="<?= $data['coords'] ?>">
                    </td>
                </tr>
                <tr>
                    <td>&nbsp;</td>
                    <td>
                        <button type="submit" class="btn green"><i class="fa fa-check"></i> Обновить</button>
                    </td>
                </tr>
            </table>
        </div>
    </form>
    <br/>
    <form name="form1" method="POST" action="<?=$photoSort.$data['id']?>/" enctype="multipart/form-data">
        <div class="table-scrollable">
            <table class="table table-striped table-bordered table-hover tablist">
                <?php foreach($photos as $photo) { ?>
                    <tr>
                        <td width="70">
                            <input type="text" name="so[<?=$photo['id']?>]" value="<?=$photo['sorder']?>" class="form-control" style="height:24px;text-align:center;">
                        </td>
                        <td>
                            <img width="128" src="<?=newthumbs($photo['img'], 'store', 196, 128, '196x128x1', 1)?>">
                        </td>
                        <td width="20" align="center">
                            <a class="btn red btn-editable" href="<?=$photoDel.$photo['id']?>/<?=$data['id']?>/">
                                <span class="fa fa-trash"></span>
                            </a>
                        </td>
                    </tr>
                <?php }?>
            </table>
        </div>
        <?php if ($photos) {?>
            <button type="submit" class="btn green"><i class="fa fa-check"></i> Обновить</button>
        <?php }?>
    </form>
<?php }?>
<script>
    $(document).ready(function() {
        $('select[name="data[city_id]"]').select2();
    });
</script>
<link href="/theme/assets/global/plugins/select2/select2.css" rel="stylesheet" type="text/css"/>
<script type="text/javascript" src="/theme/assets/global/plugins/select2/select2.min.js"></script>
