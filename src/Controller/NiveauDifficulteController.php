<?php

namespace App\Controller;

use App\Entity\Cours;
use App\Entity\NiveauDifficulte;
use App\Form\NiveauDifficulteType;
use App\Repository\CategorieRepository;
use App\Repository\NiveauDifficulteRepository;
use CMEN\GoogleChartsBundle\GoogleCharts\Charts\PieChart;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;

/**
 * @Route("/Niveaudifficulte")
 */

class NiveauDifficulteController extends AbstractController
{
    /**
     * @Route("/", name="app_niveau_difficulte_index")
     */
    public function index(): Response
    {
        $nivdiff=$this->getDoctrine()->getRepository(NiveauDifficulte::class)->findAll();
        return $this->render('niveau_difficulte/index.html.twig', [
            'nivdiff' => $nivdiff,
        ]);
    }
    /**
     * @Route("/Admin", name="app_niveau_difficulte_index_admin")
     */
    public function indexAdmin(): Response
    {

        $pieChart = new PieChart();

        $cours = $this->getDoctrine()->getManager()->getRepository(Cours::class)->f();
        $data = array();
        $stat = ['Niveaux', '%'] ;
        array_push($data, $stat);
        foreach ($cours as $nd) {
            $stat = array();
            $stat = [$nd['niveau'], $nd['1']];
            array_push($data, $stat);
        }
        $pieChart->getData()->setArrayToDataTable(
            $data
        );

        $pieChart->getOptions()->setTitle('Les Niveaux');
        $pieChart->getOptions()->setHeight(500);
        $pieChart->getOptions()->setWidth(900);
        $pieChart->getOptions()->getTitleTextStyle()->setBold(true);
        $pieChart->getOptions()->getTitleTextStyle()->setColor('#009900');
        $pieChart->getOptions()->getTitleTextStyle()->setItalic(true);
        $pieChart->getOptions()->getTitleTextStyle()->setFontName('Arial');
        $pieChart->getOptions()->getTitleTextStyle()->setFontSize(20);

        $nivdiff = $this->getDoctrine()->getRepository(NiveauDifficulte::class)->findAll();
        return $this->render('niveau_difficulte/indexAdmin.html.twig', [
            'nivdiff' => $nivdiff,
            'piechart' => $pieChart

        ]);
    }

    /**
         * @Route("/new", name="app_niveau_difficulte_new", methods={"GET", "POST"})
     */
    public function new(Request $request, NiveauDifficulteRepository $niveauDifficulteRepository): Response
    {
        $niveauDifficulte = new NiveauDifficulte();
        $form = $this->createForm(NiveauDifficulteType::class, $niveauDifficulte);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $niveauDifficulteRepository->add($niveauDifficulte);
            return $this->redirectToRoute('app_niveau_difficulte_index_admin', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('niveau_difficulte/add.html.twig', [
            'niveauDifficulte' => $niveauDifficulte,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/edit/{id}", name="app_niveau_difficulte_edit", methods={"GET", "POST"})
     */
    public function edit(Request $request, NiveauDifficulte $niveauDifficulte, NiveauDifficulteRepository $niveauDifficulteRepository): Response
    {
        $form = $this->createForm(niveauDifficulteType::class, $niveauDifficulte);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $niveauDifficulteRepository->add($niveauDifficulte);
            return $this->redirectToRoute('app_niveau_difficulte_index_admin', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('niveau_difficulte/edit.html.twig', [
            'niveauDifficulte' => $niveauDifficulte,
            'form' => $form->createView(),
        ]);
    }
    /**
     * @Route("/delete/{id}", name="app_niveau_difficulte_delete")
     * method=({"DELETE"})
     */
    public function delete(Request $request, $id)
    {
        $niveauDifficulte = $this->getDoctrine()->getRepository(NiveauDifficulte::class)->find($id);

        $entityyManager = $this->getDoctrine()->getManager();
        $entityyManager->remove($niveauDifficulte);
        $entityyManager->flush();
        $response = new Response();
        $response->send();

        return $this->redirectToRoute('app_niveau_difficulte_index_admin', [], Response::HTTP_SEE_OTHER);
    }

    /**
     * @Route("/r/search_recc", name="search_recc", methods={"GET"})
     */
    public function search_rec(Request $request, NormalizerInterface $Normalizer, NiveauDifficulteRepository $coursRepository): Response
    {

        $requestString = $request->get('searchValue');
        $requestString3 = $request->get('orderid');

        $typec = $coursRepository->findTypeC($requestString, $requestString3);
        $jsoncontentc = $Normalizer->normalize($typec, 'json', ['groups' => 'posts:read']);
        $jsonc = json_encode($jsoncontentc);
        if ($jsonc == "[]") {
            return new Response(null);
        } else {
            return new Response($jsonc);
        }
    }


    public function getData()
    {
        /**
         * @var $NiveauDifficulte niv[]
         */
        $list = [];
        $nivrec = $this->getDoctrine()->getRepository(NiveauDifficulte::class)->findAll();

        foreach ($nivrec as $niv) {
            $list[] = [
                $niv->getNiveau(),
              

            ];
        }
        return $list;
    }


    /**
     * @Route("/excel/export",  name="export")
     */
    /*
    public function export()
    {
        $spreadsheet = new Spreadsheet();

        $sheet = $spreadsheet->getActiveSheet();

        $sheet->setTitle('Reclamation List');

        $sheet->getCell('A1')->setValue('type');
        $sheet->getCell('B1')->setValue('montant');

        // Increase row cursor after header write
        $sheet->fromArray($this->getData(), null, 'A2', true);
        $writer = new Xlsx($spreadsheet);
        // $writer->save('ss.xlsx');
        $writer->save('TypeCompta' . date('m-d-Y_his') . '.xlsx');
        return $this->redirectToRoute('app_type_comptabilite_index_admin');
    }



    //*****MOBILE

    /**
     * @Route("/mobile/aff", name="affmobType")
     */
    /*
    public function affmobcategory(NormalizerInterface $normalizer)
    {
        $med=$this->getDoctrine()->getRepository(TypeComptabilite::class)->findAll();
        $jsonContent = $normalizer->normalize($med,'json',['typecomptabilite'=>'post:read']);
        return new Response(json_encode($jsonContent));
    }

    /**
     * @Route("/mobile/new", name="addmobType")
     */
    /*
    public function addmobType(Request $request,NormalizerInterface $normalizer,TypeComptabiliteRepository $typeComptabiliteRepository)
    {
        $em=$this->getDoctrine()->getManager();
        $type= new TypeComptabilite();
        $type->setType($request->get('type'));
        $type->setMontant($request->get('montant'));
        $typeComptabiliteRepository->add($type);

        $jsonContent = $normalizer->normalize($type,'json',['typecomptabilite'=>'post:read']);
        return new Response(json_encode($jsonContent));
    }

    /**
     * @Route("/mobile/edit", name="editmobType")
     */
    /*
    public function editmobType(Request $request,NormalizerInterface $normalizer)
    {   $em=$this->getDoctrine()->getManager();
        $type = $em->getRepository(TypeComptabilite::class)->find($request->get('id'));
        $type->setType($request->get('type'));
        $type->setMontant($request->get('montant'));

        $em->flush();
        $normalizer = new ObjectNormalizer();
        $normalizer->setCircularReferenceLimit(1);
        $normalizer->setCircularReferenceHandler(function ($type) {
            return $type->getId();
        });
        $encoders = [new JsonEncoder()];
        $normalizers = array($normalizer);
        $serializer = new Serializer($normalizers,$encoders);
        $formatted = $serializer->normalize($type);
        return new JsonResponse($formatted);
    }
    /**
     * @Route("/mobile/del", name="delmobType")
     */
    /*
    public function delmobType(Request $request,NormalizerInterface $normalizer)
    {           $em=$this->getDoctrine()->getManager();
        $type=$this->getDoctrine()->getRepository(TypeComptabilite::class)
            ->find($request->get('id'));
        $em->remove($type);
        $em->flush();
        $jsonContent = $normalizer->normalize($type,'json',['typecomptabilite'=>'post:read']);
        return new Response(json_encode($jsonContent));
    }
*/
}
