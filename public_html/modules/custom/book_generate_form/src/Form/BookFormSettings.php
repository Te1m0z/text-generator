<?php

namespace Drupal\book_generate_form\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\node\Entity\Node;
use Symfony\Component\HttpFoundation\RedirectResponse;
// use Drupal\Core\Ajax;
// use Drupal\Core\Ajax\AjaxResponse;
// use Drupal\Core\Ajax\ReplaceCommand;

class BookFormSettings extends FormBase
{
    public function getFormId()
    {
        return 'book-generate-form';
    }

    public function check_e_version(array $form, FormStateInterface $form_state)
    {
        $this->messenger()->addStatus('test');
        $form_state->setValueForElement($form['nav_field'], ['#value' => '1111']);

        return $form;
    }

    public function buildForm(array $form, FormStateInterface $form_state)
    {

        // $form['nav_e-version_checkbox'] = [
        //     '#type' => 'checkbox',
        //     '#title' => 'У меня электронная версия',
            // '#options' => [
            //     '1' => 'Бумажный',
            //     '2' => 'Электронный'
            // ],
        //     '#ajax' => [
        //         'callback' => '::check_e_version',
        //         // 'wrapper' => 'e_version_wrapper',
        //         'event' => 'change'
        //     ],
        // ];

        // $form['nav_field'] = [
        //     '#type' => 'textfield',
        //     '#title' => 'URL',
        //     // '#placeholder' => 'https://example.com',
        //     '#required' => true,
        //     '#id' => 'form-book-url',
        //     '#value' => 'da'
        // ];

        $form['title'] = [
            '#type' => 'textfield',
            '#title' => 'Название списка (необязательно)',
            '#placeholder' => 'Толстой. Война и мир'
        ];

        $form['author'] = [
            '#type' => 'textarea',
            '#title' => 'Автор(ы)',
            '#placeholder' => 'Иванов И. И.',
            '#required' => true,
            '#id' => 'form-book-author',
            '#rows' => '2'
        ];

        $form['name'] = [
            '#type' => 'textarea',
            '#title' => 'Название книги',
            '#placeholder' => 'Война и мир',
            '#required' => true,
            '#id' => 'form-book-name',
            '#rows' => '2'
        ];

        $form['tome-num'] = [
            '#type' => 'textfield',
            '#attributes' => [
                ' type' => 'number',
            ],
            '#title' => 'Номер тома',
            '#placeholder' => '3',
            '#required' => false,
            '#id' => 'form-book-tome-num'
        ];

        $form['tome-max'] = [
            '#type' => 'textfield',
            '#attributes' => [
                ' type' => 'number',
            ],
            '#title' => 'Всего томов',
            '#placeholder' => '5',
            '#required' => false,
            '#id' => 'form-book-tome-max'
        ];

        $form['tome-name'] = [
            '#type' => 'textfield',
            '#title' => 'Название тома',
            '#placeholder' => 'Том первый',
            '#required' => false,
            '#id' => 'form-book-tome-name'
        ];

        $form['city'] = [
            '#type' => 'textfield',
            '#title' => 'Место издания (город)',
            '#placeholder' => 'Саратов',
            '#required' => true,
            '#id' => 'form-book-city'
        ];

        $form['publish'] = [
            '#type' => 'textfield',
            '#title' => 'Издательство',
            '#placeholder' => 'Наука',
            '#required' => true,
            '#id' => 'form-book-publish'
        ];

        $form['year'] = [
            '#type' => 'textfield',
            '#attributes' => [
                ' type' => 'number'
            ],
            '#title' => 'Год издания',
            '#placeholder' => '2018',
            '#required' => true,
            '#id' => 'form-book-year'
        ];

        $form['pages'] = [
            '#type' => 'textfield',
            '#attributes' => [
                ' type' => 'number'
            ],
            '#title' => 'Количество страниц',
            '#placeholder' => '240',
            '#required' => true,
            '#id' => 'form-book-pages'
        ];

        // $form['e-version'] = [
        //     '#type' => 'textfield',
        //     '#title' => 'URL',
        //     '#placeholder' => 'https://example.com',
        //     '#required' => true,
        //     '#id' => 'form-book-url'
        // ];








        $form['display_result'] = [
            '#type' => 'button',
            '#value' => 'Посмотреть результат',
            '#id' => 'display-stroke-form-btn',
        ];

        $form['result_text'] = [
            '#type' => 'item',
            '#title' => 'Результат:',
            '#markup' => '<div id="result-text"></div>',
        ];

        $form['result_input'] = [
            '#type' => 'textarea',
            '#title' => 'Редактировать:',
            '#id' => 'form-result-input',
            '#rows' => '2',
        ];

        $form['submit'] = [
            '#type' => 'submit',
            '#value' => \Drupal::currentUser()->isAuthenticated() ? 'Сохранить список' : 'Войдите чтобы сохранить',
            '#button_type' => 'primary',
        ];

        $form['#attached']['library'][] = 'book_generate_form/book_generate_form';

        return $form;
    }

    public function submitForm(array &$form, FormStateInterface $form_state)
    {
        $form_state->setRebuild(true);

        $title_val = $form_state->getValue('title');

        if (\Drupal::currentUser()->isAuthenticated()) {
            $node = Node::create(['type' => 'article']);
            $node->setTitle($title_val !== '' ? $title_val : 'Список без заголовка');
            $node->body->value = $form_state->getValue('result_input');
            $node->body->format = 'full_html';
            $node->field_type->value = 'Книга';
            $node->setPublished(true);
            $node->save();

            $this->messenger()->addMessage('Книга успешно сохранина!');
        } else {
            $response = new RedirectResponse('/user/login', 301);
            $response->send();
        }
    }
}
