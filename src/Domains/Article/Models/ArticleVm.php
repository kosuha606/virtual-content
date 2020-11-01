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
use kosuha606\VirtualModel\VirtualModelEntity;
use kosuha606\VirtualModelHelppack\Traits\ObserveVMTrait;

/**
 * @property  $id
 * @property  $title
 * @property  $photo
 * @property  $slug
 * @property  $content
 * @property  $category_id
 * @property  $created_at
 * @property  $updated_at
 */
class ArticleVm extends VirtualModelEntity
    implements SearchableInterface,
    SeoModelInterface
{
    use MultilangTrait;

    use ObserveVMTrait;

    use SeoModelTrait;

    /**
     * @return array
     */
    public static function observers()
    {
        return [
            VersionObserver::class,
            SearchObserver::class,
            SeoUrlObserver::class,
        ];
    }

    /**
     * @return string
     */
    public function buildUrl()
    {
        $category = ArticleCategoryVm::one(['where' => [['=', 'id', $this->category_id]]]);

        return $category->buildUrl().'/'.$this->id.'-'.$this->slug;
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

    /**
     * @return array
     */
    public function attributes(): array
    {
        return [
            'id',
            'title',
            'category_id',
            'photo',
            'slug',
            'content',
            'created_at',
        ];
    }
}
