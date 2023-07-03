<?php

namespace LucasAlbuquerque\LoginSystem\View;

use LucasAlbuquerque\LoginSystem\Handler\ClassHandlerInterface;
use LucasAlbuquerque\LoginSystem\Traits\RenderHtmlTrait;
use LucasAlbuquerque\LoginSystem\Utils\SessionManager;

class ProfileView implements ClassHandlerInterface
{
    use RenderHtmlTrait;

    public function handle(): void
    {
        $sessionInfo = SessionManager::verifySessionInformation();
        list($status, $user, $userAccess, $managementData, $allUsers) = $sessionInfo;
        echo $this->renderHtml('views/profile.php', [
            'status' => $status,
            'user' => $user,
            'userAccess' => $userAccess,
            'managementData' => $managementData,
            'allUsers' => $allUsers,
        ]);
    }
}