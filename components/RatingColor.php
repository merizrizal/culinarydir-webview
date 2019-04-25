<?php
namespace webview\components;

use yii\base\Widget;
use yii\web\View;

class RatingColor extends Widget
{

    public function init()
    {
        parent::init();

        $jscript = '
            function ratingColor(element, containerRating) {

                element.each(function() {

                    var vote_value = parseFloat($(this).find(containerRating).html());

                    var elementContainer = $(this).find(containerRating);

                    if (elementContainer.hasClass("badge-info")) {

                        elementContainer.removeClass("badge-info");
                    } else if (elementContainer.hasClass("badge-success")) {

                        elementContainer.removeClass("badge-success");
                    } else if (elementContainer.hasClass("badge-primary")) {

                        elementContainer.removeClass("badge-primary");
                    } else if (elementContainer.hasClass("badge-warning")) {

                        elementContainer.removeClass("badge-warning");
                    } else if (elementContainer.hasClass("badge-danger")) {

                        elementContainer.removeClass("badge-danger");
                    } else if (elementContainer.hasClass("badge-dark")) {

                        elementContainer.removeClass("badge-dark");
                    }

                    if (vote_value == 5) {

                        elementContainer.addClass("badge-info");
                    } else if (vote_value < 5 && vote_value >= 4 ) {

                        elementContainer.addClass("badge-success");
                    } else if (vote_value < 4 && vote_value >= 3 ) {

                        elementContainer.addClass("badge-primary");
                    } else if (vote_value < 3 && vote_value >= 2 ) {

                        elementContainer.addClass("badge-warning");
                    } else if (vote_value < 2 && vote_value >= 1 ) {

                        elementContainer.addClass("badge-danger");
                    } else {

                        elementContainer.addClass("badge-dark");
                    }
                });
            }
        ';

        $this->getView()->registerJs($jscript, View::POS_HEAD);
    }
}
