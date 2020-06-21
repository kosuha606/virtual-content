<?php

use kosuha606\VirtualAdmin\Services\StringService;
use kosuha606\VirtualAdmin\Structures\DetailComponents;
use kosuha606\VirtualAdmin\Structures\ListComponents;
use kosuha606\VirtualContent\Domains\Article\Models\ArticleVm;
use kosuha606\VirtualContent\Domains\Article\Models\SeoArticleVm;
use kosuha606\VirtualContent\Domains\Text\Models\TextVm;
use kosuha606\VirtualModelHelppack\ServiceManager;

$baseEntity = 'text';
$stringService = ServiceManager::getInstance()->get(\kosuha606\VirtualAdmin\Services\StringService::class);
$baseEntityCamel = $stringService->transliterate($baseEntity);
$entityClass = TextVm::class;
$listTitle = 'Тексты';
$detailTitle = 'Текст';

return [
    'routes' => [
        $baseEntity => [
            'list' => [
                'menu' => [
                    'name' => $baseEntity.'_list',
                    'label' => $listTitle,
                    'url' => '/admin/'.$baseEntity.'/list',
                    'parent' => 'content',
                ],
                'handler' => [
                    'type' => 'vue',
                    'h1' => $listTitle,
                    'entity' => $baseEntity,
                    'component' => 'list',
                    'ad_url' => '/admin/'.$baseEntity.'/detail',
                    'crud' => [
                        'model' => $entityClass,
                        'action' => 'actionList',
                        'orderBy' => [
                            'field' => 'id',
                            'direction' => 'desc',
                        ],
                    ],
                    'filter_config' => [
                        [
                            'field' => 'id',
                            'component' => DetailComponents::INPUT_FIELD,
                            'label' => 'ID',
                        ],
                    ],
                    'list_config' => [
                        [
                            'field' => 'id',
                            'component' => ListComponents::STRING_CELL,
                            'label' => 'ID'
                        ],
                        [
                            'field' => 'description',
                            'component' => ListComponents::STRING_CELL,
                            'label' => 'Описание',
                            'props' => [
                                'link' => 1,
                            ]
                        ],
                        [
                            'field' => 'code',
                            'component' => ListComponents::STRING_CELL,
                            'label' => 'Код',
                        ],
                    ]
                ]
            ],
            'detail' => [
                'menu' => [
                    'name' => 'ad_category_detail',
                    'label' => 'Категория',
                    'url' => '/admin/ad_category/detail',
                    'visible' => false,
                ],
                'handler' => [
                    'type' => 'vue',
                    'h1' => function($model) use($detailTitle) {
                        return ($detailTitle.' '.$model->description ?: $detailTitle );
                    },
                    'entity' => $baseEntity,
                    'component' => 'detail',
                    'crud' => [
                        'model' => $entityClass,
                        'action' => 'actionView',
                    ],
                    'config' => function ($model) {
                        $stringService = ServiceManager::getInstance()->get(StringService::class);

                        return [
                            [
                                'field' => 'description',
                                'component' => DetailComponents::INPUT_FIELD,
                                'label' => 'Описание',
                                'value' => $model->description,
                            ],
                            [
                                'field' => 'code',
                                'component' => DetailComponents::INPUT_FIELD,
                                'label' => 'Код',
                                'value' => $model->code,
                            ],
                            [
                                'field' => 'content',
                                'component' => DetailComponents::REDACTOR_FIELD,
                                'label' => 'Содержимое',
                                'value' => $model->content,
                            ],
                        ];
                    },
                ]
            ],
        ]
    ]
];