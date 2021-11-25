<?php

namespace Drupal\display_lists\Controller;

use Drupal\Core\Controller\ControllerBase;


class DisplayLists extends ControllerBase
{
    public function output()
    {
        $query = \Drupal::entityQuery('node');
        $query->condition('type', 'page', '=');
        $query->condition('uid', '2', '=');
        $ids = $query->execute();
        $nodes = \Drupal\node\Entity\Node::loadMultiple($ids);
        foreach ($nodes as $node) {
            dpm($node->body->value);
        }

        return [
            '#title' => 'dada',
            '#markup' => '<h1>' . '</h1>'
        ];
    }
}
