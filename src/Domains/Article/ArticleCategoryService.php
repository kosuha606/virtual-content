<?php

namespace kosuha606\VirtualContent\Domains\Article;

use kosuha606\VirtualContent\Domains\Article\Models\ArticleCategoryVm;

class ArticleCategoryService
{
    public function categoriesTreeData()
    {
        $data = [];
        $categories = ArticleCategoryVm::many(['where' => [
            ['=', 'parent_id', null]
        ]]);

        foreach ($categories as $category) {
            $data[] = $this->categoryData($category);
        }

        return $data;
    }

    private function categoryData(ArticleCategoryVm $category)
    {
        $result = [
            'text' => $category->name,
            'href' => $category->getUrl(),
        ];

        $children = $category->children();

        foreach ($children as $child) {
            $result['nodes'][] = $this->categoryData($child);
        }

        return $result;
    }
}