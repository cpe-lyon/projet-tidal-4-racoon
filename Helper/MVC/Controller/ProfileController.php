<?php

namespace Helper\MVC\Controller;

use DateTime;
use Helper\Twig\Page;
use Helper\App\DB\DB;
use Helper\MVC\Model\Users;
use Helper\App\DB\Condition;
use Helper\App\Routes\Request;
use PDOException;

class ProfileController extends Controller
{
    protected DB $dbh; // Initialisation BDD
    public bool $isConnected = true;
    public bool $profileEditing = false;
    public bool $passwordEditing = false;
    public $currentUser;

    public function isLogged(): bool
    {
        if (session_status() !== PHP_SESSION_ACTIVE || session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        if (!isset($_SESSION['user'])) {
            return false;
        } else {
            return true;
        }
    }

    public function profile(): Page
    {
        if (!$this->isLogged()) {
            $params = [
                'isConnected' => false,
                'profileEditing' => false,
                'passwordEditing' => false
            ];
            return new Page('profil/profil.tpl.twig', $params);
        } else {
            $this->currentUser = $_SESSION['user'];
            $params = [
                'isConnected' => true,
                'profileEditing' => false,
                'passwordEditing' => false,
                'user' => $this->currentUser
            ];
            return new Page('profil/profil.tpl.twig', $params);
        }
    }

    public function profileEdit(Request $request, string $editionType): Page
    {
        if ($this->isLogged()) {
            $this->currentUser = $_SESSION['user'];
        }

        if (strcmp($editionType, 'edit') == 0) {
            $params = [
                'isConnected' => true,
                'profileEditing' => true,
                'passwordEditing' => false,
                'user' => $this->currentUser
            ];
        } elseif (strcmp($editionType, 'password') == 0) {
            $params = [
                'isConnected' => true,
                'profileEditing' => false,
                'passwordEditing' => true,
                'user' => $this->currentUser
            ];
        } elseif (strcmp($editionType, 'logout') == 0) {
            $this->logout();
            $this->redirect('/profil');
        } else {
            $this->redirect('/profil');
        }

        return new Page('profil/profil.tpl.twig', $params);
    }

    public function register(): Page
    {
        if ($this->isLogged()) {
            $this->redirect('/profil');
        }

        $this->dbh = new DB();

        $translationMonths = [
            "January" => "Janvier",
            "February" => "Février",
            "March" => "Mars",
            "April" => "Avril",
            "May" => "Mai",
            "June" => "Juin",
            "July" => "Juillet",
            "August" => "Août",
            "September" => "Septembre",
            "October" => "Octobre",
            "November" => "Novembre",
            "December" => "Décembre"
        ];

        if (!empty($_POST)) {

            // Hachage du mot de passe
            $password = password_hash($_POST['password'], PASSWORD_BCRYPT);

            // Création du nouvel utilisateur à insérer en base
            $newUser = new Users();
            $newUser->username = $_POST['username'];
            $newUser->name = $_POST['first-name'];
            $newUser->lastname = $_POST['last-name'];
            $newUser->mail = $_POST['mail'];
            $newUser->password = $password;

            try {
                $this->dbh->insertNotAll($newUser);
                $newValues = $this->dbh->getItemsWhere("users", ["id", "creationdate"], [new Condition("username", $_POST['username'])]);
                $newUser->id = $newValues->id;

                $date = date_create($newValues->creationdate);
                $newDate = date_format($date, 'F Y');

                foreach ($translationMonths as $en => $fr) {
                    if (str_contains($newDate, $en)) {
                        $newDate = str_replace($en, $fr, $newDate);
                    }
                }
                $newUser->creationdate = $newDate;

                session_destroy();
                session_start();
                $_SESSION['user'] = $newUser;

                $this->redirect('/profil');
            } catch (PDOException $e) {

                if (str_contains($e->getMessage(), '(mail)')) {
                    $params = [
                        'error' => true,
                        'errorTitle' => 'Un compte associé à ce mail existe déjà',
                        'errorDesc' => $e->getMessage(),
                    ];
                } elseif (str_contains($e->getMessage(), '(username)')) {
                    $params = [
                        'error' => true,
                        'errorTitle' => 'Un compte avec ce nom d\'utilisateur existe déjà',
                        'errorDesc' => $e->getMessage(),
                    ];
                } else {
                    $params = [
                        'error' => true,
                        'errorDesc' => $e->getMessage(),
                    ];
                }
                return new Page('profil/register.tpl.twig', $params);
            }
        }

        return new Page('profil/register.tpl.twig');
    }

    public function login(): Page
    {
        if ($this->isLogged()) {
            $this->redirect('/profil');
        }

        $this->dbh = new DB();

        if (!empty($_POST)) {
            try {
                $response = $this->dbh->getItemsWhere("users", ['*'], [new Condition("mail", $_POST['mail'])]);

                if ($response) {
                    if (password_verify($_POST['password'], $response->password) && $response != null) {
                        session_destroy();
                        session_start();
                        $_SESSION['user'] = $response;
                        $this->redirect('/profil');
                    } else {
                        $params = [
                            "error" => true,
                            "errorTitle" => "Mot de passe incorrect",
                        ];
                        return new Page('profil/login.tpl.twig', $params);
                    }
                } else {
                    $params = [
                        "error" => true,
                        "errorTitle" => "Ce mail ne correspond à aucun compte",
                    ];
                    return new Page('profil/login.tpl.twig', $params);
                }
            } catch (PDOException $e) {
                $params = [
                    'error' => true,
                    "errorTitle" => "Erreur de connexion!",
                    'errorDesc' => $e->getMessage(),
                ];
                return new Page('profil/login.tpl.twig', $params);
            }
        }

        return new Page('profil/login.tpl.twig');
    }

    protected function logout()
    {
        unset($_SESSION['user']);
    }
}
