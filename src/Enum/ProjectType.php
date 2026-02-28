<?php
namespace App\Enum;

enum ProjectType: string
{
    case SCHOOL = 'school';
    case WORK = 'work';
    case PERSONAL = 'personal';

    
    public function label(): string
    {
        return match ($this) {
            self::SCHOOL => 'Školní',
            self::WORK => 'Pracovní',
            self::PERSONAL => 'Osobní',
        };
    }
}