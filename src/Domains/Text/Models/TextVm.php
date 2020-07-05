<?php

namespace kosuha606\VirtualContent\Domains\Text\Models;

use kosuha606\VirtualModel\VirtualModelEntity;

/**
 *
 * @property $description
 * @property $code
 * @property $content
 *
 */
class TextVm extends VirtualModelEntity
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