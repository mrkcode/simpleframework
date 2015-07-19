<?php

return [
    'global' => [
        'patterns' => [
            'id' => '\d{1,4}',
        ]
    ],
    
    'suffix' => '.html',
    
    'rules'  => [
        'home' => [
            'pattern'    => '',
            'controller' => 'Home'
        ],
        'article' => [
            'pattern'    => 'article/{id}',
            'controller' => 'Home',
            'action'     => 'viewArticle'
        ]
    ],
    
    'namespaces' => [
        'admin' => [
            'rules'  => [
                'home' => [
                    'pattern'    => '',
                    'controller' => 'Home'
                ],
                'login' => [
                    'pattern'    => 'login',
                    'controller' => 'Home',
                    'action'     => 'login'
                ],
                'editArticle' => [
                    'pattern'    => 'article/edit/{id}',
                    'controller' => 'Home',
                    'action'     => 'editArticle'
                ],
            ]
        ]
    ]
];