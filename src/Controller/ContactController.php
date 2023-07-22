<?php

namespace App\Controller;

use App\Entity\Contact;
use App\Form\ContactFormType;
use Doctrine\Persistence\ManagerRegistry;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ContactController extends AbstractController
{
    #[Route('/', name: 'app_contact')]
    public function index(PaginatorInterface $paginator, Request $request, ManagerRegistry $doctrine): Response
    {
        $contactRepository = $doctrine->getRepository(Contact::class);
        $contacts = $contactRepository->findAll();

        $em = $doctrine->getManager();
        $query = $em->getRepository(Contact::class)->createQueryBuilder('e')->getQuery();
        $pagination = $paginator->paginate(
            $query, // ваш запрос
            $request->query->getInt('page', 1), // параметр передающий текущую страницу
            10 // количество элементов на странице
        );

        return $this->render('contact/index.html.twig', [
            'controller_name' => 'ContactController',
            'contacts' => $contacts,
            'pagination' => $pagination,
        ]);
    }

    #[Route('/contact/create', name: 'contact_create')]
    public function create(Request $request, ManagerRegistry $doctrine): Response
    {
        // Создание формы
        $contact = new Contact();
        $form = $this->createForm(ContactFormType::class, $contact);

        // Обработка отправки формы
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            // Получение менеджера сущностей
            $entityManager = $doctrine->getManager();

            // Сохранение контакта в базу данных
            $entityManager->persist($contact);
            $entityManager->flush();

            // Редирект на страницу с контактами
            return $this->redirectToRoute('contact_index');
        }

        // Отображение формы
        return $this->render('contact/create.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/contact/edit/{firstName}', name: 'contact_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, string $firstName, ManagerRegistry $doctrine): Response
    {
        $contactRepository = $doctrine->getRepository(Contact::class);
        $contact = $contactRepository->findOneBy(['firstName' => $firstName]);
    
        if (!$contact) {
            throw $this->createNotFoundException('Контакт не найден');
        }
    
        $form = $this->createForm(ContactFormType::class, $contact);
    
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $doctrine->getManager();
            $entityManager->persist($contact);
            $entityManager->flush();
    
            return $this->redirectToRoute('app_contact');
        }
    
        return $this->render('contact/edit.html.twig', [
            'form' => $form->createView(),
            'contact' => $contact,
        ]);
    }

    #[Route('/contact/delete/{firstName}', name: 'contact_delete')]
    public function delete(Contact $contact, ManagerRegistry $doctrine): Response
    {
        // Проверка наличия контакта с указанным ID
        if (!$contact) {
            throw $this->createNotFoundException('Контакт не найден');
        }

        // Получение менеджера сущностей
        $entityManager = $doctrine->getManager();

        // Удаление контакта из базы данных
        $entityManager->remove($contact);
        $entityManager->flush();

        // Редирект на страницу с контактами
        return $this->redirectToRoute('contact_index');
    }
}
