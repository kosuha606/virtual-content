<?php

namespace kosuha606\VirtualContent\Domains\Text\Models;

use kosuha606\VirtualModel\VirtualModel;

/**
 *
 * @property $description
 * @property $code
 * @property $content
 *
 */
class TextVm extends VirtualModel
{
    public function attributes(): array
    {
        return [
            'id',
            'description',
            'code',
            'content',
        ];
    }
}