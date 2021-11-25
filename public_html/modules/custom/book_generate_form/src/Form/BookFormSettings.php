<?php

namespace Drupal\book_generate_form\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;

use Drupal\example\Entity\Example;
use Drupal\Core\Entity\EntityInterface;
use Drupal\Core\Language\LanguageInterface;

use Drupal\node\Entity\Node;


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
            '#type' => 'button',
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
            '#type' => 'submit',
            '#value' => \Drupal::currentUser()->isAuthenticated() ? 'Сохранить список' : 'Войдите чтобы сохранить'
        );

        return $form;
    }

    public function submitForm(array &$form, FormStateInterface $form_state)
    {
        $form_state->setRebuild(true);

        $node = Node::create(['type' => 'article']);
        $node->setTitle('Книга сгенерирована');
        $node->body->value = $form_state->getValue('name');
        $node->body->format = 'full_html';
        $node->setPublished(true);
        $node->save();
    }
}
