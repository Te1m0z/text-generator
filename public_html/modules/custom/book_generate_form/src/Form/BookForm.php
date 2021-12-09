<?php

namespace Drupal\book_generate_form\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;

class BookForm extends FormBase
{
  public function getFormId()
  {
    return 'book-generate-form';
  }

  public function buildForm(array $form, FormStateInterface $form_state)
  {

    $form['container'] = [
      '#type' => 'container',
      '#id' => 'inputs-container-wrapper'
    ];

    $form['container']['title'] = [
      '#type' => 'textfield',
      '#title' => 'Название списка (необязательно)',
      '#placeholder' => 'Толстой. Война и мир',
      '#access' => TRUE
    ];

    $form['container']['eversion'] = [
      '#type' => 'submit',
      '#value' => 'У меня есть электронная версия',
      '#submit' => ['::updateEversion'],
      '#limit_validation_errors' => [],
      '#disabled' => $form_state->get('electronic')
    ];

    $form['container']['language'] = [
      '#type' => 'select',
      '#title' => 'Язык издания',
      '#options' => [
        'ru' => 'Русский',
        'en' => 'Английский'
      ],
      '#id' => 'form-book-lang',
    ];

    $form['container']['doi'] = [
      '#type' => 'textfield',
      '#title' => 'DOI (просто номер, без https...)',
      '#placeholder' => '12345',
      '#description' => 'Если есть, обязательно',
      '#id' => 'form-book-doi',
      '#attributes' => [
        ' type' => 'number'
      ]
    ];

    $form['container']['check-doi'] = [
      '#type' => 'item',
      '#markup' => '<a id="form-book-check-doi" target="_blank" href="https://doi.org/">Проверить DOI</a>',
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

    $form['container']['name'] = [
      '#type' => 'textarea',
      '#title' => 'Название книги',
      '#placeholder' => 'Война и мир',
      '#required' => true,
      '#id' => 'form-book-name',
      '#rows' => '2'
    ];

    $form['container']['tome-num'] = [
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

    $form['container']['tome-max'] = [
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

    $form['container']['tome-name'] = [
      '#type' => 'textfield',
      '#title' => 'Название тома',
      '#placeholder' => 'Том первый',
      '#required' => false,
      '#id' => 'form-book-tome-name'
    ];

    $form['container']['city'] = [
      '#type' => 'textfield',
      '#title' => 'Место издания (город)',
      '#placeholder' => 'Саратов',
      '#required' => true,
      '#id' => 'form-book-city'
    ];

    $form['container']['publish'] = [
      '#type' => 'textfield',
      '#title' => 'Издательство',
      '#placeholder' => 'Наука',
      '#required' => true,
      '#id' => 'form-book-publish'
    ];

    $form['container']['year'] = [
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

    $form['container']['pages'] = [
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

    $form['container']['other'] = [
      '#type' => 'textarea',
      '#title' => 'Прочее',
      '#id' => 'form-book-other',
      '#rows' => 2
    ];

    $form['container']['release'] = [
      '#type' => 'textfield',
      '#title' => 'Серия',
      '#id' => 'form-book-release',
    ];

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
      '#id' => 'form-book-url',
      '#rows' => 2
    ];

    $form['container']['eversion_container']['date'] = [
      '#type' => 'date',
      '#title' => 'Дата обращения',
      '#required' => true,
      '#id' => 'form-book-date',
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
      '#markup' => '<div id="result-book-text"></div>',
    ];

    $form['container']['result_input'] = [
      '#type' => 'textarea',
      '#title' => 'Редактировать:',
      '#id' => 'form-book-result-input',
      '#rows' => '2',
    ];

    $form['#attached']['library'][] = $form_state->get('lib');
    $form['#attached']['library'][] = 'book_generate_form/doi';

    $form['submit'] = [
      '#type' => 'submit',
      '#value' => 'Сохранить список',
    ];

    return $form;
  }

  public function addAuthorCallback(array &$form, FormStateInterface $form_state)
  {
    $name_field = $form_state->get('num_names');
    $form_state->set('num_names', $name_field + 1);
    $form_state->setRebuild(TRUE);
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
    $form_state->set('lib', 'book_generate_form/generate_str');
    $form_state->setRebuild(TRUE);
  }

  public function submitForm(array &$form, FormStateInterface $form_state)
  {
    $form_state->setRebuild(TRUE);
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
