<?php

namespace Drupal\home_page\Controller;

use Drupal\Core\Controller\ControllerBase;

class Welcome extends ControllerBase
{
    public function output()
    {
        return [
            '#title' => 'Добро пожаловать',
            '#attached' => [
                'library' => 'home_page/home_page',
            ],
            '#markup' =>
            '<div class="welcome">
                <h4>Выберите тип материала:</h4>
                <div class="welcome-nav">
                    <a href="/book">Книга</a>
                    <a href="/journal">Статья в журнале</a>
                    <a href="/collection">Статья в сборнике</a>
                </div>
            </div>'
        ];
    }
}
