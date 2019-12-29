<?php

namespace WebTheory\Zeref\Contracts;

interface FormControllerInterface
{
    /**
     * @return string
     */
    public function id(): string;

    /**
     * @return array
     */
    public function build(): array;
}
