<?php

namespace Drupal\book_generate_form\Controller;
use Drupal\Core\Controller\ControllerBase;

class DisplaySaveBtnController extends ControllerBase {

    public function output() {
        return [
            '#title' => 'dada',
            '#markup' => '<h1>Hello world</h1>',
            '#weight' => 100
        ];
    }
}