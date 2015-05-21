<?php

namespace Sbts\Bundle\ProjectBundle\Model;

use Doctrine\ORM\EntityManager;
use Sbts\Bundle\ProjectBundle\Entity\Project;

class ProjectManager
{
    /**
     * @var EntityManager
     */
    private $em;

    /**
     * @param EntityManager $em
     */
    public function __construct(EntityManager $em)
    {
        $this->em = $em;
    }

    /**
     * @return array|Project[]
     */
    public function getAllProjects()
    {
        $projects = $this->em
            ->getRepository('SbtsProjectBundle:Project')
            ->findAll();

        return $projects;
    }

    /**
     * @param Project $project
     * @return Project
     */
    public function saveProject(Project $project)
    {
        $this->em->persist($project);
        $this->em->flush();

        return $project;
    }
}
