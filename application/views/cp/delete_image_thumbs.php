<div class="page-bar">
    <ul class="page-breadcrumb">
        <li>
            <i class="fa fa-home"></i>
            <a href="/<?=ADM_CONTROLLER?>/menu/">Главная</a>
            <i class="fa fa-angle-right"></i>
        </li>
        <li><a>Удалить превью изображения"</a></li>
    </ul>
</div>
<? if(isset($_SESSION['delete_thumbs_response'])) {?>
    <div class="alert alert-success"><?=$_SESSION['delete_thumbs_response']?></div>
    <? unset($_SESSION['delete_thumbs_response']);?>
<?}?>

<form action="" method="post">
<div style="width:calc(100% - 150px);float:left;">
    <input name="articol" type="text" class="form-control" placeholder="Введите артикул продукта">
</div>
<div style="width:120px;float:left;margin-left:29px;"><button type="submit" class="btn red"><i class="fa fa-trash"></i> </button></div>
</form>
