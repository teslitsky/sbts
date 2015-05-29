<?php

namespace Sbts\Bundle\IssueBundle\Entity;

use Doctrine\ORM\EntityRepository;
use Sbts\Bundle\ProjectBundle\Entity\Project;
use Sbts\Bundle\UserBundle\Entity\User;

class ActivityRepository extends EntityRepository
{
    /**
     * @param Project $project
     * @param int     $limit
     *
     * @return array
     */
    public function findAllByProject($project, $limit = 20)
    {
        return $this
            ->getEntityManager()
            ->createQueryBuilder()
            ->select(['activity', 'user', 'issue'])
            ->from('SbtsIssueBundle:Activity', 'activity')
            ->innerJoin('activity.issue', 'issue')
            ->innerJoin('activity.user', 'user')
            ->innerJoin('issue.project', 'project', 'WITH', 'project.id = :project_id')
            ->setParameter('project_id', $project)
            ->orderBy('activity.created', 'DESC')
            ->setMaxResults($limit)
            ->getQuery()
            ->getResult();
    }

    /**
     * @param User $user
     * @param int  $limit
     *
     * @return array
     */
    public function findAllByUserAccessGranted($user, $limit = 20)
    {
        return $this
            ->getEntityManager()
            ->createQueryBuilder()
            ->select(['activity', 'user', 'issue'])
            ->from('SbtsIssueBundle:Activity', 'activity')
            ->innerJoin('activity.issue', 'issue')
            ->innerJoin('activity.user', 'user')
            ->innerJoin('issue.project', 'project')
            ->innerJoin('project.users', 'projectUsers', 'WITH', 'projectUsers.id = :user_id')
            ->setParameter('user_id', $user)
            ->orderBy('activity.created', 'DESC')
            ->setMaxResults($limit)
            ->getQuery()
            ->getResult();
    }

    /**
     * @param Issue $issue
     * @param int   $limit
     *
     * @return array
     */
    public function findAllByIssue($issue, $limit = 10)
    {
        return $this
            ->getEntityManager()
            ->createQueryBuilder()
            ->select(['activity', 'user', 'issue'])
            ->from('SbtsIssueBundle:Activity', 'activity')
            ->innerJoin('activity.issue', 'issue', 'WITH', 'issue.id = :issue_id')
            ->innerJoin('activity.user', 'user')
            ->setParameter('issue_id', $issue)
            ->orderBy('activity.created', 'DESC')
            ->setMaxResults($limit)
            ->getQuery()
            ->getResult();
    }

    /**
     * @param User $user
     * @param int  $limit
     *
     * @return array
     */
    public function findAllByUser($user, $limit = 20)
    {
        return $this
            ->getEntityManager()
            ->createQueryBuilder()
            ->select('activity')
            ->from('SbtsIssueBundle:Activity', 'activity')
            ->where('activity.user = :user_id')
            ->setParameter('user_id', $user)
            ->orderBy('activity.created', 'DESC')
            ->setMaxResults($limit)
            ->getQuery()
            ->getResult();
    }

    /**
     * @param int $limit
     *
     * @return array
     */
    public function findAllSortedByDate($limit = 20)
    {
        return $this
            ->getEntityManager()
            ->createQueryBuilder()
            ->select('activity')
            ->from('SbtsIssueBundle:Activity', 'activity')
            ->orderBy('activity.created', 'DESC')
            ->setMaxResults($limit)
            ->getQuery()
            ->getResult();
    }
}
