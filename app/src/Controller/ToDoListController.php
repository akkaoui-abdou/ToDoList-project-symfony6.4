<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Doctrine\ORM\EntityManagerInterface;

use App\Entity\Task;
use App\Form\TaskType;


use Knp\Component\Pager\PaginatorInterface;

#[Route('/task', name: 'task_')]
class ToDoListController extends AbstractController
{


    #[Route('/list', name: 'list-task')]
    public function list(EntityManagerInterface $entityManager, Request $request, PaginatorInterface $paginator): Response
    {

        $query = $entityManager->getRepository(Task::class)->findTaskPaginator();

       //$task = $repository->findAll();

        $task = $paginator->paginate(
            $query, /* query NOT result */
            $request->query->getInt('page', 1), /*page number*/
            10 /*limit per page*/
        );


        return $this->render('task/list.html.twig', [
            'pagination' => $task,
        ]);

    }

    #[Route('/create', name: 'create-task')]
    public function create(EntityManagerInterface $entityManager, Request $request): Response
    {

        $task = new Task();

        $form = $this->createform(TaskType::class, $task);

        $form->handleRequest($request);

        if($form->isSubmitted()  && $form->isValid()){

            $entityManager->persist($task);
            $entityManager->flush();
            $this->addFlash('success', 'Task Created!');

        }
        return $this->render('task/new.html.twig', [
            'form' => $form,
        ]);
    }


    #[Route('/edit/{id}', name: 'edit-task')]
    public function edit(Task $task, EntityManagerInterface $entityManager, Request $request): Response
    {


       # $repository = $entityManager->getRepository(Task::class);

       # $task = $repository->find($id);

        $form = $this->createform(TaskType::class, $task);

        $form->handleRequest($request);

        if($form->isSubmitted()  && $form->isValid()){

            $entityManager->flush();
            $this->addFlash('success', 'Task updated!');

            return $this->redirectToRoute("task_list-task");

        }
        return $this->render('task/edit.html.twig', [
            'form' => $form,
        ]);
    }


    #[Route('/delete/{id}', name: 'delete-task')]
    public function delete(Task $task, EntityManagerInterface $entityManager, Request $request): Response
    {

            $entityManager->remove($task);
            $entityManager->flush();
            $this->addFlash('success', 'Task deleted!');
            return $this->redirectToRoute("task_list-task");
        
    }



    
}
