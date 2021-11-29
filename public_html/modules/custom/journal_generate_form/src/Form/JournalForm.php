<?php

namespace Drupal\journal_generate_form\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\node\Entity\Node;
use Symfony\Component\HttpFoundation\RedirectResponse;

class JournalForm extends FormBase
{
    public function getFormId()
    {
        return 'journal-generate-form';
    }

    public function myAjaxCallback(array $form, FormStateInterface $form_state)
    {
        $form['output']['#value'] = '11';
        $form['output']['#type'] = 'hidden';

        return $form['output'];
    }

    public function buildForm(array $form, FormStateInterface $form_state)
    {

        $form['title'] = [
            '#type' => 'textfield',
            '#title' => 'Название списка (необязательно)',
            '#placeholder' => 'Толстой. Война и мир'
        ];

        $form['language'] = [
            '#type' => 'select',
            '#title' => 'Язык издания',
            '#options' => [
                'ru' => 'Русский',
                'en' => 'Английский'
            ],
            '#id' => 'form-journal-lang'
        ];

        $form['author'] = [
            '#type' => 'textarea',
            '#title' => 'Автор(ы)',
            '#placeholder' => 'Антонов С. Ю., Антонова А. В.',
            '#required' => true,
            '#id' => 'form-journal-author',
            '#rows' => '2'
        ];

        $form['release'] = [
            '#type' => 'textarea',
            '#title' => 'Название статьи',
            '#placeholder' => 'К теореме Ченга. II',
            '#required' => true,
            '#id' => 'form-journal-release',
            '#rows' => '2'
        ];

        $form['name'] = [
            '#type' => 'textarea',
            '#title' => 'Название журнала',
            '#placeholder' => 'Дифферинциальные уравнения',
            '#required' => true,
            '#id' => 'form-journal-name',
            '#rows' => '2'
        ];

        $form['year'] = [
            '#type' => 'textfield',
            '#attributes' => [
                ' type' => 'number',
            ],
            '#title' => 'Год издания',
            '#placeholder' => '2017',
            '#required' => false,
            '#id' => 'form-journal-year'
        ];

        $form['tome-num'] = [
            '#type' => 'textfield',
            '#attributes' => [
                ' type' => 'number',
            ],
            '#title' => 'Номер тома',
            '#placeholder' => '17',
            '#required' => false,
            '#id' => 'form-journal-tome-num'
        ];

        $form['volume'] = [
            '#type' => 'textfield',
            '#attributes' => [
                ' type' => 'number',
            ],
            '#title' => 'Номер выпуска',
            '#placeholder' => '6',
            '#required' => false,
            '#id' => 'form-journal-volume'
        ];

        $form['pages-from'] = [
            '#type' => 'textfield',
            '#attributes' => [
                ' type' => 'number'
            ],
            '#title' => 'С какой страницы',
            '#placeholder' => '127',
            '#required' => false,
            '#id' => 'form-journal-pages-from'
        ];

        $form['pages-to'] = [
            '#type' => 'textfield',
            '#attributes' => [
                ' type' => 'number'
            ],
            '#title' => 'По какую страницу',
            '#placeholder' => '137',
            '#required' => false,
            '#id' => 'form-journal-pages-to'
        ];

        $form['other'] = [
            '#type' => 'textarea',
            '#title' => 'Долнительные данные',
            '#placeholder' => '',
            '#required' => false,
            '#id' => 'form-journal-other',
            '#rows' => '2'
        ];



        $form['display_result'] = [
            '#type' => 'submit',
            '#value' => 'Посмотреть результат',
            '#id' => 'display-journal-stroke-form-btn',
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

        $title_val = $form_state->getValue('title');

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
