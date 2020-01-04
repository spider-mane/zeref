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
     * @var array
     */
    protected $config = [];

    /**
     *
     */
    public function __construct()
    {
        $this->config = $this->config();
    }

    /**
     *
     */
    public function process(ServerRequestInterface $request): FormProcessingCacheInterface
    {
        return $this->formSubmissionManager()->process($this->request($request));
    }

    /**
     *
     */
    public function formFields(ServerRequestInterface $request): array
    {
        $fieldData = $this->config['fields'];
        $controllers = $this->formFieldControllers();

        foreach ($fieldData as $field => &$data) {
            $controller = $controllers[$field];

            $data['name'] = $controller->getRequestVar();
            $data['value'] = $controller->getPresetValue($request);
        }

        return $fieldData;
    }

    /**
     * @return string[]
     */
    public function verificationFields(ServerRequestInterface $request): array
    {
        $nonceData = $this->config['nonce'];

        return [
            'nonce' => (new Hidden)
                ->setName($nonceData['name'])
                ->setValue(wp_create_nonce($nonceData['action']))
                ->toHtml()
        ];
    }

    /**
     *
     */
    protected function request(ServerRequestInterface $request): ServerRequestInterface
    {
        return $request;
    }

    /**
     *
     */
    protected function formSubmissionManager(): FormSubmissionManagerInterface
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
        $nonceData = $this->config['nonce'];

        return [
            'nonce' => new WpNonceValidator($nonceData['name'], $nonceData['action'])
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

    /**
     * @return array
     */
    abstract protected function config(): array;
}
