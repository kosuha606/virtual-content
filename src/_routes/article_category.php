<?php

use kosuha606\VirtualAdmin\Domains\User\UserVm;
use kosuha606\VirtualAdmin\Domains\Comment\CommentVm;
use kosuha606\VirtualAdmin\Domains\Version\VersionVm;
use kosuha606\VirtualAdmin\Form\SecondaryFormBuilder;
use kosuha606\VirtualAdmin\Form\SecondaryFormService;
use kosuha606\VirtualAdmin\Services\StringService;
use kosuha606\VirtualAdmin\Structures\DetailComponents;
use kosuha606\VirtualAdmin\Structures\ListComponents;
use kosuha606\VirtualAdmin\Structures\SecondaryForms;
use kosuha606\VirtualContent\Domains\Article\Models\ArticleCategoryVm;
use kosuha606\VirtualContent\Domains\Article\Models\ArticleVm;
use kosuha606\VirtualModel\VirtualModel;
use kosuha606\VirtualModel\VirtualModelEntity;
use kosuha606\VirtualModelHelppack\ServiceManager;
use kosuha606\VirtualContent\Domains\Page\Models\SeoPageVm;

$baseEntity = 'article_category';
$stringService = ServiceManager::getInstance()->get(\kosuha606\VirtualAdmin\Services\StringService::class);
$baseEntityCamel = $stringService->transliterate($baseEntity);
$entityClass = ArticleCategoryVm::class;
$listTitle = 'Категории';
$detailTitle = 'Категория';

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
                        'orderBy' => [
                            'field' => 'id',
                            'direction' => 'desc',
                        ],
                        'action' => 'actionList'
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
                            'field' => 'name',
                            'component' => ListComponents::STRING_CELL,
                            'label' => 'Название',
                            'props' => [
                                'link' => 1,
                            ]
                        ],
                        [
                            'field' => 'parent_id',
                            'component' => ListComponents::STRING_CELL,
                            'label' => 'Родитель',
                            'value' => function($model) {
                                $parent = ArticleCategoryVm::one(['where' => [['=', 'id', $model['parent_id']]]]);

                                return $parent->id
                                    ? $parent->name . ' #' . $parent->id
                                    : '--';
                            }
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
                        return ($detailTitle.' '.$model->name ?: $detailTitle );
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
                            DetailComponents::MULTILANG_FIELD(
                                DetailComponents::INPUT_FIELD,
                                'name',
                                'Название',
                                $model->name,
                                $model
                            ),
                            [
                                'field' => 'slug',
                                'component' => DetailComponents::INPUT_FIELD,
                                'label' => 'Ссылка',
                                'value' => $model->slug,
                            ],
                            [
                                'field' => 'parent_id',
                                'label' => 'Родитель',
                                'component' => DetailComponents::SELECT_FIELD,
                                'value' => $model->parent_id,
                                'props' => [
                                    'items' => $stringService->map(VirtualModelEntity::allToArray(ArticleCategoryVm::many(['where' => [[
                                        '<>', 'id', $model->id
                                    ]]])), 'id', 'name')
                                ]
                            ],
                        ];
                    },
                ]
            ],
        ]
    ]
];