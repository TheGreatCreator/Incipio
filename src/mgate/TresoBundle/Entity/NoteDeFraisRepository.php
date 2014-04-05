<?php

namespace mgate\TresoBundle\Entity;

use Doctrine\ORM\EntityRepository;

/**
 * NoteDeFraisRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class NoteDeFraisRepository extends EntityRepository
{
    /**
     * 
     * @return array
     */
    public function findAllByMandat() {        
        $entities = $this->findBy(array(), array('mandat' => 'desc', 'date' => 'asc'));
        $nfsParMandat = array();
        foreach($entities as $nf){
            $mandat = $nf->getMandat();
            if(array_key_exists($mandat, $nfsParMandat))
                $nfsParMandat[$mandat][] = $nf;
            else
                $nfsParMandat[$mandat] = array($nf);
        }        
        return $nfsParMandat;
    }
    
    /**
     * Renvoie les NDF pour un mois donné
     * YEAR MONTH DAY sont défini dans DashBoardBundle/DQL (qui doit devenir FrontEndBundle)
     * @return array
     */
    public function findAllByMonth($month, $year, $trimestriel = false) {
        $qb = $this->_em->createQueryBuilder();
        $query = $qb->select('f')
                     ->from('mgateTresoBundle:NoteDeFrais', 'f');
        if($trimestriel)
            $query->where("MONTH(f.date) >= $month")
                  ->andWhere("MONTH(f.date) < ($month + 2)");
        else
            $query->where("MONTH(f.date) = $month");
        
        $query->andWhere("YEAR(f.date) = $year")->orderBy("f.date");       
                    
        return $query->getQuery()->getResult();;
    }
}
