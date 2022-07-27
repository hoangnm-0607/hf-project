<?php


namespace App\Controller;


use App\Repository\CourseRepository;
use App\Service\CourseGeneratorService;
use Exception;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CourseEventGeneratorController
{
    private CourseGeneratorService $courseGeneratorService;

    private CourseRepository $courseRepository;

    public function __construct(
        CourseGeneratorService $courseGeneratorService,
        CourseRepository $courseRepository
    ) {
        $this->courseGeneratorService = $courseGeneratorService;
        $this->courseRepository = $courseRepository;
    }

    /**
     * @Route("/generate-course-events/", name="plugin_generate_course_events")
     * @throws Exception
     */
    public function generateCourseEvents(Request $request): JsonResponse
    {
        sleep(2); // wait for ajax

        if ($objectId = $request->get('object_id')) {
            $course         = $this->courseRepository->getOneCourseById($objectId);

            $dateList   = $this->courseGeneratorService->getEventSeriesDates(
                $course->getRepetition(),
                $course->getWeekdays(),
                $course->getCourseDate(),
                $course->getEndDate(),
            );

            $eventErrorCollection = $this->courseGeneratorService->generateNewEventCollection(
                $dateList,
                $course
            );

            $oldEventCollection = $this->courseRepository->getUnpublishedSingleEvents($course);

            $course->setSingleEvents(
                array_merge($oldEventCollection, iterator_to_array($eventErrorCollection->getEvents()))
            );
            $course->save();

            return new JsonResponse('success', Response::HTTP_OK);

        }
        return new JsonResponse('failure', Response::HTTP_INTERNAL_SERVER_ERROR);
    }


}
