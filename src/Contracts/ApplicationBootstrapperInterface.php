<?php

namespace WebTheory\Zeref\Contracts;

use WebTheory\Zeref\Application;

interface ApplicationBootstrapperInterface
{
    /**
     *
     */
    public function bootstrap(Application $app);
}
