<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Image;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\EntityManager;

/**
 * Image controller.
 *
 * @Route("admin/image")
 */
class ImageController extends Controller
{
    /**
     * Lists all image entities.
     *
     * @Route("/", name="admin_image_index")
     * @Method("GET")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $images = $em->getRepository('AppBundle:Image')->findBy(['isDisabled' => false]);
        
        $tabImg = [];
        foreach ($images as $image) {
            $tabImg[$image->getId()] = $this->GetSizeName($image->getSize());
        }

        return $this->render('image/index.html.twig', array(
            'images' => $images,
            'tabImg' => $tabImg,
        ));
    }

    /**
     * Creates a new image entity.
     *
     * @Route("/new", name="admin_image_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request)
    {
        $image = new Image();
        $form = $this->createForm('AppBundle\Form\ImageType', $image);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $file = $image->getImageName();
            $fileName = uniqid().'.'.$file->guessExtension();
            $size = $file->getClientSize(); 
            
            $file->move(
                $this->getParameter('images_directory'),
                $fileName
            );
            
            $image->setSize($size);
            $image->setImageName($fileName);
            
            $em->persist($image);
            $em->flush();

            return $this->redirectToRoute('admin_image_show', array('id' => $image->getId()));
        }

        return $this->render('image/new.html.twig', array(
            'image' => $image,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a image entity.
     *
     * @Route("/{id}", name="admin_image_show")
     * @Method("GET")
     */
    public function showAction(Image $image)
    {
        $deleteForm = $this->createDeleteForm($image);

        return $this->render('image/show.html.twig', array(
            'image' => $image,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing image entity.
     *
     * @Route("/{id}/edit", name="admin_image_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, Image $image)
    {
        $deleteForm = $this->createDeleteForm($image);
        $editForm = $this->createForm('AppBundle\Form\ImageType', $image);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('admin_image_edit', array('id' => $image->getId()));
        }

        return $this->render('image/edit.html.twig', array(
            'image' => $image,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a image entity.
     *
     * @Route("/{id}", name="admin_image_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, Image $image)
    {
        $form = $this->createDeleteForm($image);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($image);
            $em->flush();
        }

        return $this->redirectToRoute('admin_image_index');
    }

    /**
     * Creates a form to delete a image entity.
     *
     * @param Image $image The image entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Image $image)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('admin_image_delete', array('id' => $image->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
    
    /**
     * Finds and displays a image entity.
     *
     * @Route("/delete/image/{id}", name="admin_delete_image")
     * @Method("PUT")
     */
    public function deleteImageAction(Image $image) {
        $em = $this->getDoctrine()->getManager();
        $image->setIsDisabled(true);
        $em->flush();
        
        return $this->redirectToRoute('admin_image_index');
    }
    
    function GetSizeName($octet)
    {
        // Array contenant les differents unités 
        $unite = array('octet','ko','mo','go');
        
        if(!$octet)
            return null;

        if ($octet < pow(10,3)) // octet
        {
            return $octet.$unite[0];
        }
        else 
        {
            if ($octet < pow(10,6)) // ko
            {
                $ko = round($octet/1024,2);
                return $ko.$unite[1];
            }
            else // Mo ou Go 
            {
                if ($octet < pow(10,9)) // Mo 
                {
                    $mo = round($octet/(1024*1024),2);
                    return $mo.$unite[2];
                }
                elseif($octet < pow(10,12)) // Go 
                {
                    $go = round($octet/(1024*1024*1024),2);
                    return $go.$unite[3];    
                }   
            }
        }
    }
}
