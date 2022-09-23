<?php

namespace App\Controller;

use App\Entity\Cours;
use App\Repository\CoursRepository;
use App\Form\CoursType;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;

class CoursController extends AbstractController
{
    /**
     * @Route("/cours", name="app_cours")
     */
    public function index(): Response
    {
        return $this->render('cours/index.html.twig', [
            'controller_name' => 'CoursController',
        ]);
    }


    /**
     * @Route("/listcours", name="app_listcours")
     */
    public function list(): Response
    {
        $cours=$this->getDoctrine()->getRepository(Cours::class)->findAll();
        return $this->render('cours/listCours.html.twig', [
            'cours' => $cours,
        ]);
    }


    /**
     * @Route("/addcours", name="app_addcours", methods={"GET", "POST"})
     */
    public function add(Request $request, CoursRepository $coursRepository):Response
    {
        $cours= new Cours();
        $form= $this->createForm(CoursType::class,$cours);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $coursRepository->add($cours);
            $file = $cours->getImagec();
            $fileName=md5(uniqid()).'.'.$file->guessExtension();

            try {
                $file->move(
                    $this->getParameter('images_directory'),
                    $fileName
                );
            } catch (FileException $e) {
                // ... handle exception if something happens during file upload
            }

            $em = $this->getDoctrine()->getManager();
            $cours->setImagec($fileName);
            $em->persist($cours);
            $em->flush();
            $this->addFlash('message', 'Ajout avec succés !');
            return $this->redirectToRoute("app_addcours");

        }
        return $this->render("cours/add.html.twig",array("formcours"=>$form->createView()));
    }


    /**
     * @Route("/visualiser/{id}",name="visualiser")
     */
    public function visualiser($id){
        $cours = $this->getDoctrine()->getRepository(Cours::class)->find($id);
        return $this->render('cours/afficheC.html.twig', [
            'cours' => $cours,
        ]);

    }


    /**
     * @Route("/delete/{id}", name="app_deletecours")
     * method=({"DELETE"})
     */
    public function delete($id)
    {
        $cours = $this->getDoctrine()->getRepository(cours::class)->find($id);

        $entityyManager = $this->getDoctrine()->getManager();
        $entityyManager->remove($cours);
        $entityyManager->flush();
        
        $this->addFlash('message', 'Suppression avec succée !');
        

        return $this->redirectToRoute('app_listcours');
    }



}
