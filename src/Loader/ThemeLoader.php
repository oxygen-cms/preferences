<?php

namespace Oxygen\Preferences\Loader;

use Oxygen\Preferences\PreferencesManager;
use Oxygen\Preferences\Repository;
use Oxygen\Theme\ThemeManager;
use Oxygen\Theme\ThemeNotFoundException;

class ThemeLoader implements LoaderInterface {

    /**
     * @var LoaderInterface
     */
    protected $currentThemeLoader;

    /**
     * @var PreferencesManager
     */
    protected $preferences;

    /**
     * @var ThemeManager
     */
    protected $themes;

    /**
     * @var string
     */
    private $frontendName;

    /**
     * @var string
     */
    private $backendName;

    /**
     * @param LoaderInterface $currentThemeLoader
     * @param PreferencesManager $preferences
     * @param ThemeManager $themes
     * @param string                                     $frontendName
     * @param string                                     $backendName
     */
    public function __construct(LoaderInterface $currentThemeLoader, PreferencesManager $preferences, ThemeManager $themes, string $frontendName, string $backendName) {
        $this->currentThemeLoader = $currentThemeLoader;
        $this->preferences = $preferences;
        $this->themes = $themes;
        $this->frontendName = $frontendName;
        $this->backendName = $backendName;
    }

    /**
     * Loads the preferences and returns the repository.
     *
     * @return Repository
     */
    public function load() {
        return new Repository(
            [$this->frontendName => $this->currentThemeLoader->load()->get($this->backendName)]
        );
    }

    /**
     * Stores the preferences.
     *
     * @param Repository                 $repository
     * @throws ThemeNotFoundException
     */
    public function store(Repository $repository) {
        if($repository->hasChanged($this->frontendName)) {
            $name = $repository->get('theme');
            $newTheme = $this->themes->get($name);
            foreach($newTheme->getProvides() as $key => $value) {
                $schema = $this->preferences->getSchema($key);
                $schema->getRepository()->fill($value);
                $schema->storeRepository();
            }
            $this->currentThemeLoader->store(new Repository([$this->backendName => $name]));
        }
    }
}
