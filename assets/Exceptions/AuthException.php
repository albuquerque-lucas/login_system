<?php

namespace LucasAlbuquerque\LoginSystem\Exceptions;

use Exception;

class AuthException extends Exception
{
  protected $messageType;
  protected $expiration;
  protected $redirect;
  public function __construct($message, $messageType, $expiration, $redirect)
  {
    parent::__construct($message);
    $this->messageType = $messageType;
    $this->expiration = $expiration;
    $this->redirect = $redirect;
  }

  public function getMessageType()
  {
    return $this->messageType;
  }

  public function getExpiration()
  {
    return $this->expiration;
  }

  public function getRedirect()
  {
    return $this->redirect;
  }
}