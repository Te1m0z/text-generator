<?php

namespace Drupal\book_generate_form\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;

class BookForm extends FormBase
{
    public function buildForm(array $form, FormStateInterface $form_state)
    {

        $form['fields-container'] = [
            '#type' => 'container',
            '#id' => 'inputs-container-wrapper'
        ];

        $form['fields-container']['title'] = [
            '#type' => 'textfield',
            '#title' => 'Название списка (необязательно)',
            '#placeholder' => 'Толстой. Война и мир',
            '#access' => \Drupal::currentUser()->isAuthenticated() ? true : false
        ];

        $form['fields-container']['electronic-version'] = [
            '#type' => 'checkbox',
            '#title' => 'У меня есть электронная версия',
            // '#submit' => ['::changeEversion'],
            '#ajax' => [
                'callback' => '::changeEversion',
                'event' => 'change',
                'wrapper' => 'electronic-fieldset-wrapper'
            ],
            '#id' => 'book-check-e-version'
        ];

        $form['fields-container']['language'] = [
            '#type' => 'select',
            '#title' => 'Язык издания',
            '#options' => [
                'ru' => 'Русский',
                'en' => 'Английский'
            ],
            '#id' => 'form-book-lang',
        ];

        $form['fields-container']['doi'] = [
            '#type' => 'textfield',
            '#title' => 'DOI (просто номер, без https...)',
            '#placeholder' => '12345',
            '#description' => 'Если есть, обязательно',
            '#id' => 'form-book-doi',
            '#attributes' => [
                ' type' => 'number'
            ]
        ];

        $form['fields-container']['check-doi'] = [
            '#type' => 'item',
            '#markup' => '<a id="form-book-check-doi" target="_blank" href="https://doi.org/">Проверить DOI</a>',
        ];


        $num_names = $form_state->get('num_names'); // NULL
        $e_version = $form_state->get('electronic');

        if ($num_names === NULL) {
            $num_names = $form_state->set('num_names', 1);
            $num_names = 1;
        }

        if ($e_version === NULL) {
            $e_version = $form_state->set('electronic', false);
            $e_version = false;
        }

        $form['#tree'] = TRUE;

        $form['fields-container']['names_fieldset'] = [
            '#type' => 'fieldset',
            '#prefix' => '<div id="names-fieldset-wrapper">',
            '#suffix' => '</div>',
            '#title' => 'Автор(ы)'
        ];

        for ($i = 1; $i <= $num_names; $i++) {

            $form['fields-container']['names_fieldset']['author_set_' . $i] = [
                '#type' => 'fieldset',
                '#title' => 'Автор ' . $i,
                '#attributes' => [' class' => 'author_set_item']
            ];

            $form['fields-container']['names_fieldset']['author_set_' . $i]['author_first_name_' . $i] = [
                '#type' => 'textfield',
                '#title' => 'Имя',
                '#required' => TRUE,
                '#id' => 'author_first_name_' . $i,
            ];

            $form['fields-container']['names_fieldset']['author_set_' . $i]['author_last_name_' . $i] = [
                '#type' => 'textfield',
                '#title' => 'Фамилия',
                '#required' => TRUE,
                '#id' => 'author_last_name_' . $i,
            ];

            $form['fields-container']['names_fieldset']['author_set_' . $i]['author_middle_name_' . $i] = [
                '#type' => 'textfield',
                '#title' => 'Отчество',
                '#required' => FALSE,
                '#id' => 'author_middle_name_' . $i,
            ];
        }

        $form['fields-container']['names_fieldset']['actions'] = [
            '#type' => 'actions',
        ];

        $form['fields-container']['names_fieldset']['actions']['add_name'] = [
            '#type' => 'submit',
            '#value' => 'Добавить автора',
            '#submit' => ['::addOne'],
            '#ajax' => [
                'callback' => '::addmoreCallback',
                'wrapper' => 'names-fieldset-wrapper',
            ],
            '#limit_validation_errors' => [
                [
                    'inputs-container',
                    'names_fieldset'
                ]
            ]
        ];

        // Если существует более одного имени, добавьте кнопку удаления.
        if ($num_names > 1) {
            $form['fields-container']['names_fieldset']['actions']['remove_name'] = [
                '#type' => 'submit',
                '#value' => 'Remove one',
                '#submit' => ['::removeCallback'],
                '#ajax' => [
                    'callback' => '::addmoreCallback',
                    'wrapper' => 'names-fieldset-wrapper',
                ],
                '#limit_validation_errors' => []
            ];
        }

        $form['fields-container']['name'] = [
            '#type' => 'textarea',
            '#title' => 'Название книги',
            '#placeholder' => 'Война и мир',
            '#required' => true,
            '#id' => 'form-book-name',
            '#rows' => '2'
        ];

        $form['fields-container']['tome-num'] = [
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

        $form['fields-container']['tome-max'] = [
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

        $form['fields-container']['tome-name'] = [
            '#type' => 'textfield',
            '#title' => 'Название тома',
            '#placeholder' => 'Том первый',
            '#required' => false,
            '#id' => 'form-book-tome-name'
        ];

        $form['fields-container']['city'] = [
            '#type' => 'textfield',
            '#title' => 'Место издания (город)',
            '#placeholder' => 'Саратов',
            '#required' => true,
            '#id' => 'form-book-city'
        ];

        $form['fields-container']['publish'] = [
            '#type' => 'textfield',
            '#title' => 'Издательство',
            '#placeholder' => 'Наука',
            '#required' => true,
            '#id' => 'form-book-publish'
        ];

        // $form['fields-container']['year'] = [
        //     '#type' => 'textfield',
        //     '#attributes' => [
        //         ' type' => 'number',
        //         ' min' => '1',
        //         ' max' => date('Y')
        //     ],
        //     '#title' => 'Год издания',
        //     '#placeholder' => '2018',
        //     '#required' => true,
        //     '#id' => 'form-book-year',
        //     '#size' => 30
        // ];

        // $form['fields-container']['pages'] = [
        //     '#type' => 'textfield',
        //     '#attributes' => [
        //         ' type' => 'number',
        //         ' min' => '1',
        //         ' max' => '99999'
        //     ],
        //     '#title' => 'Количество страниц',
        //     '#placeholder' => '240',
        //     '#required' => true,
        //     '#id' => 'form-book-pages'
        // ];

        // $form['fields-container']['other'] = [
        //     '#type' => 'textarea',
        //     '#title' => 'Прочее',
        //     '#id' => 'form-book-other',
        //     '#rows' => 2
        // ];

        // $form['fields-container']['release'] = [
        //     '#type' => 'textfield',
        //     '#title' => 'Серия',
        //     '#id' => 'form-book-release',
        // ];


        // $form['electronic_fieldset'] = [
        //     '#type' => 'fieldset',
        //     '#prefix' => '<div id="electronic-fieldset-wrapper">',
        //     '#suffix' => '</div>',
        // ];

        // if ($form_state->get('electronic')) {

        //     $form['electronic_fieldset']['url'] = [
        //         '#type' => 'textarea',
        //         '#title' => 'URL',
        //         '#placeholder' => 'https://example.com',
        //         '#required' => true,
        //         '#id' => 'form-book-url',
        //         '#rows' => 2
        //     ];

        //     $form['electronic_fieldset']['date'] = [
        //         '#type' => 'date',
        //         '#title' => 'Дата обращения',
        //         '#required' => true,
        //         '#id' => 'form-book-date',
        //         '#attributes' => [
        //             ' min' => '1900-01-01',
        //             ' max' => date('Y-m-d'),
        //         ],
        //         '#default_value' => date('Y-m-d')
        //     ];
        // }


        $form['fields-container']['actions'] = [
            '#type' => 'actions'
        ];

        $form['fields-container']['actions']['result'] = [
            '#type' => 'button',
            '#value' => 'Посмотреть результат',
            // '#submit' => ['::checkFormValid'],
            '#ajax' => [
                'callback' => '::checkFormValid',
                'event' => 'click',
                'wrapper' => 'inputs-container-wrapper'
            ],
            '#id' => 'display-book-stroke-form-btn',
            '#limit_validation_error' => [
                [
                    'fields-container'
                ]
            ]
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
            // '#required' => true
        ];

        $form['#attached']['library'][] = 'book_generate_form/book_generate_form';

        // $session = \Drupal::request()->getSession();

        // if (empty($session->get('my_variable'))) {
        //     $session->set('my_variable', 1);
        // }

        // $form['test'] = [
        //     '#type' => 'markup',
        //     '#markup' => $session->get('my_variable', 1)
        // ];

        $form['submit'] = [
            '#type' => 'submit',
            '#value' => 'Сохранить список',
            '#weight' => 100
        ];

        return $form;
    }

    // public function validateForm(array &$form, FormStateInterface $form_state)
    // {
    //     parent::validateForm($form, $form_state);

    //     $session = \Drupal::request()->getSession();
    //     $session->set('my_variable', $session->get('my_variable') + 1);
    // }

    /**
     * {@inheritdoc}
     */

    public function getFormId()
    {
        return 'book-generate-form';
    }

    public function checkFormValid(array &$form, FormStateInterface $form_state) {
        if (!$form_state->hasAnyErrors()) {
            $form['fields-container']['#attributes'] = ['data-valid' => 'true'];
        } else {
            $form['fields-container']['#attributes'] = ['data-valid' => 'false'];
        }

        return $form['fields-container'];
    }

    public function validateFormCallback(array &$form, FormStateInterface $form_state)
    {
        return $form['fields-container'];
    }

    public function addmoreCallback(array &$form, FormStateInterface $form_state)
    {
        return $form['fields-container']['names_fieldset'];
    }

    public function addOne(array &$form, FormStateInterface $form_state)
    {
        $name_field = $form_state->get('num_names');
        $add_button = $name_field + 1;
        $form_state->set('num_names', $add_button);
        $form_state->setRebuild();
    }

    public function removeCallback(array &$form, FormStateInterface $form_state)
    {
        $name_field = $form_state->get('num_names');
        if ($name_field > 1) {
            $remove_button = $name_field - 1;
            $form_state->set('num_names', $remove_button);
        }
        $form_state->setRebuild();
    }

    public function checkEversion($form, FormStateInterface $form_state)
    {
        $form_state->set('electronic', true);

        $form_state->setRebuild();
        // return $form['electronic_fieldset'];
    }

    public function updateForm($form, FormStateInterface $form_state)
    {
        return $form['names_fieldset']['input'];
    }

    public function submitForm(array &$form, FormStateInterface $form_state)
    {
        $this->messenger()->addMessage('dada');


        // $form_state->setRebuild();
        // $title_val = $form_state->getValue('input-title');

        // if (\Drupal::currentUser()->isAuthenticated()) {
        //     $node = Node::create(['type' => 'article']);
        //     $node->setTitle($title_val !== '' ? $title_val : 'Список без заголовка');
        //     $node->body->value = $form_state->getValue('result_input');
        //     $node->body->format = 'full_html';
        //     $node->field_type->value = 'Книга';
        //     $node->setPublished(true);
        //     $node->save();

        //     $this->messenger()->addMessage('Книга успешно сохранина!');
        // } else {
        //     $response = new RedirectResponse('/user/login', 301);
        //     $response->send();
        // }
    }
}
