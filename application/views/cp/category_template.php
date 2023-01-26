<?php
    $headerloc='category';
    $e_path='/'.ADM_CONTROLLER.'/'.$headerloc.'/';
?>
<?php if(isset($subcategories) && count($subcategories)) { ?>
    <?php foreach($subcategories as $sub) { ?>
        <tr>
            <td oid="<?=$sub['id']?>" class="sorthold">
                <input type="text" class="form-control changesord" cid="<?=$sub['id']?>" value="<?=$sub['sorder']?>" name="sord[<?=$sub['id']?>]">
            </td>
            <td>
                <?php $px = $sub['level'] * 20; ?>
                <?php $px2 = 24 - ($sub['level'] * 3); ?>
                <a style="margin-left: <?=$px?>px; font-size: <?=$px2?>px; text-decoration: none" onclick="toggleCat(<?=$sub['id']?>,this);" href="javascript:void(0);">
                    <?=$sub['name']?>
                </a>
            </td>
            <td>
                <div class="flex_checkbox">
                    <?php if (!empty($sub['is_shown'])) $mod=' checked'; else $mod='';?>
                    <label><input <?=$mod?> type="checkbox" oid="<?=$sub['id']?>" class="changer2 form-control" value="1"> Отображать</label>

                    <?php if (!empty($sub['show_popup_18'])) $mod=' checked'; else $mod='';?>
                    <label><input <?=$mod?> type="checkbox" oid="<?=$sub['id']?>" class="changer4 form-control" value="1"> 18+</label>

                    <?php if (!empty($sub['is_new'])) $mod=' checked'; else $mod='';?>
                    <label><input <?=$mod?> type="checkbox" oid="<?=$sub['id']?>" class="changer7 form-control" value="1">new</label>

                    <?php if($sub['count_child'] == 0) {?>
                        <?php if (!empty($sub['is_russian_size'])) $mod=' checked'; else $mod='';?>
                       <label><input <?=$mod?> type="checkbox" oid="<?=$sub['id']?>" class="changer5 form-control" value="1">Размер</label>
                    <?php }?>
                </div>
            </td>
            <td>
                <a href="<?=$e_path.$sub['id']?>/" class="btn blue"><i class="fa fa-pencil"></i> Редактировать</a>
                <a href="<?=$e_path.'delete/'.$sub['id']?>/" class="btn red category_remove"><i class="fa fa-trash"></i> </a>
            </td>
        </tr>
    <?php } ?>
<?php } ?>
