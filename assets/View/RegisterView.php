<?php

namespace LucasAlbuquerque\LoginSystem\View;

use LucasAlbuquerque\LoginSystem\Traits\RenderHtmlTrait;
use LucasAlbuquerque\LoginSystem\Utils\SessionManager;

class RegisterView
{
    use RenderHtmlTrait;

    public function handle(): void
    {
        $sessionInfo = SessionManager::verifySessionInformation();
        list($status, $user) = $sessionInfo;
        echo $this->renderHtml('views/register.php', [
            'status' => $status,
            'user' => $user,
        ]);
    }
}