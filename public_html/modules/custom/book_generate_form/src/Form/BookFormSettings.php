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
            '#placeholder' => $this->t('Имя книги')
        ];

        $form['author'] = [
            '#type' => 'textfield',
            '#title' => $this->t('Book\'s author'),
            '#placeholder' => $this->t('Имя автора книги')
        ];

        $form['submit'] = [
            '#type' => 'submit',
            '#value' => $this->t('submit')
        ];

        return $form;
    }

    public function validateForm(array &$form, FormStateInterface $form_state)
    {
        if (strlen($form_state->getValue('name')) < 5) {
            $form_state->setErrorByName('name', 'Поле "Имя" не может быть короче 5 символов');
        }
    }

    public function submitForm(array &$form, FormStateInterface $form_state)
    {
        $this->messenger()->addStatus('Форма сохранена');
    }
}
