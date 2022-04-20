<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\EntityManagerInterface;
use App\Form\UserEditType;
use App\Repository\ConversationRepository;
use App\Repository\BlockListRepository;


/**
 * @Route("user/account/")
 * @isGranted("ROLE_USER")
 */
class UserAccountController extends AbstractController
{
   private $manager;

    /**
     * @param EntityManagerInterface $manager
     */
    public function __construct(EntityManagerInterface $manager
)
    {
        $this->EntityManagerInterface = $manager;
       
    }
    
    
    /**
     * Edit user
     *
     * @Route("editionuser", name="user_account_edit")
     * 
     * @param Request $request
     * @return Response
     */
    public function edit(Request $request)
    {
        $user = $this->getUser();

        $form = $this->createForm(UserEditType::class, $user);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $this->manager->flush();

            $this->addFlash(
                'success',
                "Votre profil a bien était mis à jour !"
            );

            return $this->redirectToRoute('user_account_index');
        }

        return $this->render('user/account/edit.html.twig', [
            'body_class' => "profil-edit content-center",
            'user' => $user,
            'form' => $form->createView()
        ]);
    }

    /**
     * Profil
     *
     * @Route("", name="user_account_index")
     * 
     * @return Response
     */
    public function index()
    {
        return $this->render('user/account/index.html.twig', [
            'user' => $this->getUser()
        ]);
    }


}
