<?php

namespace App\Controller;

use App\Repository\UserRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ForgotPWDController extends AbstractController
{

    public $error = null;

    /**
     * @Route("/forgot/", name="forgot")
     */
    public function index(Request $request, UserRepository $user)
    {
        dump($request);
        if ($request->request->has("userName")) {
            dd("entre");
            try {
                $user->findOneBy(array('user_name' => $request->request->get('ForgotUserName')));
                dd("ok");
            } catch (\Throwable $th) {
                
            }
        }

        return $this->render('forgot_pwd/index.html.twig',[
            "error"=> $this->error,
        ]);
    }
}
