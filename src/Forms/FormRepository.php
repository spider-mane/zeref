<?php

namespace WebTheory\Zeref\Forms;

use WebTheory\Zeref\Contracts\FormControllerInterface;

class FormRepository
{
    /**
     * @var FormControllerInterface[]
     */
    protected $forms;

    /**
     *
     */
    public function register(string $id, FormControllerInterface $form)
    {
        $this->forms[$id] = $form;
    }

    /**
     *
     */
    public function get(string $form)
    {
        return ($this->forms[$form])->build();
    }
}
