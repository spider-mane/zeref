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
        add_action("admin_post_{$this->id}", [$this, 'handle']);

        if (true === $this->nopriv) {
            add_action("admin_post_nopriv_{$this->id}", [$this, 'handle']);
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
            'fields' => $handler->formFields($request),
            'checks' => array_merge(
                $handler->verificationFields($request),
                $this->requestFields($request)
            ),
        ];
    }

    /**
     *
     */
    public function handle()
    {
        $request = $this->getRequest();

        $this->process($request)->redirect($request)->exit($request);
    }

    /**
     *
     */
    public function process(ServerRequestInterface $request): Form
    {
        $this->initHandler()->process($request);

        return $this;
    }

    /**
     *
     */
    protected function redirect(ServerRequestInterface $request): Form
    {
        wp_safe_redirect($this->redirect ?? wp_get_referer());

        return $this;
    }

    /**
     *
     */
    protected function exit(ServerRequestInterface $request)
    {
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
    public static function create(string $id, string $handler, bool $nopriv = false, ?string $redirect = null): Form
    {
        return (new static($id, $handler, $nopriv, $redirect))->hook();
    }
}
