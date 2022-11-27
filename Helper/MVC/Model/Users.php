<?php

namespace App\MVC\Model;

use DateTime;

class Users
{
    public int $id;
    public string $username;
    public string $name;
    public string $lastname;
    public string $mail;
    public string $password;
    public DateTime $creationdate;
    public string $confirmationtoken;
    public DateTime $confirmationdate;
}
