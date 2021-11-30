<?php

namespace Drupal\journal_generate_form\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\node\Entity\Node;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Drupal\Core\Ajax\AjaxResponse;
use Drupal\Core\Ajax\HtmlCommand;

class JournalForm extends FormBase
{
    public function getFormId()
    {
        return 'journal-generate-form';
    }

    function setDoiUrl($form, FormStateInterface $form_state)
    {

        $response = new AjaxResponse();

        $response->addCommand(new HtmlCommand('#form-journal-check-doi', [
            '#markup' => '<a target="_blank" href=' .
            'https://doi.org/' .
            $form_state->getValue('input-doi') . '>Проверить DOI</a>',
        ]));

        return $response;
    }

    function returnAjax($form, FormStateInterface $form_state)
    {
        $response = new AjaxResponse();

        $content = [];

        if ($form_state->getValue('select-material') == 'electronic') {
            $content[] = $form['input-url'];
            $content[] = $form['input-date'];
        } else {
            $content = null;
        }

        $response->addCommand(new HtmlCommand('#edit-fields-journal-e-version', $content));

        return $response;
    }

    public function buildForm(array $form, FormStateInterface $form_state)
    {

        $form['input-title'] = [
            '#type' => 'textfield',
            '#title' => 'Название списка (необязательно)',
            '#placeholder' => 'Толстой. Война и мир'
        ];

        $form['select-material'] = [
            '#type' => 'select',
            '#title' => 'Тип материала',
            '#options' => [
                'book' => 'Книжный',
                'electronic' => 'Электронный'
            ],
            '#ajax' => [
                'callback' => '::returnAjax',
                'wrapper' => 'edit-fields-journal-e-version',
                'method' => 'replace',
                'effect' => 'fade'
            ],
            '#id' => 'form-journal-material',
        ];

        $form['select-language'] = [
            '#type' => 'select',
            '#title' => 'Язык издания',
            '#options' => [
                'ru' => 'Русский',
                'en' => 'Английский'
            ],
            '#id' => 'form-journal-lang',
        ];

        $form['input-doi'] = [
            '#type' => 'textfield',
            '#title' => 'DOI (просто номер, без https...)',
            '#placeholder' => '12345',
            '#description' => 'Если есть, обязательно',
            '#required' => false,
            '#id' => 'form-journal-doi',
            '#ajax' => [
                'event' => 'input',
                'callback' => '::setDoiUrl',
                'wrapper' => 'form-journal-check-doi',
                'method' => 'replace',
            ],
            '#attributes' => [
                ' type' => 'number'
            ],
            '#size' => 50
        ];

        $form['check-doi'] = [
            '#type' => 'item',
            '#id' => 'form-journal-check-doi',
            '#markup' => '<a target="_blank" href=' . $form_state->getValue('input-doi') . '>Проверить DOI</a>',
        ];

        $form['input-author'] = [
            '#type' => 'textarea',
            '#title' => 'Автор(ы)',
            '#placeholder' => 'Антонов С. Ю., Антонова А. В.',
            '#required' => true,
            '#id' => 'form-journal-author',
            '#rows' => '2'
        ];

        $form['input-release'] = [
            '#type' => 'textarea',
            '#title' => 'Название статьи',
            '#placeholder' => 'К теореме Ченга. II',
            '#required' => true,
            '#id' => 'form-journal-release',
            '#rows' => '2'
        ];

        $form['input-name'] = [
            '#type' => 'textarea',
            '#title' => 'Название журнала',
            '#placeholder' => 'Дифферинциальные уравнения',
            '#required' => true,
            '#id' => 'form-journal-name',
            '#rows' => '2'
        ];

        $form['input-year'] = [
            '#type' => 'textfield',
            '#attributes' => [
                ' type' => 'number',
                ' min' => '1',
                ' max' => date('Y')
            ],
            '#title' => 'Год издания',
            '#placeholder' => '2017',
            '#required' => true,
            '#id' => 'form-journal-year'
        ];

        $form['input-tome-num'] = [
            '#type' => 'textfield',
            '#attributes' => [
                ' type' => 'number',
                ' min' => '1',
                ' max' => '99999'
            ],
            '#title' => 'Номер тома',
            '#placeholder' => '17',
            '#required' => true,
            '#id' => 'form-journal-tome-num'
        ];

        $form['input-volume'] = [
            '#type' => 'textfield',
            '#attributes' => [
                ' type' => 'number',
                ' min' => '1',
                ' max' => '99999'
            ],
            '#title' => 'Номер выпуска',
            '#placeholder' => '6',
            '#required' => true,
            '#id' => 'form-journal-volume'
        ];

        $form['input-pages-from'] = [
            '#type' => 'textfield',
            '#attributes' => [
                ' type' => 'number',
                ' min' => '1',
                ' max' => '99999'
            ],
            '#title' => 'С какой страницы',
            '#placeholder' => '127',
            '#required' => true,
            '#id' => 'form-journal-pages-from'
        ];

        $form['input-pages-to'] = [
            '#type' => 'textfield',
            '#attributes' => [
                ' type' => 'number',
                ' min' => '1',
                ' max' => '99999'
            ],
            '#title' => 'По какую страницу',
            '#placeholder' => '137',
            '#required' => true,
            '#id' => 'form-journal-pages-to'
        ];

        $form['input-other'] = [
            '#type' => 'textarea',
            '#title' => 'Долнительные данные',
            '#placeholder' => '',
            '#required' => true,
            '#id' => 'form-journal-other',
            '#rows' => '2'
        ];

        // hidden fiels //

        $form['input-url'] = [
            '#type' => 'textarea',
            '#title' => 'URL',
            '#placeholder' => 'https://example.com',
            '#required' => true,
            '#id' => 'form-journal-url',
            '#access' => $form_state->getValue('select-material') == 'electronic',
            '#rows' => 2
        ];

        $form['input-date'] = [
            '#type' => 'date',
            '#title' => 'Дата обращения',
            '#required' => true,
            '#id' => 'form-journal-date',
            '#access' => $form_state->getValue('select-material') === 'electronic',
            '#attributes' => [
                ' min' => '1900-01-01',
                ' max' => date('Y-m-d'),
            ]
        ];

        $form['eversion-conatiner'] = [
            '#type' => 'container',
            '#id' => 'edit-fields-journal-e-version'
        ];








        $form['display_result'] = [
            '#type' => 'button',
            '#value' => 'Посмотреть результат',
            '#id' => 'display-journal-stroke-form-btn'
        ];

        $form['result_text'] = [
            '#type' => 'item',
            '#title' => 'Результат:',
            '#markup' => '<div id="result-journal-text"></div>',
        ];

        $form['result_input'] = [
            '#type' => 'textarea',
            '#title' => 'Редактировать:',
            '#id' => 'form-journal-result-input',
            '#rows' => '2',
            '#description' => 'В "Мои списки" сохранится текст из поля "Редактировать"',
            '#required' => true
        ];

        $form['submit'] = [
            '#type' => 'submit',
            '#value' => \Drupal::currentUser()->isAuthenticated() ? 'Сохранить список' : 'Войдите чтобы сохранить',
            '#button_type' => 'primary',
        ];

        $form['#attached']['library'][] = 'journal_generate_form/journal_generate_form';

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
            $node->field_type->value = 'Журнал';
            $node->setPublished(true);
            $node->save();

            $this->messenger()->addMessage('Журнал успешно сохранён!');
        } else {
            $response = new RedirectResponse('/user/login', 301);
            $response->send();
        }
    }
}
