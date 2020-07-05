<?php

namespace kosuha606\VirtualContent\Domains\Article;

use kosuha606\VirtualContent\Domains\Article\Models\ArticleCategoryVm;
use kosuha606\VirtualModel\VirtualModel;

class ArticleCategoryService
{
    public $allActiveParentIds = [];

    public function categoriesTreeData($activeCategoryId = null)
    {
        $data = [];
        $categories = ArticleCategoryVm::many(['where' => [
            ['=', 'parent_id', null]
        ]]);

        if ($activeCategoryId) {
            $activeCategory = ArticleCategoryVm::one(['where' => [['=', 'id', $activeCategoryId]]]);
            $parents = VirtualModel::allToArray($activeCategory->parents());
            $this->allActiveParentIds = array_column($parents, 'id');
        }

        foreach ($categories as $category) {
            $data[] = $this->categoryData($category, $activeCategoryId);
        }

        return $data;
    }

    private function categoryData(ArticleCategoryVm $category, $activeCategoryId = null)
    {
        $result = [
            'text' => $category->name,
            'expanded' => $category->id == $activeCategoryId || in_array($category->id, $this->allActiveParentIds),
            'href' => $category->getUrl(),
        ];

        $children = $category->children();

        foreach ($children as $child) {
            $result['nodes'][] = $this->categoryData($child, $activeCategoryId);
        }

        return $result;
    }
}