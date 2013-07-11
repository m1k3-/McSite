<?php
/**
 * Created by JetBrains PhpStorm.
 * User: Doc
 * Date: 01/10/12
 * Time: 22:45
 * To change this template use File | Settings | File Templates.
 */

namespace MineDoc\HomeBundle\Lib;

use Doctrine\ORM\EntityManager;
use Symfony\Component\Validator\Constraints\DateTime;
use MineDoc\HomeBundle\Entity\User;
use Symfony\Component\HttpFoundation\Session;

class Mc
{
    protected $em;

    public function __construct(EntityManager $em)
    {
        $this->em = $em;
    }

    static public function verifyMail($mail, $key)
    {
        return ($key == md5($mail . "cobra's the best") ? 1 : 0);
    }

    static public function sendRefuseMail($to)
    {
        $Subject = "Inscription Doc's server (MineCraft)";

        $mail_Data = "Salut !\n";
        $mail_Data .= "\n";
        $mail_Data .= "Ton inscription a été refusée sur www.docserver.fr\n";
        $mail_Data .= "\n";
        $mail_Data .= "Si tu as été refusé, cela peut être dû à plusieurs raisons:\n";
        $mail_Data .= "\t- Pseudo incorrect, comportant des espaces ou caractères interdits\n";
        $mail_Data .= "\t- Informations erronées, faux prénom/nom ou mail invalide\n";
        $mail_Data .= "\t- Multiples inscriptions sur le site\n";
        $mail_Data .= "\n";
        $mail_Data .= "Essaie de te réinscrire en respectant ces critères\n";
        $mail_Data .= "\n";
        $mail_Data .= "Bye !\n";

        $headers = 'From: "Doc CoBrA"<noreply@docserver.fr>' . "\n";
        $headers .= 'Reply-To: noreply@docserver.fr' . "\n";
        $headers .= 'Content-Type: text/plain; charset="iso-8859-1"' . "\n";
        $headers .= 'Content-Transfer-Encoding: 8bit';

        mail($to, $Subject, $mail_Data, $headers);
    }

    static public function sendMail1($to)
    {
        $Subject = "Inscription Doc's server (MineCraft)";

        $mail_Data = "Salut !\n";
        $mail_Data .= "\n";
        $mail_Data .= "Ton inscription a été validée sur www.docserver.fr\n";
        $mail_Data .= "\n";
        $mail_Data .= "N'oublie pas d'inviter du monde sur le serveur en précisant de mettre ton pseudo dans le champ parrain lors de leur inscription, tu gagneras des sous virtuels a utiliser sur le site en ressources ou pour louer une parcelle sécurisée.\n";
        $mail_Data .= "\n";
        $mail_Data .= "Bon jeu !\n";

        $headers = 'From: "Doc CoBrA"<noreply@docserver.fr>' . "\n";
        $headers .= 'Reply-To: noreply@docserver.fr' . "\n";
        $headers .= 'Content-Type: text/plain; charset="iso-8859-1"' . "\n";
        $headers .= 'Content-Transfer-Encoding: 8bit';

        mail($to, $Subject, $mail_Data, $headers);
    }

    static public function sendMail($to)
    {
        $Subject = "Inscription Doc's server (MineCraft)";

        $key = md5($to . "cobra's the best");

        $mail_Data = "Salut !\n";
        $mail_Data .= "\n";
        $mail_Data .= "Tu as demandé une inscription sur le site www.docserver.fr, pour cela vérifie ton mail en cliquant sur ce lien: www.docserver.fr/verify/" . $to  ."/" . $key . "\n";
        $mail_Data .= "\n";
        $mail_Data .= "Voici quelques règles a connaître avant de jouer sur le serveur:\n";
        $mail_Data .= "\t\t- Il est interdit de faire du griefing, détruire les créations des autres, voler des ressources ou de tuer quelqu'un sans son bon vouloir.\n";
        $mail_Data .= "\t\t- Toute tentative de triche ou de vol de compte entraine un bannissement définitif.\n";
        $mail_Data .= "\t\t- Aucun abus du type tnt / feu ne sera accepté.\n";
        $mail_Data .= "\t\t- Si un joueur fait ramer le serveur consciemment ou le fait crasher, il sera banni de façon définitive.\n";
        $mail_Data .= "\n";
        $mail_Data .= "Merci d'attendre la validation humaine de votre compte, il se peut qu'un administrateur vous contacte.\n";
        $mail_Data .= "\n";
        $mail_Data .= "Bon jeu !\n";

        $headers = 'From: "Doc CoBrA"<noreply@docserver.fr>' . "\n";
        $headers .= 'Reply-To: noreply@docserver.fr' . "\n";
        $headers .= 'Content-Type: text/plain; charset="iso-8859-1"' . "\n";
        $headers .= 'Content-Transfer-Encoding: 8bit';

        mail($to, $Subject, $mail_Data, $headers);
    }

    static public function CanEarn(User $user)
    {
        $now = new \DateTime('now');
        return ($now < $user->getEmtime()) ? 1 : 0;
    }

    static public function GetRank($level)
    {
        switch ($level) {
            case -1:
                $rank = "Mail non validé";
                break;
            case 0:
                $rank = "Sous-fifre";
                break;
            case 1:
                $rank = "Membre";
                break;
            case 2:
                $rank = "Donateur";
                break;
            case 3:
                $rank = "Modérateur";
                break;
            case 5:
                $rank = "Admin";
                break;
            default :
                $rank = "Non défini";
                break;
        }
        return ($rank);
    }

    static public function Test()
    {
        return (42);
    }
}
