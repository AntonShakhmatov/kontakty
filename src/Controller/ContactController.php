<?php

namespace App\Controller;

use App\Entity\Contact;
use App\Form\ContactFormType;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ContactController extends AbstractController
{
    #[Route('/contact', name: 'app_contact')]
    public function index(ManagerRegistry $doctrine): Response
    {
        $contact = $doctrine->getRepository(Contact::class);

        return $this->render('contact/index.html.twig', [
            'controller_name' => 'ContactController',
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
            // return $this->redirectToRoute('/contact');
        }
    
        // Отображение формы
        return $this->render('contact/create.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/contact/{id}/edit', name: 'contact_edit')]
    public function edit(Contact $contact, Request $request): Response
    {
        // Создание формы существующего контакта
        $form = $this->createForm(ContactFormType::class, $contact);

        // Обработка отправки формы
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            // Сохранение изменений контакта в базу данных
            // Редирект на страницу с контактами
        }

        // Отображение формы
        return $this->render('contact/edit.html.twig', [
            'form' => $form->createView(),
            'contact' => $contact,
        ]);
    }
}
