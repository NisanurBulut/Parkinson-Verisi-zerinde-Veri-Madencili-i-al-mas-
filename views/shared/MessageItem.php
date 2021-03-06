<?php

namespace app\views\shared;

use app\core\Application;

class MessageItem
{
    public static function showMessage()
    {
        if(Application::$app->session->getFlash('message')) {
            $state=Application::$app->session->getFlash('state') ?? '';
            $icon=Application::$app->session->getFlash('icon') ?? '';
            $header=Application::$app->session->getFlash('header') ?? '';
            $content=Application::$app->session->getFlash('message') ?? '';

            echo sprintf('<div id="sessionMessage" class="ui %s icon message" style="float:right;max-width:300px;">
                            <i class=" %s icon"></i>
                            <i class="close icon"></i>
                            <div class="header">
                                %s
                            </div>
                            <p>%s</p>
                            </div>', $state, $icon, $header, $content);
        }
    }
}
?>