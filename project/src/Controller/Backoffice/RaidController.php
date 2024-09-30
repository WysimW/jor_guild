<?php

namespace App\Controller\Backoffice;

use App\Entity\Boss;
use App\Entity\Raid;
use App\Form\BossType;
use App\Form\RaidType;
use App\Entity\RaidTier;
use App\Entity\Extension;
use App\Form\RaidTierType;
use App\Form\RaidTypeEdit;
use App\Form\ExtensionType;
use App\Entity\GuildBossProgress;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Repository\GuildBossProgressRepository;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;


class RaidController extends AbstractController
{
    #[Route('/raids', name: 'admin_raids_list')]
    public function listRaids(EntityManagerInterface $em): Response
    {
        $raids = $em->getRepository(Raid::class)->findAll();

        return $this->render('admin/raid/raids_list.html.twig', [
            'raids' => $raids,
        ]);
    }

    #[Route('/raid/new', name: 'admin_raid_new')]
    public function newRaid(Request $request, EntityManagerInterface $em, GuildBossProgressRepository $progressRepository): Response
    {
        $raid = new Raid();
        $form = $this->createForm(RaidType::class, $raid);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $raid->setArchived(false);
            $em->persist($raid);
            $em->flush();

            // Mettre à jour GuildBossProgress en fonction des boss vaincus
            foreach ($raid->getDownBosses() as $boss) {
                // Mettre à jour la progression de guilde pour ce boss à la difficulté du raid
                // Supposons que le raid a une propriété 'difficulty'
                $difficulty = $raid->getMode(); // Assurez-vous que cette propriété existe

                $progress = $progressRepository->findOneBy([
                    'boss' => $boss,
                    'difficulty' => $difficulty,
                ]);

                if ($progress) {
                    if (!$progress->getDefeated()) {
                        $progress->setDefeated(true);
                        $progress->setFirstKillDate(new \DateTime());
                    }
                    $progress->setKillCount($progress->getKillCount() + 1);
                    $em->persist($progress);
                }
            }

            $em->flush();

            return $this->redirectToRoute('admin_dashboard');
        }

        return $this->render('admin/raid/new_raid.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/raid/{id}/edit', name: 'admin_raid_edit')]
    public function editRaid(Request $request, Raid $raid, EntityManagerInterface $em): Response
    {
        $form = $this->createForm(RaidTypeEdit::class, $raid);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $em->flush();

            // Rediriger vers la liste des raids après la modification
            return $this->redirectToRoute('admin_raids_list');
        }

        return $this->render('admin/raid/edit_raid.html.twig', [
            'form' => $form->createView(),
            'raid' => $raid,
        ]);
    }
    #[Route('/raid/{id}/toggle-archive', name: 'admin_raid_toggle_archive')]
    public function toggleArchiveRaid(Raid $raid, EntityManagerInterface $em): RedirectResponse
    {
        $raid->setArchived(!$raid->isArchived());
        $em->persist($raid);
        $em->flush();

        $this->addFlash(
            'success',
            $raid->isArchived() ? 'Le raid a été archivé avec succès.' : 'Le raid a été désarchivé avec succès.'
        );

        return $this->redirectToRoute('admin_raids_list');
    }
    
}
