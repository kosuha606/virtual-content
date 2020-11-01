<?php

namespace kosuha606\VirtualContent\Domains\Article\Models;

use kosuha606\VirtualModel\VirtualModelEntity;

/**
 * @property $id
 * @property $article_id
 * @property $meta_title
 * @property $meta_keywords
 * @property $meta_description
 */
class SeoArticleVm extends VirtualModelEntity
{
    /**
     * @return array
     */
    public function attributes(): array
    {
        return [
            'id',
            'article_id',
            'meta_title',
            'meta_keywords',
            'meta_description',
        ];
    }
}
