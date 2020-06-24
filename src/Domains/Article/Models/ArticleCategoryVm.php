<?php

namespace kosuha606\VirtualContent\Domains\Article\Models;

use kosuha606\VirtualAdmin\Domains\Multilang\MultilangTrait;
use kosuha606\VirtualAdmin\Domains\Seo\SeoModelInterface;
use kosuha606\VirtualAdmin\Domains\Seo\SeoModelTrait;
use kosuha606\VirtualAdmin\Domains\Seo\SeoUrlObserver;
use kosuha606\VirtualModel\VirtualModel;
use kosuha606\VirtualModelHelppack\Traits\ObserveVMTrait;

/**
 *
 * @property $id,
 * @property $name,
 * @property $slug
 * @property $parent_id
 *
 */
class ArticleCategoryVm extends VirtualModel implements SeoModelInterface
{
    use MultilangTrait;

    use ObserveVMTrait;

    use SeoModelTrait;

    public static function observers()
    {
        return [
            SeoUrlObserver::class,
        ];
    }

    public function buildUrl()
    {
        /** @var ArticleCategoryVm[] $parents */
        $parents = array_reverse($this->parents());
        $parentsSlug = '/';

        if ($parent = $this->parent()) {
            $parentsSlug = $parent->getUrl().'/';
        }

        return $parentsSlug.$this->id.'-'.$this->slug;
    }

    public function attributes(): array
    {
        return [
            'id',
            'name',
            'slug',
            'parent_id',
        ];
    }

    /**
     * @return ArticleCategoryVm
     */
    public function parent()
    {
        if (!$this->parent_id) {
            return null;
        }

        $parent = ArticleCategoryVm::one(['where' => [['=', 'id', $this->parent_id]]]);
        return $parent;
    }

    /**
     * Все родители категории
     *
     * @return array
     */
    public function parents()
    {
        $parents = [];

        if (!$this->parent_id) {
            return $parents;
        }

        $parent = ArticleCategoryVm::one(['where' => [['=', 'id', $this->parent_id]]]);
        $parents[] = $parent;

        if ($parent->parent_id) {
            $parents = array_merge($parents, $parent->parents());
        }

        return $parents;
    }
}