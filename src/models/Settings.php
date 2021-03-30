<?php

namespace Creativeorange\Translate\models;

use Craft;
use craft\base\Model;

class Settings extends Model
{
    public $googleTranslateKey;
    public $cacheTime = 3600;
    public $useApiKey = true;

    public function rules()
    {
        return [
            [['googleTranslateKey'], 'string'],
            [['cacheTime'], 'number'],
            [['useApiKey'], 'boolean'],
        ];
    }

    public function getGoogleTranslateKey()
    {
        return Craft::parseEnv($this->googleTranslateKey);
    }
}