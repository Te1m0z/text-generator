<?php

namespace Drupal\book_generate_form\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;

use Drupal\book_generate_form\Entity\SavedList;
use Drupal\Core\Entity\EntityInterface;
use Drupal\Core\Language\LanguageInterface;


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
        );

        $form['result_text'] = [
            '#markup' => '<div id="result-text">' . $form_state->getValue('result_input') . '</div>',
            '#prefix' => '<p>' . $this->t('Результат:') . '</p>'
        ];

        $form['result_input'] = [
            '#type' => 'textarea',
            '#title' => $this->t('Редактировать:'),
            '#id' => 'form-result-input'
        ];

        $form['#attached']['library'][] = 'book_generate_form/book_generate_form';

        $form['save_list'] = array(
            '#type' => 'button',
            '#value' => \Drupal::currentUser()->isAuthenticated() ? 'Сохранить список' : 'Войдите чтобы сохранить'
        );

        return $form;
    }

    public function submitForm(array &$form, FormStateInterface $form_state)
    {
        $form_state->setRebuild(true);

        // $created = time();
        // $uuid_service = \Drupal::service('uuid');
        // $uuid = $uuid_service->generate();
        // $lc = LanguageInterface::LANGCODE_DEFAULT;
        // $saved_list = new SavedList([
        //     'uuid' => array($lc => $uuid),
        //     'created' => array($lc => $created),
        //     'fint' => array($lc => 10),
        //     'fstring' => array($lc => 'some text'),
        // ], 'example');
        // $saved_list->save();
    }
}
