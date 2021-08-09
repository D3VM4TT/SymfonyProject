<?php


namespace App\Twig;


use Twig\Extension\AbstractExtension;
use Twig\Extension\GlobalsInterface;
use Twig\TwigFilter;

/**
 * Class AppExtension
 * @package App\Twig
 */
class AppExtension extends AbstractExtension implements GlobalsInterface
{
    /**
     * @var string
     */
    private $message;


    /**
     * AppExtension constructor.
     * @param string $message
     */
    public function __construct(string $message)
    {
        $this->message = $message;
    }

    /**
     * @return string[]
     */
    public function getGlobals(): array
    {
        return ['message' => $this->message];
    }


    /**
     * @return array|TwigFilter[]
     */
    public function getFilters()
    {
        return [
            new TwigFilter('price', [$this, 'formatPrice'])
        ];
    }

    /**
     * @param $price
     * @return string
     */
    public function formatPrice($price)
    {
        return '$' . number_format((float)$price, 2, '.', '');
    }


}