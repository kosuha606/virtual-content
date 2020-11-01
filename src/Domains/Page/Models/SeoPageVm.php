<?php

namespace kosuha606\VirtualContent\Domains\Page\Models;

use kosuha606\VirtualAdmin\Domains\Multilang\MultilangTrait;
use kosuha606\VirtualModel\VirtualModelEntity;

/**
 * @property $id
 * @property $page_id
 * @property $meta_title
 * @property $meta_keywords
 * @property $meta_description
 */
class SeoPageVm extends VirtualModelEntity
{
    use MultilangTrait;

    /**
     * @return array
     */
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
