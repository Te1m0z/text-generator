<?php

namespace Drupal\book_generate_form\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\node\Entity\Node;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Drupal\Core\Ajax\AjaxResponse;
use Drupal\Core\Ajax\HtmlCommand;

class BookForm extends FormBase
{
    public function getFormId()
    {
        return 'book-generate-form';
    }

    function returnAjax($form, FormStateInterface $form_state)
    {
        // $form['url']['type'] = 'textarea';

        $response = new AjaxResponse();

        $content = [];

        if ($form_state->getValue('type-book') == 'electronic') {
            $content[] = $form['url'];
            $content[] = $form['date'];
        } else {
            $content = null;
        }

        $response->addCommand(new HtmlCommand('#edit-fields-book-url', $content));

        return $response;
    }

    public function buildForm(array $form, FormStateInterface $form_state)
    {

        $form['title'] = [
            '#type' => 'textfield',
            '#title' => 'Название списка (необязательно)',
            '#placeholder' => 'Толстой. Война и мир'
        ];

        $form['type-book'] = [
            '#type' => 'select',
            '#title' => 'Тип материала',
            '#options' => [
                'book' => 'Книжный',
                'electronic' => 'Электронный'
            ],
            '#ajax' => [
                'callback' => '::returnAjax',
                'wrapper' => 'edit-fields-book-url',
                'method' => 'replace',
                'effect' => 'fade'
            ],
        ];

        $form['language'] = [
            '#type' => 'select',
            '#title' => 'Язык издания',
            '#options' => [
                'ru' => 'Русский',
                'en' => 'Английский'
            ],
            '#id' => 'form-book-lang'
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

        // hidden fiels //

        $form['url'] = [
            '#type' => 'textarea',
            '#title' => 'URL',
            '#placeholder' => 'https://example.com',
            '#required' => true,
            '#id' => 'form-book-url',
            '#access' => $form_state->getValue('type-book') == 'electronic'
        ];

        $form['date'] = [
            '#type' => 'date',
            '#title' => 'Дата обращения',
            '#date_format' => 'd.m.Y',
            '#required' => true,
            '#id' => 'form-book-date',
            '#access' => $form_state->getValue('type-book') === 'electronic'
        ];

        $form['url-conatiner'] = [
            '#type' => 'container',
            '#id' => 'edit-fields-book-url'
        ];








        $form['display_result'] = [
            '#type' => 'submit',
            '#value' => 'Посмотреть результат',
            '#id' => 'display-book-stroke-form-btn',
        ];

        $form['result_text'] = [
            '#type' => 'item',
            '#title' => 'Результат:',
            '#markup' => '<div id="result-book-text"></div>',
        ];

        $form['result_input'] = [
            '#type' => 'textarea',
            '#title' => 'Редактировать:',
            '#id' => 'form-book-result-input',
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
