<?
$uri3=$this->uri->segment(3);

$head1='О нас - изображения';
$headerloc='about_images';

$e_path='/'.ADM_CONTROLLER.'/'.$headerloc.'/';

if ($_SERVER['REQUEST_METHOD']=='POST') {

	$number_of_files_uploaded = count($_FILES['gallery']['name']);
	for ($i = 0; $i < $number_of_files_uploaded; $i++) {
		$_FILES['gallery_one']['name']     = $_FILES['gallery']['name'][$i];
		$_FILES['gallery_one']['type']     = $_FILES['gallery']['type'][$i];
		$_FILES['gallery_one']['tmp_name'] = $_FILES['gallery']['tmp_name'][$i];
		$_FILES['gallery_one']['error']    = $_FILES['gallery']['error'][$i];
		$_FILES['gallery_one']['size']     = $_FILES['gallery']['size'][$i];

		if ($this->upload->do_upload('gallery_one')) {
			$data=$this->upload->data();
			$this->db->insert('about_images',array('sorder'=>1,'image'=>$data['file_name']));
		}
	}

	if (!empty($_POST['sorder'])) {
		foreach($_POST['sorder'] as $key=>$val) {
			$this->db->where('ID',$key)->update('about_images',array('sorder'=>$val));
		}
	}
}
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
        </li>
    </ul>
</div>

<form name="form1" method="POST" action="<?=$e_path.$uri3;?>/" enctype="multipart/form-data">
    <div class="tabbable tabbable-custom">
        <ul class="nav nav-tabs">
            <li class="active">
                <a href="#tab_1_2" data-toggle="tab">Галерея</a>
            </li>
        </ul>
        <div class="tab-content">
            <div class="tab-pane active" id="tab_1_2">
                <div class="">
                    <table class="table table-striped table-bordered table-hover">
                        <?
                        $imgs=$this->db->order_by('sorder asc,ID desc')->get('about_images')->result_array();
                        foreach($imgs as $img) {
                            echo '<tr id="img_'.$img['ID'].'">';
                                echo "<td width=\"100\"><input class=\"form-control\" type=\"text\" name=\"sorder[".$img['ID']."]\" value=\"".intval(@$img['sorder'])."\"  style=\"height:24px;text-align:center;\"></td>";
                                echo '<td><img src="/public/about_images/'.$img['image'].'" style="max-width:300px;max-height:200px;"></td>';
                                echo '<td width="150">';
                                    echo '<a href="javascript:void(0);" onclick="delAboutImg('.$img['ID'].');" class="btn red"><i class="fa fa-trash"></i> </a>';
                                echo '</td>';
                            echo '</tr>';
                        }
                        ?>
                        <tr>

                        </tr>
                        <tr>
                            <td colspan="2"><input type="file" multiple="multiple" name="gallery[]"></td>
                            <td><button type="submit" class="btn green"><i class="fa fa-check"></i> Обновить</button></td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
    </div>
</form><br />
<script>
function delAboutImg(id) {
    $('#img_'+id).remove();
    $.get('/<?=ADM_CONTROLLER?>/del_about_images/'+id+'/');
}
</script>
