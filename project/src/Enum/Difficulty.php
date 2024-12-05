<?php
namespace App\Enum;

abstract class Difficulty
{
    const LFR = 'LFR';
    const NORMAL = 'Normal';
    const HEROIC = 'Héroïque';
    const MYTHIC = 'Mythique';

    public static function getAvailableDifficulties()
    {
        return [
            self::LFR,
            self::NORMAL,
            self::HEROIC,
            self::MYTHIC,
        ];
    }
}
