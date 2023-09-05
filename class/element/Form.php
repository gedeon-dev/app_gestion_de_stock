<?php
namespace Formulaire;

/**
 * From
 * 
 * 
 * retourn un formulaire
 * 
 * @author Gedeon.dev
 * @author Gedeon.dev <gedeonmitoumona@gmail.com>
 * 
 * @version 1.0
 * 
 * @copyright ECEI appli enregistrement
 * 
 */
class Form{    
    /**
     * input
     * renvoie le un champ de type input
     *
     * @param  string $label nom du label
     * @param  string $type type du label
     * @param  string $name attribu name
     * @return string
     */
    public static function input(string $label, string $type, $name, $valeur = null):string
    {
        return <<<HTML
        <label for="$label">$label</label>
        <input type="$type" name="$name" id="$label" value="$valeur">
HTML;
    }
    
    /**
     * select
     * renvoie un champ de type select
     *
     * @param  string $name valeur attribu name
     * @param  string $valeur valeur de l'option
     * @return string
     */
    public static function select(int $valeur, string $unite):string
    {
        return<<<HTML
            <option value="$valeur">$unite</option>
HTML;
    }
}