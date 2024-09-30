<?php

namespace App\Controller\Backoffice;

use App\Entity\Profession;
use App\Form\ProfessionType;
use App\Repository\ProfessionRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/profession')]
class ProfessionController extends AbstractController
{
    #[Route('/', name: 'admin_profession_list', methods: ['GET'])]
    public function list(ProfessionRepository $professionRepository): Response
    {
        $professions = $professionRepository->findAll();

        return $this->render('admin/profession/list.html.twig', [
            'professions' => $professions,
        ]);
    }

    #[Route('/new', name: 'admin_profession_new', methods: ['GET', 'POST'])]
    public function new(Request $request, ProfessionRepository $professionRepository): Response
    {
        $profession = new Profession();
        $form = $this->createForm(ProfessionType::class, $profession);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $professionRepository->save($profession, true);

            return $this->redirectToRoute('admin_profession_list');
        }

        return $this->render('admin/profession/new.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/{id}/edit', name: 'admin_profession_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Profession $profession, ProfessionRepository $professionRepository): Response
    {
        $form = $this->createForm(ProfessionType::class, $profession);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $professionRepository->save($profession, true);

            return $this->redirectToRoute('admin_profession_list');
        }

        return $this->render('admin/profession/edit.html.twig', [
            'profession' => $profession,
            'form' => $form->createView(),
        ]);
    }

    #[Route('/{id}', name: 'admin_profession_delete', methods: ['POST'])]
    public function delete(Request $request, Profession $profession, ProfessionRepository $professionRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$profession->getId(), $request->request->get('_token'))) {
            $professionRepository->remove($profession, true);
        }

        return $this->redirectToRoute('admin_profession_list');
    }
}
