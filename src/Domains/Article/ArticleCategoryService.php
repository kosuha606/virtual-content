<?php

namespace kosuha606\VirtualContent\Domains\Article;

use kosuha606\VirtualContent\Domains\Article\Models\ArticleCategoryVm;
use kosuha606\VirtualModel\VirtualModelEntity;

class ArticleCategoryService
{
    /** @var array  */
    public $allActiveParentIds = [];

    /**
     * @param null $activeCategoryId
     * @return array
     * @throws \Exception
     */
    public function categoriesTreeData($activeCategoryId = null)
    {
        $data = [];
        $categories = ArticleCategoryVm::many(['where' => [
            ['=', 'parent_id', null]
        ]]);

        if ($activeCategoryId) {
            $activeCategory = ArticleCategoryVm::one(['where' => [['=', 'id', $activeCategoryId]]]);
            $parents = VirtualModelEntity::allToArray($activeCategory->parents());
            $this->allActiveParentIds = array_column($parents, 'id');
        }

        foreach ($categories as $category) {
            $data[] = $this->getCategoryData($category, $activeCategoryId);
        }

        return $data;
    }

    /**
     * @param ArticleCategoryVm $category
     * @param null $activeCategoryId
     * @return array
     * @throws \Exception
     */
    private function getCategoryData(ArticleCategoryVm $category, $activeCategoryId = null)
    {
        $result = [
            'text' => $category->name,
            'expanded' => $category->id == $activeCategoryId || in_array($category->id, $this->allActiveParentIds),
            'href' => $category->getUrl(),
        ];

        $children = $category->children();

        foreach ($children as $child) {
            $result['nodes'][] = $this->getCategoryData($child, $activeCategoryId);
        }

        return $result;
    }
}
