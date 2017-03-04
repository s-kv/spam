<?php

return array(
    'procent' => array(
        'title'        => 'Скидка, %',
        'description'  => 'Процент скидки',
        'value'        => '',
        'control_type' => waHtmlControl::INPUT,
    ),
    'limit' => array(
        'title'        => 'Количество покупок со скидкой',
        'description'  => 'Количество покупок со скидкой',
        'value'        => '',
        'control_type' => waHtmlControl::INPUT,
    ),
    'expire_days' => array(
        'title'        => 'Количество дней действия',
        'description'  => 'Срок действия',
        'value'        => '',
        'control_type' => waHtmlControl::INPUT,
    ),
    'from' => array(
        'title'        => 'Отправитель письма',
        'description'  => 'e-mail отправителя письма',
        'value'        => '',
        'control_type' => waHtmlControl::INPUT,
    ),    
    'subject' => array(
        'title'        => 'Тема письма',
        'description'  => 'Тема электронного письма',
        'value'        => '',
        'control_type' => waHtmlControl::INPUT,
    ),    
    'letter' => array(
        'title'        => 'Шаблон письма HTML',
        'description'  => 'Письмо в формате HTML',
        'value'        => '',
        'control_type' => waHtmlControl::TEXTAREA,
    ),
    'is_test' => array(
        'title'        => 'Тестовый режим',
        'description'  => 'При включении письма будут отправляться на специальный ящик',
        'value'        => '',
        'control_type' => waHtmlControl::CHECKBOX,
    ),    
    'to' => array(
        'title'        => 'Получатель письма',
        'description'  => 'Тестовый получатель письма - для проведения тестирования',
        'value'        => '',
        'control_type' => waHtmlControl::INPUT,
    )   
);
