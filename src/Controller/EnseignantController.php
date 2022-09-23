<?php

namespace App\Controller;

use App\Entity\Enseignant;

use App\Form\EnseignantType;
use Knp\Component\Pager\PaginatorInterface;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
class EnseignantController extends AbstractController
{
    /**
     * @Route("/enseignant", name="app_enseignant")
     */
    public function index(): Response
    {
        return $this->render('enseignant/index.html.twig', [
            'controller_name' => 'EnseignantController',
        ]);
    }


     /**
     * @Route("/enseignant_list",name="enseignant_list")
     */
    public function list(Request $request, PaginatorInterface $paginator)
    {
        $donnees = $this->getDoctrine()->getRepository(Enseignant::class)->findAll();

        $admin = $paginator->paginate(
            $donnees,
            $request->query->getInt('page', 1),
            3
        );
        return $this->render("Enseignant/enseignantaffiche.html.twig",
            array('tabadmin'=>$admin));
    }


    



    /**
     * @Route("/addE",name="addEnseignant")
     */
    public function add(Request $request, UserPasswordEncoderInterface $userPasswordEncoderInterface){
        $admin= new Enseignant();
        $form= $this->createForm(EnseignantType::class,$admin);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $admin->setPassword(
                
                    $form->get('password')->getData()
                )
            ;
            $admin->setState(0);
            $admin->setRole('admin');
            $em = $this->getDoctrine()->getManager();
            $em->persist($admin);
            $em->flush();
            $this->addFlash('message', 'Ajout avec succés !');
            return $this->redirectToRoute("addEnseignant");

        }
        return $this->render("Enseignant/listenseignant.html.twig",array("formuser"=>$form->createView()));
    }


    
    /**
     * @Route("/removeE/{id}",name="removeE")
     */
    public function delete($id){
        $admin= $this->getDoctrine()->getRepository(Enseignant::class)->find($id);
        $em= $this->getDoctrine()->getManager();
        $em->remove($admin);
        $em->flush();
        $this->addFlash('message', 'Suppression avec succée !');
        return $this->redirectToRoute("enseignant_list");

    }

    /**
     * @Route("/EnableE/{id}", name="enableE")
     * @param $id
     * @return
     */
    public function EnableUser($id)
    {
        $admin = $this->getDoctrine()->getRepository(Enseignant::class)->find($id);
        $admin->setState(0);
        $entityManager = $this->getDoctrine()->getManager();
        $admin->setIsVerified(1);
        $entityManager->flush();
        $this->addFlash("success","Compte Activé !!") ;
        return $this->redirectToRoute('list_enseignant', ['id' => $admin->getId()]);
    }

    /**
     * @Route("/DiableE/{id}", name="diableE")
     * @param $id
     * @return
     */
    public function DiableEnseignant($id)
    {
        $admin = $this->getDoctrine()->getRepository(Enseignant::class)->find($id);
        $admin->setState(1);

        $entityManager = $this->getDoctrine()->getManager();
        $admin->setIsVerified(0);
        $entityManager->flush();
        $this->addFlash("success","Compte désactivé !!") ;
        return $this->redirectToRoute('list_enseignant', ['id' => $admin->getId()]);
    }


    /**
     * @Route("/searchE", name="search_Enseignant", requirements={"id":"\d+"})

     */
     public function searchGuides(Request $request, NormalizerInterface $Normalizer)
     {
         $repository = $this->getDoctrine()->getRepository(Enseignant::class);
         $requestString = $request->get('searchValue');
         $user = $repository->findUserByNom($requestString);
         $jsonContent = $Normalizer->normalize($user, 'json',[]);

         return new Response(json_encode($jsonContent));
     }


}
