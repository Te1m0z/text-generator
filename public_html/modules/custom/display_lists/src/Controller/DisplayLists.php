<?php

namespace Drupal\display_lists\Controller;

use Drupal\Core\Controller\ControllerBase;


class DisplayLists extends ControllerBase
{
    public function output()
    {
        $uuid = '33a98474-5976-4888-84cd-1883570bf87e';
        $query = \Drupal::entityQuery('example');
        $query->condition('uuid', $uuid);
        $id = $query->execute();

        dpm($id);

        return [
            '#title' => 'dada',
            '#markup' => '<h1>' . $id[1] . '</h1>'
        ];
    }
}
