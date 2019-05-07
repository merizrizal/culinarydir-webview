<?php
namespace webview\components;

use Yii;
use yii\base\Widget;

class Snackbar extends Widget
{
    public function init()
    {
        parent::init();

        $this->getView()->registerJsFile(Yii::$app->homeUrl . 'lib/snackbarjs/snackbar.min.js', ['depends' => 'yii\web\JqueryAsset']);
    }

    public static function messageResponse()
    {
        return '
            function messageResponse(icon, title, message, type) {

                if (title != "302") {

                    $.snackbar({
                        content:
                            "<div class=\"bg-" + type + " list-group\">" +
                                "<div class=\"list-group-item\"><i class=\"" + icon + "\"></i>" + title + "</div>" +
                                "<div class=\"list-group-item\">" + message + "</div>" +
                            "</div>",
                        htmlAllowed: true
                    });
                }
            }
        ';
    }

    public static function stickySnackbar()
    {
        return '
            function stickySnackbar(icon, title, message, type) {

                return $.snackbar({
                    content:
                        "<div class=\"bg-" + type + " list-group snackbar-cart\">" +
                            "<div class=\"list-group-item title-container\"><i class=\"" + icon + "\"></i><span class=\"snackbar-title\">" + title + "</span></div>" +
                            "<div class=\"list-group-item\">" + message + "</div>" +
                        "</div>",
                    htmlAllowed: true,
                    timeout: 0,
                    style: "sticky-cart"
                });
            }
        ';
    }
}
