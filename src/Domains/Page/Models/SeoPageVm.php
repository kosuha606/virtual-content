<?php

namespace kosuha606\VirtualContent\Domains\Page\Models;

use kosuha606\VirtualAdmin\Domains\Multilang\MultilangTrait;
use kosuha606\VirtualModel\VirtualModelEntity;

class SeoPageVm extends VirtualModelEntity
{
    use MultilangTrait;

    public function attributes(): array
    {
        return [
            'id',
            'page_id',
            'meta_title',
            'meta_keywords',
            'meta_description',
        ];
    }
}