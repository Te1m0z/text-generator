<?php

namespace Drupal\book_generate_form\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;


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
            '#required' => true
        ];

        $form['author'] = [
            '#type' => 'textfield',
            '#title' => $this->t('Book\'s author'),
            '#placeholder' => $this->t('Имя автора книги'),
            '#required' => true
        ];

        $form['submit'] = [
            '#type' => 'submit',
            '#value' => $this->t('submit')
        ];

        $form['result'] = [
            '#type' => 'textfield',
            '#default_value' => 'test-value',
            '#prefix' => '<p>Результат:</p>'
        ];

        return $form;
    }

    public function validateForm(array &$form, FormStateInterface $form_state)
    {
        // $form['result']['#default_value'] = 'blabla';
        $form['result']['#default_value'] = 'my_value';
        // $form_state->setValue(array('result' , 0 , '#default_value'), 'blabla');
    }

    public function submitForm(array &$form, FormStateInterface $form_state)
    {
        $form_state->setRebuild(true);
    }
}
