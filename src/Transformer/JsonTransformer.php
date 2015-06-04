<?php

namespace Oxygen\Preferences\Transformer;

use Exception;
use Oxygen\Preferences\Repository;

class JsonTransformer {

    /**
     * Converts a JSON string into a preferences repository.
     *
     * @param string $json
     * @throws Exception if the preferences are invalid
     * @return Repository
     */
    
    public function toRepository($json) {
        $json = $json == '' ? '{}' : $json;
        $array = json_decode($json, true);
        if(!is_array($array)) {
            throw new Exception("Invalid Preferences");
        } else {
            return new Repository($array);
        }
    }

    /**
     * Converts a preferences repository into a JSON string.
     *
     * @param Repository $repository
     * @param boolean $pretty
     * @return string
     */
    public function fromRepository(Repository $repository, $pretty = false) {
        return json_encode($repository->toArray(), $pretty ? JSON_PRETTY_PRINT : 0);
    }

}