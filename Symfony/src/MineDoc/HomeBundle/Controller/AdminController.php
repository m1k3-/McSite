<?php

namespace MineDoc\HomeBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use MineDoc\HomeBundle\Entity\News;
use MineDoc\HomeBundle\Form\Type\NewsType;
use MineDoc\HomeBundle\Form\Type\PictureType;
use MineDoc\HomeBundle\Entity\Picture;
use MineDoc\HomeBundle\Entity\Item;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Response;
use MineDoc\HomeBundle\Form\Type\LoginType;
use MineDoc\HomeBundle\Form\Type\RegisterType;
use MineDoc\HomeBundle\Form\Type\ItemType;
use MineDoc\HomeBundle\Entity\User;

class AdminController extends Controller
{

    /**
     * @Route("/panel/{pages}/{orderby}/{type}/{search}", name="panel")
     * @Template()
     */
    public function panelAction($pages, $orderby, $type, $search)
    {
        $session = $this->getRequest()->getSession();
        $session->set('chatstamp', 0);
        $user = new User;
        $item = new Item;

        $opt = array();
        $opt['orderby'] = $orderby;
        $opt['type'] = $type;
        $opt['search'] = $search;

        $form_login = $this->createForm(new LoginType, $user);
        $item_create = $this->createForm(new ItemType, $item);

        $currentuser = $this->getDoctrine()->getRepository('MineDocHomeBundle:User')->find($session->get('id'));
        if ($currentuser != null) {
            $level = $currentuser->getLevel();
        } else {
            $level = 0;
        }

        $modifuser = new User;

        $ua = array();
        $entry = $this->getDoctrine()->getEntityManager()->getRepository("MineDocHomeBundle:User")->getLastUsers($pages, $opt);
        $users = array();
        foreach ($entry as $user)
        {
            $ua['form'] = $this->createForm(new RegisterType, $modifuser)->createView();
            $ua['user'] = $user;
            $users[] = $ua;
        }
        return array(
            'form_item' => $item_create->createView(),
            'users' => $users,
            'form_login' => $form_login->createView(),
            'level' => $level,
        );
    }

    /**
     * @Route("/newsadmin", name="newsadmin")
     * @Template()
     */
    public function newsadminAction()
    {
        $session = $this->getRequest()->getSession();
        $session->set('chatstamp', 0);

        $currentuser = $this->getDoctrine()->getRepository('MineDocHomeBundle:User')->find($session->get('id'));
        if ($currentuser != null) {
            $level = $currentuser->getLevel();
        } else {
            return $this->redirect($this->generateUrl('homepage'));
        }

        $username = $currentuser->getLogin();

        $testpic = new Picture($username);
        $picture_form = $this->createForm(new PictureType(), $testpic);

        $news = new News();
        $test_form = $this->createForm(new NewsType(), $news);

        $request = $this->get('request');

        if ($request->getMethod() == 'POST') {
            $test_form->bindRequest($request);
            $picture_form->bindRequest($request);

            if ($test_form->isValid() && $picture_form->isValid()) {
                $news->setPicture($testpic);
                $em = $this->getDoctrine()->getEntityManager();
                if (getimagesize("upload/" . $testpic->getName() . "/" . $testpic->getFile())['mime'] != "image/png" && getimagesize("upload/" . $testpic->getName() . "/" . $testpic->getFile())['mime'] != "image/jpeg") {
                    $this->get('session')->setFlash('warning', 'Type d\'image invalide !');
                    return $this->redirect($this->generateUrl('newsadmin'));
                }
                $em->persist($testpic);
                $em->persist($news);
                $em->flush();
                $this->get('session')->setFlash('notice', 'Ok !');
                return $this->redirect($this->generateUrl('homepage'));
            }
        }

        return array(
            'username' => $currentuser->getLogin(),
            'level' => $level,
            'picform' => $picture_form->createView(),
            'formtest' => $test_form->createView()
        );
    }

    /**
     * @Route("/do/{type}/{id}/{new}", name="do")
     */
    public function doitAction($type, $id, $new)
    {
        $session = $this->getRequest()->getSession();
        $request = $this->get('request');
        $service = $this->get('mc_server');
        $modifuser = new User;
        $form = $this->createForm(new RegisterType, $modifuser);

        $currentuser = $this->getDoctrine()->getRepository('MineDocHomeBundle:User')->find($session->get('id'));

        if ($currentuser != null) {
            $level = $currentuser->getLevel();
        } else {
            return $this->redirect($this->generateUrl('homepage'));
        }

        if ($level == 5)
        {
            $user = $this->getDoctrine()->getRepository('MineDocHomeBundle:User')->find($id);
            if ($type == 1) //Delete user
            {
                $service->sendCommand("whitelist remove " . $this->getDoctrine()->getRepository('MineDocHomeBundle:User')->find($id)->getLogin(), $session);
                $this->getDoctrine()->getEntityManager()->getRepository("MineDocHomeBundle:User")->deleteUser($id);
            }
            elseif ($type == 4) //Update level
            {
                if ($user->getLevel() == 0 && $new > 0)
                {
                    //$service->sendSilentCommand("authme register ". $user->getLogin() . " " . $user->getPassword(), $session);
                    $service->sendCommand("whitelist add ". $user->getLogin(), $session);
                }
                else if ($new == 0)
                {
                    $service->sendCommand("whitelist remove " . $this->getDoctrine()->getRepository('MineDocHomeBundle:User')->find($id)->getLogin(), $session);
                }
                $this->getDoctrine()->getEntityManager()->getRepository("MineDocHomeBundle:User")->updateLevelUser($id, $new);
            }
            elseif ($type == 7) {
                if ($user->getMoney() + $new < 0) {
                    $new = 0;
                } else {
                    $new = $new + $user->getMoney();
                }
                $this->getDoctrine()->getEntityManager()->getRepository("MineDocHomeBundle:User")->updateMoneyUser($id, $new);
            }
            else { //Update Fieldz
                if ($request->getMethod() == 'POST') {
                    $form->bindRequest($request);
                    $user = $this->getDoctrine()->getRepository('MineDocHomeBundle:User')->find($id);

                    if ($type == 2) {
                        $new = $modifuser->getLogin();
                        $this->getDoctrine()->getEntityManager()->getRepository("MineDocHomeBundle:User")->updateLoginUser($id, $new);
                    } elseif ($type == 3) {
                        $new = $modifuser->getMail();
                        $this->getDoctrine()->getEntityManager()->getRepository("MineDocHomeBundle:User")->updateMailUser($id, $new);
                    } elseif ($type == 5) {
                        $new = $modifuser->getPassword();
                        $this->getDoctrine()->getEntityManager()->getRepository("MineDocHomeBundle:User")->updatePassUser($id, $new);
                    } elseif ($type == 6) {
                        $new = $modifuser->getParrain();
                        $this->getDoctrine()->getEntityManager()->getRepository("MineDocHomeBundle:User")->updateParrainUser($id, $new);
                    }
                    else {
                        exit("Type de commande inconnu, veuillez contacter CoBrA");
                    }
                }
            }
        }
        return $this->redirect($this->generateUrl('panel', array('pages' => 15, 'orderby' => 'id', 'type' => 'asc', 'search' => 'nc')));
    }

    /**
     * @Route("/saveitem", name="saveitem")
     */
    public function saveitemAction()
    {
        $session = $this->getRequest()->getSession();
        $item = new Item;
        $form = $this->createForm(new ItemType, $item);
        $request = $this->get('request');

        if ($request->getMethod() == 'POST') {
            $form->bindRequest($request);

            if ($form->isValid() && $this->getDoctrine()->getRepository('MineDocHomeBundle:User')->find($session->get('id'))->getLevel() == 5) {
                $em = $this->getDoctrine()->getEntityManager();
                $em->persist($item);
                $em->flush();
            }
        }
        return $this->redirect($this->generateUrl('panel', array('pages' => 15, 'orderby' => 'id', 'type' => 'asc', 'search' => 'nc')));
    }
}