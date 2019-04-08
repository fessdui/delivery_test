<?php
namespace App\Controller;

use App\Entity\Schedule;
use App\Repository\ScheduleRepository;
use phpDocumentor\Reflection\DocBlock\Tags\Var_;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\Entity\Courier;
use App\Entity\Region;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use App\Service\AppHelper;
use Symfony\Component\Serializer\Serializer;

/**
 * Index controller.
 *
 * Class IndexController
 * @package App\Controller
 */
class IndexController extends AbstractController
{
    /**
     * @Route("/")
     * @return Response
     */
    public function getList()
    {
        $pageName = 'index';
        /** @var ScheduleRepository $scheduleRepository */
        $scheduleRepository = $this->getDoctrine()
            ->getRepository(Schedule::class);
        $schedules = $scheduleRepository->findAll();

        return $this->render('index.html.twig', [
            'schedules' => $schedules,
        ]);
    }

    /**
     * @Route("/test")
     * @throws \Exception
     */
    public function createScheduleForm(){
        $region = new Region();
        /**
         * For start show couriers for current date.
         */
        $date = new \DateTime('now', new \DateTimeZone('GMT+3'));

        $courier = $this->getDoctrine()
            ->getRepository(Courier::class)
            ->getFreeCourier($region, $date);

        $regions = $this->getDoctrine()
            ->getRepository(Region::class)
            ->findAll();

        return $this->render('form.html.twig', [
            'couriers' => $courier,
            'regions' => $regions,
        ]);
    }

    /**
     * @Route("/checkfreecourier", name="check_free_courier")
     * @param Request $request
     * @return JsonResponse
     * @throws \Exception
     */
    public function checkFreeCourier(Request $request){
        if ($request->isXmlHttpRequest()) {
            $result = ['error' => true];
            $regionId = $request->request->get('region');
            $dateFromPost = $request->request->get('date');
            $productExist = $request->request->get('product_exist');

            $date = new \DateTime($dateFromPost, new \DateTimeZone('GMT+3'));

            /** @var Region $region */
            $region = $this->getDoctrine()
                ->getRepository(Region::class)
                ->find($regionId);

            if ($region) {
                $couriers = $this->getDoctrine()
                    ->getRepository(Courier::class)
                    ->getFreeCourier($region, $date, $productExist);

                /** @var ScheduleRepository $scheduleRepository */
                $scheduleRepository = $this->getDoctrine()
                    ->getRepository(Schedule::class);
                $estimatedArrival = $scheduleRepository->getEstimatedTimeArrival($dateFromPost, $region);

                /** @var Serializer $serializer */
                $serializer = $this->container->get('serializer');
                $couriers = $serializer->serialize($couriers, 'json');

                $result = [
                    'error' => false,
                    'couriers' => $couriers,
                    'estimate_arrival_time' => $estimatedArrival->format('Y-m-d H:i:s')
                ];
            }

            return new JsonResponse($result);
        }

        throw new \Exception('No route!');
    }

    /**
     * @Route("/saveSchedule", name="save_schedule")
     * @param Request $request
     * @return JsonResponse
     * @throws \Exception
     */
    public function saveSchedule(Request $request){
        if ($request->isXmlHttpRequest()) {
            $result = ['error' => true];
            $regionId = $request->request->get('region');
            $dateFromPost = $request->request->get('date');
            $courierId = $request->request->get('courier');

            /** @var Region $region */
            $region = $this->getDoctrine()
                ->getRepository(Region::class)
                ->find($regionId);

            if ($region) {
                /** @var ScheduleRepository $scheduleRepository */
                $scheduleRepository = $this->getDoctrine()
                    ->getRepository(Schedule::class);
                /** @var Courier $courier */
                $courier = $this->getDoctrine()
                    ->getRepository(Courier::class)
                    ->find($courierId);

                if ($scheduleRepository->saveSchedule($region,$dateFromPost, $courier)) {
                    $result['error'] = false;
                    $result['result'] = 'Запись успешно создана!';
                } else {
                    $result['result'] = 'Такой Курьер не найден!';
                }
            } else {
                $result['result'] = 'Такой Регион не найден!';
            }

            return new JsonResponse($result);
        }

        throw new \Exception('No route!');
    }
}