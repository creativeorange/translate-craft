<?php

namespace creativeorange\translate\models;

use Craft;
use craft\base\Model;

class Settings extends Model
{
    public $googleTranslateKey;
    public $path;
    public $cacheTime = 3600;
    public $useApiKey = true;

    public function rules()
    {
        return [
            [['googleTranslateKey', 'path'], 'string'],
            [['cacheTime'], 'number'],
            [['useApiKey'], 'boolean'],
        ];
    }

    public function getGoogleTranslateKey()
    {
        return Craft::parseEnv($this->googleTranslateKey);
    }

    public function getPath()
    {
        return Craft::parseEnv($this->path);
    }
}