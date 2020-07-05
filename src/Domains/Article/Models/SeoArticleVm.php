<?php

namespace kosuha606\VirtualContent\Domains\Article\Models;

use kosuha606\VirtualModel\VirtualModelEntity;

class SeoArticleVm extends VirtualModelEntity
{
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