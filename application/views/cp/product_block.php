<?php
$uri3=$this->uri->segment(3);

$head1='Блоки текстовых разделов';
$head2='Редактирование блока';
$head3='header_ru';
$addnew='блок';
$tblname='product_block';
$headerloc='product_block';

$e_path='/'.ADM_CONTROLLER.'/'.$headerloc.'/';
$delpath='/'.ADM_CONTROLLER.'/delete_row/'.$tblname.'/';
$delallpath='/'.ADM_CONTROLLER.'/delete_all/'.$tblname.'/';
$delreturnpath='/'.ADM_CONTROLLER.'/delete_row_return/'.$tblname.'/';

$form=array();
$form[]=admin_form_text(true,'header','Название');
$form[]=admin_form_textarea(true,'announce','Краткое описание');
$form[]=admin_form_textarea(true,'text','Текст','ckeditor');
$form[]=admin_form_file(false,'image','Изображение (235px x 200px)',$tblname);

$form1=convert_form($form);

$files=array('image');
$checker=array('header_ru');

standart_form_script($tblname);
?>

<?php if (empty($uri3)) {?>
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

    <form class="form-inline" name="form1" method="POST" action="<?=$e_path?>" enctype="multipart/form-data">
        <div class="portlet box">
            <div class="portlet-title">
                <div  style="color:#888;font-size:26px;">
                    <?=$head1?>
                </div>
            </div>
            <div class="portlet-body" >
                <div class="" style="padding:10px 15px;">
                    <div>
                        <input type="text" style="width:25% !important;" class="form-control" name="search_barcode" id="search_barcode" placeholder="Код товара" />
                        <button class="btn btn-success addProduct">Выбрать</button>
                    </div>
                </div>
            </div>
        </div>
    </form>


    <form class="form" name="form1" id="add-form" style="display:none;" method="POST" enctype="multipart/form-data">
        <div class="portlet box">
            <div class="portlet-body">
                <div class="" >
                    <table class="table table-striped table-bordered table-hover">
                        <tbody>
                            <tr>
                                <td width="200">Название RU *</td>
                                <td>
                                    <input type="text" name="name_ru[]" class="form-control" value="" />
                                </td>
                            </tr>
                            <tr>
                                <td width="200">Название RO *</td>
                                <td>
                                    <input type="text" name="name_ro[]" class="form-control" value="" />
                                </td>
                            </tr>
                            <tr>
                                <td width="200">Текст RU *</td>
                                <td>
                                    <textarea class="ckeditor" name="text_ru_0[]" class="form-control" required></textarea>
                                </td>
                            </tr>
                            <tr>
                                <td width="200">Текст RO *</td>
                                <td>
                                    <textarea class="ckeditor" name="text_ro_0[]" class="form-control" required></textarea>
                                </td>
                            </tr>
                            <tr>
                                <td width="200">Изображение </td>
                                <td>
                                    <input type="hidden" value="" name="coordinates[]" class="coordinates">
                                    <input type="file" name="img[]" class="form-control"/>
                                </td>
                            </tr>
                            <tr>
                                <td width="200">Положение картинки </td>
                                <td class="flex_td">
                                    <select name="position[]" class="form-control position" style="width:auto;">
                                        <option value="5" data-size="" >Просто текст</option>
                                        <option value="0" data-size="590x370px">Картинка слева</option>
                                        <option value="1" data-size="590x370px">Картинка справа</option>
                                        <option value="2" data-size="1262x440px" >Текст над Картинкой</option>
                                        <option value="3" data-size="100x100px" >Текст с иконкой</option>
                                        <option value="4" data-size="" >Видео</option>
                                    </select>
                                    <span class="display_size"></span>
                                    <input type="hidden" id="block_id" name="block_id[]" value="0"/>
                                </td>
                            </tr>
                            <tr>
                                <td width="200"></td>
                                <td>
                                    <button type="submit" class="btn btn-success" >Добавить</button>
                                    <input type="hidden" id="product_id" name="product_id"/>
                                    <span class="pull-right">
                                        <button class="btn btn-success addProductBlock"><i class="fa fa-plus"></i></button>
                                    </span>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </form>
    <br />
<?php } else { ?>
    <div class="portlet box">
        <div class="portlet-title">
            <div  style="color:#888;font-size:26px;">
                <?=$head2?>
            </div>
        </div>
        <div class="portlet-body" >
        </div>
    </div>

    <div class="page-bar">
        <ul class="page-breadcrumb">
            <li>
                <i class="fa fa-home"></i>
                <a href="/<?=ADM_CONTROLLER?>/menu/">Главная</a>
                <i class="fa fa-angle-right"></i>
            </li>
            <li>
                <a href="/<?=ADM_CONTROLLER?>/product_block">Блоки товаров</a>
                <i class="fa fa-angle-right"></i>
            </li>
            <li>
                <a><?=$head2?></a>
            </li>
        </ul>
    </div>

    <?php $blocks = $this->db->where('product_id', $uri3)->get('product_block')->result_array();?>

    <form class="form" name="form1" id="add-form"  method="POST" enctype="multipart/form-data">
        <div class="portlet box">
            <div class="portlet-body">
                <div class="" >
                    <table class="table table-striped table-bordered table-hover">
                        <tbody>
                            <?php $i = 0; ?>
                            <?php foreach($blocks as $block ):?>
                                <?php if($i != 0):?>
                                    <tr class="block_<?=$block['id']?>">
                                        <td>&nbsp;</td>
                                        <td>&nbsp;</td>
                                    </tr>
                                <?php endif;?>
                                <tr class="block_<?=$block['id']?>">
                                    <td width="200">Название RU *</td>
                                    <td>
                                        <input type="text" name="name_ru[]" class="form-control" value="<?=$block['name_ru']?>" />
                                    </td>
                                </tr>
                                <tr class="block_<?=$block['id']?>">
                                    <td width="200">Название RO *</td>
                                    <td>
                                        <input type="text" name="name_ro[]" class="form-control" value="<?=$block['name_ro']?>" />
                                    </td>
                                </tr>
                                <tr class="block_<?=$block['id']?>">
                                    <td width="200">Текст RU *</td>
                                    <td>
                                        <textarea class="ckeditor" name="text_ru_0[]" class="form-control" required><?=$block['text_ru']?></textarea>
                                    </td>
                                </tr>
                                <tr class="block_<?=$block['id']?>">
                                    <td width="200">Текст RO *</td>
                                    <td>
                                        <textarea class="ckeditor" name="text_ro_0[]" class="form-control" required><?=$block['text_ro']?></textarea>
                                    </td>
                                </tr>
                                <?php $display=($block['position']==2) ? 'table-row' : 'none'; ?>
                                <tr class="block_<?=$block['id']?>" style="display:<?=$display?>">
                                    <td width="200">выберите цвет и нажмите на изображение, чтобы выбрать положение текста </td>
                                    <td><input type="text" name="color[]" class="form-control minicolors minicolors-input" value="<?=$block['color']?>" /></td>
                                </tr>
                                <tr class="block_<?=$block['id']?>" style="display:<?=$display?>">
                                    <td width="200">фон (цвет)</td>
                                    <td><input type="text" name="background[]" class="form-control minicolors minicolors-input" value="<?=$block['background']?>" /></td>
                                </tr>
                                <tr class="block_<?=$block['id']?>" style="display:<?=$display?>">
                                    <td width="200">прозрачность фона</td>
                                    <td><input type="text" name="opacity[]" class="form-control" value="<?=$block['opacity']?>" /></td>
                                </tr>
                                <tr class="block_<?=$block['id']?>">
                                    <td width="200">Изображение </td>
                                    <td>
                                        <input type="hidden" value="<?=$block['coordinates']?>" name="coordinates[]" class="coordinates">
                                        <input type="file" name="img[<?=$block['id']?>]" class="form-control"/>
                                        <?php if(!empty($block['img'])){?>
                                            <div>
                                                <?php if($block['position']==2) { ?>
                                                    <?php $src=newthumbs($block['img'], 'product_block', '1265', '440', '1265x440x0', 0)?>
                                                    <div id="pointmap" style="position:relative; height:440px; background:url(<?=$src?>) no-repeat center center;">
                                                        <?php $pizza = explode(',',$block['coordinates']);?>
                                                        <div class="image-macker" style="left:<?=$pizza[0]?>%; top:<?=$pizza[1]?>px;"></div>
                                                    </div>
                                                <?php } else { ?>
                                                    <img src="<?=newthumbs($block['img'], 'product_block', '150', '150', '150x150x1', 1)?>"/>
                                                <?php }?>
                                                <br />
                                                <br />
                                                <button class="btn red deleteBlockImage"  data-id="<?=$block['id']?>"><i class="fa fa-trash"></i></button>
                                            </div>
                                        <?php }?>
                                    </td>
                                </tr>
                                <tr class="block_<?=$block['id']?>">
                                    <td width="200">Положение картинки </td>
                                    <td class="flex_td">
                                        <select name="position[]" class="form-control position" style="width:auto;">
                                            <option <?=($block['position'] == 5) ? ' selected' : ''?> value="5" data-size="" >Просто текст</option>
                                            <option <?=($block['position'] == 0) ? ' selected' : ''?> value="0" data-size="590x370px">Картинка слева</option>
                                            <option <?=($block['position'] == 1) ? ' selected' : ''?> value="1" data-size="590x370px">Картинка справа</option>
                                            <option <?=($block['position'] == 2) ? ' selected' : ''?> value="2" data-size="1262x440px">Текст над Картинкой</option>
                                            <option <?=($block['position'] == 3) ? ' selected' : ''?> value="3" data-size="100x100px">Текст с иконкой</option>
                                            <option <?=($block['position'] == 4) ? ' selected' : ''?> value="4" data-size="">Видео</option>
                                        </select>
                                        <input type="hidden" id="block_id" name="block_id[]" value="<?=$block['id'];?>"/>
                                        <span class="display_size"></span>
                                    </td>
                                </tr>
                                <?php if($i != 0):?>
                                    <tr class="block_<?=$block['id']?>">
                                        <td>&nbsp;</td>
                                        <td>
                                            <span class="pull-right">
                                                <span class="pull-right">
                                                <button class="btn btn-danger deleteProductBlock" data-id="<?=$block['id']?>" id="block_number_<?=$block['id']?>">
                                                    <i class="fa fa-minus"></i>
                                                </button>
                                                </span>
                                            </span>
                                        </td>
                                    </tr>
                                <?php endif;?>
                                <?php $i++; ?>
                            <?php endforeach; ?>
                            <tr>
                                <td width="200"></td>
                                <td>
                                    <button type="submit" class="btn btn-success" >Изменить</button>
                                    <span class="pull-right">
                                        <button class="btn btn-success addProductBlock"><i class="fa fa-plus"></i></button>
                                    </span>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </form>
<?php } ?>
<?php if(empty($uri3)){?>
    <?php $product_blocks = $this->db->get("product_block")->result();?>
    <?php if(!empty($product_blocks)){?>
        <?php
            $ids = array_unique(array_map(function($item) {return $item->product_id;},$product_blocks));
            $products = $this->db->where_in("id", $ids)->get("product")->result();
        ?>
        <div class="" id="blocks_table">
            <table class="table table-striped table-bordered table-hover dataTable no-footer ">
                <thead>
                    <tr class="heading nodrop nodrag">
                        <th>Название</th>
                        <th width="250">Действия</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $categories = []; ?>
                    <?php foreach($products as $product){
                        $block_cat = $this->db->where("product_id", $product->id)->get("category_product")->result();
                        $i = 0;
                        $full_cat = "";
                        $cat_levels = [];
                        $cat_ids = [];
                        foreach($block_cat as $cat){
                            $cat_name = $this->db->where('id', $cat->category_id)->get('category')->row();
                            if(!empty($cat_name)){
                                if($i == 0){
                                    $full_cat .= $cat_name->name_ru;
                                } else {
                                    $full_cat .= ' / '.$cat_name->name_ru;
                                }
                            }
                            $i++;

                            $cat_levels[] = $cat_name->level;
                            $cat_ids[] = $cat->category_id;
                        }
                        $max_level = max($cat_levels);
                        $key = array_search($max_level, $cat_levels);

                        $block_cats[] = $cat_ids[$key];

                        if(!in_array($cat_ids[$key], $categories)){
                            $categories[$cat_ids[$key]]['blocks'][] = $product;
                            $categories[$cat_ids[$key]]['cat_name']= $full_cat;
                        } else {
                            $pos = array_search($cat_ids[$key]);
                            $categories[$pos][] = $product;
                        }

                    }?>

                    <?php foreach($categories as $category){ ?>
                        <tr><td colspan="3" class="text-center"><b><?= $category['cat_name']; ?></b></td></tr>
                        <?php foreach($category['blocks'] as $prod){?>
                            <tr>
                                <td>
                                    <a href="<?=$e_path?><?=$prod->id?>"><?=$prod->name_ru?></a>
                                </td>
                                <td align="center">
                                    <a href="<?=$e_path?><?=$prod->id?>" class="btn blue">
                                        <i class="fa fa-pencil"></i> Редактировать
                                    </a>
                                    <a href="<?=$delallpath?><?=$prod->id?>" data-id="<?php $prod->id;?>" class="btn red deleteBlocksRow">
                                        <i class="fa fa-trash"></i></a>
                                </td>
                            </tr>
                        <?php }?>
                    <?php }?>
                </tbody>
            </table>
        </div>
    <?php }?>
<?php }?>

<?php if(!empty($uri3)):?>
    <?php $blocks = $this->db->where('product_id', $uri3)->order_by('sorder ASC')->get('product_block')->result();?>
    <?php if(!empty($blocks)):?>
        <div class="" id="blocks_table">
            <table class="table table-striped table-bordered table-hover dataTable no-footer ">
                <tr class="heading nodrop nodrag">
                    <th>Сортировка</th>
                    <th>Название</th>
                </tr>
                <tbody>
                <?php
                $categories = [];
                foreach($blocks as $block){
                    $block_cat = $this->db->where("product_id", $block->product_id)->get("category_product")->result();
                    $i = 0;
                    $full_cat = "";
                    $cat_levels = [];
                    $cat_ids = [];
                    foreach($block_cat as $cat){
                        $cat_name = $this->db->where('id', $cat->category_id)->get('category')->row();
                        if(!empty($cat_name)){
                            if($i == 0){
                                $full_cat .= $cat_name->name_ru;
                            } else {
                                $full_cat .= ' / '.$cat_name->name_ru;
                            }
                        }
                        $i++;

                        $cat_levels[] = $cat_name->level;
                        $cat_ids[] = $cat->category_id;
                    }
                    $max_level = max($cat_levels);
                    $key = array_search($max_level, $cat_levels);

                    $block_cats[] = $cat_ids[$key];

                    if(!in_array($cat_ids[$key], $categories)){
                        $categories[$cat_ids[$key]]['blocks'][] = $block;
                        $categories[$cat_ids[$key]]['cat_name']= $full_cat;
                    } else {
                        $pos = array_search($cat_ids[$key]);
                        $categories[$pos][] = $block;
                    }

                }?>

                <?php foreach($categories as $category): ?>
                    <tr><td colspan="3" class="text-center"><b><?= $category['cat_name']; ?></b></td></tr>
                    <?php foreach($category['blocks'] as $block):?>
                        <tr>
                            <td width="40" celpadding="5" cellspacing="10" oid="" align="center" class="sorthold text-center">
                                <input type="text" name="sorder" data-id="<?=$block->id?>" class="form-control text-center sortblock" value="<?=$block->sorder;?>"/>
                            </td>
                            <td>
                                <?=$block->name_ru?>
                            </td>
                        </tr>
                    <?php endforeach;?>
                <?php endforeach;?>
                </tbody>
            </table>
        </div>
    <?php endif;?>
<?php endif;?>

<?php if(empty($uri3)):?>
    <script>
        $( "#search_barcode" ).autocomplete({
            source: function( request, response ) {
                $.ajax({
                    url: "/<?=ADM_CONTROLLER?>/search_by_sku/",
                    method:"POST",
                    data: {
                        term: request.term
                    },
                    success: function( data ) {
                        data = JSON.parse(data);
                        response(data);
                    },
                    error: function( err ){
                    }
                });
            },
            minLength: 3,
            select: function( event, ui ) {
                $( "#search_barcode" ).attr('data-id', ui.item['value']);
                $( "#search_barcode" ).val(ui.item['label']);
                return false;
            }
        }).data("uiAutocomplete")._renderItem =  function( ul, item ){
            var html = "<a><b>"+item['bar_code']+'</b> - '+item['label'] + "</a>";
            return $("<li></li>").data("item.autocomplete", item).append(html).appendTo(ul);
        };
    </script>
<?php endif;?>
<script>

    $(document).on('click', '.deleteBlockImage', function(e){
        e.preventDefault();
        var image = $(this);
        img_id = $(this).data('id');
        $.ajax({
            method:'POST',
            url:'/<?=ADM_CONTROLLER?>/delete_page_block_img',
            data: { 'id' : img_id },
            success: function(data){
                if(data == 0){
                    image.closest('div').remove();
                }
            },
            error: function(err){
            }
        });
    });

    $(document).on('click', '.deleteProductBlock', function(e){
        e.preventDefault();
        var button = $(this);
        var id = $(this).data('id');
        var oid = $(this).data('oid');

        if(confirm('Вы уверенны? ')){
            if(id){
                $.ajax({
                    method:'POST',
                    url:'/<?=ADM_CONTROLLER?>/delete_product_block',
                    data: { 'id' : id },
                    success: function(data){
                        if(data == 0){
                            $('.block_'+id).remove();
                        }
                    },
                    error: function(err){
                    }
                });
            } else {
                $('.new_'+oid).remove();
            }
        }
    });

    $(".addProduct").on('click', function(e){
        e.preventDefault();
        var product_id = $("#search_barcode").data('id');
        if(product_id != null && product_id != undefined){
            $("#add-form").show();
            $("#product_id").val(product_id);
            $.ajax({
                method:'POST',
                url:'/<?=ADM_CONTROLLER?>/get_products_blocks',
                data: { 'product_id':product_id },
                success: function(data){
                    data = JSON.parse(data);
                    var rows = '';
                    if(data){
                        data.forEach(function(block){
                            rows += '<tr><td width="40" celpadding="5" cellspacing="10" oid="" align="center" class="sorthold text-center"><input type="text" name="sorder" data-id="'+block.id+'" class="form-control text-center sortblock" value="'+block.sorder+'"/></td><td><a href="<?=$e_path?>'+block.id+'">'+block.name_ru+'</a></td><td align="center"><a href="<?=$e_path?>'+block.id+'" class="btn blue"><i class="fa fa-pencil"></i> Редактировать</a><a href="<?=$delpath?>'+block.id+'" data-id="'+block.id+'" class="btn red deleteBlocksRow"><i class="fa fa-trash"></i> </a></td></tr>';
                        });
                        $('#product_blocks').append(rows);
                        $('#blocks_table').show();
                    }
                },
                error: function(err){
                }
            });

        }
    });

    $(document).on('change', '.sortblock', function(e){
        var sorder = $(this).val();
        if(sorder){
            var id = $(this).data('id');
            $.ajax({
                method:'POST',
                url:'/<?=ADM_CONTROLLER?>/sorder_page_block/',
                data: { 'id' : id, 'value' : sorder },
                success: function(data){
                },
                error: function(err){
                }
            });
        }
    });

    $(document).on('click', '.addProductBlock', function(e){
        e.preventDefault();
        var last = $(this).closest('tr');
        var block_amt = ($('textarea').length / 2) + 1;
        var new_block =
            '<tr class="new_'+block_amt+'">' +
            '<td width="200">&nbsp;</td><td>&nbsp;</td></tr>'+
            '<tr  class="new_'+block_amt+'">'+
            '<td width="200">Название RU *</td>'+
            '<td> <input type="text" name="name_ru[]" class="form-control" value="" /> </td>'+
            '</tr>'+
            '<tr  class="new_'+block_amt+'">'+
            '<td width="200">Название RO *</td>'+
            '<td> <input type="text" name="name_ro[]" class="form-control" value="" /> </td>'+
            '</tr>'+
            '<tr  class="new_'+block_amt+'">'+
            '<td width="200">Текст RU *</td>'+
            '<td> <textarea class="ckeditor" name="text_ru_0[]" id="text_ru_'+block_amt+'" class="form-control" required></textarea> </td>'+
            '</tr>'+
            '<tr  class="new_'+block_amt+'">'+
            '<td width="200">Текст RO *</td>'+
            '<td> <textarea class="ckeditor" name="text_ro_0[]" id="text_ro_'+block_amt+'" class="form-control" required></textarea> </td>'+
            '</tr>'+
            '<tr class="new_'+block_amt+'">'+
            '<td width="200">Изображение </td>'+
            '<td>'+
            '<input type="hidden" value="" name="coordinates[]" classs="coordinates">'+
            '<input type="file" name="img[]" class="form-control"/>'+
            '</td>'+
            '</tr>'+
            '<tr  class="new_'+block_amt+'">'+
            '<td width="200">Положение картинки </td>'+
            '<td class="flex_td">'+
            '<select name="position[]" class="form-control position" style="width:auto;">'+
            '<option value="5" data-size="" >Просто текст</option>'+
            '<option value="0" data-size="590x370px">Картинка слева</option>'+
            '<option value="1" data-size="590x370px">Картинка справа</option>'+
            '<option value="2" data-size="1262x440px">Текст над Картинкой</option>'+
            '<option value="3" data-size="100x100px">Текст с иконкой</option>'+
            '<option value="4" data-size="">Видео</option>'+
            '</select>'+
            '<input type="hidden" value="0" id="block_id" name="block_id[]" />'+
            '<span class="display_size"></span>'+
            '</td>'+
            '</tr>'+
            '<tr  class="new_'+block_amt+'">'+
            '<td width="200"></td>'+
            '<td>'+
            '<span class="pull-right">'+
            '<button class="btn btn-danger deleteProductBlock" data-oid="'+block_amt+'" id="block_number_'+block_amt+'">'+
            '<i class="fa fa-minus"></i>'+
            '</button>'+
            '</span>'+
            '</td>'+
            '</tr>';
        last.before(new_block);
        CKEDITOR.replace('text_ru_'+block_amt);
        CKEDITOR.replace('text_ro_'+block_amt);
    });
    $(document).on('change', '.position', function() {
        var size = $(this).find('option:selected').data('size');
        $(this).closest('td').find('.display_size').empty().append(size);
    });

    $( window ).load(function() {
        $( ".position" ).each(function( index ) {
            var size = $(this).find('option:selected').data('size');
            $(this).closest('td').find('.display_size').empty().append(size);
        });
    });

    function readURL(input) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();

            reader.onload = function(e) {
                $('#blah').attr('src', e.target.result);
            }

            reader.readAsDataURL(input.files[0]);
        }
    }

    $("#imgInp").change(function() {
        readURL(this);
    });

    $(document).on('click', '#pointmap', function(e) {
        var offset = $(this).offset();
        var x = Math.floor((e.pageX - offset.left) / $(this).width() * 10000)/100;
        var y = parseInt(e.pageY - offset.top);
        var coordinates = x + ','+ y;
        $(this).closest('td').find('.coordinates').val(coordinates);
        $(this).closest('td').find('.image-macker').css({left: 'calc('+x+'% - 0px)', top: 'calc('+y+'px - 0px)'});
    });

</script>
<style>
    .flex_td {
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

</style>
<link href="/theme/assets/global/plugins/jquery-minicolors/jquery.minicolors.css" rel="stylesheet" type="text/css"/>
<script src="/theme/assets/global/plugins/jquery-minicolors/jquery.minicolors.min.js" type="text/javascript"></script>
<script>

    $('input.minicolors').minicolors({
        animationSpeed: 100,
        animationEasing: 'swing',
        change: null,
        changeDelay: 0,
        control: 'hue',
        defaultValue: '',
        hide: null,
        hideSpeed: 100,
        inline: false,
        letterCase: 'lowercase',
        opacity: false,
        position: 'default',
        show: null,
        showSpeed: 100,
        swatchPosition: 'left',
        textfield: true,
        theme: 'bootstrap'
    });
</script>
