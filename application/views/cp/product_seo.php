<?php
$uri3=$this->uri->segment(3);

$head1='Сео название для товаров';
$head2='Редактирование Сео название для товаров';
$tblname='product';
$headerloc='product_seo_check';

$e_path='/'.ADM_CONTROLLER.'/'.$headerloc.'/';

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

    <form class="form-inline" id="get_id" name="form1" method="POST" action="<?=$e_path?>" enctype="multipart/form-data">
        <div class="portlet box">
            <div class="portlet-title">
                <div  style="color:#888;font-size:26px;">
                    <?=$head1?>
                </div>
            </div>
            <div class="portlet-body" >
                <div class="" style="padding:10px 15px;">
                    <div>
                        <input type="text" style="width:50% !important;" class="form-control" name="identificator" id="identificator" placeholder="Код товара" />
                        <button name="check" class="btn btn-success addProduct">Выбрать</button>

                    </div>
                </div>
            </div>
        </div>
    </form>
<table class="table table-striped table-bordered table-hover">
    <thead>
    <tr>
        <td colspan="3" style="text-align:center;">Результаты поиска</td>
    </tr>
    <tr><td>ID</td><td>Имя товара</td><td>Изменить</td></tr>
    </thead>
    <tbody id="result-elements">
    <tr>
        <td colspan="3" style="text-align:center; color: red;">список пуст</td>
    </tr>
    </tbody>
</table>
    <script type="text/javascript">
        function show_elements(result) {
            $("#result-elements").html(' ');
            $.each(result, function (key, item) {
                $("#result-elements").append("<tr><td>" + item.id + "</td><td>" + item.name_ru + "</td><td><a href='/<?=ADM_CONTROLLER?>/product_seo/" + item.id + "/' class='btn blue'><i class=\"fa fa-pencil\"></i>Изменить</a></td></tr>");
            });
        }
        $("#get_id").submit(function(e){
            return false;
        });
        $(document).ready(function() {
            $('#identificator').change(function(e) {
                e.preventDefault();
                $.ajax({
                    type: "POST",
                    url: '/<?=ADM_CONTROLLER?>/product_seo_list/',
                    data: $(this).serialize(),
                    dataType: 'json',
                    success: function(response){
                        if (response.res != '1'){
                            show_elements(response);
                        }else{
                            alert('Не найдено товаров!');
                        }
                    }
                });
            });
        });
    </script>
<?php
    if(isset($modified_products)){
        echo 'ok';
    }
    ?>
    <table class="table table-striped table-bordered table-hover">
        <thead>
        <tr>
            <td colspan="3" style="text-align:center;">Измененные товары</td>
        </tr>
        <tr>
            <td>ID</td>
            <td>Имя товара</td>
            <td>Изменить</td>
        </tr>
        </thead>
        <tbody>
        <?php
        $modified_products = $this->db->where('meta_title_ro !=', '')->or_where('meta_title_ru !=', '')->get('product')->result();
        if(!empty($modified_products)){
            foreach($modified_products as $product_mod){
            ?>
            <tr>
                <td><?=$product_mod->id?></td>
                <td><?=$product_mod->name_ru?></td>
                <td><a href='/<?=ADM_CONTROLLER?>/product_seo/<?=$product_mod->id?>/' class='btn blue'><i class="fa fa-pencil"></i>Изменить</a></td>
            </tr>
        <? }
            }else{
            echo '<tr><td colspan="3" style="text-align:center; color:red;">нет товаров</td></tr>';
        }
        ?>
        </tbody>
    </table>

<? } else { ?>

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
                <a href="/<?=ADM_CONTROLLER?>/product_seo">CEO товаров</a>
                <i class="fa fa-angle-right"></i>
            </li>
            <li>
                <a><?=$head2?></a>
            </li>
        </ul>
    </div>

    <? $prods = $this->db->where('id', $uri3)->get('product')->row();
        if($prods){
            //next
        }else{
            header('Location: /'.ADM_CONTROLLER.'/product_seo/');
        }
    ?>

    <form class="form" name="form1" id="add-form"  method="POST" enctype="multipart/form-data">
        <div class="portlet box">
            <div class="portlet-body">
                <div class="" >
                    <table class="table table-striped table-bordered table-hover">
                        <tbody>
                        <tr>
                            <td>ID товара</td>
                            <td style=" font-size: medium"><?php echo $prods->id; ?></td>
                        </tr>
                        <tr>
                            <td>Название товара</td>
                            <td style=" font-size: medium"><?php echo $prods->name_ru; ?></td>
                        </tr>
                        <tr>
                            <td>СЕО название RU</td>
                            <td><input type="text" name="meta_title_ru" class="form-control" value="<?php echo $prods->meta_title_ru; ?>"> </td>
                        </tr>
                        <tr>
                            <td>СЕО название RO</td>
                            <td><input type="text" name="meta_title_ro" class="form-control" value="<?php echo $prods->meta_title_ro; ?>"> </td>
                        </tr>
                        <tr>
                            <td>СЕО описание RU</td>
                            <td><input type="text" name="meta_description_ru" class="form-control" value="<?php echo $prods->meta_description_ru; ?>"> </td>
                        </tr>
                        <tr>
                            <td>СЕО описание RO</td>
                            <td><input type="text" name="meta_description_ro" class="form-control" value="<?php echo $prods->meta_description_ro; ?>"> </td>
                        </tr>
                            <tr>
                                <td width="200"></td>
                                <td>
                                    <button type="submit" class="btn btn-success" >Изменить</button>
                                </td>
                            </tr>
                        </tbody>
                </table>
            </div>
        </div>
        </div>
    </form>

<? } ?>


