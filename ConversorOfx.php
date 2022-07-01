<?php

class ConversorOfx
{

    public static function toJson($file)
    {
        $ofx = explode('<OFX>', $file);

        preg_match_all('/([A-Z]+)[:]([A-Z]+|[0-9]+)/', $ofx[0], $headerString);
        $header = array();
        foreach ($headerString[1] as $key => $value) {
            $header[$value] = $headerString[2][$key];
        }

        $content = '<OFX>' . $ofx[1];

        if (!self::verifyXml($content)) {
            $content = self::trataXml($content);
            die();
        }

        $xml     = simplexml_load_string(utf8_encode($content));
        $content = json_encode($xml);
        $content = json_decode($content, true);
        $json    = json_encode(array('header' => $header, 'content' => $content));

        return $json;
    }

    private static function verifyXml($file)
    {
        preg_match_all('/(<([A-Z]+)>)/', $file, $matchsAbertura);
        $tagsAbrir  =  array_unique($matchsAbertura[2]);

        preg_match_all('/(<\/[A-Z]+>)/', $file, $matchsFechamento);
        $tagsFechar =  array_unique($matchsFechamento[0]);

        foreach ($tagsAbrir as $tag) {
            if (!in_array('</' . $tag . '>', $tagsFechar)) {
                return 0;
            }
        }

        return 1;
    }

    private static function trataXml($file)
    {

        preg_match_all('/(<([A-Z]+)>)/', $file, $matchsAbertura);
        $tagsAbrir  =  array_unique($matchsAbertura[2]);

        preg_match_all('/(<\/[A-Z]+>)/', $file, $matchsFechamento);
        $tagsFechar =  array_unique($matchsFechamento[0]);

        $tagsPrecisamFechar = [];

        foreach ($tagsAbrir as $tag) {
            if (!in_array('</' . $tag . '>', $tagsFechar)) {
                // TODO 
            }
        }

        print_r($tagsPrecisamFechar);
    }
}
