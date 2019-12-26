<?php

namespace WebTheory\Zeref\Forms;

use GuzzleHttp\Psr7\ServerRequest;
use Psr\Http\Message\ServerRequestInterface;
use WebTheory\Saveyour\Fields\Hidden;
use WebTheory\Zeref\Contracts\FormControllerInterface;
use WebTheory\Zeref\Contracts\FormInterface;

class Form implements FormControllerInterface
{
    /**
     *
     */
    protected $id;

    /**
     *
     */
    protected $handler;

    /**
     * @var bool
     */
    protected $nopriv = false;

    /**
     * @var string
     */
    protected $redirect;

    /**
     *
     */
    protected $config;

    /**
     *
     */
    public function __construct(string $id, string $handler, bool $nopriv = false, ?string $redirect = null)
    {
        $this->id = $id;
        $this->nopriv = $nopriv;
        $this->setHandler($handler);

        if ($redirect) {
            $this->redirect = $redirect;
        }
    }

    /**
     * Set the value of form
     *
     * @param string $form
     *
     * @return self
     */
    public function setHandler(string $form)
    {
        if (class_exists($form) && in_array(FormInterface::class, class_implements($form))) {
            $this->form = $form;
        } else {
            $interface = FormInterface::class;
            $message = "\$form argument must be a reference to an implementation of $interface";

            throw new \Exception($message);
        }

        return $this;
    }

    /**
     *
     */
    protected function hook()
    {
        add_action("admin_post_{$this->id}", [$this, 'process']);

        if (true === $this->nopriv) {
            add_action("admin_post_nopriv_{$this->id}", [$this, 'process']);
        }

        return $this;
    }

    /**
     *
     */
    protected function build()
    {
        $handler = $this->initHandler();
        $request = $this->getRequest();

        return [
            'method' => $this->method(),
            'action' => $this->action(),
            'verification' => array_merge(
                $handler->verificationFields($request),
                $this->requestFields($request)
            ),
            'fields' => $handler->getFields($request),
            'field_data' => $handler->getFieldsData($request),
        ];
    }

    /**
     *
     */
    public function process()
    {
        $this->initHandler()->process($this->getRequest());
        $this->redirect();

        exit;
    }

    /**
     *
     */
    protected function initHandler(): FormInterface
    {
        return new $this->handler;
    }

    /**
     *
     */
    protected function method()
    {
        return 'post';
    }

    /**
     *
     */
    protected function action()
    {
        return esc_url(admin_url('admin-post.php'));
    }

    /**
     *
     */
    protected function getRequest(): ServerRequestInterface
    {
        return ServerRequest::fromGlobals();
    }

    /**
     *
     */
    protected function requestFields(ServerRequestInterface $request)
    {
        return [
            'action' => (new Hidden)
                ->setName('action')
                ->setValue($this->id)
                ->toHtml(),

            'referer' => wp_referer_field(false)
        ];
    }

    /**
     *
     */
    protected function redirect()
    {
        wp_safe_redirect($this->redirect ?? wp_get_referer());
    }

    /**
     *
     */
    public static function create(string $id, string $handler, bool $nopriv = false, ?string $redirect = null): Form
    {
        return (new static($id, $handler, $nopriv, $redirect))->hook();
    }
}
