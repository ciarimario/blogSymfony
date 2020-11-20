<?php

namespace App\Twig;

use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;
use Twig\TwigFunction;

class AppExtension extends AbstractExtension
{
    private $postImageDirectory;

    public function __construct(string $postImageDirectory)
    {
        $this->postImageDirectory = $postImageDirectory;
    }

    public function getFilters(): array
    {
        return [
            // If your filter generates SAFE HTML, you should add a third
            // parameter: ['is_safe' => ['html']]
            // Reference: https://twig.symfony.com/doc/2.x/advanced.html#automatic-escaping
            new TwigFilter('filter_name', [$this, 'doSomething']),
        ];
    }

    public function getFunctions(): array
    {
        return [
            // @param est le nom de la fonction twig à utiliser dans les fichiers twig
            // @param le nom de la fonction php définie en dessous
            new TwigFunction('asset_post_image', [$this, 'assetPostImage']),
        ];
    }

    public function assetPostImage($image)
    {
        // si le nom de l'image ne contient pas 'http'
        // c'est un nom d'image pas une url
        /* if (strpos($image, 'http://') === false && strpos($image, 'https://') === false) {
            return $this->postImageDirectory . '/' . $image;
        } */
        // si 'http' est au début du nom du fichier
        // c'est une url et donc c'est un chemin absolu que l'on affiche tel quel
        /* if (strpos($image, 'http://') == 0 && strpos($image, 'https://') == 0) {
            return $image;
        } */
        if (filter_var($image, FILTER_VALIDATE_URL)) {
            return $image;
        }

        return $this->postImageDirectory . '/' . $image;

        // ...
    }
}
