<?php

namespace LucasAlbuquerque\LoginSystem\View;

use LucasAlbuquerque\LoginSystem\Handler\ClassHandlerInterface;
use LucasAlbuquerque\LoginSystem\Traits\RenderHtmlTrait;

class LoginView implements ClassHandlerInterface
{
    use RenderHtmlTrait;

    public function handle(): void
    {
        echo $this->renderHtml('views/login.php', []);
    }
}