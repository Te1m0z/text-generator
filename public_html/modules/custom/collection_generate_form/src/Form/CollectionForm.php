<?php

namespace Drupal\collection_generate_form\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\node\Entity\Node;


class CollectionForm extends FormBase
{
    public function getFormId()
    {
        return 'collection-generate-form';
    }

    public function buildForm(array $form, FormStateInterface $form_state)
    {

        $form['container'] = [
            '#type' => 'container',
            '#id' => 'inputs-container-wrapper'
        ];

        $form['container']['title-article'] = [
            '#type' => 'textfield',
            '#title' => 'Название списка (необязательно)',
            '#placeholder' => 'Это статья из журнала',
            '#access' => \Drupal::currentUser()->isAuthenticated(),
        ];

        $form['container']['eversion'] = [
            '#type' => 'submit',
            '#value' => 'У меня есть электронная версия',
            '#submit' => ['::updateEversion'],
            '#limit_validation_errors' => [],
            '#disabled' => boolval($form_state->get('electronic'))
        ];

        $form['container']['language'] = [
            '#type' => 'select',
            '#title' => 'Язык издания',
            '#options' => [
                'ru' => 'Русский',
                'en' => 'Английский'
            ],
            '#id' => 'form-collection-lang',
        ];

        $form['container']['doi'] = [
            '#type' => 'textfield',
            '#title' => 'DOI (просто номер, без https...)',
            '#placeholder' => '12345',
            '#description' => 'Если есть, обязательно',
            '#id' => 'form-collection-doi',
            '#attributes' => [
                ' type' => 'number'
            ]
        ];

        $form['container']['check-doi'] = [
            '#type' => 'item',
            '#markup' => '<a id="form-collection-check-doi" target="_blank" href="https://doi.org/">Проверить DOI</a>',
        ];

        $num_names = $form_state->get('num_names');

        if ($num_names === NULL) {
            $form_state->set('num_names', 1);
        }

        $form['#tree'] = TRUE;

        $form['container']['names_fieldset'] = [
            '#type' => 'fieldset',
            '#prefix' => '<div id="names-fieldset-wrapper">',
            '#suffix' => '</div>',
            '#title' => 'Автор(ы)'
        ];

        for ($i = 1; $i <= $form_state->get('num_names'); $i++) {

            $form['container']['names_fieldset']['author_set_' . $i] = [
                '#type' => 'fieldset',
                '#title' => 'Автор ' . $i,
                '#attributes' => [' class' => 'author_set_item']
            ];

            $form['container']['names_fieldset']['author_set_' . $i]['author_first_name_' . $i] = [
                '#type' => 'textfield',
                '#title' => 'Имя',
                '#required' => TRUE,
                '#id' => 'author_first_name_' . $i,
            ];

            $form['container']['names_fieldset']['author_set_' . $i]['author_last_name_' . $i] = [
                '#type' => 'textfield',
                '#title' => 'Фамилия',
                '#required' => TRUE,
                '#id' => 'author_last_name_' . $i,
            ];

            $form['container']['names_fieldset']['author_set_' . $i]['author_middle_name_' . $i] = [
                '#type' => 'textfield',
                '#title' => 'Отчество',
                '#required' => FALSE,
                '#id' => 'author_middle_name_' . $i,
            ];
        }

        $form['container']['names_fieldset']['actions'] = [
            '#type' => 'actions',
        ];

        $form['container']['names_fieldset']['actions']['add_name'] = [
            '#type' => 'submit',
            '#value' => 'Добавить автора',
            '#submit' => ['::addAuthorCallback'],
            '#limit_validation_errors' => [
                [
                    'container',
                    'names_fieldset'
                ]
            ]
        ];

        if ($form_state->get('num_names') > 1) {
            $form['container']['names_fieldset']['actions']['remove_name'] = [
                '#type' => 'submit',
                '#value' => 'Remove one',
                '#submit' => ['::removeAuthorCallback'],
                '#limit_validation_errors' => []
            ];
        }

        $form['container']['release'] = [
            '#type' => 'textarea',
            '#title' => 'Название статьи',
            '#placeholder' => 'Ортогональные системы сдвигов в поле p-адических чисел',
            '#required' => true,
            '#id' => 'form-collection-release',
            '#rows' => '2'
        ];

        $form['container']['name'] = [
            '#type' => 'textarea',
            '#title' => 'Название сборника',
            '#required' => true,
            '#id' => 'form-collection-name',
            '#rows' => '2'
        ];

        $form['container']['tome-num'] = [
            '#type' => 'textfield',
            '#title' => 'Номер тома',
            // '#required' => true,
            '#id' => 'form-collection-tome-num',
            '#rows' => '2',
            '#attributes' => [
                ' type' => 'number',
                ' min' => '1',
                ' max' => '999999'
            ],
        ];

        $form['container']['tome-name'] = [
            '#type' => 'textfield',
            '#title' => 'Название тома',
            // '#required' => true,
            '#id' => 'form-collection-tome-name'
        ];

        $form['container']['issue'] = [
            '#type' => 'textfield',
            '#title' => 'Номер выпуска',
            '#required' => true,
            '#id' => 'form-collection-issue',
            '#attributes' => [
                ' type' => 'number',
            ]
        ];

        $form['container']['place'] = [
            '#type' => 'textfield',
            '#title' => 'Место издания',
            '#required' => true,
            '#id' => 'form-collection-place',
            '#placeholder' => 'Москва'
        ];

        $form['container']['publish'] = [
            '#type' => 'textfield',
            '#title' => 'Издательство',
            '#required' => true,
            '#id' => 'form-collection-publish',
            '#placeholder' => 'Наука'
        ];

        $form['container']['pages-from'] = [
            '#type' => 'textfield',
            '#attributes' => [
                ' type' => 'number',
                ' min' => '1',
                ' max' => '99999'
            ],
            '#title' => 'С какой страницы',
            '#placeholder' => '92',
            '#required' => true,
            '#id' => 'form-collection-pages-from'
        ];

        $form['container']['pages-to'] = [
            '#type' => 'textfield',
            '#attributes' => [
                ' type' => 'number',
                ' min' => '1',
                ' max' => '99999'
            ],
            '#title' => 'По какую страницу',
            '#placeholder' => '137',
            '#required' => true,
            '#id' => 'form-collection-pages-to'
        ];

        $form['container']['other'] = [
            '#type' => 'textarea',
            '#title' => 'Долнительные данные',
            '#placeholder' => '',
            '#id' => 'form-collection-other',
            '#rows' => '2'
        ];

        // hidden fiels //

        $isEversion = $form_state->get('electronic');

        if ($isEversion === NULL) {
            $form_state->set('electronic', FALSE);
        }

        $form['container']['eversion_container'] = [
            '#type' => 'container',
            '#title' => 'Электронная версия',
            '#prefix' => '<div id="electronic-fieldset-wrapper">',
            '#suffix' => '</div>',
            '#access' => $form_state->get('electronic')
        ];

        $form['container']['eversion_container']['url'] = [
            '#type' => 'textarea',
            '#title' => 'URL',
            '#placeholder' => 'https://example.com',
            '#required' => true,
            '#id' => 'form-collection-url',
            '#rows' => 2
        ];

        $form['container']['eversion_container']['date'] = [
            '#type' => 'date',
            '#title' => 'Дата обращения',
            '#required' => true,
            '#id' => 'form-collection-date',
            '#default_value' => date('Y-m-d'),
            '#attributes' => [
                ' min' => '1900-01-01',
                ' max' => date('Y-m-d'),
            ],
        ];

        $form['container']['eversion_container']['remove_btn'] = [
            '#type' => 'submit',
            '#value' => 'удалить эл. версию',
            '#submit' => ['::updateEversion'],
            '#limit_validation_errors' => []
        ];



        $form['container']['actions'] = [
            '#type' => 'actions'
        ];

        $form['container']['actions']['result'] = [
            '#type' => 'submit',
            '#value' => 'Посмотреть результат',
            '#submit' => ['::allowDisplayStroke']
        ];

        $form['container']['result_text'] = [
            '#type' => 'item',
            '#title' => 'Результат:',
            '#markup' => '<div id="result-collection-text"></div>',
        ];

        $form['hidden_result_stroke'] = [
            '#type' => 'textfield',
            '#id' => 'result-collection-text-hidden',
            '#attributes' => [
                ' hidden' => 'true'
            ]
        ];


        $form['#attached']['library'][] = 'collection_generate_form/main';
        $form['#attached']['library'][] = 'collection_generate_form/doi';
        $form['#attached']['library'][] = $form_state->get('lib');


        $form['submit'] = [
            '#type' => 'submit',
            '#value' => 'Сохранить список',
            '#suffix' => 'Чтобы сохранить список, сначала нажмите "Посмотреть результат"',
            '#disabled' => boolval(!$form_state->get('is_submit_btn_enabled'))
        ];

        $form['note'] = [
            '#type' => 'item',
            '#markup' => 'Нужно сначала <a href="/user/login">Войти</a>',
            '#access' => boolval($form_state->get('is_display_note'))
        ];

        return $form;
    }

    public function addAuthorCallback(array &$form, FormStateInterface $form_state)
    {
        $name_field = $form_state->get('num_names');
        $form_state->set('num_names', $name_field + 1);
        return $form_state->setRebuild(TRUE);
    }

    public function removeAuthorCallback(array &$form, FormStateInterface $form_state)
    {
        $name_field = $form_state->get('num_names');
        if ($form_state->get('num_names') > 1) {
            $form_state->set('num_names', $name_field - 1);
        }
        $form_state->setRebuild(TRUE);
    }

    public function updateEversion($form, FormStateInterface $form_state)
    {
        $old = $form_state->get('electronic');
        $form_state->set('electronic', !$old);
        $form_state->setRebuild(TRUE);
    }

    public function allowDisplayStroke($form, FormStateInterface $form_state)
    {
        $form_state->set('lib', 'collection_generate_form/generate_str');
        $form_state->set('is_submit_btn_enabled', TRUE);
        $form_state->setRebuild(TRUE);
    }

    public function submitForm(array &$form, FormStateInterface $form_state)
    {

        if (\Drupal::currentUser()->isAuthenticated()) {
            $node = Node::create(['type' => 'article']);
            $node->setTitle(($form_state->getValue(['container', 'title-article']) == NULL) ? 'Список без заголовка' : $form_state->getValue(['container', 'title-article']));
            $node->body->value = $form_state->getValue('hidden_result_stroke');
            $node->body->format = 'basic_html';
            $node->field_type->value = 'Статья в сборнике';
            $node->setPublished(true);
            $node->save();
            $this->messenger()->addMessage('Статья в сборнике успешно сохранина!');
        } else {
            $form_state->set('is_display_note', TRUE);
        }

        $form_state->setRebuild();
    }
}
