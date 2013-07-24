<?php

namespace MineDoc\HomeBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use MineDoc\HomeBundle\Entity\Item;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Response;
use MineDoc\HomeBundle\Form\Type\LoginType;
use MineDoc\HomeBundle\Form\Type\ItemType;
use MineDoc\HomeBundle\Entity\User;
use MineDoc\HomeBundle\Lib\Mc;

class ShopController extends Controller
{
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

        $nbr = ($nbr < 0? 0 : $nbr);

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
                elseif ($nbr == 0)
                {
                    $session->setFlash('warning', 'Quantité invalide !');
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
}