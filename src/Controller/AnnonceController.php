<?php

namespace App\Controller;

use App\Entity\Categorie;
use App\Entity\Annonce;
use App\Form\RatingCType;
use App\Form\AnnonceType;
use App\Repository\AnnonceRepository;
use Dompdf\Dompdf;
use Dompdf\Options;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;

/**
 * @Route("/annonce")
 */

class AnnonceController extends AbstractController
{
    /**
     * @Route("/", name="app_annonce_index")
     */
    public function index(): Response
    {
        $annonces=$this->getDoctrine()->getRepository(Annonce::class)->findAll();
        return $this->render('annonce/index.html.twig', [
            'annonces' => $annonces,
        ]);
    }
    /**
     * @Route("/Admin", name="app_annonce_index_admin")
     */
    public function indexAdmin(): Response
    {
        $annonces=$this->getDoctrine()->getRepository(Annonce::class)->findAll();
        return $this->render('annonce/indexAdmin.html.twig', [
            'annonces' => $annonces,
        ]);
    }


    /**
     * @Route("/new", name="app_annonce_new", methods={"GET", "POST"})
     */
    public function new(Request $request, AnnonceRepository $annonceRepository): Response
    {
        $annonce = new Annonce();
        $form = $this->createForm(AnnonceType::class, $annonce);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $file = $form->get('image')->getData();
            if($file)
            {
                $fileName = md5(uniqid()).'.'.$file->guessExtension();
                try {
                    $file->move(
                        $this->getParameter('images_directory'),
                        $fileName
                    );
                } catch (FileException $e){

                }
                $annonce->setImage($fileName);
            }
            else
            {
                $annonce->setImage("NoImage.png");
            }

           


            $annonceRepository->add($annonce);
            return $this->redirectToRoute('app_annonce_index_admin', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('annonce/add.html.twig', [
            'annonce' => $annonce,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/edit/{id}", name="app_annonce_edit", methods={"GET", "POST"})
     */
    public function edit(Request $request, Annonce $annonce, AnnonceRepository $annonceRepository): Response
    {
        $form = $this->createForm(AnnonceType::class, $annonce);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $file = $form->get('image')->getData();
            if($file)
            {
                $fileName = md5(uniqid()).'.'.$file->guessExtension();
                try {
                    $file->move(
                        $this->getParameter('images_directory'),
                        $fileName
                    );
                } catch (FileException $e){

                }
                $annonce->setImage($fileName);
            }
            $annonceRepository->add($annonce);
            return $this->redirectToRoute('app_annonce_index_admin', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('annonce/edit.html.twig', [
            'annonce' => $annonce,
            'form' => $form->createView(),
        ]);
    }
    /**
     * @Route("/editRating/{id}", name="app_annonce_edit_rating", methods={"GET", "POST"})
     */
    public function editRating(Request $request, Annonce $annonce, AnnonceRepository $annonceRepository): Response
    {
        $form = $this->createForm(RatingCType::class, $annonce);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $annonceRepository->add($annonce);
            return $this->redirectToRoute('app_annonce_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('annonce/editRating.html.twig', [
            'annonce' => $annonce,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/delete/{id}", name="app_annonce_delete")
     * method=({"DELETE"})
     */
    public function delete(Request $request, $id)
    {
        $annonce = $this->getDoctrine()->getRepository(Annonce::class)->find($id);

        $entityyManager = $this->getDoctrine()->getManager();
        $entityyManager->remove($annonce);
        $entityyManager->flush();

        $response = new Response();
        $response->send();

        return $this->redirectToRoute('app_annonce_index_admin', [], Response::HTTP_SEE_OTHER);
    }

    /**
     * @Route("/r/search_relation", name="search_rela", methods={"GET"})
     */
    public function search_reccc(Request $request, NormalizerInterface $Normalizer, AnnonceRepository $annonceRepository): Response
    {

        $requestString = $request->get('searchValue');
        $requestString3 = $request->get('orderid');

        $annonce = $annonceRepository->findRelactionnel($requestString, $requestString3);
        $jsoncontentc = $Normalizer->normalize($annonce, 'json', ['groups' => 'posts:read']);
        $jsonc = json_encode($jsoncontentc);
        if ($jsonc == "[]") {
            return new Response(null);
        } else {
            return new Response($jsonc);
        }
    }

    /**
     * @Route("/pdf/{id}", name="annonce_pdf")
     */
    public function PDF(int $id)
    {
        //on definit les option du pdf
        $pdfOptions = new Options();
        //police par defaut
        $pdfOptions->set('defaultFont', 'Arial');
        $pdfOptions->setIsRemoteEnabled(true);

        // Instantiate Dompdf with our options
        $dompdf = new Dompdf($pdfOptions);
        $annonce = $this->getDoctrine()->getRepository(Annonce::class)->find($id);



        // Retrieve the HTML generated in our twig file
        $html = $this->renderView('annonce/pdf.html.twig', [
            'annonce' => $annonce
        ]);

        // Load HTML to Dompdf
        $dompdf->loadHtml($html);



        // (Optional) Setup the paper size and orientation 'portrait' or 'portrait'
        $dompdf->setPaper('A3', 'paysage');

        // Render the HTML as PDF
        $dompdf->render();



        // Output the generated PDF to Browser (inline view)
        $dompdf->stream("annonce.pdf", [
            "Attachment" => false
        ]);
        return new Response();
    }


    //*****MOBILE

    /**
     * @Route("/mobile/aff", name="affmobannonce")
     */
    public function affmobcategory(NormalizerInterface $normalizer)
    {
        $med=$this->getDoctrine()->getRepository(Annonce::class)->findAll();
        $jsonContent = $normalizer->normalize($med,'json',['annonce'=>'post:read']);
        return new Response(json_encode($jsonContent));
    }

    /**
     * @Route("/mobile/new", name="addmobannonce")
     */
    public function addmobcategorie(Request $request,NormalizerInterface $normalizer,AnnonceRepository $annonceRepository)
    {
        $em=$this->getDoctrine()->getManager();
        $annonce= new Annonce();
        $annonce->setNom($request->get('nom'));
        $annonce->setDescription($request->get('description'));
        $annonce->setImage($request->get('image'));
        $categorie = $this->getDoctrine()->getRepository(Categorie::class)->findOneBy([
            'role' =>  $request->get('role')
        ]);
        $annonce->setCategorie($categorie);
        $annonce->setRating(0);
        $annonceRepository->add($annonce);

        $jsonContent = $normalizer->normalize($annonce,'json',['annonce'=>'post:read']);
        return new Response(json_encode($jsonContent));
    }

    /**
     * @Route("/mobile/edit", name="editmobannonce")
     */
    public function editmobcategorie(Request $request,NormalizerInterface $normalizer)
    {   $em=$this->getDoctrine()->getManager();
        $annonce= new Annonce();

        $annonce = $em->getRepository(Annonce::class)->find($request->get('id'));

        $annonce->setNom($request->get('nom'));
        $annonce->setDescription($request->get('description'));
        $annonce->setImage($request->get('image'));
        $categorie = $this->getDoctrine()->getRepository(Categorie::class)->findOneBy([
            'role' =>  $request->get('role')
        ]);
        $annonce->setCategorie($categorie);


        $em->flush();
        $normalizer = new ObjectNormalizer();
        $normalizer->setCircularReferenceLimit(1);
        $normalizer->setCircularReferenceHandler(function ($annonce) {
            return $annonce->getId();
        });
        $encoders = [new JsonEncoder()];
        $normalizers = array($normalizer);
        $serializer = new Serializer($normalizers,$encoders);
        $formatted = $serializer->normalize($annonce);
        return new JsonResponse($formatted);
    }
    /**
     * @Route("/mobile/del", name="delmobannonce")
     */
    public function delmobcategorie(Request $request,NormalizerInterface $normalizer)
    {           $em=$this->getDoctrine()->getManager();
        $annonce=$this->getDoctrine()->getRepository(Annonce::class)
            ->find($request->get('id'));
        $em->remove($annonce);
        $em->flush();
        $jsonContent = $normalizer->normalize($annonce,'json',['annonce'=>'post:read']);
        return new Response(json_encode($jsonContent));
    }


}
