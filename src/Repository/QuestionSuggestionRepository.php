<?php

namespace App\Repository;

use App\Entity\Answer;
use App\Entity\Question;
use App\Entity\QuestionSuggestion;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\NonUniqueResultException as NonUniqueResultExceptionAlias;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<QuestionSuggestion>
 *
 * @method QuestionSuggestion|null find($id, $lockMode = null, $lockVersion = null)
 * @method QuestionSuggestion|null findOneBy(array $criteria, array $orderBy = null)
 * @method QuestionSuggestion[]    findAll()
 * @method QuestionSuggestion[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class QuestionSuggestionRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, QuestionSuggestion::class);
    }

    public function save(QuestionSuggestion $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(QuestionSuggestion $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

//    /**
//     * @return QuestionSuggestion[] Returns an array of QuestionSuggestion objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('q')
//            ->andWhere('q.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('q.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

    /**
     * @param Question $question
     * @param Answer $answer
     * @return QuestionSuggestion|null
     * @throws NonUniqueResultExceptionAlias
     */
    public function findSuggestionByQuestionAndAnswer(Question $question, Answer $answer): ?QuestionSuggestion
    {
        return $this->createQueryBuilder('q')
            ->andWhere('q.question = :question')
            ->setParameter('question', $question)
            ->andWhere('q.answer = :answer')
            ->setParameter('answer', $answer)
            ->andWhere('q.isCorrect = :isCorrect')
            ->setParameter('isCorrect', 1)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
}
