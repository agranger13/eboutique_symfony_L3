<?php

namespace App\Controller;

use App\Entity\CommandLine;
use App\Form\CommandLineType;
use App\Repository\CommandLineRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/command/line")
 * @IsGranted("ROLE_ADMIN")
 */
class CommandLineController extends AbstractController
{
    /**
     * @Route("/", name="command_line_index", methods={"GET"})
     */
    public function index(CommandLineRepository $commandLineRepository): Response
    {
        return $this->render('command_line/index.html.twig', [
            'command_lines' => $commandLineRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="command_line_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $commandLine = new CommandLine();
        $form = $this->createForm(CommandLineType::class, $commandLine);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($commandLine);
            $entityManager->flush();

            return $this->redirectToRoute('command_line_index');
        }

        return $this->render('command_line/new.html.twig', [
            'command_line' => $commandLine,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="command_line_show", methods={"GET"})
     */
    public function show(CommandLine $commandLine): Response
    {
        return $this->render('command_line/show.html.twig', [
            'command_line' => $commandLine,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="command_line_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, CommandLine $commandLine): Response
    {
        $form = $this->createForm(CommandLineType::class, $commandLine);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('command_line_index');
        }

        return $this->render('command_line/edit.html.twig', [
            'command_line' => $commandLine,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="command_line_delete", methods={"DELETE"})
     */
    public function delete(Request $request, CommandLine $commandLine): Response
    {
        if ($this->isCsrfTokenValid('delete'.$commandLine->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($commandLine);
            $entityManager->flush();
        }

        return $this->redirectToRoute('command_line_index');
    }
}
