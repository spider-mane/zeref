<?php

namespace WebTheory\Zeref\Forms;

use Psr\Http\Message\ServerRequestInterface;
use WebTheory\Leonidas\Forms\Validators\WpNonceValidator;
use WebTheory\Saveyour\Contracts\FormDataProcessorInterface;
use WebTheory\Saveyour\Contracts\FormFieldControllerInterface;
use WebTheory\Saveyour\Contracts\FormProcessingCacheInterface;
use WebTheory\Saveyour\Contracts\FormSubmissionManagerInterface;
use WebTheory\Saveyour\Contracts\FormValidatorInterface;
use WebTheory\Saveyour\Controllers\FormSubmissionManager;
use WebTheory\Saveyour\Fields\Hidden;
use WebTheory\Zeref\Contracts\FormInterface;

abstract class AbstractFormHandler implements FormInterface
{
    /**
     *
     */
    protected $nonce = [
        'name' => null,
        'action' => null,
    ];

    /**
     *
     */
    protected const FORM_DATA = [];

    /**
     *
     */
    protected const FIELD_DATA = [];

    /**
     * @return string[]
     */
    public function verificationFields(ServerRequestInterface $request): array
    {
        return [
            'nonce' => (new Hidden)
                ->setName($this->nonce['name'])
                ->setValue(wp_create_nonce($this->nonce['action']))
                ->toHtml(),
        ];
    }

    /**
     *
     */
    public function getFieldsData(ServerRequestInterface $request): array
    {
        $fieldData = static::FIELD_DATA;
        $controllers = $this->formFieldControllers();

        foreach ($fieldData as $field => &$data) {
            $controller = $controllers[$field];

            $data['name'] = $controller->getRequestVar();
            $data['value'] = $controller->getPresetValue($request);
        }

        return $fieldData;
    }

    /**
     *
     */
    public function process(ServerRequestInterface $request): FormProcessingCacheInterface
    {
        return $this->getFormSubmissionManager()->process($this->customizeRequest($request));
    }

    /**
     *
     */
    protected function customizeRequest(ServerRequestInterface $request): ServerRequestInterface
    {
        return $request;
    }

    /**
     *
     */
    protected function getFormSubmissionManager(): FormSubmissionManagerInterface
    {
        return (new FormSubmissionManager())
            ->setValidators($this->formRequestValidators())
            ->setFields($this->formFieldControllers())
            ->setProcessors($this->formDataProcessors());
    }

    /**
     * @return FormValidatorInterface[]
     */
    protected function formRequestValidators(): array
    {
        return [
            'nonce' => new WpNonceValidator($this->nonce['name'], $this->nonce['action'])
        ];
    }

    /**
     * @return FormDataProcessorInterface[]
     */
    protected function formDataProcessors(): array
    {
        return [];
    }

    /**
     * @return FormFieldControllerInterface[]
     */
    abstract protected function formFieldControllers(): array;
}
