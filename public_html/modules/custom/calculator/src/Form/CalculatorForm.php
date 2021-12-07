<?php

namespace Drupal\calculator\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;

class CalculatorForm extends FormBase
{
  public function getFormId()
  {
    return 'calculator-module-form';
  }

  public function buildForm(array $form, FormStateInterface $form_state)
  {
    $result_str = $form_state->get('result_str');

    if ($result_str === NULL) {
      $result_str = $form_state->set('result_str', 'empty 222');
      $result_str = 'empty 222';
    }

    $form['result'] = [
      '#type' => 'item',
      '#markup' => $result_str,
      '#id' => 'calc-result'
    ];

    $form['actions'] = ['#type' => 'actions'];

    $form['btns_set'] = [
      '#type' => 'fieldset',
      '#id' => 'btns_set'
    ];

    for ($n = 1; $n < 10; $n++) {
      $form['btns_set']['actions']['item_' . $n] = [
        '#type' => 'submit',
        '#value' => $n,
        '#ajax' => [
          'callback' => '::setNumberCallBack',
          'wrapper' => 'calc-result',
          'prevent' => 'click',
          'progress' => [
            'type' => 'none'
          ],
        ]
      ];
    }

    $form['btns_set']['actions']['plus'] = [
      '#type' => 'submit',
      '#value' => '+',
      '#ajax' => [
        'callback' => '::setNumberCallBack',
        'wrapper' => 'calc-result',
        'progress' => [
          'type' => 'none'
        ],
      ]
    ];

    $form['actions']['submit'] = [
      '#type' => 'submit',
      '#value' => 'Go'
    ];

    $form['#attached']['library'][] = 'calculator/calculator';

    return $form;
  }

  public function setNumberCallBack(array &$form, FormStateInterface $form_state)
  {
    $old_val = $form_state->get('result_str');
    $new_val = $old_val . $form_state->getTriggeringElement()['#value'];
    dpm($new_val);
    $form_state->set('result_str', $new_val);
    $form['result']['#markup'] = $old_val . $form_state->getTriggeringElement()['#value'];
//    $form_state->setRebuild(TRUE);

    return $form['result'];
  }

  public function submitForm(array &$form, FormStateInterface $form_state)
  {
//    $this->messenger()->addMessage('nais');
  }
}
