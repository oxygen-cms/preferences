<?php

namespace Oxygen\Preferences\Transformer;

use Oxygen\Preferences\Repository;

class JsonTransformer {

    /**
     * Converts a JSON string into a preferences repository.
     *
     * @param string $json
     * @return Repository
     */

    public function toRepository($json) {
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