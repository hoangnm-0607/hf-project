<?php


namespace App\Controller;


use App\Repository\SingleEventRepository;
use Carbon\Carbon;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AdminController
{

    private SingleEventRepository $singleEventRepository;

    public function __construct(SingleEventRepository $singleEventRepository)
    {
        $this->singleEventRepository = $singleEventRepository;
    }

    /**
     * @Route("/check-single-events/")
     */
    public function checkIfCourseEventIsModifiable(Request $request)
    {
        $reason = '';

        $id = $request->query->getInt('id');
        if ($object = $this->singleEventRepository->getOneSingleEventById($id)) {
            if ($object->getCourseDate() >= new Carbon() && $object->getBookings()) {
                $reason = 'bookings';
            } elseif ($object->getCourseDate() <= new Carbon()) {
                $reason = 'pastDate';
            }

            $response = [
                'message' => $reason,
            ];
        }

        return new JsonResponse($response ?? [], Response::HTTP_OK);
    }
}
