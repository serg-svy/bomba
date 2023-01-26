<div class="popup__reviews popup">
    <div class="popup__inner">
        <div class="popup__close"><img alt="delete" src="/dist/img/icons/Delete.svg"></div>
        <div class="popup__title"><?=FEEDBACK_ON_PRODUCT?></div>
        <form class="review-form" action="/<?=$lclang?>/product/feedback/" method="post" enctype="multipart/form-data">
            <input type="hidden" value="<?=$product->id?>" name="review[product_id]">
            <div class="block__inputs">
                <div class="input__item">
                    <label for="review_name"><?=NAME?><sup>*</sup></label>
                    <input type="text" id="review_name" value="" name="review[name]" required>
                </div>
                <div class="input__item">
                    <label for="review_surname"><?=SURNAME?><sup>*</sup></label>
                    <input type="text" id="review_surname" value="" name="review[surname]" required>
                </div>
            </div>
            <div class="input__item">
                <label for="review_text"><?=PRODUCT_COMMENT?></label>
                <textarea id="review_text" name="review[text]"></textarea>
            </div>
            <div class="input__file">
                <input name="files[]" class="d-none" type="file" multiple accept=".gif,.jpg,.jpeg,.png" id="review_file"><img src="/dist/img/icons/i46.svg">
                <label for="review_file"><?=UPLOAD_A_PHOTO?></label>
            </div>
            <div class="input__item">
                <input id="review_score" style="opacity:0;position:absolute;padding:0;height:0;" type="text" name="review[score]" required>
                <label for="review_score"><?=RATING?><sup>*</sup></label>
                <div class="review__add__stars"></div>
            </div>
            <div class="checkbox__item">
                <label for="agree-ck1">
                    <input type="checkbox" id="agree-ck1" name="review[personal_data]" required>
                    <span><?=I_ACCEPT?> <a href="#"><?=$menu['all'][18]->title?></a></span>
                </label>
            </div>
            <div class="checkbox__item">
                <label for="agree-ck2">
                    <input type="checkbox" id="agree-ck2" name="review[terms]" required>
                    <span><?=I_ACCEPT?> <a href="#"><?=$menu['all'][19]->title?></a></span>
                </label>
            </div>
            <input class="btn" type="submit" value="<?=SEND?>" onclick="checkFeedback();">
        </form>
        <script>
            $(".review__add__stars").starRating({
                starSize: 25,
                readOnly: false,
                totalStars: 5,
                emptyColor: 'lightgray',
                activeColor: 'rgb(213 9 16)',
                initialRating: 0,
                strokeWidth: 0,
                useGradient: false,
                minRating: 1,
                useFullStars: true,
                callback: function(currentRating, $el){
                    $("#review_score").val(currentRating);
                }
            });
            function checkFeedback() {
                let name = $('#review_name');
                let surname = $('#review_surname');
                let score = $('#review_score');
                let f = false;

                if (name.val() === '') {
                    name.addClass('review_error');
                    f = true;
                } else {
                    name.removeClass('review_error');
                }
                if (surname.val() === '') {
                    surname.addClass('review_error');
                    f = true;
                } else {
                    surname.removeClass('review_error');
                }

                if (score.val() === '') {
                    $(".review__add__stars").addClass('review_error');
                    f = true;
                } else {
                    $(".review__add__stars").removeClass('review_error');
                }

                if ($("#agree-ck1").is(':checked')) {
                    $("#agree-ck1").closest('div').removeClass('review_error');
                } else {
                    f = true;
                    $("#agree-ck1").closest('div').addClass('review_error');
                }

                if ($("#agree-ck2").is(':checked')) {
                    $("#agree-ck2").closest('div').removeClass('review_error');
                } else {
                    f = true;
                    $("#agree-ck2").closest('div').addClass('review_error');
                }

                if (f) {
                    return false;
                } else {
                    $('.review-form').submit();
                }
            }
        </script>
        <style>
            .review_error {
                border: 1px solid red !important;
            }
            .review__add__stars{
                width: fit-content;
                border-radius: 5px;
                padding-right: 4px;
            }
        </style>
    </div>
</div>
