<?php

namespace Teo\ProductBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;

class DefaultController extends Controller
{
    public function indexAction($name)
    {
        return $this->render('TeoProductBundle:Default:index.html.twig', array('name' => $name));
    }

    public function downloadAction($id)
    {
        $attachment = $this->container
            ->get('doctrine')
            ->getRepository('TeoProductBundle:Attachment')
            ->find($id)
            ;

        $path = $attachment->getPath();

        $basePath = dirname($this->container->getParameter('kernel.root_dir')) . '/web';

        $content = file_get_contents($basePath . $path);

        $response = new Response();

        $response->headers->set('Content-Type', 'application/octet-stream');
        $response->headers->set('Content-Disposition', 'attachment;filename="' .  basename($path));

        $response->setContent($content);

        return $response;
    }
}
