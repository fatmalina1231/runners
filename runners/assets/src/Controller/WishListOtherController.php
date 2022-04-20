<?php

namespace App\Controller;

use App\Entity\ListWishes;
use App\Entity\ListAccess;
use App\Entity\User;
use App\Entity\Wishes;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Bridge\Twig\Extension\TranslationExtension;
use Symfony\Component\Form\Forms;
use Symfony\Component\Translation\Loader\XliffFileLoader;
use Symfony\Component\Translation\Translator;

class WishListOtherController extends AbstractController{
    /**
     * @Route("/wishlistother", name="wishlistother")
     */

    public function wishlistOther(Request $request){

        $user = $this->getUser();
        $repository = $this->getDoctrine()->getRepository(ListWishes::class);
        $repository_2 = $this->getDoctrine()->getRepository(ListAccess::class);
        $CheckList = $repository_2->FindBy(['id_user' => $user->getId()]);
        $ListWishes = array();

        foreach($CheckList as $CL){
            if($CL){
                $ListWishes = $repository->findBy(['id' => $CL->getIdList()]);
            }
         }        

        return $this->render('user/wish/wishlist/wishlistother.html.twig',[
            'ListWishes' => $ListWishes,
            ]);
    }
    /**
     * @Route("/wishlistother/open/{id}", name="wishlistotheropen",methods={"GET","HEAD"})
     */
    
     public function wishlistOtherOpen(Request $request,ListWishes $id){
        $repository_2 = $this->getDoctrine()->getRepository(User::class);
        $username_login = $this->getUser()->getUsername();
        if(!$id){
            $error = 1;
            $NameList = NULL;
            $DescList = NULL;
            $Username = NULL;
            $ListWishes = NULL;
            $ListLink[] = array("link" => NULL);
            $ListUser[] = array("username" => NULL);
        }else{
            $CheckUser = $repository_2->findOneby(['id' => $id->getIdUser()]);
            $repository_3 = $this->getDoctrine()->getRepository(Wishes::class);
            $ListWishes = $repository_3->findby(['id_list' => $id]);
            $ListUser = array();
            $ListLink = array();
            foreach($ListWishes as $LW){
                if($LW->getIdUser() != NULL){
                    $ListUser[] = $repository_2->findOneby(['id' => $LW->getIdUser()]);
                }else{
                    $ListUser[] = array("username" => NULL);
                }
                $ListLink[] = $LW->getLink();
            }

            $NameList = $id->getName();
            $DescList = $id->getDescription();
            $Username = $CheckUser->getUsername();
            $error = 0;
        }

        return $this->render('user/wish/wishlist/wishlistotherOpen.html.twig',
    [
        'NameList'  => $NameList,
        'DescList'  => $DescList,
        'Username'  => $Username,
        'ListWishes' => $ListWishes,
        'username_login' =>  $username_login,
        'ListLink'  => $ListLink,
        'ListUser'  => $ListUser,
        'error'     => $error,
    ]);

     }
     /**
     * @Route("/wishlistother/join/{id}", name="wishlistotherjoin",methods={"GET","HEAD"})
     */
     public function wishlistOtherJoin(Request $request,Wishes $id){
        $user = $this->getUser();
        if(!$id){
            $this->addFlash('no-code', 'Nie ma takiego przedmiotu na liscie!');
            return $this->redirect($this->generateUrl('wishlistotheropen', ['id' => $id->getIdList()]));
        }
        if($id->getIdUser() != NULL){
            $this->addFlash('no-code', 'Ktoś już wybrał wybrany przedmiot!');
            return $this->redirect($this->generateUrl('wishlistotheropen', ['id' => $id->getIdList()]));
        }
        
        $id->setIdUser($user->getId());
        $em = $this->getDoctrine()->getManager();
        $em->persist($id);
        $em->flush();
        $this->addFlash('success', 'Przypisałeś do siebie wybrany przedmiot!');
     
     return $this->redirect($this->generateUrl('wishlistotheropen', ['id' => $id->getIdList()]));
    }
    /**
     * @Route("/wishlistother/leave/{id}", name="wishlistotherleave",methods={"GET","HEAD"})
     */
    public function wishlistOtherLeave(Request $request,Wishes $id)
    {
        $user = $this->getUser();
        if(!$id){
            $this->addFlash('no-code', 'Nie ma takiego przedmiotu na liscie!');
            return $this->redirect($this->generateUrl('wishlistotheropen', ['id' => $id->getIdList()]));
        }
        if($id->getIdUser() == NULL){
            $this->addFlash('no-code', 'Nikt nie wybrał tego przedmiotu!');
            return $this->redirect($this->generateUrl('wishlistotheropen', ['id' => $id->getIdList()]));
        }

        $id->setIdUser("0");
        $em = $this->getDoctrine()->getManager();
        $em->persist($id);
        $em->flush();
        $this->addFlash('success', 'Wypisałeś od siebie wybrany przedmiot!');

     return $this->redirect($this->generateUrl('wishlistotheropen', ['id' => $id->getIdList()]));

    }
}
?>