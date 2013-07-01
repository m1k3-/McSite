<?php

namespace MineDoc\HomeBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use MineDoc\HomeBundle\Entity\Item;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Response;
use MineDoc\HomeBundle\Form\Type\LoginType;
use MineDoc\HomeBundle\Entity\Login;
use MineDoc\HomeBundle\Form\Type\ItemType;
use MineDoc\HomeBundle\Entity\User;
use MineDoc\HomeBundle\Lib\Mc;

class ActionController extends Controller
{
    /**
     * @Route("/sendutil/{name}", name="send_util_command")
     */
    public function send_util_commandAction($name)
    {
        $session = $this->getRequest()->getSession();
        $service = $this->get('mc_server');

        $currentuser = $this->getDoctrine()->getRepository('MineDocHomeBundle:User')->find($session->get('id'));

        if ($currentuser != null) {
            $level = $currentuser->getLevel();
        } else {
            return $this->redirect($this->generateUrl('homepage'));
        }

        $money = $currentuser->getMoney();

        if ($name =="tp") {
            if ($money >= 20) {
                $currentuser->setMoney($money - 20);
                $em = $this->getDoctrine()->getEntityManager();
                $em->persist($currentuser);
                $em->flush();
                $service->sendCommand("tp " . $currentuser->getLogin() . " -135 65 248", $session);
                $response = array(
                    'notice' => "<span class='notice'>Et hop !</span>",
                    'money' => $money - 20,
                );
            }
            else {
                $response = array(
                    'notice' => "<span class='warning'>Vous n'avez pas assez de sous !</span>",
                    'money' => $money,
                );
            }
        }
        return new Response(json_encode($response));
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
     * @Route("/login", name="login")
     */
    public function loginAction()
    {
        $session = $this->getRequest()->getSession();
        $request = $this->get('request');
        $login = new Login();
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
                        $session->set('login', $user->getLogin());
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
}