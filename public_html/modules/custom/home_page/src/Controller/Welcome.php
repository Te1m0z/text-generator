<?php

namespace Drupal\home_page\Controller;

use Drupal\Core\Controller\ControllerBase;

class Welcome extends ControllerBase
{
    public function output()
    {
        return [
            '#title' => 'Генератор списков литературы',
            '#markup' =>
            '<div class="welcome">
                <h2>Добро пожаловать</h2>
                <div>
                    <a href="/book">Создать список для книги</a>
                </div>
            </div>'
        ];
    }
}
