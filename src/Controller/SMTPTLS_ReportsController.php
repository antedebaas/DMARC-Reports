<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Contracts\Translation\TranslatorInterface;

use App\Entity\SMTPTLS_Reports;
use App\Entity\SMTPTLS_Seen;
use App\Entity\Domains;

use App\Repository\SMTPTLS_ReportsRepository;
use App\Repository\SMTPTLS_SeenRepository;

class SMTPTLS_ReportsController extends AbstractController
{
    private $em;
    private $router;
    private $translator;

    public function __construct(EntityManagerInterface $em, UrlGeneratorInterface $router, TranslatorInterface $translator)
    {
        $this->em = $em;
        $this->router = $router;
        $this->translator = $translator;
    }

    #[Route(path: '/reports/smtptls', name: 'app_smtptls_reports', methods: ['GET'])]
    public function index(): Response
    {
        $pages=array("page"=>1,"next" => false,"prev" => false);

        if(isset($_GET["page"]) && $_GET["page"] > 0)
        {
            $pages["page"] = intval($_GET["page"]);
        } else {
            $pages["page"] = 1;
        }

        if(isset($_GET["perpage"]) && $_GET["perpage"] > 0)
        {
            $pages['perpage'] = intval($_GET["perpage"]);
        } else {
            $pages['perpage'] = 17;
        }
        
        $repository = $this->em->getRepository(Domains::class);
        $domains = $repository->findBy(array('id' => $this->getUser()->getRoles()));

        $repository = $this->em->getRepository(SMTPTLS_Reports::class);
        if(in_array("ROLE_ADMIN", $this->getUser()->getRoles())) {
            $reports = $repository->findBy(array(),array('id' => 'DESC'),$pages["perpage"], ($pages["page"]-1)*$pages["perpage"]);
        } else {
            $reports = $repository->findOwnedBy(array('domain' => $domains),array('id' => 'DESC'),$pages["perpage"], ($pages["page"]-1)*$pages["perpage"]);
        }
        $totalreports = $repository->getTotalRows($domains, $this->getUser()->getRoles());
        
        $repository = $this->em->getRepository(SMTPTLS_Seen::class);
        $reportsseen = $repository->getSeen($reports, $this->getUser()->getId());

        if(count($reports) == 0 && $totalreports != 0 && $pages["page"] != 1) { return $this->redirectToRoute('app_smtptls_reports'); }
        
        if($totalreports/$pages['perpage'] > $pages["page"]) { $pages["next"] = true; }
        if($pages["page"]-1 > 0) { $pages["prev"] = true; }

        return $this->render('smtptls_reports/index.html.twig', [
            'reports' => $reports,
            'pages' => $pages,
            'reportsseen' => $reportsseen,
            'menuactive' => 'reports',
            'breadcrumbs' => array(
                array('name' => $this->translator->trans("Reports"), 'url' => $this->router->generate('app_reports')),
                array('name' => $this->translator->trans("SMTPTLS"), 'url' => $this->router->generate('app_smtptls_reports'))
            ),
        ]);
    }

    #[Route(path: '/reports/smtptls/report/{report}', name: 'app_smtptls_reports_report', methods: ['GET'])]
    public function report(
        #[MapQueryParameter(filter: FILTER_VALIDATE_INT)] SMTPTLS_Reports $report
    ): Response
    {
        $repository = $this->em->getRepository(SMTPTLS_Seen::class);
        $is_seen = $repository->findOneBy(array('report' => $report->getId(), 'user' => $this->getUser()->getId()));
        if(!$is_seen){
            $is_seen = new SMTPTLS_Seen;
            $is_seen->setReport($report);
            $is_seen->setUser($this->getUser());
            $this->em->persist($is_seen);
            $this->em->flush();
        }

        #dd($report->getSMTPTLS_Policies()->getSMTPTLS_MXRecords());

        return $this->render('smtptls_reports/report.html.twig', [
            'menuactive' => 'reports',
            'breadcrumbs' => array(
                array('name' => $this->translator->trans("Reports"), 'url' => $this->router->generate('app_reports')),
                array('name' => $this->translator->trans("SMTPTLS"), 'url' => $this->router->generate('app_smtptls_reports')),
                array('name' => $this->translator->trans("Report")." #".$report->getId(), 'url' => $this->router->generate('app_smtptls_reports'))
            ),
            'report' => $report
        ]);
    }
}
