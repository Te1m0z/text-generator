<?php

namespace Drupal\display_lists\Controller;

use Drupal\Core\Controller\ControllerBase;
use Drupal\node\Entity\Node;

class DisplayLists extends ControllerBase
{
    public function output()
    {
        $user_id = \Drupal::currentUser()->id();
        $nids = \Drupal::entityQuery('node')->condition('uid', $user_id)->execute();
        $nodes = Node::loadMultiple($nids);

        $str = '';

        foreach ($nodes as $node) {
            $str .= $node->body->value . '<br/>';
        }

        return [
            '#title' => 'My lists',
            '#markup' => $node->body->value,
        ];
    }
}
