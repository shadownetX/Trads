<?php

namespace Trads;

class ValidTraductor
{

    public static function authorizeLangue($langue)
    {
        $rules = self::getLanguages();

        if (in_array($langue, $rules)) {
            return true;
        } else {
            return false;
        }

    }

    public static function getLanguages()
    {
        return [
            'en',
            'fr',
        ];
    }

    public static function getRessources()
    {
        return [
            'global',
            'produitheque',
            'link',
            'videotheque',
        ];
    }

    public static function dirRessources($lang, $ressource, $extension)
    {
        return ROOT . "/src/Trads/$lang/$ressource.$extension";
    }

    public static function dirRessource($json_data, $extension)
    {
        return ROOT . "/src/Trads/$json_data.$extension";
    }

    /**
     * @param $templates
     * @return bool
     */
    public static function authorizeRessource($ressources)
    {
        $rules = self::getRessources();

        // initialisation du chargement des traductions à TRUE
        // si erreur lors du chargement retourne false;
        $loading = true;
        foreach ($ressources as $template) {
            if (!in_array($template, $rules)) {
                $loading = false;
            }
            return $loading;
        }
    }


}