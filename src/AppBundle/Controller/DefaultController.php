<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use AppBundle\Entity\Carte;

class DefaultController extends Controller
{
    /**
     * @Route("/", name="homepage")
     */
    public function indexAction(Request $request)
    {
        // replace this example code with whatever you need
        return $this->render('default/index.html.twig', [
            'base_dir' => realpath($this->getParameter('kernel.project_dir')).DIRECTORY_SEPARATOR,
        ]);
    }
    
    /**
     * @Route("/project", name="project")
     */
    public function projectAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        
        $user = $this->getUser();
        $carte = new Carte();
        $carte->setUser($user);
        
        $em->persist($carte);
        $em->flush();                                      
        
        return $this->render('default/project.html.twig', [
            'carte' => $carte,
        ]);
    }
    
    /**
     * @Route("/caculate-repere", name="caculateRepere")
     */
    public function caculateRepereAction(Request $request)
    {
        return new JsonResponse(['result'=>'yes']);
    }
}
