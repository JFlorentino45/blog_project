<?php

namespace App\Repository;

use App\Entity\Blog;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Blog>
 *
 * @method Blog|null find($id, $lockMode = null, $lockVersion = null)
 * @method Blog|null findOneBy(array $criteria, array $orderBy = null)
 * @method Blog[]    findAll()
 * @method Blog[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class BlogRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Blog::class);
    }

    public function findAllOrderedByLatest(): array
    {
        return $this->createQueryBuilder('b')
            ->orderBy('b.createdAt', 'DESC')
            ->setMaxResults(7)
            ->getQuery()
            ->getResult();
    }

    public function findMoreBlogs(int $offset): array
    {
        return $this->createQueryBuilder('b')
            ->orderBy('b.createdAt', 'DESC')
            ->setMaxResults(5)
            ->setFirstResult($offset)
            ->getQuery()
            ->getResult();
    }

    public function findMyBlogsOrderedByLatest($user): array
    {
        return $this->createQueryBuilder('b')
            ->andWhere('b.createdBy = :user')
            ->setParameter('user', $user)
            ->orderBy('b.createdAt', 'DESC')
            ->setMaxResults(7)
            ->getQuery()
            ->getResult();
    }

    public function findMoreMyBlogs($user, $offset): array
    {
        return $this->createQueryBuilder('b')
            ->andWhere('b.createdBy = :user')
            ->setParameter('user', $user)
            ->orderBy('b.createdAt', 'DESC')
            ->setMaxResults(5)
            ->setFirstResult($offset)
            ->getQuery()
            ->getResult();
    }
}
