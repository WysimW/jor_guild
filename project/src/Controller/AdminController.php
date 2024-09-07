<?php

// src/Controller/AdminController.php

namespace App\Controller;

use App\Entity\Raid;
use App\Form\RaidType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AdminController extends AbstractController
{
    #[Route('/admin', name: 'admin_dashboard')]
    public function dashboard(): Response
    {
        return $this->render('admin/dashboard.html.twig', [
            'title' => 'Dashboard',
        ]);
    }

    #[Route('/admin/raid/new', name: 'admin_raid_new')]
    public function newRaid(Request $request, EntityManagerInterface $em): Response
    {
        $raid = new Raid();
        $form = $this->createForm(RaidType::class, $raid);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($raid);
            $em->flush();

            return $this->redirectToRoute('admin_dashboard');
        }

        return $this->render('admin/new_raid.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
