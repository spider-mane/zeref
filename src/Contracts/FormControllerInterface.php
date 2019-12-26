<?php

namespace WebTheory\Zeref\Contracts;

interface FormControllerInterface
{
    /**
     *
     */
    public function id();

    /**
     *
     */
    public function build(): array;
}
