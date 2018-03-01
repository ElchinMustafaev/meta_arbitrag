<?php
/**
 * Created by PhpStorm.
 * User: el
 * Date: 01.03.18
 * Time: 16:38
 */

namespace ApiBundle\Service;
use Symfony\Component\DependencyInjection\ContainerInterface;

class DashBoard
{
    private $contailer;

    private $em;

    /**
     * Livecoin constructor.
     *
     * @param ContainerInterface $container
     * @param \Doctrine\ORM\EntityManager $em
     */
    function __construct(ContainerInterface $container, \Doctrine\ORM\EntityManager $em)
    {
        $this->container = $container;
        $this->em = $em;
    }

    public function getInfoForTable()
    {
        $qb = $this
            ->em
            ->createQueryBuilder('h');
        $qb->select('h.exchange1, h.spread, h.pair, h.revSpread, h.exchange2,  h.timeStamp')
            ->from('ApiBundle:History', 'h')
            ->orderBy('h.timeStamp', 'ASC')
            ->groupBy('h.exchange1, h.exchange2, h.pair')
            //->where('ISNUMERIC(h.spread) AND ISNUMERIC(h.rev_spread)')
        ;
        $query = $qb->getQuery();
        $result = $query->getResult();

        foreach ($result as $key => $value)
        {
            if (!(is_numeric($value['spread']) && is_numeric($value['revSpread']))) {
                unset($result[$key]);
            } else {
                $result[$key]['timeStamp'] = date(DATE_ATOM, $value['timeStamp']);
            }
        }

        return $result;
    }
}