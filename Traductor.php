<?php

namespace Trads;

class Traductor
{

    protected $langue;
    protected $ressources;
    protected $traductions;
    protected $exploitable;

    public function __construct($langue, $ressource, $extension = "json")
    {
        // initialisation de l'exploitation du fichier de traduction à FALSE
        $this->exploitable = true;

        // on force ressource en array()
        $ressources = (!is_array($ressource)) ? array($ressource) : $ressource;

        // validation des données par ValidTraductor
        if (ValidTraductor::authorizeLangue($langue) && ValidTraductor::authorizeRessource($ressources)) {

            $this->langue = $langue;
            $this->ressources = array_unique($ressources);

            /**
             * Chargement de plusieurs ressources de traduction
             */

            $this->traductions = $this->chargementRessources($this->ressources, $extension);
        }

    }

    public function chargementRessources($ressources, $extension)
    {

        $output = array();
        foreach ($ressources as $ressource) {

            $dir_fichier = ValidTraductor::dirRessources($this->langue, $ressource, $extension);

            if (file_exists($dir_fichier)) {

                $source_array = json_decode(utf8_encode(file_get_contents($dir_fichier)));

                foreach ($source_array as $key => $value) {

                    $output[$key] = $value;
                }
            } else {

                $this->exploitable = false;
            }
        }

        return (Object)$output;

    }

    public static function translate($keyTrad, $options = null, $json_data, $extension = "json")
    {

        $dir_fichier = ValidTraductor::dirRessource($json_data, $extension);

        if (file_get_contents($dir_fichier)) {

            $data = json_decode(file_get_contents($dir_fichier));

            if (isset($data->$keyTrad)) {

                return self::replacer($data->$keyTrad, $options);
            } else {

                return false;
            }

        } else {
            return false;
        }

    }

    static function replacer($string, $options)
    {
        if ($options != null && sizeof($options) > 0) {

            foreach ($options as $key => $value) {

                $recherche = ":$key:";
                $remplace = $value;

                if (preg_match("/$recherche/", $string)) {
                    $string = preg_replace("/$recherche/", $remplace, $string);
                }
            }

            return utf8_decode($string);
        }

        return utf8_decode($string);
    }

    public function load($ressource, $extension = "json")
    {
        // on force ressource en array()
        $ressources = (!is_array($ressource)) ? array($ressource) : $ressource;

        // validation des données par ValidTraductor
        if (ValidTraductor::authorizeLangue($this->langue) && ValidTraductor::authorizeRessource($ressources)) {



            $this->ressources = array_unique(
                array_merge(
                    $this->ressources,
                    $ressources)
            );
            $this->traductions = (Object) array_unique(
                array_merge(
                    (Array) $this->traductions,
                    (Array) $this->chargementRessources($ressources, $extension)
                )
            );
        }
    }

    public function trans($keyTrad, $options = null)
    {


        if ($this->isExploitable()) {

            if (isset($this->traductions->$keyTrad)) {
                return self::replacer($this->traductions->$keyTrad, $options);
            } else {
                return false;
            }

        } else {

            return false;
        }

    }

    /**
     * @return bool
     */
    public function isExploitable()
    {
        return $this->exploitable;
    }


}