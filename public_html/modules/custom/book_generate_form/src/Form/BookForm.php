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

    // function setDoiUrl($form, FormStateInterface $form_state)
    // {

    //     $response = new AjaxResponse();

    //     $response->addCommand(new HtmlCommand('#form-book-check-doi', [
    //         '#markup' => '<a target="_blank" href=' . 'https://doi.org/' . $form_state->getValue('input-doi') . '>Проверить DOI</a>'
    //     ]));

    //     return $response;
    // }

    public function checkEversion($form, FormStateInterface $form_state)
    {
        $response = new AjaxResponse();

        $content = [];

        if ($form_state->getValue('electronic-version') == true) {
            $content[] = $form['input-url'];
            $content[] = $form['input-date'];
        } else {
            $content = null;
        }

        $response->addCommand(new HtmlCommand('#edit-fields-book-e-version', $content));

        return $response;
    }

    public function buildForm(array $form, FormStateInterface $form_state)
    {

        $form['input-title'] = [
            '#type' => 'textfield',
            '#title' => 'Название списка (необязательно)',
            '#placeholder' => 'Толстой. Война и мир',
            '#access' => \Drupal::currentUser()->isAuthenticated() ? true : false
        ];

        $form['electronic-version'] = [
            '#type' => 'checkbox',
            '#title' => 'У меня есть электронная версия',
            '#ajax' => [
                'callback' => '::checkEversion',
                'event' => 'change'
            ],
            '#id' => 'book-check-e-version',
        ];

        $form['select-language'] = [
            '#type' => 'select',
            '#title' => 'Язык издания',
            '#options' => [
                'ru' => 'Русский',
                'en' => 'Английский'
            ],
            '#id' => 'form-book-lang',
        ];

        $form['input-doi'] = [
            '#type' => 'textfield',
            '#title' => 'DOI (просто номер, без https...)',
            '#placeholder' => '12345',
            '#description' => 'Если есть, обязательно',
            '#required' => false,
            '#id' => 'form-book-doi',
            // '#ajax' => [
            //     'event' => 'change',
            //     'callback' => '::setDoiUrl',
            //     'wrapper' => 'form-book-check-doi',
            //     'method' => 'replace',
            // ],
            '#attributes' => [
                ' type' => 'number'
            ]
        ];

        $form['check-doi'] = [
            '#type' => 'item',
            '#id' => 'form-book-check-doi',
            '#markup' => '<a target="_blank" href=' . $form_state->getValue('input-doi') . '>Проверить DOI</a>',
        ];

        // $form['test'] = [
        //     '#type' => 'container',
        //     '#title' => 'Авторы',
        //     'child' => [
        //         [
        //             '#type' => 'textfield',
        //             '#title' => 'Автор 1',
        //             '#required' => true,
        //             '#id' => 'form-book-author',
        //         ],
        //         [
        //             '#type' => 'button',
        //             '#value' => 'Добавить автора'
        //         ]
        //     ]
        // ];

        $form['input-name'] = [
            '#type' => 'textarea',
            '#title' => 'Название книги',
            '#placeholder' => 'Война и мир',
            '#required' => true,
            '#id' => 'form-book-name',
            '#rows' => '2'
        ];

        $form['input-tome-num'] = [
            '#type' => 'textfield',
            '#attributes' => [
                ' type' => 'number',
                ' min' => '1',
                ' max' => '999'
            ],
            '#title' => 'Номер тома',
            '#placeholder' => '3',
            '#required' => false,
            '#id' => 'form-book-tome-num'
        ];

        $form['input-tome-max'] = [
            '#type' => 'textfield',
            '#attributes' => [
                ' type' => 'number',
                ' min' => '1',
                ' max' => '999'
            ],
            '#title' => 'Всего томов',
            '#placeholder' => '5',
            '#required' => false,
            '#id' => 'form-book-tome-max'
        ];

        $form['input-tome-name'] = [
            '#type' => 'textfield',
            '#title' => 'Название тома',
            '#placeholder' => 'Том первый',
            '#required' => false,
            '#id' => 'form-book-tome-name'
        ];

        $form['input-city'] = [
            '#type' => 'textfield',
            '#title' => 'Место издания (город)',
            '#placeholder' => 'Саратов',
            '#required' => true,
            '#id' => 'form-book-city'
        ];

        $form['input-publish'] = [
            '#type' => 'textfield',
            '#title' => 'Издательство',
            '#placeholder' => 'Наука',
            '#required' => true,
            '#id' => 'form-book-publish'
        ];

        $form['input-year'] = [
            '#type' => 'textfield',
            '#attributes' => [
                ' type' => 'number',
                ' min' => '1',
                ' max' => date('Y')
            ],
            '#title' => 'Год издания',
            '#placeholder' => '2018',
            '#required' => true,
            '#id' => 'form-book-year',
            '#size' => 30
        ];

        $form['input-pages'] = [
            '#type' => 'textfield',
            '#attributes' => [
                ' type' => 'number',
                ' min' => '1',
                ' max' => '99999'
            ],
            '#title' => 'Количество страниц',
            '#placeholder' => '240',
            '#required' => true,
            '#id' => 'form-book-pages'
        ];

        $form['input-other'] = [
            '#type' => 'textarea',
            '#title' => 'Прочее',
            '#id' => 'form-book-other',
            '#rows' => 2
        ];

        $form['input-release'] = [
            '#type' => 'textfield',
            '#title' => 'Серия',
            '#id' => 'form-book-release',
        ];

        // hidden fiels //

        $form['input-url'] = [
            '#type' => 'textarea',
            '#title' => 'URL',
            '#placeholder' => 'https://example.com',
            '#required' => true,
            '#id' => 'form-book-url',
            '#access' => $form_state->getValue('electronic-version') == true,
            '#rows' => 2
        ];

        $form['input-date'] = [
            '#type' => 'date',
            '#title' => 'Дата обращения',
            '#required' => true,
            '#id' => 'form-book-date',
            '#access' => $form_state->getValue('electronic-version') == true,
            '#attributes' => [
                ' min' => '1900-01-01',
                ' max' => date('Y-m-d'),
            ]
        ];

        $form['eversion-conatiner'] = [
            '#type' => 'container',
            '#id' => 'edit-fields-book-e-version'
        ];




        $form['display_result'] = [
            '#type' => 'submit',
            '#value' => 'Посмотреть результат',
            '#id' => 'display-book-stroke-form-btn',
            '#name' => 'display-result',
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
            '#description' => 'В "Мои списки" сохранится текст из поля "Редактировать"',
            '#required' => true
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
        $title_val = $form_state->getValue('input-title');

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
