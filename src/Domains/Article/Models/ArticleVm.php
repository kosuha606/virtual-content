<?php

namespace kosuha606\VirtualContent\Domains\Article\Models;

use kosuha606\VirtualAdmin\Domains\Multilang\MultilangTrait;
use kosuha606\VirtualAdmin\Domains\Search\SearchableInterface;
use kosuha606\VirtualAdmin\Domains\Search\SearchIndexDto;
use kosuha606\VirtualAdmin\Domains\Search\SearchObserver;
use kosuha606\VirtualAdmin\Domains\Seo\SeoModelInterface;
use kosuha606\VirtualAdmin\Domains\Seo\SeoModelTrait;
use kosuha606\VirtualAdmin\Domains\Seo\SeoUrlObserver;
use kosuha606\VirtualAdmin\Domains\Version\VersionObserver;
use kosuha606\VirtualModel\VirtualModel;
use kosuha606\VirtualModelHelppack\Traits\ObserveVMTrait;

/**
 *
 * @property  $id
 * @property  $title
 * @property  $photo
 * @property  $slug
 * @property  $content
 * @property  $created_at
 *
 */
class ArticleVm extends VirtualModel
    implements SearchableInterface,
    SeoModelInterface
{
    use MultilangTrait;

    use ObserveVMTrait;

    use SeoModelTrait;

    public static function observers()
    {
        return [
            VersionObserver::class,
            SearchObserver::class,
            SeoUrlObserver::class,
        ];
    }

    public function buildUrl()
    {
        return '/news/'.$this->id.'_'.$this->slug;
    }

    /**
     * @return SearchIndexDto
     * @throws \Exception
     */
    public function buildIndex(): SearchIndexDto
    {
        return new SearchIndexDto(1, [
            [
                'field' => 'title',
                'value' => $this->title,
                'type' => 'text',
            ],
            [
                'field' => 'url',
                'value' => $this->getUrl(),
                'type' => 'keyword',
            ],
            [
                'field' => 'content',
                'value' => $this->content,
                'type' => 'text',
            ],
        ]);
    }

    public function attributes(): array
    {
        return [
            'id',
            'title',
            'photo',
            'slug',
            'content',
            'created_at',
        ];
    }
}