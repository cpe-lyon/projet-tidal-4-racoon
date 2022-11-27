<?php

namespace Helper\MVC;

use Helper\Twig\Page;
use Helper\App\DB;
use Helper\Models\Condition;
use Helper\Models\Users;
use PDOException;

class ProfilController extends Controller
{
    protected DB $dbh; // Initialisation BDD
    public bool $isConnected = true;
    public bool $profileEditing = false;
    public bool $passwordEditing = false;

    public function profile(): Page
    {
        $params = [
            'isConnected' => $this->isConnected,
            'profileEditing' => $this->profileEditing,
            'passwordEditing' => $this->passwordEditing
        ];

        return new Page('profil/profil.tpl.twig', $params);
    }

    public function register(): Page
    {
        $this->dbh = new DB();
        if (!empty($_POST)) {
            $this->sendRegisterForm();
        }

        return new Page('profil/register.tpl.twig');
    }

    public function login(): Page
    {
        $this->dbh = new DB();
        if (!empty($_POST)) {
            $this->sendLoginForm();
        }

        return new Page('profil/login.tpl.twig');
    }

    public function confirmProfile(int $confirmId, int $confirmToken): Page
    {
        var_dump($confirmId);
        var_dump($confirmToken);
        /*
        try {
            $usernameCondition = [new Condition("id", $confirmId)];
            $userInfos = $this->dbh->getItemsWhere("users", ['*'], $usernameCondition);
            var_dump($userInfos);
        } catch (PDOException $e) {
            // erreur de récupération des infos du client, mauvais mail de confirmation
            var_dump($e->getMessage());
        }

        if ($userInfos && $userInfos->confirmationtoken == $confirmToken) {
            // Démarrage de la session si le mail est confirmé
            session_start();
            // Mise à jour de la table pour valider la confirmation

            // TODO: Faire requête d'update de la table
            // $pdo->prepare('UPDATE users SET confirmation_token = NULL, confirmed_at = NOW() WHERE id = ?')->execute([$user_id]);

            // TODO: Style et déclencheur flash message
            $_SESSION['flash']['success'] = 'Votre compte a bien été validé';
            $_SESSION['isConnected'] = true;
            $_SESSION['user'] = $userInfos;

            var_dump($_SESSION['user']);

            return new Page('profil/profil.tpl.twig');
        } else {
            $_SESSION['flash']['danger'] = "Ce token n'est plus valide";
            return new Page('profil/login.tpl.twig');
        }*/
        $params = [
            'isConnected' => $this->isConnected,
            'profileEditing' => $this->profileEditing,
            'passwordEditing' => $this->passwordEditing
        ];

        return new Page('profil/profil.tpl.twig', $params);
    }

    protected function sendRegisterForm(): void
    {
        // Hachage du mot de passe
        $password = password_hash($_POST['password'], PASSWORD_BCRYPT);
        // Génération du token pour validation du compte
        $token = $this->randomToken(60);

        $newUser = new Users();
        $newUser->username = $_POST['username'];
        $newUser->name = $_POST['first-name'];
        $newUser->lastname = $_POST['last-name'];
        $newUser->mail = $_POST['mail'];
        $newUser->password = $password;
        $newUser->confirmationtoken = $token;

        try {
            $response = $this->dbh->insertNotAll($newUser);
            var_dump($response);
        } catch (PDOException $e) {
            var_dump($e->getMessage());

            // 23505 : Violation clé unique - username ou mail
            if (str_contains($e->getMessage(), '(mail)')) {
                var_dump("mail");
            } elseif (str_contains($e->getMessage(), '(username)')) {
                var_dump("username");
            }
        }

        $this->verifyUserMail($_POST['username'], $_POST['mail'], $token);
    }

    protected function verifyUserMail($username, $mail, $token)
    {
        try {
            $usernameCondition = [new Condition("username", $username)];
            $response = $this->dbh->getItemsWhere("users", ['id'], $usernameCondition);
            $userId = $response->id;
        } catch (PDOException $e) {
            var_dump($e->getMessage());
        }

        try {
            // Envoi d'un mail de confirmation
            mail($mail, 'Confirmation de votre compte A.A.A.', "Afin de valider votre compte merci de cliquer sur ce lien\n\nhttp://racoon/profil/confirm/$userId/$token");
            // On redirige l'utilisateur vers la page de login avec un message flash
            $_SESSION['flash']['success'] = 'Un mail de confirmation vous a été envoyé pour valider votre compte';
            header('Location: login.php');
        } catch (\Throwable $th) {
            var_dump("Erreur dans l'envoi du mail de confirmation");
        }
    }

    protected function sendLoginForm(): void
    {
        $conditions = [new Condition("mail", $_POST['mail']), new Condition("confirmationdate", NULL, "IS NOT NULL")];
        $response = $this->dbh->getItemsWhere("users", ['*'], $conditions);

        if (password_verify($_POST['password'], $response->password) && $response != null) {

            var_dump("Connecté");
            // $_SESSION['auth'] = $response;
            // $_SESSION['flash']['success'] = 'Vous êtes maintenant connecté';
            // header('Location: account.php');

        } else {
            // $_SESSION['flash']['danger'] = 'Identifiant ou mot de passe incorrecte';
            var_dump("Pas connecté");
        }
    }

    // Generate a random token
    protected function randomToken($length): string
    {
        $alphabet = "0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ";
        return substr(str_shuffle(str_repeat($alphabet, $length)), 0, $length);
    }
}
