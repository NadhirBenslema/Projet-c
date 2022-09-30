<?php

namespace App\Controller;

use App\Entity\Cours;
use App\Entity\NiveauDifficulte;
use App\Form\CoursType;
use App\Repository\CoursRepository;
use App\Repository\NiveauDifficulteRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;

/**
 * @Route("/cours")
 */

class CoursController extends AbstractController
{
    /**
     * @Route("/", name="app_cours_index")
     */
    public function index(): Response
    {
        $coursers=$this->getDoctrine()->getRepository(Cours::class)->findAll();

        foreach ($coursers as $courser){
            $datec[] = [
                'id' => $courser->getId(),
                'start' => $courser->getDate()->format('Y-m-d H:i:s'),
                'title' => $courser->getDescription(),
            ];
        }

        $data = json_encode($datec);
        return $this->render('cours/index.html.twig', [
            'coursers' => $coursers,
            'data' => $data,

        ]);
    }


    /**
     * @Route("/new", name="app_cours_new", methods={"GET", "POST"})
     */
    public function new(Request $request, CoursRepository $coursRepository): Response
    {
        $cours = new cours();
        $form = $this->createForm(CoursType::class, $cours);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $coursRepository->add($cours);
            return $this->redirectToRoute('app_cours_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('cours/add.html.twig', [
            'cours' => $cours,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/edit/{id}", name="app_cours_edit", methods={"GET", "POST"})
     */
    public function edit(Request $request, Cours $cours, CoursRepository $coursRepository): Response
    {
        $form = $this->createForm(CoursType::class, $cours);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $coursRepository->add($cours);
            return $this->redirectToRoute('app_cours_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('cours/edit.html.twig', [
            'cours' => $cours,
            'form' => $form->createView(),
        ]);
    }
    /**
     * @Route("/delete/{id}", name="app_cours_delete")
     * method=({"DELETE"})
     */
    public function delete(Request $request, $id)
    {
        $cours = $this->getDoctrine()->getRepository(Cours::class)->find($id);

        $entityyManager = $this->getDoctrine()->getManager();
        $entityyManager->remove($cours);
        $entityyManager->flush();

        $response = new Response();
        $response->send();

        return $this->redirectToRoute('app_cours_index', [], Response::HTTP_SEE_OTHER);
    }

    //*****MOBILE

    /**
     * @Route("/mobile/aff", name="affmobCompta")
     */
    /*
    public function affmobCompta(NormalizerInterface $normalizer)
    {
        $med=$this->getDoctrine()->getRepository(Comptabilite::class)->findAll();
        $jsonContent = $normalizer->normalize($med,'json',['comptabilite'=>'post:read']);
        return new Response(json_encode($jsonContent));
    }

    /**
     * @Route("/mobile/new", name="addmobCompta")
     */
    /*
    public function addmobCompta(Request $request,NormalizerInterface $normalizer,ComptabiliteRepository $comptabiliteRepository)
    {
        $em=$this->getDoctrine()->getManager();
        $compta= new Comptabilite();
        $compta->setLibelle($request->get('libelle'));
        $compta->setDescription($request->get('description'));
        $typecompta = $this->getDoctrine()->getRepository(TypeComptabilite::class)->findOneBy([
            'type' =>  $request->get('type')
        ]);
        $compta->setIdType($typecompta);

        $rest=substr($request->get('datec'), 0, 20);
        $rest1=substr($request->get('datec'), 30, 34);
        $res=$rest.$rest1;
        try {
            $date = new \DateTime($res);
            $compta->setDate($date);
        } catch (\Exception $e) {

        }
        $comptabiliteRepository->add($compta);


        $jsonContent = $normalizer->normalize($compta,'json',['comptabilite'=>'post:read']);
        return new Response(json_encode($jsonContent));
    }

    /**
     * @Route("/mobile/edit", name="editmobCompta")
     */
    /*
    public function editmobCompta(Request $request,NormalizerInterface $normalizer)
    {   $em=$this->getDoctrine()->getManager();
        $compta = $em->getRepository(Comptabilite::class)->find($request->get('id'));
        $compta->setLibelle($request->get('libelle'));
        $compta->setDescription($request->get('description'));
        $typecompta = $this->getDoctrine()->getRepository(TypeComptabilite::class)->findOneBy([
            'type' =>  $request->get('type')
        ]);
        $compta->setIdType($typecompta);

        $rest=substr($request->get('datec'), 0, 20);
        $rest1=substr($request->get('datec'), 30, 34);
        $res=$rest.$rest1;
        try {
            $date = new \DateTime($res);
            $compta->setDate($date);
        } catch (\Exception $e) {

        }


        $em->flush();
        $normalizer = new ObjectNormalizer();
        $normalizer->setCircularReferenceLimit(1);
        $normalizer->setCircularReferenceHandler(function ($compta) {
            return $compta->getId();
        });
        $encoders = [new JsonEncoder()];
        $normalizers = array($normalizer);
        $serializer = new Serializer($normalizers,$encoders);
        $formatted = $serializer->normalize($compta);
        return new JsonResponse($formatted);
    }
    /**
     * @Route("/mobile/del", name="delmobCompta")
     */
    /*
    public function delmobCompta(Request $request,NormalizerInterface $normalizer)
    {           $em=$this->getDoctrine()->getManager();
        $type=$this->getDoctrine()->getRepository(Comptabilite::class)
            ->find($request->get('id'));
        $em->remove($type);
        $em->flush();
        $jsonContent = $normalizer->normalize($type,'json',['comptabilite'=>'post:read']);
        return new Response(json_encode($jsonContent));
    }
    */

}
