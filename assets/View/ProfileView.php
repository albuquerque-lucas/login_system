<?php

namespace LucasAlbuquerque\LoginSystem\View;

use LucasAlbuquerque\LoginSystem\Traits\RenderHtmlTrait;
use LucasAlbuquerque\LoginSystem\Utils\SessionManager;

class ProfileView
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