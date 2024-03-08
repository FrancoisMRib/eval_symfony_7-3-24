<?php

namespace App\Controller;

use App\Entity\Task;
use App\Form\TaskType;
use App\Repository\TaskRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class TaskController extends AbstractController
{
    private TaskRepository $taskRepository;
    // private EntityManagerInterface $em;

    public function __construct(TaskRepository $taskRepository,
        EntityManagerInterface $em) 
    {
        $this->taskRepository = $taskRepository;

        // $this->em = $em;
    }

    #[Route('/task', name: 'app_task')]
    public function index(): Response
    {
        return $this->render('task/index.html.twig', [
            'controller_name' => 'TaskController',
        ]);
    }

    #[Route('/task/add', name: 'app_task_add')]
    public function addTask(Request $request , EntityManagerInterface $em): Response
    {
        $msg = "";
        $task = new Task();
        $form = $this->createForm(TaskType::class, $task);
        $form->handleRequest($request);
        if($form->isSubmitted())
        {   
            $em->persist($task);
            $em->flush();
            $msg = "La tâche a été ajoutée en BDD";
        }
        return $this->render('task/index.html.twig', [
            'form'=> $form->createView(),
            'msg' => $msg
        ]);
    }


    #[Route('/task/all', name: 'app_task_all')]
    public function taskAll(): Response
    {
        $tasks = $this->taskRepository->findAll();

        return $this->render('task/task_all.html.twig', [
            'tasks' => $tasks,
        ]);
    }
}
