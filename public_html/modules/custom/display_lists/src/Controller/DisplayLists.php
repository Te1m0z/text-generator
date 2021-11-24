<?php

namespace Drupal\display_lists\Controller;

use Drupal\Core\Controller\ControllerBase;

class DisplayLists extends ControllerBase
{
    public function output()
    {

        $entity_ids = \Drupal::entityQuery('example')
            ->condition('uuid', '33a98474-5976-4888-84cd-1883570bf87e', '=')
            ->execute();

        dpm($entity_ids);

        return [
            '#title' => 'dada',
            '#markup' => '<h1>' . $entity_ids[1] . '</h1>'
        ];
    }
}
