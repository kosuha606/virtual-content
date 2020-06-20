<?php

namespace kosuha606\VritualContent\Domains\Page\Models;

use kosuha606\VirtualModel\VirtualModel;

class SeoPageVm extends VirtualModel
{
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