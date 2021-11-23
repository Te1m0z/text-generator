<?php

namespace Drupal\book_generate_form\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;

// use Drupal\Core\Ajax\AjaxResponse;
// use Drupal\Core\Ajax\HtmlCommand;


class BookFormSettings extends FormBase
{

    public function getFormId()
    {
        return 'book-generate-form';
    }

    public function buildForm(array $form, FormStateInterface $form_state)
    {

        $form['name'] = [
            '#type' => 'textfield',
            '#title' => $this->t('Book\'s title'),
            '#placeholder' => $this->t('Имя книги'),
            '#required' => true,
            '#id' => 'form-book'
        ];

        $form['author'] = [
            '#type' => 'textfield',
            '#title' => $this->t('Book\'s author'),
            '#placeholder' => $this->t('Имя автора книги'),
            '#required' => true
        ];

        $form['submit'] = array(
            '#type' => 'submit',
            '#value' => $this->t('Готово'),
            '#button_type' => 'primary',
            '#id' => 'submit-form-btn'
            // '#ajax' => [
            //     'callback' => '::ajaxSubmitCallback',
            //     'event' => 'click',
            //     'progress' => [
            //         'type' => 'throbber',
            //     ]
            // ]
        );

        $form['result_text'] = [
            '#markup' => '<div id="result-text"></div>',
            '#prefix' => '<p>' . $this->t('Результат:') . '</p>'
        ];

        $form['result_input'] = [
            '#type' => 'textarea',
            '#title' => $this->t('Редактировать:'),
            '#id' => 'form-result-input'
        ];

        $form['#attached']['library'][] = 'book_generate_form/book_generate_form';

        return $form;
    }

    // public function ajaxSubmitCallback(array &$form, FormStateInterface $form_state)
    // {
    //     $response = new AjaxResponse();
    //     $response->addCommand(new HtmlCommand('#result-text', $form_state->getValue('name')));
    //     $response->addCommand(new HtmlCommand('#form-result-input', $form_state->getValue('name')));

    //     return $response;
    // }

    public function submitForm(array &$form, FormStateInterface $form_state)
    {
        $form_state->setRebuild(true);
    }
}
