<?php

namespace GenesisShirt\Model;

class MetadataModel
{
    function create($image, $external_url, $name, $description)
    {
        return [
            'image' => $image,
            'external_url' => $external_url,
            'name' => $name,
            'description' => $description
        ];
    }
}