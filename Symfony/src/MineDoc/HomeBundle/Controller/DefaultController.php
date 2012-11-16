<?php

namespace MineDoc\HomeBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Validator\Constraints\Date;
use Symfony\Component\Validator\Constraints\DateTime;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Response;
use MineDoc\HomeBundle\Entity\User;
use MineDoc\HomeBundle\Entity\Item;
use MineDoc\HomeBundle\Form\Type\LoginType;
use MineDoc\HomeBundle\Form\Type\RegisterType;
use MineDoc\HomeBundle\Entity\Login;
use MineDoc\HomeBundle\Lib\Mc; // To use it, Mc::Function()

class DefaultController extends Controller
{
    /**
     * @Route("/game", name="game")
     * @Template()
     */
    public function gameAction()
    {
        return array();
    }

    /**
     * @Route("/", name="homepage")
     * @Template()
     */
    public function indexAction()
    {
        $session = $this->getRequest()->getSession();
        $session->set('chatstamp', 0);
        $user = new User;
        $form_login = $this->createForm(new LoginType(), $user);

        $commands = $this->getDoctrine()->getRepository('MineDocHomeBundle:Commands')->getCommands();

        $res_commands = array();
        $command_ = array();

        $news = $this->getDoctrine()->getRepository('MineDocHomeBundle:News')->getLastNews(5);

        foreach ($commands as $command)
        {
            $modiftime = date_modify($command->getLastUse(), $command->getDelay() . 'seconds');
            $command_["name"] = $command->getName();
            $command_["label"] = $command->getLabel();
            $command_["type"] = $command->getType();
            $command_["time"] = date_format($modiftime, 'Y-m-d H:i:s');
            $command_["available"] = (time() - $command->getLastUse()->getTimeStamp() <= 0 ? 0 : 1);
            $res_commands[] = $command_;
        }
        $currentuser = $this->getDoctrine()->getRepository('MineDocHomeBundle:User')->find($session->get('id'));

        if ($currentuser != null)
        {
            $emtime = date_format($currentuser->getEmtime(), 'Y-m-d H:i:s');
            $earnmoney = Mc::CanEarn($currentuser);
            $money = $currentuser->getMoney();
        }
        else
        {
            $money = 0;
            $emtime = 0;
            $earnmoney = 0;
        }
        $rank = Mc::GetRank($session->get('level'));
        return array(
            'commands' => $res_commands,
            'earnmoney' => $earnmoney,
            'earnmoneytime' => $emtime,
            'form_login' => $form_login->createView(),
            'level' => $session->get('level'),
            'news' => $news,
            'rank' => $rank,
            'money' => $money,
        );
    }

    /**
     * @Route("/verify/{mail}/{key}", name="verify")
     */
    public function verifyAction($mail, $key)
    {
        if (Mc::verifyMail($mail, $key) == 1) {
            $currentuser = $this->getDoctrine()->getRepository('MineDocHomeBundle:User')->findOneByMail($mail);
            if ($currentuser == null) {
                $this->get('session')->setFlash('warning', 'Compte non existant !');
            } else {
                if ($currentuser->getLevel() == -1) {
                    $currentuser->setLevel(0);
                    $em = $this->getDoctrine()->getEntityManager();
                    $em->persist($currentuser);
                    $em->flush();
                    $this->get('session')->setFlash('notice', 'e-Mail validé ! Un admin va vérifer votre compte');
                } else {
                    $this->get('session')->setFlash('warning', 'e-Mail déjà validé !');
                }
            }
        } else {
            $this->get('session')->setFlash('warning', 'e-Mail ou clef invalide !');
        }
        return $this->redirect($this->generateUrl('homepage'));
    }

    /**
     * @Route("/send/{type}/{name}", name="send_command")
     */
    public function send_commandAction($type, $name)
    {
        $session = $this->getRequest()->getSession();
        $service = $this->get('mc_server');

        $currentuser = $this->getDoctrine()->getRepository('MineDocHomeBundle:User')->find($session->get('id'));
        if ($currentuser != null) {
            $level = $currentuser->getLevel();
        } else {
            return $this->redirect($this->generateUrl('homepage'));
        }

//----------------------------------Set Day-----------------------------------
        if ($type == 1)
        {
            $command = $this->getDoctrine()->getRepository('MineDocHomeBundle:Commands')->findOneByName($name);

            $time = date_format(date_modify(new \DateTime("now"), $command->getDelay()." seconds"), 'Y-m-d H:i:s');

            $response = array(
                'time' => "<span class='notice'>Le bouton sera utilisable a partir de " . $time . "</span>",
            );
        }

//------------------------------------Earn Money-------------------------------
        elseif ($type == 2)
        {
            if ($level > 1)
            {
                $now = new \DateTime("now");
                if ($currentuser->getEmtime() < $now)
                {
                    $this->getDoctrine()->getRepository('MineDocHomeBundle:User')->changeMoneyUser($session->get('id'), 5);
                    $money = $currentuser->getMoney() + 5;

                    $earnmoneytime = $now;
                    $earnmoneytime->add(new \DateInterval('P1D'));
                    $currentuser->setEmtime($earnmoneytime);
                    $earnmoneytime = date_format($earnmoneytime, 'Y-m-d H:i:s');

                    $em = $this->getDoctrine()->getEntityManager();
                    $em->persist($currentuser);
                    $em->flush();
                    $ok = 1;
                }
                else
                {
                    $money = $currentuser->getMoney();
                    $earnmoneytime = date_format($currentuser->getEmtime(), 'Y-m-d H:i:s');
                    $ok = 0;
                }
                $response = array(
                    'earnmoneytime' => "<span class='notice'>Le bouton sera utilisable a partir de " . $earnmoneytime . "</span>",
                    'money' => $money,
                    'ok' => $ok
                );
            }
            else
            {
                $response = array(
                    'earnmoneytime' => "<span class='warning'>Vous n'êtes pas donateur</span>",
                    'money' => 0,
                    'ok' => 0,
                );
            }
        }
        else
        {
            exit("Commande inconnue, veuillez contacter CoBrA");
        }

        if ($type != 2) {
            if ((time() - $command->getLastUse()->getTimestamp()) > $command->getDelay()) {
                if ($command->getType() == 0) {
                    $service->sendCommand($command->getCommand(), $session);
                } else {
                    $service->sendCommandCrea($command->getCommand(), $session);
                }
                $command->setLastUse(date_create());
                $em = $this->getDoctrine()->getEntityManager();
                $em->persist($command);
                $em->flush();
                $response['ok'] = 1;
            }
        }
        return new Response(json_encode($response));
    }

    /**
     * @Route("/chat_get", name="chat_get")
     */
    public function chat_getAction()
    {
        $service = $this->get('mc_server');
        $session = $this->getRequest()->getSession();
        $chat = $this->getDoctrine()->getRepository('MineDocHomeBundle:Chat');
        $last_messages = $chat->getLastMessages(40);
        array_reverse($last_messages);
        $messages = "";
        $chatstamp = $chat->getLast()->getDate()->getTimestamp();
        if ($session->get('chatstamp') != $chatstamp || $session->get('chatstamp') == 0)
        {
            foreach ($last_messages as $message)
            {
                $messages = "<div class='div_chat'><div class='time_chat'>".date_format($message->getDate(), '[H:i]')."</div><div class='name_chat''>".htmlspecialchars($message->getLogin()).":</div>".htmlspecialchars($message->getMessage())."</div>".$messages;
            }
            $messages = $service->chatParse($messages);
            $session->set('chatstamp', $chatstamp);
        }
        return new Response($messages);
    }

    /**
     * @Route("/chat_send", name="chat_send")
     */
    public function chat_sendAction()
    {
        $session = $this->getRequest()->getSession();
        $request = $this->get('request');
        $chat = $this->getDoctrine()->getRepository('MineDocHomeBundle:Chat');

        $currentuser = $this->getDoctrine()->getRepository('MineDocHomeBundle:User')->find($session->get('id'));
        if ($currentuser != null) {
            $login = $currentuser->getLogin();
        } else {
            return new Response();
        }

        if( $request->getMethod() == 'POST' )
        {
            $chat->postMessage($login ,$_POST['message']);
        }
        return new Response();
    }

    /**
     * @Route("/register", name="register")
     * @Template
     */
    public function registerAction()
    {
        $session = $this->getRequest()->getSession();
        $session->set('chatstamp', 0);
        $reguser = new User();
        $user = new User();
        $form_login = $this->createForm(new LoginType, $user);
        $form = $this->createForm(new RegisterType, $reguser);
        $request = $this->get('request');

        if( $request->getMethod() == 'POST' )
        {
            $form->bindRequest($request);

            if( $form->isValid() )
            {
                Mc::sendMail($reguser->getMail());
                $em = $this->getDoctrine()->getEntityManager();
                $em->persist($reguser);
                $em->flush();
                $this->get('session')->setFlash('notice', 'Vous êtes enregistré !');
                return $this->redirect($this->generateUrl('homepage'));
            }
        }

        return array(
            'form' => $form->createView(),
            'form_login' => $form_login->createView(),
        );
    }

    /**
     * @Route("/login", name="login")
     */
    public function loginAction()
    {
        $session = $this->getRequest()->getSession();
        $request = $this->get('request');
        $login = new Login;
        $form = $this->createForm(new LoginType(), $login);

        if ($request->getMethod() == 'POST') {
            $form->bindRequest($request);

            if ($form->isValid()) {
                $user = $this->getDoctrine()->getRepository('MineDocHomeBundle:User')->findOneByLogin($login->getLogin());
                if ( $user != null)
                {
                    if ($login->getPassword() == $user->getPassword())
                    {
                        $session->set('id', $user->getId());
                        $session->set('logged', 1);
                        $session->set('name', $user->getFirstname());
                        $session->set('level', $user->getLevel());
                        $session->setFlash('notice', 'Vous êtes loggé !');
                    }
                    else
                    {
                        $session->remove('logged');
                        $session->remove('level');
                        $session->setFlash('warning', 'Le login ou password est incorrect');
                    }
                }
                else
                {
                    $session->remove('logged');
                    $session->remove('level');
                    $session->setFlash('warning', 'Le login ou password est incorrect');
                }
            }
            return $this->redirect($this->generateUrl('homepage'));
        }
    }

    /**
     * @Route("/logout", name="logout")
     */
    public function logoutAction()
    {
        $session = $this->getRequest()->getSession();
        $session->clear();
        $session->setFlash('notice', 'Vous êtes déconnecté.');

        return $this->redirect($this->generateUrl('homepage'));
    }

    /**
     * @Route("/dons", name="dons")
     * @Template
     */
    public function donateAction()
    {
        $session = $this->getRequest()->getSession();
        $session->set('chatstamp', 0);
        $user = new User();
        $form_login = $this->createForm(new LoginType, $user);

        return array('form_login' => $form_login->createView());
    }

    /**
     * @Route("/shop", name="shop")
     * @Template
     */
    public function shopAction()
    {
        $session = $this->getRequest()->getSession();

        $session->set('chatstamp', 0);
        $user = new User();
        $form_login = $this->createForm(new LoginType, $user);

        $currentuser = $this->getDoctrine()->getRepository('MineDocHomeBundle:User')->find($session->get('id'));

        if ($currentuser != null) {
            $level = $currentuser->getLevel();
        } else {
            return $this->redirect($this->generateUrl('homepage'));
        }

        $money = $currentuser->getMoney();
        $rank = Mc::GetRank($level);

        $item_a = array();
        $entry = $this->getDoctrine()->getEntityManager()->getRepository("MineDocHomeBundle:Item")->getAvailableItems();
        $items = array();
        foreach ($entry as $item) {
            $item_a['name'] = $item->getName();
            $item_a['id'] = $item->getGameid();
            $item_a['price'] = number_format($item->getPrice(), 2);
            $item_a['counter'] = $item->getCounter();
            $item_a['category'] = $item->getCategory();
            $name_file = str_replace(" ", "_", "bundles/minedochome/images/icons/".$item_a['name'].".png");
            if (file_exists($name_file)) {
                $item_a["picture"] = $name_file;
            } else{
                $item_a["picture"] = "bundles/minedochome/images/icons/default_item.png";
            }
            $items[] = $item_a;
        }
        return array(
            'items' => $items,
            'form_login' => $form_login->createView(),
            'level' => $level,
            'rank' => $rank,
            'money' => $money,
            'login' => $currentuser->getLogin(),
        );
    }

    /**
     * @Route("/buy/{id}/{nbr}", name="buy")
     */
    public function buyAction($id, $nbr)
    {
        $session = $this->getRequest()->getSession();
        $service = $this->get('mc_server');

        $user = $this->getDoctrine()->getRepository('MineDocHomeBundle:User')->find($session->get('id'));

        if ($user != null)
        {
            $money_user = $user->getMoney();
            $item = $this->getDoctrine()->getRepository('MineDocHomeBundle:Item')->findOneByGameid($id);

            if ($item != null)
            {
                $money = $item->getPrice() * $nbr;
                if ($money_user >= $money)
                {
                    $user->setMoney($money_user - $money);
                    $item->setCounter($item->getCounter() + $nbr);
                    $em = $this->getDoctrine()->getEntityManager();
                    $em->persist($user);
                    $em->persist($item);
                    $em->flush();
                    $service->sendCommandBuy($item->getName(), $nbr, "give " . $user->getLogin() . " " . $id . " " . $nbr, $session);
                    $session->setFlash('notice', 'Achat effectué !');
                }
                else
                {
                    $session->setFlash('warning', 'Vous n\'avez pas assez de sous');
                }
            }
            else
            {
                $session->setFlash('warning', 'Item inconnu !');
            }
        }
        else
        {
            $session->setFlash('warning', 'Veuillez vous connecter');
        }
        return $this->redirect($this->generateUrl('shop'));
    }

    /**
     * @Route("/dynmap", name="dynmap")
     * @Template
     */
    public function dynmapAction()
    {
        $session = $this->getRequest()->getSession();

        $session->set('chatstamp', 0);
        $user = new User();
        $form_login = $this->createForm(new LoginType, $user);

        return array(
            'form_login' => $form_login->createView(),
        );
    }

}
