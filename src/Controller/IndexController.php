<?php
namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

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
    public function number()
    {
        $pageName = 'index';

        return $this->render('index.html.twig', [
            'page_ame' => $pageName,
        ]);
    }
}