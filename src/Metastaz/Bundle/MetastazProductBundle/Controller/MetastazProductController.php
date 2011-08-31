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
use Metastaz\Bundle\MetastazProductBundle\Entity\MetastazProductCategory;
use Metastaz\Bundle\MetastazProductBundle\Form\MetastazProductWithCategoryType;
use Metastaz\Util\MetastazFormFactory;

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

        $fields = array();
        foreach($entity->getMetastazTemplateFields() as $field) {
            $fields[$field->getMetaNamespace()][] = $field;
        }

        return array(
            'entity'      => $entity,
            'fields'      => $fields,
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Displays a form to create a new MetastazProduct entity.
     *
     * @Route("/new", name="metastaz_product_new")
     * @Template()
     */
    public function newAction(Request $request)
    {
        $entity = new MetastazProduct();
        $form   = $this->createForm(new MetastazProductWithCategoryType(), $entity);

        $action_url = $this->get('router')->generate('metastaz_product_create');
        $params = array(
            'entity' => $entity,
            'form'   => $form->createView(),
            'action' => 'create',
            'action_url' => $action_url
        );

        if ($request->isXmlHttpRequest())
            return $this->render('MetastazProductBundle:MetastazProduct:form.html.twig', $params);

        return array('params' => $params);
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
        $form    = $this->createForm(new MetastazProductWithCategoryType(), $entity);

        if ('POST' === $request->getMethod()) {
            $form->bindRequest($request);

            if ($form->isValid()) {
                $em = $this->getDoctrine()->getEntityManager();

                if($form->has('categorySuggestion') && $categorySuggestion = $form->get('categorySuggestion')->getData())
                {
                    $category = new MetastazProductCategory();
                    $category->setName($categorySuggestion);
                    $category->setTemplateName($categorySuggestion);
                    $entity->setCategory($category);
                }

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

        $editForm = MetastazFormFactory::createForm($this->container, $entity);
        $deleteForm = $this->createDeleteForm($id);

        $form_action_url = $this->get('router')->generate(
            'metastaz_product_update',
            array('id' => $entity->getId())
        );
/*
        $metastaz_form_action_url = $this->get('router')->generate(
            'metastaz_product_update_meta',
            array('id' => $entity->getId())
        );
*/
        $form_params = array(
            'entity' => $entity,
            'form'   => $editForm->createView(),
            'action' => 'update',
            'action_url' => $form_action_url
        );
/*
        $metastaz_form_params = array(
            'entity' => $entity,
            'form'   => $metastazForm->createView(),
            'action' => 'update',
            'action_url' => $metastaz_form_action_url
        );
*/
        return array(
            'entity'               => $entity,
            'form_params'          => $form_params,
//            'metastaz_form_params' => $metastaz_form_params,
            'delete_form'          => $deleteForm->createView(),
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

        $metastazForm = MetastazFormFactory::createForm($this->container, $entity);
        $editForm = $this->createForm(new MetastazProductWithCategoryType(), $entity);
        $editForm->add($metastazForm);
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

        $metastazForm = MetastazFormFactory::createForm($this->container, $entity);
        $deleteForm = $this->createDeleteForm($id);

        $form_action_url = $this->get('router')->generate(
            'metastaz_product_update',
            array('id' => $entity->getId())
        );
        $metastaz_form_action_url = $this->get('router')->generate(
            'metastaz_product_update_meta',
            array('id' => $entity->getId())
        );
        $form_params = array(
            'entity' => $entity,
            'form'   => $editForm->createView(),
            'action' => 'update',
            'action_url' => $form_action_url
        );
        $metastaz_form_params = array(
            'entity' => $entity,
            'form'   => $metastazForm->createView(),
            'action' => 'update',
            'action_url' => $metastaz_form_action_url
        );

        return array(
            'entity'               => $entity,
            'form_params'          => $form_params,
            'metastaz_form_params' => $metastaz_form_params,
            'delete_form'          => $deleteForm->createView(),
        );
    }

    /**
     * Edits an existing MetastazProduct entity.
     *
     * @Route("/{id}/update_meta", name="metastaz_product_update_meta")
     * @Method("post")
     * @Template("MetastazProductBundle:MetastazProduct:edit.html.twig")
     */
    public function updateMetastazAction($id)
    {
        $em = $this->getDoctrine()->getEntityManager();
        $entity = $em->getRepository('MetastazProductBundle:MetastazProduct')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find MetastazProduct entity.');
        }

        $metastazForm = MetastazFormFactory::createForm($this->container, $entity);
        $request = $this->getRequest();

        if ('POST' === $request->getMethod()) {
            $metastazForm->bindRequest($request);

            if ($metastazForm->isValid()) {
                $entity->flushMetastaz();
                return $this->redirect($this->generateUrl('metastaz_product_edit', array('id' => $id)));
            }
        }

        $editForm = $this->createForm(new MetastazProductWithCategoryType(), $entity);
        $deleteForm = $this->createDeleteForm($id);

        $form_action_url = $this->get('router')->generate(
            'metastaz_product_update',
            array('id' => $entity->getId())
        );
        $metastaz_form_action_url = $this->get('router')->generate(
            'metastaz_product_update_meta',
            array('id' => $entity->getId())
        );
        $form_params = array(
            'entity' => $entity,
            'form'   => $editForm->createView(),
            'action' => 'update',
            'action_url' => $form_action_url
        );
        $metastaz_form_params = array(
            'entity' => $entity,
            'form'   => $metastazForm->createView(),
            'action' => 'update',
            'action_url' => $metastaz_form_action_url
        );

        return array(
            'entity'               => $entity,
            'form_params'          => $form_params,
            'metastaz_form_params' => $metastaz_form_params,
            'delete_form'          => $deleteForm->createView(),
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
