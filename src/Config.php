<?php

namespace WebTheory\Zeref;

use Noodlehaus\Config as NoodlehausConfig;
use Noodlehaus\Parser\ParserInterface;

class Config extends NoodlehausConfig
{
    /**
     * {@inheritDoc}
     */
    protected function loadFromFile($path, ParserInterface $parser = null)
    {
        $paths = $this->getValidPath($path);
        $this->data = [];

        foreach ($paths as $path) {
            // Get file information
            $info = pathinfo($path);
            $parts = explode('.', $info['basename']);
            $extension = array_pop($parts);
            $entry = $parts[0];

            if ($parser === null) {
                // Skip the `dist` extension
                if ($extension === 'dist') {
                    $extension = array_pop($parts);
                }

                // Get file parser
                $parser = $this->getParser($extension);

                // Try to load file
                $this->data = array_replace_recursive($this->data, [$entry => $parser->parseFile($path)]);

                // Clean parser
                $parser = null;
            } else {
                // Try to load file using specified parser
                $this->data = array_replace_recursive($this->data, [$entry => $parser->parseFile($path)]);
            }
        }
    }
}
