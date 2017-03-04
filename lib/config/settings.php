<?php

return array(
    'flex_discount' => array(
        'title'        => 'Гибкая скидка',
        'description'  => 'Гибкая скидка, с привязанным генератором купонов',
        'value'        => '',
        'control_type' => waHtmlControl::SELECT,
        'options_callback' => array('shopSpamPlugin', 'getDiscounts')
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
        'description'  => '{$name} - имя скидки<br/>'
                        . '{$description} - описание скидки<br/>'
                        . '{$discount_percentage} - скидка в процентах<br/>'
                        . '{$discount} - скидка в денежных единицах<br/>'
                        . '{$discount_currency} - валюта скидки<br/>'
                        . '{$affiliate_percentage} - бонусы в процентах<br/>'
                        . '{$affiliate} - скидка в бонусах<br/>'
                        . '{$coupon.code} - код активного купона',
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
