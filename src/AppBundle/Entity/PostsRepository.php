<?php

namespace AppBundle\Entity;

use Doctrine\ORM\EntityRepository;

/**
 * PostsRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class PostsRepository extends EntityRepository
{
    	public function findPost() {
		$query = $this->createQueryBuilder('p');
		return $query->getQuery()->getResult();
	}
}
