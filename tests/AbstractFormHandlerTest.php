<?php

use PHPUnit\Framework\TestCase;
use WebTheory\Saveyour\Controllers\FormFieldController;
use WebTheory\Zeref\Forms\AbstractFormHandler;

class AbstractFormHandlerTest extends TestCase
{
    /**
     *
     */
    protected function generateDummyChild()
    {
        return new class extends AbstractFormHandler
        {
            public $request;
            public $nonce = [
                'name' => 'testname',
                'value' => 'testvalue',
            ];

            protected function formFieldControllers(): array
            {
                return (new FormFieldController('test'));
            }
        };
    }
}
