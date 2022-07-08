<?php

namespace App\Twig;

use App\Entity\Product;
use Twig\Extension\AbstractExtension;
use NumberFormatter;
use Twig\TwigFilter;

class IntlExtension extends AbstractExtension
{
    public function getFilters(): array
    {
        return [
            new TwigFilter('localizednumber', [$this, 'twigLocalizedNumberFilter']),
            new TwigFilter('units_of_measure', [$this, 'twigUnitsOfMeasureFilter']),
        ];
    }

    public function twigLocalizedNumberFilter($number): string
    {
        $whole = floor($number);
        $fraction = floor(100 * ($number - $whole));

        return (new NumberFormatter(
                'ru-RU', NumberFormatter::SPELLOUT
            ))->format($whole) . ' Рублей ' . $fraction . ' Копеек';
    }

    public function twigUnitsOfMeasureFilter(string $baseUnit): string
    {
        if ($baseUnit === Product::BASE_UNIT_PIECE) {
            return 'ШТ';
        }

        return 'КГ';
    }
}
