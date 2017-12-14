<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class RestController extends Controller
{
    public function user(Request $request)
    {
        die($this->container->get('foo.bar'));

        // $form = $this
        //     ->createFormBuilder()
        //     ->add('foo', TextType::class)
        //     ->getForm();

        return new JsonResponse(['username' => $request->get('username')]);
    }
}
