<?php

use kosuha606\VirtualAdmin\Domains\User\UserVm;
use kosuha606\VirtualAdmin\Domains\Comment\CommentVm;
use kosuha606\VirtualAdmin\Domains\Seo\SeoPageVm;
use kosuha606\VirtualAdmin\Form\SecondaryFormBuilder;
use kosuha606\VirtualAdmin\Form\SecondaryFormService;
use kosuha606\VirtualAdmin\Services\StringService;
use kosuha606\VirtualAdmin\Structures\DetailComponents;
use kosuha606\VirtualAdmin\Structures\ListComponents;
use kosuha606\VirtualAdmin\Structures\SecondaryForms;
use kosuha606\VirtualContent\Domains\Page\Models\PageVm;
use kosuha606\VirtualModel\VirtualModelEntity;
use kosuha606\VirtualModelHelppack\ServiceManager;

$baseEntity = 'page';
$stringService = ServiceManager::getInstance()->get(\kosuha606\VirtualAdmin\Services\StringService::class);
$baseEntityCamel = $stringService->transliterate($baseEntity);
$entityClass = PageVm::class;
$listTitle = 'Страницы';
$detailTitle = 'Страница';

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
                            'field' => 'title',
                            'component' => ListComponents::STRING_CELL,
                            'label' => 'Заголовок',
                            'props' => [
                                'link' => 1,
                            ]
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
                        return ($detailTitle.' '.$model->title ?: $detailTitle );
                    },
                    'entity' => $baseEntity,
                    'component' => 'detail',
                    'crud' => [
                        'model' => $entityClass,
                        'action' => 'actionView',
                    ],

                    'additional_config' => function($model) {
                        $secondaryService = ServiceManager::getInstance()->get(SecondaryFormService::class);

                        $configComment = $secondaryService
                            ->buildForm()
                            ->setMasterModel($model)
                            ->setMasterModelId($model->id.','.get_class($model))
                            ->setMasterModelField('model_id,model_class')
                            ->setRelationType(SecondaryFormBuilder::ONE_TO_MANY)
                            ->setRelationClass(CommentVm::class)
                            ->setTabName('Комментарии')
                            ->setRelationEntities(CommentVm::many(['where' => [
                                ['=', 'model_id', $model->id],
                                ['=', 'model_class', get_class($model)],
                            ]]))
                            ->setConfig(function ($inModel) use ($model) {
                                $stringService = ServiceManager::getInstance()->get(StringService::class);

                                return [
                                    [
                                        'field' => 'model_id',
                                        'label' => 'Продукт',
                                        'component' => DetailComponents::HIDDEN_FIELD,
                                        'value' => $model->id,
                                    ],
                                    [
                                        'field' => 'model_class',
                                        'label' => 'Продукт',
                                        'component' => DetailComponents::HIDDEN_FIELD,
                                        'value' => get_class($model),
                                    ],
                                    [
                                        'field' => 'user_id',
                                        'label' => 'Пользователь',
                                        'component' => DetailComponents::SELECT_FIELD,
                                        'value' => $inModel->user_id,
                                        'props' => [
                                            'items' => $stringService->map(VirtualModel::allToArray(UserVm::many(['where' => [['all']]])), 'id', 'email')
                                        ]
                                    ],
                                    [
                                        'field' => 'content',
                                        'label' => 'Сообщение',
                                        'component' => DetailComponents::TEXTAREA_FIELD,
                                        'value' => $inModel->content,
                                    ],
                                ];
                            })
                            ->getConfig()
                        ;

                        return [
                            SecondaryForms::SEO_PAGE($model),
                            $configComment,
                        ];
                    },
                    'config' => function ($model) {
                        $stringService = ServiceManager::getInstance()->get(StringService::class);

                        return [
                            DetailComponents::MULTILANG_FIELD(
                                DetailComponents::INPUT_FIELD,
                                'title',
                                'Заголовок',
                                $model->title,
                                $model
                            ),
                            [
                                'field' => 'slug',
                                'component' => DetailComponents::INPUT_FIELD,
                                'label' => 'Ссылка',
                                'value' => $model->slug,
                            ],
                            DetailComponents::MULTILANG_FIELD(
                                DetailComponents::REDACTOR_FIELD,
                                'content',
                                'Содержимое',
                                $model->content,
                                $model
                            ),
                        ];
                    },
                ]
            ],
        ]
    ]
];