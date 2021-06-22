<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Orchestra\Parser\Xml\Facade as XmlParser;

class ReadFileXml extends Controller
{
    //
    public function load()
    {
        $xmlFile = file_get_contents('upload/xml/product-list.xml');

        $xml = new \SimpleXMLElement($xmlFile);
        foreach ($xml->channel->item as $element) {
            dump($element);
        }
    }
}
