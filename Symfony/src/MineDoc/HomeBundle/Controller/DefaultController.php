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
        if ($session->get('alert_mail') == 2) {
            $session->set('alert_mail', 1);
        } elseif ($session->get('alert_mail') == 2) {
            $session->set('alert_mail', 0);
        }
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
     * @Route("/news", name="news")
     * @Template()
     */
    public function newsAction()
    {
        $session = $this->getRequest()->getSession();
        $session->set('chatstamp', 0);
        $user = new User;
        $form_login = $this->createForm(new LoginType(), $user);

        $news = $this->getDoctrine()->getRepository('MineDocHomeBundle:News')->getLastNews(30);

        return array(
            'form_login' => $form_login->createView(),
            'news' => $news,
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
     * @Route("/chat_get", name="chat_get")
     */
    public function chat_getAction()
    {
        $service = $this->get('mc_server');
        $session = $this->getRequest()->getSession();
        $chat = $this->getDoctrine()->getRepository('MineDocHomeBundle:Chat');
        $last_messages = $chat->getLastMessages(35);
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
     * @Route("/account", name="account")
     * @Template
     */
    public function accountAction()
    {
        $session = $this->getRequest()->getSession();
        $session->set('chatstamp', 0);

        $currentuser = $this->getDoctrine()->getRepository('MineDocHomeBundle:User')->find($session->get('id'));
        if ($currentuser != null) {
            $level = $currentuser->getLevel();
        } else {
            return $this->redirect($this->generateUrl('homepage'));
        }

        $user = new User;
        $tmpuser = new User;

        $form_login = $this->createForm(new LoginType, $user);
        $form = $this->createForm(new RegisterType, $tmpuser);

        $request = $this->get('request');

        if($request->getMethod() == 'POST')
        {
            $form->bindRequest($request);

            if($tmpuser->getPassword() != null)
            {
                $em = $this->getDoctrine()->getEntityManager();
                $currentuser->setPassword($tmpuser->getPassword());
                $em->persist($currentuser);
                $em->flush($currentuser);
                $this->get('session')->setFlash('notice', 'Informations modifiées !');
                return $this->redirect($this->generateUrl('homepage'));
            }
        }

        return array(
            'form' => $form->createView(),
            'form_login' => $form_login->createView(),
        );
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
                $this->get('session')->set('alert_mail', 2);
                return $this->redirect($this->generateUrl('homepage'));
            }
        }

        return array(
            'form' => $form->createView(),
            'form_login' => $form_login->createView(),
        );
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
