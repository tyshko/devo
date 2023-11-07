<?php

namespace App\Repository;

use App\Entity\ToDo;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<ToDo>
 *
 * @method ToDo|null find($id, $lockMode = null, $lockVersion = null)
 * @method ToDo|null findOneBy(array $criteria, array $orderBy = null)
 * @method ToDo[]    findAll()
 * @method ToDo[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ToDoRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ToDo::class);
    }

    public function findAllTodos(int $page = 1, int $limit = 10)
    {
        $offset = ($page - 1) * $limit;

        $qb = $this->createQueryBuilder('t')
            ->setFirstResult($offset)
            ->setMaxResults($limit);

        return $qb->getQuery()->getArrayResult();
    }
}
