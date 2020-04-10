<?php

namespace Oxygen\Preferences\Transformer;

use Exception;

use Oxygen\Preferences\Repository;

class JavascriptTransformer extends JsonTransformer {

    /**
     * Converts a JSON string into a preferences repository.
     *
     * @param string $json
     * @return Repository
     * @throws Exception
     */
    public function toRepository($json) {
        throw new Exception("Cannot convert Javascript object to Preferences Repository");
    }

    /**
     * Converts a preferences repository into a JSON string.
     *
     * @param Repository $repository
     * @param string     $variableName
     * @param boolean    $pretty
     * @return string
     */
    public function fromRepository(Repository $repository, $variableName = 'preferences', $pretty = false) {
        $code = '<script>';
        $start = '';
        foreach(explode('.', $variableName) as $part) {
            $access = $start . $part;
            $code .= "if(typeof {$access} === 'undefined') {{$access}={};}";
            $start .= $part . '.';
        }
        $code .= $variableName . ' = ' . parent::fromRepository($repository) . ';</script>';
        return $code;
    }

}