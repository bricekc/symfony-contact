<?php

namespace App\Controller;

use App\Entity\Contact;
use App\Form\ContactType;
use App\Repository\ContactRepository;
use Doctrine\Persistence\ManagerRegistry;
use Knp\Component\Pager\PaginatorInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Entity;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ContactController extends AbstractController
{
    #[Route('/contact', name: 'app_contact')]
    public function index(Request $request, ContactRepository $repository, PaginatorInterface $paginator): Response
    {
        //$contacts = $repository->findBy([], ['lastname' => 'ASC', 'firstname' => 'ASC']);
        $search = $request->query->get('search', '');
        $contacts = $repository->search($search);
        $contacts = $paginator->paginate(
            $contacts,
            $request->query->getInt('page', 1),10
        );

        return $this->render('contact/index.html.twig', ['contacts' => $contacts]);
    }

        /**
         * ancienne version de show sans utilisé le paramconverter.
         */
    /*
    #[Route('/contact/{contactId<\d+>}', name: 'app_contact_id')]
    public function show(int $contactId, ContactRepository $repository): Response
    {
        $contact = $repository->findOneBy(['id' => $contactId]);
        if (!$contact) {
            throw $this->createNotFoundException("l'id du contact n'existe pas");
        }
        return $this->render('contact/show.html.twig', ['contact' => $contact]);
    }*/
        #[Route('/contact/{id<\d+>}', name: 'app_contact_id')]
        #[Entity('contact', expr: 'repository.findWithCategory(id)')]
    public function show(Contact $contact): Response
    {
        return $this->render('contact/show.html.twig', ['contact' => $contact]);
    }

    #[Route('/contact/{id}/update', name: 'app_contact_update', requirements: ['id' => '\d+'])]
    public function update(Request $request, ManagerRegistry $doctrine, Contact $contact): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');
        $entityManager = $doctrine->getManager();
        $form = $this->createForm(ContactType::class, $contact);
        // $form->add('save', SubmitType::class, ['label' => 'Modifier']);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_contact_id', ['id' => $contact->getId()]);
        }

        return $this->renderForm('contact/update.html.twig', ['contact' => $contact, 'form' => $form]);
    }

    #[Route('/contact/create', name: 'app_contact_create', requirements: ['id' => '\d+'])]
    public function create(Request $request, ContactRepository $service): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_REMEMBERED');
        $contact = new Contact();
        $form = $this->createForm(ContactType::class, $contact);
        // $form->add('save', SubmitType::class, ['label' => 'Ajouter']);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $service->save($contact, true);

            return $this->redirectToRoute('app_contact_id', ['id' => $contact->getId()]);
        }

        return $this->renderForm('contact/create.html.twig', ['form' => $form]);
    }

    #[Route('/contact/{id}/delete', name: 'app_contact_delete', requirements: ['id' => '\d+'])]
    public function delete(Request $request, ManagerRegistry $doctrine, Contact $contact): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');
        $entityManager = $doctrine->getManager();
        $form = $this->createFormBuilder($contact)
            ->add('delete', SubmitType::class, ['label' => 'Supprimer', 'attr' => ['class' => 'btn btn-primary']])
            ->add('cancel', SubmitType::class, ['label' => 'Annuler', 'attr' => ['class' => 'btn btn-secondary']])
            ->getForm();

        $form->handleRequest($request);
        if ($form->getClickedButton() && 'delete' === $form->getClickedButton()->getName()) {
            $entityManager->remove($contact);
            $entityManager->flush();

            return $this->redirectToRoute('app_contact');
        }

        if ($form->getClickedButton() && 'cancel' === $form->getClickedButton()->getName()) {
            return $this->redirectToRoute('app_contact_id', ['id' => $contact->getId()]);
        }

        return $this->renderForm('contact/delete.html.twig', ['contact' => $contact, 'form' => $form]);
    }
}
