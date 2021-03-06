<?php

namespace kosuha606\VirtualContent\Domains\Article\Models;

use kosuha606\VirtualAdmin\Domains\Multilang\MultilangTrait;
use kosuha606\VirtualAdmin\Domains\Seo\SeoModelInterface;
use kosuha606\VirtualAdmin\Domains\Seo\SeoModelTrait;
use kosuha606\VirtualAdmin\Domains\Seo\SeoUrlObserver;
use kosuha606\VirtualModel\VirtualModelEntity;
use kosuha606\VirtualModelHelppack\Traits\ObserveVMTrait;

/**
 * @property $id,
 * @property $name,
 * @property $slug
 * @property $parent_id
 */
class ArticleCategoryVm extends VirtualModelEntity implements SeoModelInterface
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
            SeoUrlObserver::class,
        ];
    }

    /**
     * @return string
     */
    public function buildUrl()
    {
        $parentsSlug = '/';

        if ($parent = $this->parent()) {
            $parentsSlug = $parent->buildUrl().'/';
        }

        return $parentsSlug.$this->id.'-'.$this->slug;
    }

    /**
     * @return array
     */
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
     * @return array
     */
    public function allChildrens()
    {
        $children = $this->children();

        /** @var ArticleCategoryVm $child */
        foreach ($children as $child) {
            $children = array_merge($children, $child->children());
        }

        return $children;
    }

    /**
     * @return array
     */
    public function children()
    {
        $children = ArticleCategoryVm::many(['where' => [
            ['=', 'parent_id', $this->id],
        ]]);

        return $children;
    }

    /**
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
