<?php

namespace creativeorange\translate;

use Craft;
use craft\base\Plugin;
use creativeorange\translate\models\Settings;

class Translate extends Plugin
{
    /**
     * @var self
     */
    public static $plugin;
    public $hasCpSettings = true;

    public function init()
    {
        parent::init();
        static::$plugin = $this;

        $this->setPluginComponents();
        $this->addTwigExtensions();
    }

    protected function createSettingsModel()
    {
        return new Settings();
    }

    protected function settingsHtml()
    {
        return Craft::$app->getView()->renderTemplate(
            'translate/settings',
            ['settings' => $this->getSettings()]
        );
    }

    private function addTwigExtensions()
    {
        if (Craft::$app->request->getIsSiteRequest()) {
            // Add the twig extensions
            Craft::$app->view->registerTwigExtension(new \creativeorange\translate\web\twig\extensions\Translate);
        }
    }

    private function setPluginComponents()
    {
        $this->setComponents([
            'translate' => \creativeorange\translate\services\Translate::class,
        ]);
    }
}