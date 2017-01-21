<?php

namespace DTRE\OeilBundle\Controller;

use DTRE\OeilBundle\Entity\Sensor;
use DTRE\OeilBundle\Form\SensorType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use FOS\RestBundle\Controller\Annotations as Rest; // alias pour toutes les annotations


class SensorController extends Controller
{
    /**
     * @Rest\View()
     * @Rest\Get("/sensors")
     */
    public function getSensorsAction(Request $request)
    {
        $sensors = $this
            ->getDoctrine()
            ->getRepository('DTREOeilBundle:Sensor')
            ->findAll();

        return $sensors;
    }

    /**
     * @Rest\View()
     * @Rest\Get("/sensors/{id}")
     */
    public function getSensorAction(Request $request)
    {
        $sensor = $this
            ->getDoctrine()
            ->getRepository('DTREOeilBundle:Sensor')
            ->find($request->get('id'));

        if (NULL ===$sensor) {
            return new JsonResponse(['message' => 'Sensor not found'], Response::HTTP_NOT_FOUND);
        }

        return $sensor;
    }

    /**
     * @Rest\View(statusCode=Response::HTTP_CREATED)
     * @Rest\Post("/sensors")
     */
    public function postUsersAction(Request $request)
    {
        $sensor = new Sensor();
        $form = $this->createForm(SensorType::class, $sensor);
        $form->submit($request->request->all());

        if ($form->isValid()){
            $em = $this
                ->getDoctrine()
                ->getManager();
            $em->persist($sensor);
            $em->flush();
            return $sensor;
        }
        else {
            return $form;
        }
    }
}
