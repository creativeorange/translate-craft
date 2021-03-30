<?php

namespace Creativeorange\Translate;

use Craft;
use craft\base\Plugin;
use Creativeorange\Translate\models\Settings;

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
            Craft::$app->view->registerTwigExtension(new \Creativeorange\Translate\web\twig\extensions\Translate);
        }
    }

    private function setPluginComponents()
    {
        $this->setComponents([
            'translate' => \Creativeorange\Translate\services\Translate::class,
        ]);
    }
}