<?php

namespace Metastaz\Bundle\MetastazProductBundle\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Metastaz\Bundle\MetastazProductBundle\Entity\MetastazProduct;
use Metastaz\Bundle\MetastazProductBundle\Form\MetastazProductType;

/**
 * MetastazProduct controller.
 * 
 * @author:  Gabriel BONDAZ <gabriel.bondaz@idci-consulting.fr>
 * @licence: GPL
 */
class MetastazProductController extends Controller
{
    /**
     * Lists all MetastazProduct entities.
     *
     * @Route("/", name="metastaz_product")
     * @Template()
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getEntityManager();

        $entities = $em->getRepository('MetastazProductBundle:MetastazProduct')->findAll();

        return array('entities' => $entities);
    }

    /**
     * Finds and displays a MetastazProduct entity.
     *
     * @Route("/{id}/show", name="metastaz_product_show")
     * @Template()
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getEntityManager();

        $entity = $em->getRepository('MetastazProductBundle:MetastazProduct')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find MetastazProduct entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        //$entity->loadMetastaz();
/*
        $entity->putMetastaz('Infos', 'Largeur', '205');
        $entity->putMetastaz('Infos', 'Hauteur', '55');
        $entity->putMetastaz('Infos', 'Diametre', 'H');
        $entity->putMetastaz('Infos', 'Poids', '100');
        $entity->putMetastaz('Infos', 'Couleur', 'gris');
        $entity->putMetastaz('Infos', 'Runflat', 200);
        $entity->putMetastaz('Infos', 'Vitesse', '280');*/
        //$entity->persistMetastaz();
        //$entity->flushMetastaz();

        $indexes = array();
        foreach($entity->getMetastazIndexes() as $field) {
            $indexes[$field->getMetaNamespace()][] = $field->getMetaKey();
        }

        return array(
            'entity'      => $entity,
            'indexes'     => $indexes,
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Displays a form to create a new MetastazProduct entity.
     *
     * @Route("/new", name="metastaz_product_new")
     * @Template()
     */
    public function newAction()
    {
        $entity = new MetastazProduct();
        $form   = $this->createForm(new MetastazProductType(), $entity);

        return array(
            'entity' => $entity,
            'form'   => $form->createView()
        );
    }

    /**
     * Creates a new MetastazProduct entity.
     *
     * @Route("/create", name="metastaz_product_create")
     * @Method("post")
     * @Template("MetastazProductBundle:MetastazProduct:new.html.twig")
     */
    public function createAction()
    {
        $entity  = new MetastazProduct();
        $request = $this->getRequest();
        $form    = $this->createForm(new MetastazProductType(), $entity);

        if ('POST' === $request->getMethod()) {
            $form->bindRequest($request);

            if ($form->isValid()) {
                $em = $this->getDoctrine()->getEntityManager();
                $em->persist($entity);
                $em->flush();

                return $this->redirect($this->generateUrl('metastaz_product_show', array('id' => $entity->getId())));
                
            }
        }

        return array(
            'entity' => $entity,
            'form'   => $form->createView()
        );
    }

    /**
     * Displays a form to edit an existing MetastazProduct entity.
     *
     * @Route("/{id}/edit", name="metastaz_product_edit")
     * @Template()
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getEntityManager();

        $entity = $em->getRepository('MetastazProductBundle:MetastazProduct')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find MetastazProduct entity.');
        }

        $editForm = $this->createForm(new MetastazProductType(), $entity);
        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Edits an existing MetastazProduct entity.
     *
     * @Route("/{id}/update", name="metastaz_product_update")
     * @Method("post")
     * @Template("MetastazProductBundle:MetastazProduct:edit.html.twig")
     */
    public function updateAction($id)
    {
        $em = $this->getDoctrine()->getEntityManager();

        $entity = $em->getRepository('MetastazProductBundle:MetastazProduct')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find MetastazProduct entity.');
        }

        $editForm   = $this->createForm(new MetastazProductType(), $entity);
        $deleteForm = $this->createDeleteForm($id);

        $request = $this->getRequest();

        if ('POST' === $request->getMethod()) {
            $editForm->bindRequest($request);

            if ($editForm->isValid()) {
                $em = $this->getDoctrine()->getEntityManager();
                $em->persist($entity);
                $em->flush();

                return $this->redirect($this->generateUrl('metastaz_product_edit', array('id' => $id)));
            }
        }

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Deletes a MetastazProduct entity.
     *
     * @Route("/{id}/delete", name="metastaz_product_delete")
     * @Method("post")
     */
    public function deleteAction($id)
    {
        $form = $this->createDeleteForm($id);
        $request = $this->getRequest();

        if ('POST' === $request->getMethod()) {
            $form->bindRequest($request);

            if ($form->isValid()) {
                $em = $this->getDoctrine()->getEntityManager();
                $entity = $em->getRepository('MetastazProductBundle:MetastazProduct')->find($id);

                if (!$entity) {
                    throw $this->createNotFoundException('Unable to find MetastazProduct entity.');
                }

                $em->remove($entity);
                $em->flush();
            }
        }

        return $this->redirect($this->generateUrl('metastaz_product'));
    }

    private function createDeleteForm($id)
    {
        return $this->createFormBuilder(array('id' => $id))
            ->add('id', 'hidden')
            ->getForm()
        ;
    }
}
