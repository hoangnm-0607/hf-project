<?php

namespace App\Service;

use App\Entity\Crondispatch;
use Carbon\Carbon;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\Persistence\ObjectManager;
use Exception;
use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Output\NullOutput;
use Symfony\Component\HttpKernel\KernelInterface;

class CronService
{
    const TABLE_NAME = 'crondispatch';

    private ObjectManager $entityManager;


    public function __construct(ManagerRegistry $doctrine)
    {
        $this->entityManager = $doctrine->getManager();
    }

    /**
     * @throws Exception
     */
    public function checkOrCreateLockedJob(string $job, string $parameters): bool
    {
        $crondispatchRepository = $this->entityManager->getRepository(Crondispatch::class);
        /** @var Crondispatch|null $jobEntry */
        $jobEntry = $crondispatchRepository->findOneBy(["jobname" => $job, "parameters" => $parameters]);
        if ($jobEntry && $jobEntry->getActive()) {
            return false;
        } else {
            if (!$jobEntry) {
                $jobEntry = new Crondispatch();
                $jobEntry->setJobname($job);
                $jobEntry->setParameters($parameters);
                $this->entityManager->persist($jobEntry);
            }
            $jobEntry->setActive(true);
            $jobEntry->setTimestamp(Carbon::now());

            $this->entityManager->flush();

            return true;
        }
    }

    /**
     * Returns 0 if job runs correctly, otherwise an error code
     *
     * @throws Exception
     */
    public function runCronjob(
        KernelInterface $kernel,
        string $job,
        string $parameters,
        bool $paramsAsArray = false,
    ): int
    {
        $application = new Application($kernel);
        $application->setAutoExit(false);
        $application->setCatchExceptions(true);

        $input =  ['command' => $job];
        if ($parameters) {
            $params = explode(' ', $parameters);
            foreach ($params as $param) {
                $keyValue = explode('=', $param,2);

                if ($keyValue[1]) {
                    $input[$keyValue[0]] = ($paramsAsArray ? [$keyValue[1]] : $keyValue[1]);
                } else {
                    $input[$keyValue[0]] = '';
                }
            }
        }

        $arrayInput = new ArrayInput($input);

        return $application->run($arrayInput, new NullOutput());
    }

    /**
     * @throws Exception
     */
    public function removeLockForJob(string $job, string $parameters): int
    {
        $qb = $this->entityManager->createQueryBuilder();
        return $qb->update(Crondispatch::class, 'c')
            ->set('c.active', 0)
            ->where('c.jobname = :jobname')
            ->setParameter('jobname', $job)
            ->andWhere('c.parameters = :parameters')
            ->setParameter('parameters', $parameters)
            ->getQuery()->execute();
    }

}
