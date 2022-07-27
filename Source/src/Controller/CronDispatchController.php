<?php

namespace App\Controller;

use App\Service\CronService;
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\KernelInterface;
use Symfony\Component\Routing\Annotation\Route;

class CronDispatchController extends AbstractController
{
    const DISPATCHABLE_JOBS = [
        'pimcore:maintenance',
        'hanse:archive',
        'hanse:notify:missinglink',
        'hanse:notify:cancelled',
        'hanse:notify:imminent',
        'hanse:cleanup:assets',
        'hanse:cleanup:voucher',
        'hanse:cleanup:partner:terminated'
    ];

    private CronService $cronService;

    public function __construct(CronService $cronService)
    {
        $this->cronService = $cronService;
    }


    /**
     * @Route ("/system/dispatch/{job}", methods={"GET"}, requirements={"job"="[a-z-]+"})
     *
     * @param string          $job
     * @param KernelInterface $kernel
     * @param Request $request
     *
     * @return Response
     * @throws \Doctrine\DBAL\Exception
     * @throws Exception
     */
    public function dispatchAction(string $job, KernelInterface $kernel, Request $request): Response
    {
        $job = str_replace('-', ':', $job);
        $jobParams = $request->get('params') ?? '';

        if (in_array($job, self::DISPATCHABLE_JOBS)) {

            // We'll rely on pimcore:maintenance own locking mechanism, so we'll skip our own
            if ($job === 'pimcore:maintenance') {
                $this->cronService->runCronjob($kernel, $job, $jobParams, true);
            } else {
                if ( $this->cronService->checkOrCreateLockedJob($job, $jobParams) ) {
                    $this->cronService->runCronjob($kernel, $job, $jobParams);
                    // We're logging the errors of our own jobs, so we'll remove the lock afterwards to prevent blocking.
                    $this->cronService->removeLockForJob($job, $jobParams);
                }
            }

            return new Response('', Response::HTTP_ACCEPTED);
        }

        return new Response('Authorization required!', Response::HTTP_UNAUTHORIZED);
    }
}
