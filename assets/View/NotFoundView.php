<?php

namespace LucasAlbuquerque\LoginSystem\View;

use LucasAlbuquerque\LoginSystem\Traits\RenderHtmlTrait;
use LucasAlbuquerque\LoginSystem\Utils\SessionManager;

class NotFoundView
{
    use RenderHtmlTrait;

    public function handle(): void
    {
        $sessionInfo = SessionManager::verifySessionInformation();
        list($status, $user) = $sessionInfo;
        echo $this->renderHtml('views/notFound.php', [
            'status' => $status,
            'user' => $user,
        ]);
    }
}