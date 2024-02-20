<?php

class TypeReclamationEnum
{
    const TYPE_1 = 'violation of the rules of the platform';
    const TYPE_2 = 'inappropriate content';
    const TYPE_3 = 'other reasons';

    public static function getTypes(): array
    {
        return array_flip([
            self::TYPE_1,
            self::TYPE_2,
            self::TYPE_3,
        ]);
    }
}

