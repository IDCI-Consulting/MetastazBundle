<?php

namespace Metastaz\Bundle\MetastazDashboardBundle\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

/**
 * MetastazDashboard controller.
 * 
 * @author:  Gabriel BONDAZ <gabriel.bondaz@idci-consulting.fr>
 * @licence: GPL
 */
class MetastazDashboardController extends Controller
{
    /**
     * Dashboard
     *
     * @Route("/dashboard", name="dashboard")
     * @Template()
     */
    public function indexAction()
    {
        return array();
    }

    /**
     * Panel
     *
     * @Route("/panel", name="panel")
     * @Template()
     */
    public function panelAction()
    {
        $params = $this->container->getParameter('metastaz.parameters');

        $panel = array();
        $panel['metastaz_product_enabled']  = $this->get('router')->getRouteCollection()->get('metastaz_product');
        $panel['instance_pooling_enabled']  = $params['container']['instance_pooling'];
        $panel['templating_enabled']        = $params['container']['use_template'];
        $panel['store_parameters']          = $params['store'];

        return array(
            'panel'  => $panel,
        );
    }

    /**
     * Menu
     *
     * @Route("/menu/{current}", name="menu")
     * @Template()
     */
    public function menuAction($current)
    {
        if(!isset($current))
        {
            var_dump($this->get('router')->match('/metastaz/product/'));
            die;
        }

        $menu = array();

        if($this->get('router')->getRouteCollection()->get('metastaz_template'))
        {
            $menu['metastaz_template'] = array(
                'label' => 'Gestion des templates',
                'current' => $current == 'template' ? 'current' : ''
            );
        }

        if($this->get('router')->getRouteCollection()->get('metastaz_product'))
        {
           $menu['metastaz_product'] = array(
                'label' => 'Gestion des produits',
                'current' => $current == 'product' ? 'current' : ''
            );
        }

        return array('menu' => $menu);
    }
}
