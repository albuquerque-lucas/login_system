<?php

namespace LucasAlbuquerque\LoginSystem\Controller;

use LucasAlbuquerque\LoginSystem\Handler\ClassHandlerInterface;
use LucasAlbuquerque\LoginSystem\View\ProfileView;
use PDO;

class ProfileController implements ClassHandlerInterface
{
  private $ProfileView;
  public function __construct()
    {
      $this->ProfileView = new ProfileView();
    }

  public function handle(): void
  {
    switch($_SERVER['PATH_INFO']){
      case '/profile':
          $this->ProfileView->handle();
          break;
      }
  }
}