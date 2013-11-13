<?php

namespace Acme\TeacherBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;


class DefaultController extends Controller
{
    
    public function indexAction($name)
    {
        return $this->render('AcmeTeacherBundle:Default:index.html.twig', array('name' => $name));
    }
}
