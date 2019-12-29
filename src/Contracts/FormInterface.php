<?php

namespace WebTheory\Zeref\Contracts;

use Psr\Http\Message\ServerRequestInterface;
use WebTheory\Saveyour\Contracts\FormProcessingCacheInterface;

interface FormInterface
{
    /**
     *
     */
    public function __construct();

    /**
     *
     */
    public function process(ServerRequestInterface $request): FormProcessingCacheInterface;

    /**
     * @return array
     */
    public function formFields(ServerRequestInterface $request): array;

    /**
     * @return string[]
     */
    public function verificationFields(ServerRequestInterface $request): array;
}
