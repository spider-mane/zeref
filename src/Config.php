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
            $entry = preg_replace("/[^A-Za-z0-9 ]/", '_', $parts[0]);

            if ($parser === null) {
                $extension = array_pop($parts);

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
