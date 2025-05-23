<?php

namespace App\Controller;

use App\Entity\Note;
use App\Entity\Etat;
use Doctrine\Common\Collections\Criteria;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Component\Security\Core\User\UserInterface;

final class R209controllerController extends AbstractController
{
    #[IsGranted('ROLE_USER')]
    #[Route('/r209controller', name: 'app_r209controller')]
    public function index(): Response
    {
        return $this->render('r209controller/index.html.twig');
    }

    #[IsGranted('ROLE_USER')]
    #[Route('/notes', name: 'app_notes')]
    public function notes(EntityManagerInterface $em, UserInterface $user): Response
    {
        $notesEnCours = $em->createQueryBuilder()
            ->select('n')
            ->from(Note::class, 'n')
            ->join('n.etat', 'e')
            ->where('e.nom = :etat')
            ->setParameter('etat', 'En cours')
            ->getQuery()
            ->getResult();

        $notesUrgentes = $em->createQueryBuilder()
            ->select('n')
            ->from(Note::class, 'n')
            ->join('n.tag', 't')
            ->where('t.nom = :tag')
            ->setParameter('tag', 'Urgent')
            ->getQuery()
            ->getResult();

        $notesUtilisateur = $user->getNotes();

        $etatEnCours = $em->getRepository(Etat::class)->findOneBy(['nom' => 'En cours']);
        $criteria = Criteria::create()->where(Criteria::expr()->eq('etat', $etatEnCours));
        $notesEnCoursUtilisateur = $notesUtilisateur->matching($criteria);

        return $this->render('r209controller/notes.html.twig', [
    'notes_en_cours' => $notesEnCours,
    'notes_urgentes' => $notesUrgentes,
    'notes_utilisateur' => $notesUtilisateur,
    'notes_en_cours_utilisateur' => $notesEnCoursUtilisateur,
]);

    }
}

