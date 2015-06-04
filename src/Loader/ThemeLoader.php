<?php

namespace Oxygen\Preferences\Loader;

use Oxygen\Core\Config\Repository as Config;
use Oxygen\Core\Theme\ThemeManager;
use Oxygen\Preferences\Repository;
use Oxygen\Preferences\Schema;

class ThemeLoader implements LoaderInterface {

    /**
     * Config repository to use.
     *
     * @var Config
     */

    protected $config;

    /**
     * Theme manager to use.
     *
     * @var ThemeManager
     */

    protected $themes;

    /**
     * Constructs the ConfigLoader.
     *
     * @param Config        $config
     * @param ThemeManager  $themes
     */
    public function __construct(Config $config, ThemeManager $themes) {
        $this->config = $config;
        $this->themes = $themes;
    }

    /**
     * Loads the preferences and returns the repository.
     *
     * @return Repository
     */
    public function load() {
        return new Repository([
            'theme' => $this->themes->getCurrentKey()
        ]);
    }

    /**
     * Stores the preferences.
     *
     * @param Repository $repository
     * @param Schema $schema
     * @return void
     */
    public function store(Repository $repository, Schema $schema) {
        if($repository->hasChanged('theme')) {
            $newTheme = $this->themes->get($repository->get('theme'));
            foreach($newTheme->getProvides() as $key => $value) {
                $this->config->write($key, $value);
            }
            $this->themes->setCurrentKey($newTheme->getKey());
        }
    }

}