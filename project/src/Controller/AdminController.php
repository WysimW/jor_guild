<?php

// src/Controller/AdminController.php

namespace App\Controller;

use App\Entity\Boss;
use App\Entity\Raid;
use App\Form\BossType;
use App\Form\RaidType;
use App\Entity\RaidTier;
use App\Entity\Extension;
use App\Form\RaidTierType;
use App\Form\ExtensionType;
use App\Entity\GuildBossProgress;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Repository\GuildBossProgressRepository;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class AdminController extends AbstractController
{
    #[Route('/admin/raid/new', name: 'admin_raid_new')]
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

        return $this->render('admin/new_raid.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    // Méthode pour créer une nouvelle Extension
    #[Route('/admin/extension/new', name: 'admin_extension_new')]
    public function newExtension(Request $request, EntityManagerInterface $em): Response
    {
        $extension = new Extension();
        $form = $this->createForm(ExtensionType::class, $extension);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($extension);
            $em->flush();

            return $this->redirectToRoute('admin_dashboard');
        }

        return $this->render('admin/new_extension.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    // Méthode pour créer un nouveau RaidTier
    #[Route('/admin/raidtier/new', name: 'admin_raidtier_new')]
    public function newRaidTier(Request $request, EntityManagerInterface $em): Response
    {
        $raidTier = new RaidTier();
        $form = $this->createForm(RaidTierType::class, $raidTier);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($raidTier);
            $em->flush();

            return $this->redirectToRoute('admin_dashboard');
        }

        return $this->render('admin/new_raidtier.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    // Méthode pour créer un nouveau Boss
    #[Route('/admin/boss/new', name: 'admin_boss_new')]
    public function newBoss(Request $request, EntityManagerInterface $em): Response
    {
        $boss = new Boss();
        $form = $this->createForm(BossType::class, $boss);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($boss);

            // Créer les GuildBossProgress pour chaque difficulté
            $difficulties = ['Normal', 'Héroïque', 'Mythique']; // Vous pouvez également utiliser une énumération

            foreach ($difficulties as $difficulty) {
                $progress = new GuildBossProgress();
                $progress->setBoss($boss);
                $progress->setDifficulty($difficulty);
                $progress->setDefeated(false);
                // $progress->setFirstKillDate(null); // Par défaut null

                $em->persist($progress);
            }

            $em->flush();

            return $this->redirectToRoute('admin_dashboard');
        }

        return $this->render('admin/new_boss.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/admin/boss/{id}/update-status', name: 'admin_boss_update_status', methods: ['POST'])]
public function updateBossStatus(Request $request, Boss $boss, EntityManagerInterface $em): Response
{
    // Récupérer les données du formulaire
    $difficulty = $request->request->get('difficulty');
    $defeated = $request->request->get('defeated') === 'true';

    // Trouver l'enregistrement GuildBossProgress correspondant
    $guildBossProgress = $em->getRepository(GuildBossProgress::class)->findOneBy([
        'boss' => $boss,
        'difficulty' => $difficulty,
    ]);

    if (!$guildBossProgress) {
        throw $this->createNotFoundException('Progression pour cette difficulté non trouvée.');
    }

    // Mettre à jour les informations
    $guildBossProgress->setDefeated($defeated);
    if ($defeated && !$guildBossProgress->getFirstKillDate()) {
        $guildBossProgress->setFirstKillDate(new \DateTime());
    }
    if ($defeated) {
        $guildBossProgress->setKillCount($guildBossProgress->getKillCount() + 1);
    }

    $em->flush();

    return $this->redirectToRoute('admin_dashboard');
}

}
