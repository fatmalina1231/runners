<?php

namespace App\Controller;

use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Forms;

class OptionsController extends AbstractController
{
    private $passwordEncoder;
    /**
     * @Route("/options ", name="OptionsIndex")
     */
    public function OptionsIndex(Request $request): Response
    {
        $form_nickname = $this->createFormBuilder()
        ->add('username',TextType::class,['required' => true,'allow_extra_fields' => true])
        ->add('ChangeUsername',SubmitType::class)
        ->getForm();

        $form_password = $this->createFormBuilder()
        ->add('old_password',TextType::class,[
            'required' => true
        ])
        ->add('password', RepeatedType::class, [
            'type' => PasswordType::class,
            'required' => true,
            'first_options' =>  ['label' => 'Password'],
            'second_options' => ['label' => 'Confirm Password'] 
        ])
        ->add('ChangePassword',SubmitType::class)
        ->getForm();

        $form_email = $this->createFormBuilder()
        ->add('email',EmailType::class,['allow_extra_fields' => true])
        ->add('ChangeEmail',SubmitType::class)
        ->getForm();

            $form_nickname->handleRequest($request);
            $form_password->handleRequest($request);
            $form_email->handleRequest($request);

            if($form_nickname->isSubmitted() && $form_nickname->isValid()){
                $data = $form_nickname->getData();
                $this->ChangeNicknameOptions($data);
            }
            if($form_password->isSubmitted() && $form_password->isValid()){
                $data = $form_password->getData();
                $this->ChangePasswordOptions($data);
            }
            if($form_email->isSubmitted() && $form_email->isValid()){
                $data = $form_email->getData();
                //zmiana emaila
            }

        return $this->render('user/wish/options/OptionsIndex.html.twig',
        ['form_nickname' => $form_nickname->createView(),
         'form_password' => $form_password->createView(),
         'form_email' => $form_email->createView()]);
    }

    public function ChangeNicknameOptions($data)
    {
        $User = $this->getUser();
        $User->setUsername($data["username"]);
        $em = $this->getDoctrine()->getManager();
        $em->persist($User);
        $em->flush();
        $this->addFlash('success', 'Username został zmieniony!'); 
    }

    public function ChangePasswordOptions($data)
    {
        $User = $this->getUser();
        $checkPass = $passwordEncoder->isPasswordValid($User, $data["old_password"]);
        if($checkPass === true) {
            $password = $passwordEncoder->encodePassword($User, $data["password"]);
            $User->setPassword($password);
            $em = $this->getDoctrine()->getManager();
            $em->persist($User);
            $em->flush();
            $this->addFlash('success', 'Hasło zostało zmienione!'); 
        } else {
            $this->addFlash('error', 'Hasło nie mogło zostać zmienione!'); 
        }
    }

    public function ChangeEmailOptions()
    {

    }
    public function SendEmailVerification()
    {

    }
}
