<?php
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id = $this->input->post('id', true);
    $id = intval($id);
    if (!empty($_FILES['data']['tmp_name'])) {
        $this->upload->do_upload('data');
        $resarr = $this->upload->data();
        $file = $resarr['file_name'];

        if (strtolower($resarr['file_ext']) == '.jpg' || strtolower($resarr['file_ext']) == '.jpeg' || strtolower($resarr['file_ext']) == '.pdf' || strtolower($resarr['file_ext']) == '.png') {
            $this->db->where('id', $id)->update('header_options', array('data' => $file));
        }
    }

    $this->db->where('id', 2)->update('header_options', array('data' => $this->input->post('colorPicker', true)));
}
$options = $this->db->get('header_options')->result();
?>
<?php if (!empty($options)): ?>
    <div class="row">
        <div class="col-xs-12">
            <div class="portlet box blue-hoki">
                <div class="portlet-title">
                    <div class="caption">
                        <i class="fa fa-gift"></i>Логотип
                    </div>
                </div>
                <div class="portlet-body">
                    <form method="post" enctype="multipart/form-data">
                        <input type="hidden" name="id" value="1">
                        <div class="">
                            <table class="table table-striped table-bordered table-hover">
                                <tbody>
                                <tr>
                                    <td width="200">Файл</td>
                                    <td><input type="file" name="data" class="form-control read-file"></td>
                                </tr>
                                <tr>
                                    <td width="200">Превью</td>
                                    <td>
                                        <img src="<?= newthumbs($options[0]->data, 'header_options', 0, 0, '0x0x0', 0) ?>" alt="" class="img-responsive">
                                    </td>
                                </tr>
                                <tr>
                                    <td width="200">&nbsp;</td>
                                    <td>
                                        <button type="submit" class="btn green"><i class="fa fa-check"></i> Обновить</button>
                                    </td>
                                </tr>
                                </tbody>
                            </table>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <br>
    <br>
    <div class="row">
        <div class="col-xs-12">
            <div class="portlet box blue-hoki">
                <div class="portlet-title">
                    <div class="caption">
                        <i class="fa fa-gift"></i>Основной цвет
                    </div>
                </div>
                <div class="portlet-body">
                    <form method="post" enctype="multipart/form-data">
                        <input type="hidden" name="id" value="2">
                        <div class="">
                            <table class="table table-striped table-bordered table-hover">
                                <tbody>
                                <tr>
                                    <td width="200">цвет</td>
                                    <td><input type="text" name="colorPicker" value="<?=$options[1]->data?>" class="form-control colorPicker"></td>
                                </tr>
                                <tr>
                                    <td width="200">&nbsp;</td>
                                    <td>
                                        <button type="submit" class="btn green"><i class="fa fa-check"></i> Обновить</button>
                                    </td>
                                </tr>
                                </tbody>
                            </table>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <br>
    <br>
<?php endif; ?>

<script src="/assets/js/colorpicker.js"></script>
<script>
    $(document).ready(function() {
        $('.colorPicker').ColorPicker({
            onSubmit: function(hsb, hex, rgb, el) {
                $(el).val(hex);
                $(el).ColorPickerHide();
            },
            onBeforeShow: function () {
                $(this).ColorPickerSetColor(this.value);
            }
        })
            .bind('keyup', function(){
                $(this).ColorPickerSetColor(this.value);
            });
    });
</script>

<script>
    jQuery(document).ready(function ($) {
        function readURL(input) {
            if (input.files && input.files[0]) {
                var reader = new FileReader();
                reader.onload = function (e) {
                    $(input).closest('tbody').find('.img-responsive').attr('src', e.target.result);
                }
                reader.readAsDataURL(input.files[0]);
            }
        }

        $(".read-file").change(function () {
            readURL(this);
        });
    });
</script>
