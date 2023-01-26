<form action="" method="post">
<div>
    <input required="required" name="articol" type="text" class="form-control" placeholder="Введите артикул продукта">
    <button type="submit" > проверить</button>
</div>
</form>
<style>
    pre {
        font-size: 16px;
    }
</style>
<?php
if($_SERVER['REQUEST_METHOD'] == 'POST'){
    $articol = $this->input->post('articol', true);
    //$articol = trim($articol);

    dump('Articol: '.$articol, true);

    $dopa = substr($articol, -2);
    $dir = realpath('public')."/products/" . $dopa . "/" . $articol;

    if(!is_dir($dir)) {
        dump('нет такой папки - '.$dir);
    }

    $dirs = array_filter(glob($dir.'/*'), 'is_dir');

    foreach($dirs as $directory) {
        $pizza = explode('/', $directory);
        if(end($pizza) != 'thumbs') {
            $images = glob($directory . "/*.jpg");
            dump('Цвет: ' . end($pizza), true);

            if($images) {
                foreach ($images as $img) {
                    $part = explode('bombamd/', $img);
                    dump('    ' . end($part), true);
                }
            } else {
                dump('Пустая папка', true);
            }
        }
    }
}
?>
