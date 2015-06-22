<?php


namespace Oxygen\Preferences\Loader;

use Oxygen\Preferences\PreferencesManager;
use Oxygen\Preferences\Repository;
use Oxygen\Preferences\Schema;
use Oxygen\Theme\ThemeManager;

class ThemeLoader implements LoaderInterface {

    protected $loader;

    protected $preferences;

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
     * @param \Oxygen\Preferences\Loader\LoaderInterface $parent
     * @param \Oxygen\Preferences\PreferencesManager     $preferences
     * @param \Oxygen\Theme\ThemeManager                 $themes
     * @param string                                     $frontendName
     * @param string                                     $backendName
     */
    public function __construct(LoaderInterface $parent, PreferencesManager $preferences, ThemeManager $themes, $frontendName, $backendName) {
        $this->loader = $parent;
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
            [$this->frontendName => $this->loader->load()->get($this->backendName)]
        );
    }

    /**
     * Stores the preferences.
     *
     * @param Repository                 $repository
     * @throws \Oxygen\Theme\ThemeNotFoundException
     */
    public function store(Repository $repository) {
        if($repository->hasChanged($this->frontendName)) {
            $name = $repository->get('theme');
            $newTheme = $this->themes->get($name);
            foreach($newTheme->getProvides() as $key => $value) {
                $schema = $this->preferences->get($key);
                $schema->getRepository()->fill($value);
                $schema->storeRepository();
            }
            $this->loader->store(new Repository([$this->backendName => $name]));
        }
    }
}