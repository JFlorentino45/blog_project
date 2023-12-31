<?php

namespace App\Repository;

use App\Entity\Comments;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class CommentsRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Comments::class);
    }

    public function findBlogOrderedByLatest(int $blogId): array
    {
        return $this->createQueryBuilder('c')
            ->Where('c.hidden = 0')
            ->andWhere('c.blog = :blogId')
            ->setParameter('blogId', $blogId)
            ->orderBy('c.createdAt', 'DESC')
            ->getQuery()
            ->getResult();
    }

    public function findBlogOrderedByLatestAdmin(int $blogId): array
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.blog = :blogId')
            ->setParameter('blogId', $blogId)
            ->orderBy('c.createdAt', 'DESC')
            ->getQuery()
            ->getResult();
    }

    public function findAllOrderedByLatest(): array
    {
        return $this->createQueryBuilder('c')
            ->orderBy('c.createdAt', 'DESC')
            ->getQuery()
            ->getResult();
    }

    public function findReported(): array
    {
        return $this->createQueryBuilder('c')
            ->Where('c.hidden = 1')
            ->orderBy('c.createdAt', 'DESC')
            ->getQuery()
            ->getResult();
    }
}
