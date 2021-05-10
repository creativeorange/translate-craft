<?php

namespace creativeorange\translate\models;

use Craft;
use craft\base\Model;

class Settings extends Model
{
    public $googleTranslateKey;
    public $path;
    public $excludedWords = [];
    public $cacheTime = 31556926;
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

    public function getExcludedWords()
    {
        return $this->excludedWords;
    }

    public function getEnabledExcludedWords()
    {
        return \array_map(
            function ($word) {
                return $word['word'];
            },
            \array_filter(
                $this->excludedWords,
                function ($word) {
                    return $word['enabled'];
                }
            )
        );
    }
}