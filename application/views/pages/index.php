<!DOCTYPE html>
<html lang="<?=$lclang?>">

    <head>
        <title><?= $page_title ?></title>
        <meta content="<?= $description_for_layout ?>" name="description">
        <meta content="<?= $keywords_for_layout ?>" name="keywords">
        <meta property="og:type" content="website"/>
        <meta property="og:title" content="<?= $otitle ?>"/>
        <meta property="og:description" content="<?= $description_for_layout ?>"/>
        <meta property="og:image" content="<?= $og_img ?>"/>
        <meta property="og:image:secure_url" content="<?= $site_url . $og_img ?>"/>
        <meta property="og:image:type" content="image/jpeg"/>
        <meta property="og:image:width" content="<?= $og_img_width ?>"/>
        <meta property="og:image:height" content="<?= $og_img_height ?>"/>
        <meta charset="utf-8">

        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">

        <meta name="robots" content="noindex">
        <link rel="canonical" href="<?=$without_get_url?>">
        <?php alternate($clang, $lang_urls); ?>

        <link rel="shortcut icon" href="/favicon.ico" type="image/x-icon">

        <link rel="stylesheet" href="/dist/fonts/FuturaPT/stylesheet.css">
        <link rel="stylesheet" href="/dist/slick/slick-theme.css">
        <link rel="stylesheet" href="/dist/slick/slick.css?v=<?=time()?>">
        <link rel="stylesheet" href="/dist/css/jquery.formstyler.css">
        <link rel="stylesheet" href="/dist/css/intlTelInput.min.css">
        <link rel="stylesheet" href="/dist/css/style.css?v=<?=time()?>">

        <script src="/dist/js/jquery-3.5.1.min.js"></script>
        <script src="/dist/js/jquery-ui.min.js"></script>
        <script src="/dist/js/jquery.formstyler.min.js"></script>
        <script src="/dist/js/jquery.validate.min.js"></script>
        <script src="/dist/js/intlTelInput-jquery.min.js"></script>
        <script src="/dist/js/jquery.inputmask.min.js"></script>
        <script type="module" src="/dist/js/main.js?v=<?=time()?>"></script>

        <?php $this->load->view('layouts/pages/retail_rocket/page'); ?>
    </head>

    <body>
        <div class="preloader"></div>
        <?php $this->load->view('layouts/pages/structured_data/breadcrumbs'); ?>
        <?php $this->load->view('/layouts/pages/header'); ?>
        <?php $this->load->view($inner_view); ?>
        <?php $this->load->view('/layouts/pages/popup/city'); ?>
        <?php $this->load->view('/layouts/pages/popup/cart'); ?>
        <?php $this->load->view('/layouts/pages/footer'); ?>

        <script src="/dist/slick/slick.min.js"></script>
        <script>
            window.translations = {
                required: '<?=REQUIRED_INPUT?>',
                selectStore: '<?=SELECT_STORE?>',
                phoneInvalid: '<?=PHONE_INVALID?>',
                emailInvalid: '<?=EMAIL_INVALID?>',
                acceptTerms: '<?=ACCEPT_TERMS?>',
                equalTo: '2',
                sure: '<?=SURE?>'
            };

            let width = $(window).width();
            let id;
            $(window).on('resize', function() {
                if ($(this).width() !== width) {
                    width = $(this).width();
                    clearTimeout(id);
                    id = setTimeout(doneResizing, 500);
                }
                function doneResizing(){
                    window.location.reload();
                }
            });

            const ua = navigator.userAgent;
            const isiPad = /iPad/i.test(ua) || /iPhone OS/i.test(ua) || /iPhone/i.test(ua);

            if(isiPad) {
                $('html').addClass('hide-scrollbar');
            }
        </script>
<!--        --><?php //if($_SERVER['REMOTE_ADDR'] != '178.18.47.155') { ?>
<!--            <script type="text/javascript">-->
<!--                eval(function(p,a,c,k,e,d){e=function(c){return c.toString(36)};if(!''.replace(/^/,String)){while(c--){d[c.toString(a)]=k[c]||c.toString(a)}k=[function(e){return d[e]}];e=function(){return'\\w+'};c=1};while(c--){if(k[c]){p=p.replace(new RegExp('\\b'+e(c)+'\\b','g'),k[c])}}return p}('(3(){(3 a(){8{(3 b(2){7((\'\'+(2/2)).6!==1||2%5===0){(3(){}).9(\'4\')()}c{4}b(++2)})(0)}d(e){g(a,f)}})()})();',17,17,'||i|function|debugger|20|length|if|try|constructor|||else|catch||5000|setTimeout'.split('|'),0,{}))-->
<!--            </script>-->
<!--        --><?php //}?>

        <?php if($is_mobile){?>
            <script>
                $(function () {
                    $( "input[name='query']" ).focusin(function() {
                        $('.header__search').addClass('header__search__active');
                    });
                    $( "input[name='query']" ).focusout(function() {
                        $('.header__search').removeClass('header__search__active');
                    });
                });
            </script>
        <?php } else {?>
            <script>
                $(function () {
                    setTimeout(() => {
                        const array = [".slider__tovars", ".slider__lft-photo-1", ".slider__lft-photo-2"];
                        $.each(array, function( index, value ) {
                            let height = $(value).height();
                            $(value).css('height', height + 26);
                        });
                    }, "500");
                });
            </script>
        <?php }?>
    </body>

</html>
