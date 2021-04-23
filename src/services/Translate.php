<?php

namespace creativeorange\translate\services;

use creativeorange\translate\exceptions\TranslateException;
use Google\Cloud\Translate\V2\TranslateClient;
use Google\Cloud\Translate\V3\TranslationServiceClient;
use yii\base\Component;

class Translate extends Component
{
    private static $translation_client_v2 = null;
    private static $translation_client_v3 = null;

    private static function getV2TranslationClientInstance(): TranslateClient
    {
        if (self::$translation_client_v2 === null) {
            self::$translation_client_v2 = new TranslateClient([
                'key' => \creativeorange\translate\Translate::$plugin->getSettings()->getGoogleTranslateKey()
            ]);
        }

        return self::$translation_client_v2;
    }

    private static function getV3TranslationClientInstance(): TranslationServiceClient
    {
        if (self::$translation_client_v3 === null) {
            self::$translation_client_v3 = new TranslationServiceClient([
                'credentials' => \creativeorange\translate\Translate::$plugin->getSettings()->getPath()
            ]);
        }

        return self::$translation_client_v3;
    }

    public static function translate($content, $to_language = null, $from_language = null): string
    {
        if ($to_language === null) {
            throw new TranslateException("Please specify at least 1 parameter for the translate filter with context `${content}`");
        }

        if ($from_language === null) {
            $from_language = \Craft::$app->sites->getCurrentSite()->language;
        }

        $text = $content;

        if ($to_language === $from_language) {
            return $text;
        }

        if (\creativeorange\translate\Translate::$plugin->getSettings()->useApiKey) {
            // Use V2
            $hash = \md5("V2_{$to_language}_{$from_language}_{$content}");

            $translation = \Craft::$app->cache->getOrSet($hash, function () use ($content, $to_language, $from_language) {
                return self::translateV2($content, $to_language, $from_language);
            }, \creativeorange\translate\Translate::$plugin->getSettings()->cacheTime);

            if (\is_array($translation) && !empty($translation['text'])) {
                $text = $translation['text'];
            }
        } else {
            // Use V3
            $hash = \md5("V3_{$to_language}_{$from_language}_{$content}");

            $translation = \Craft::$app->cache->getOrSet($hash, function () use ($content, $to_language, $from_language) {
                return self::translateV3($content, $to_language, $from_language);
            }, \creativeorange\translate\Translate::$plugin->getSettings()->cacheTime);

            if (isset($translation->getTranslations()[0])) {
                $text = $translation->getTranslations()[0]->getTranslatedText();
            }
        }

        return $text;
    }

    private static function translateV2($content, $to_language, $from_language = null)
    {
        $options = [
            'target' => $to_language,
        ];
        if ($from_language !== null) {
            $options['source'] = $from_language;
        }

        return self::getV2TranslationClientInstance()->translate($content, $options);
    }

    private static function translateV3($content, $to_language, $from_language = null)
    {
        $extra_options = [];
        if ($from_language !== null) {
            $extra_options = [
                'sourceLanguageCode' => $from_language
            ];
        }

        return self::getV3TranslationClientInstance()->translateText(
            [$content],
            $to_language,
            TranslationServiceClient::locationName(
                \creativeorange\translate\Translate::$plugin->getSettings()->getGoogleTranslateKey(), 'global'),
            $extra_options,
        );
    }
}