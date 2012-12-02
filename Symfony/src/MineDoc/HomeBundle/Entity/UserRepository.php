<?php

namespace MineDoc\HomeBundle\Entity;

use Doctrine\ORM\EntityRepository;

/**
 * UserRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class UserRepository extends EntityRepository
{
    public function getLastUsers($i, $opt)
    {
        $search = "";
        $parameters = array();
        if ($opt['search'] != "nc" && trim($opt['search']) != "") {
            $keywords = explode(" ", trim($opt['search']));

            $search = " WHERE ";
            $separator= " AND ";
            $l = 0;
            foreach ($keywords as $keyword) {
                $parameter = 'keyword' . $l;
                $parameters[$parameter] = '%'.$keyword.'%';
                $search .= " (u.id LIKE :" . $parameter . " OR u.firstname LIKE :" . $parameter . " OR u.login LIKE :" . $parameter . " OR u.mail LIKE :" . $parameter . " OR u.name LIKE :" . $parameter . ") AND";
                $l++;
            }
            $search = substr($search, 0, -3);
        } else {
            $separator= " WHERE ";
        }

        $add ="";

        if ($opt['more'] == "act") {
            $add = " ". $separator ." u.level = 0 ";
        } elseif ($opt['more'] == "nact") {
            $add = " ". $separator ." u.level = -1 ";
        }

        $orderby = " ORDER BY u." . $opt['orderby'] . " " . $opt['type'];
        $counter = $this->getEntityManager()
            ->createQuery('SELECT COUNT(u) FROM MineDocHomeBundle:User u ' . $search . $add . $orderby)
            ->getSingleScalarResult();

        $query =  $this->getEntityManager()
            ->createQuery('SELECT u FROM MineDocHomeBundle:User u ' . $search . $add . $orderby);
        foreach ($parameters as $key => $value) {
            $query->setParameter($key, $value);
        }
        $res['obj'] = $query->setMaxResults($i)->getResult();
        $res['count'] = $counter;
        return $res;
    }

    public function deleteUser($id)
    {
        $query =  $this->getEntityManager()->createQuery('DELETE FROM MineDocHomeBundle:User u WHERE u.id = ?1');
        $query->setParameter(1, $id);
        $query->execute();
    }

    public function updateLevelUser($id, $level)
    {
        $query = $this->getEntityManager()->createQuery('UPDATE MineDocHomeBundle:User u SET u.level = ?2 WHERE u.id = ?1');
        $query->setParameter(1, $id);
        $query->setParameter(2, $level);
        $query->execute();
    }

    public function changeMoneyUser($id, $money)
    {
        $query = $this->getEntityManager()->createQuery('UPDATE MineDocHomeBundle:User u SET u.money = u.money + ?2 WHERE u.id = ?1');
        $query->setParameter(1, $id);
        $query->setParameter(2, $money);
        $query->execute();
    }

    public function updateMailUser($id, $mail)
    {
        $query = $this->getEntityManager()->createQuery('UPDATE MineDocHomeBundle:User u SET u.mail = ?2 WHERE u.id = ?1');
        $query->setParameter(1, $id);
        $query->setParameter(2, $mail);
        $query->execute();
    }

    public function updatePassUser($id, $pass)
    {
        $query = $this->getEntityManager()->createQuery('UPDATE MineDocHomeBundle:User u SET u.password = ?2 WHERE u.id = ?1');
        $query->setParameter(1, $id);
        $query->setParameter(2, $pass);
        $query->execute();
    }

    public function updateParrainUser($id, $parrain)
    {
        $query = $this->getEntityManager()->createQuery('UPDATE MineDocHomeBundle:User u SET u.parrain = ?2 WHERE u.id = ?1');
        $query->setParameter(1, $id);
        $query->setParameter(2, $parrain);
        $query->execute();
    }

    public function updateLoginUser($id, $login)
    {
        $query = $this->getEntityManager()->createQuery('UPDATE MineDocHomeBundle:User u SET u.login = ?2 WHERE u.id = ?1');
        $query->setParameter(1, $id);
        $query->setParameter(2, $login);
        $query->execute();
    }

    public function updateMoneyUser($id, $money)
    {
        $query = $this->getEntityManager()->createQuery('UPDATE MineDocHomeBundle:User u SET u.money = ?2 WHERE u.id = ?1');
        $query->setParameter(1, $id);
        $query->setParameter(2, $money);
        $query->execute();
    }
}
