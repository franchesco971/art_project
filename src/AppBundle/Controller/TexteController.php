<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Texte;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;use Symfony\Component\HttpFoundation\Request;

/**
 * Texte controller.
 *
 * @Route("admin/texte")
 */
class TexteController extends Controller
{
    /**
     * Lists all texte entities.
     *
     * @Route("/", name="admin_texte_index")
     * @Method("GET")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $textes = $em->getRepository('AppBundle:Texte')->findAll();

        return $this->render('texte/index.html.twig', array(
            'textes' => $textes,
        ));
    }

    /**
     * Creates a new texte entity.
     *
     * @Route("/new", name="admin_texte_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request)
    {
        $texte = new Texte();
        $form = $this->createForm('AppBundle\Form\TexteType', $texte);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($texte);
            $em->flush();

            return $this->redirectToRoute('admin_texte_show', array('id' => $texte->getId()));
        }

        return $this->render('texte/new.html.twig', array(
            'texte' => $texte,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a texte entity.
     *
     * @Route("/{id}", name="admin_texte_show")
     * @Method("GET")
     */
    public function showAction(Texte $texte)
    {
        $deleteForm = $this->createDeleteForm($texte);

        return $this->render('texte/show.html.twig', array(
            'texte' => $texte,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing texte entity.
     *
     * @Route("/{id}/edit", name="admin_texte_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, Texte $texte)
    {
        $deleteForm = $this->createDeleteForm($texte);
        $editForm = $this->createForm('AppBundle\Form\TexteType', $texte);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('admin_texte_edit', array('id' => $texte->getId()));
        }

        return $this->render('texte/edit.html.twig', array(
            'texte' => $texte,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a texte entity.
     *
     * @Route("/{id}", name="admin_texte_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, Texte $texte)
    {
        $form = $this->createDeleteForm($texte);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($texte);
            $em->flush();
        }

        return $this->redirectToRoute('admin_texte_index');
    }

    /**
     * Creates a form to delete a texte entity.
     *
     * @param Texte $texte The texte entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Texte $texte)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('admin_texte_delete', array('id' => $texte->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
}
