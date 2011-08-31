<?php

namespace Metastaz\Bundle\MetastazTemplateBundle\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Metastaz\Bundle\MetastazTemplateBundle\Entity\MetastazTemplate;
use Metastaz\Bundle\MetastazTemplateBundle\Entity\MetastazTemplateField;
use Metastaz\Bundle\MetastazTemplateBundle\Form\MetastazTemplateFieldType;
use Metastaz\Bundle\MetastazTemplateBundle\Form\MetastazTemplateType;
use Metastaz\Util\MetastazFormFactory;

/**
 * MetastazTemplate controller.
 * 
 * @author:  Gabriel BONDAZ <gabriel.bondaz@idci-consulting.fr>
 * @licence: GPL
 */
class MetastazTemplateController extends Controller
{
    /**
     * Lists all MetastazTemplate entities.
     *
     * @Route("/", name="metastaz_template")
     * @Template()
     */
    public function indexAction(Request $request)
    {
        $em = $this->getDoctrine()->getEntityManager('metastaz_template');
        $entities = $em->getRepository('MetastazTemplateBundle:MetastazTemplate')->findAll();
        return array('entities' => $entities);
    }

    /**
     * Finds and displays a MetastazTemplate entity.
     *
     * @Route("/{id}/show", name="metastaz_template_show")
     * @Template()
     */
    public function showAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getEntityManager('metastaz_template');
        $entity = $em->getRepository('MetastazTemplateBundle:MetastazTemplate')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find MetastazTemplate entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity'      => $entity,
            'delete_form' => $deleteForm->createView()
        );
    }

    /**
     * Displays a form to create a new MetastazTemplate entity.
     *
     * @Route("/new", name="metastaz_template_new")
     * @Template()
     */
    public function newAction(Request $request)
    {
        $entity = new MetastazTemplate();
        $form = $this->createForm(new MetastazTemplateType(), $entity);

        $action_url = $this->get('router')->generate('metastaz_template_create');
        $params = array(
            'entity' => $entity,
            'form'   => $form->createView(),
            'action' => 'create',
            'action_url' => $action_url
        );

        if ($request->isXmlHttpRequest())
            return $this->render('MetastazTemplateBundle:MetastazTemplate:templateForm.html.twig', $params);

        return array('params' => $params);
    }
    
    /**
     * Displays a form to create a new MetastazTemplateField entity for a MetastazTemplate.
     *
     * @Route("/{id}/new_field", name="metastaz_template_field_new")
     * @Template()
     */
    public function newFieldAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getEntityManager('metastaz_template');

        $template = $em->getRepository('MetastazTemplateBundle:MetastazTemplate')->find($id);

        if (!$template) {
            throw $this->createNotFoundException('Unable to find MetastazTemplate entity.');
        }

        $entity = new MetastazTemplateField();
        $entity->setMetastazTemplate($template);
        $form   = $this->createForm(new MetastazTemplateFieldType(), $entity);

        $action_url = $this->get('router')->generate('metastaz_template_field_create', array(
            'id' => $template->getId()
        ));
        $params = array(
            'entity' => $entity,
            'form'   => $form->createView(),
            'action' => 'create',
            'action_url' => $action_url
        );

        if ($this->getRequest()->isXmlHttpRequest())
            return $this->render('MetastazTemplateBundle:MetastazTemplate:templateFieldForm.html.twig', $params);

        return array(
            'template' => $template,
            'params' => $params
        );
    }

    /**
     * Creates a new MetastazTemplate entity.
     *
     * @Route("/create", name="metastaz_template_create")
     * @Method("post")
     * @Template("MetastazTemplateBundle:MetastazTemplate:new.html.twig")
     */
    public function createAction(Request $request)
    {
        $entity  = new MetastazTemplate();
        $form    = $this->createForm(new MetastazTemplateType(), $entity);
        $form->bindRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getEntityManager('metastaz_template');
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('metastaz_template', array('id' => $entity->getId())));
        }

        return array(
            'entity' => $entity,
            'form'   => $form->createView()
        );
    }
    
    /**
     * Creates a new MetastazTemplateField entity for a MetastazTemplate.
     *
     * @Route("/{id}/create_field", name="metastaz_template_field_create")
     * @Method("post")
     * @Template("MetastazTemplateBundle:MetastazTemplate:newField.html.twig")
     */
    public function createFieldAction(Request $request, $id)
    {
        $entity  = new MetastazTemplateField();
        $form    = $this->createForm(new MetastazTemplateFieldType(), $entity);
        $form->bindRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getEntityManager('metastaz_template');
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('metastaz_template_edit', array('id' => $id)));
        }

        return array(
            'entity' => $entity,
            'form'   => $form->createView()
        );
    }

    /**
     * Displays a form to edit an existing MetastazTemplate entity.
     *
     * @Route("/{id}/edit", name="metastaz_template_edit")
     * @Template()
     */
    public function editAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getEntityManager('metastaz_template');
        $entity = $em->getRepository('MetastazTemplateBundle:MetastazTemplate')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find MetastazTemplate entity.');
        }

        $editForm = $this->createForm(new MetastazTemplateType(), $entity);
        $deleteForm = $this->createDeleteForm($id);

        $action_url = $this->get('router')->generate('metastaz_template_update', array(
            'id' => $entity->getId()
        ));
        $params = array(
            'entity'     => $entity,
            'form'       => $editForm->createView(),
            'action'     => 'update',
            'action_url' => $action_url
        );

        return array(
            'entity'          => $entity,
            'metastaz_template_fields' => $entity->getMetastazTemplateFields(),
            'params'          => $params,
            'delete_form'     => $deleteForm->createView(),
        );
    }

    /**
     * Displays a form to edit an existing MetastazTemplateField entity for a MetastazTemplate.
     *
     * @Route("/edit_field/{id}", name="metastaz_template_field_edit")
     * @Template()
     */
    public function editFieldAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getEntityManager('metastaz_template');
        $entity = $em->getRepository('MetastazTemplateBundle:MetastazTemplateField')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find MetastazTemplateField entity.');
        }

        $editForm = $this->createForm(new MetastazTemplateFieldType(), $entity);
        $deleteForm = $this->createDeleteForm($id);

        $action_url = $this->get('router')->generate('metastaz_template_field_update', array(
            'id' => $entity->getId()
        ));
        $params = array(
            'entity'     => $entity,
            'form'       => $editForm->createView(),
            'action'     => 'update',
            'action_url' => $action_url
        );

        return array(
            'entity'      => $entity,
            'params'      => $params,
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Edits an existing MetastazTemplate entity.
     *
     * @Route("/{id}/update", name="metastaz_template_update")
     * @Method("post")
     * @Template("MetastazTemplateBundle:MetastazTemplate:edit.html.twig")
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getEntityManager('metastaz_template');
        $entity = $em->getRepository('MetastazTemplateBundle:MetastazTemplate')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find MetastazTemplate entity.');
        }

        $editForm   = $this->createForm(new MetastazTemplateType(), $entity);
        $deleteForm = $this->createDeleteForm($id);

        $editForm->bindRequest($request);

        if ($editForm->isValid()) {
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('metastaz_template'));
        }

        return array(
            'entity'            => $entity,
            'metastaz_template_fields'   => $entity->getMetastazTemplateFields(),
            'edit_form'         => $editForm->createView(),
            'delete_form'       => $deleteForm->createView(),
        );
    }

    /**
     * Edits an existing MetastazTemplateField entity.
     *
     * @Route("/update_field/{id}", name="metastaz_template_field_update")
     * @Method("post")
     * @Template("MetastazTemplateBundle:MetastazTemplate:editField.html.twig")
     */
    public function updateFieldAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getEntityManager('metastaz_template');
        $entity = $em->getRepository('MetastazTemplateBundle:MetastazTemplateField')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find MetastazTemplateField entity.');
        }

        $editForm   = $this->createForm(new MetastazTemplateFieldType(), $entity);
        $deleteForm = $this->createDeleteForm($id);

        $editForm->bindRequest($request);

        if ($editForm->isValid()) {
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('metastaz_template_edit', array('id' => $entity->getMetastazTemplateId())));
        }

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Deletes a MetastazTemplate entity.
     *
     * @Route("/{id}/delete", name="metastaz_template_delete")
     * @Method("post")
     */
    public function deleteAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);
        $form->bindRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getEntityManager('metastaz_template');
            $entity = $em->getRepository('MetastazTemplateBundle:MetastazTemplate')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find MetastazTemplate entity.');
            }

            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('metastaz_template'));
    }
    
    /**
     * Deletes a MetastazTemplateField entity.
     *
     * @Route("/delete_field/{id}", name="metastaz_template_field_delete")
     * @Method("post")
     */
    public function deleteFieldAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);
        $form->bindRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getEntityManager('metastaz_template');
            $entity = $em->getRepository('MetastazTemplateBundle:MetastazTemplateField')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find MetastazTemplateField entity.');
            }

            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('metastaz_template_edit', array('id' => $entity->getMetastazTemplateId())));
    }

    /**
     * Displays a MetastazTemplate form.
     *
     * @Route("/{id}/form/show", name="metastaz_template_form_show")
     * @Template("MetastazTemplateBundle:MetastazTemplate:showForm.html.twig")
     */
    public function showFormAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getEntityManager('metastaz_template');
        $entity = $em->getRepository('MetastazTemplateBundle:MetastazTemplate')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find MetastazTemplate entity.');
        }

        $form = MetastazFormFactory::createForm($this->container, $entity);

        return array(
            'entity'    => $entity,
            'form'      => $form->createView()
        );
    }

    private function createDeleteForm($id)
    {
        return $this->createFormBuilder(array('id' => $id))
            ->add('id', 'hidden')
            ->getForm()
        ;
    }
}
