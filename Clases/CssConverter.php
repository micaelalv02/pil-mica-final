<?php

namespace Clases;


class CssConverter {
/***
 * css2obj - convert a CSS string to an object representation
 *
 * @note - comments are lost
 * @param string cssraw - the string to be parsed
 * @return object - the result of parsing
 ***/
    static function css2obj($cssraw) {
        //exclude comment blocks
        $blocks = explode('/*', $cssraw);
        $css = '';
        if(count($blocks) != 1) {
            foreach($blocks as $block) {
                $blockparts = explode('*/', $block);
                if(count($blockparts) !== 2) {
                    continue;
                }
                $css .= $blockparts[1];
            }
        } else {
            $css = $cssraw;
        }
        //parse the remainder
        $parts = explode('}', $css);
        $result = [];
        foreach($parts as $part) {
            $bits = explode('{', $part);
            if(count($bits) !== 2) {
                continue;
            }
            $selector = trim($bits[0]);
            $actions = explode(';', trim($bits[1]));
            $result[$selector] = [];
            foreach($actions as $action) {
                $action = trim($action);
                $actbit = explode(':', $action,2);
                if(count($actbit) !== 2) {
                    continue;
                }
                $name = trim($actbit[0]);
                $val = trim($actbit[1]);
                $result[$selector][$name] = $val;
               
            }
        }
        return (object) $result;
    }
    static function obj2css($obj) {
        $result = '';
        foreach($obj as $selector=>$attributes) {
            $selector  = (substr($selector, 0) == "_") ? str_replace("_",".",$selector) : $selector;
            $selector  = str_replace("_"," ",$selector);
            $result .= "\n$selector {";
            foreach($attributes as $name=>$val) {
                $val = (strpos($val, '!important') === false) ? $val." !important" : $val;
                $result .= "\n $name : $val;";
            }
            $result .= "\n}";
        }
        return $result;
    }

    static function obj2cssOnlyKeys($obj) {
        $array = [];
        foreach($obj as $selector => $attributes) {            
         $array[] = $selector;
        //  var_dump($selector);
        }
        return $array;
    }
}
?>