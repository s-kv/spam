<?php

return array(
    'shop_spam' => array(
        'id' => array('int', 11, 'null' => 0, 'autoincrement' => 1),
        'email' => array('varchar', 200, 'null' => 0, 'default' => ''),
        'coupon_code' => array('varchar', 50, 'null' => 0, 'default' => ''),
        ':keys' => array(
            'PRIMARY' => 'id',
        ),
        ':options' => array('engine' => 'MyISAM')
    )
);
