<?php

namespace App\Controller;

use App\Entity\ListWishes;
use App\Entity\ListAccess;
use App\Entity\Wishes;
use App\Entity\User;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Bridge\Twig\Extension\TranslationExtension;
use Symfony\Component\Form\Forms;
use Symfony\Component\Translation\Loader\XliffFileLoader;
use Symfony\Component\Translation\Translator;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;


class WishListController extends AbstractController{
    /**
     * @Route("/wishlist", name="wishlist")
     */

    public function wishlist(Request $request){

        $repository = $this->getDoctrine()->getRepository(ListWishes::class);
        $ListWishes = $repository->findBy(['id_user' => $this->getUser()->getId()]);
        return $this->render('user/wish/wishlist/wishlist.html.twig',[
            'ListWishes' => $ListWishes,
            ]);
    }
     /**
     * @Route("/wishlist/share/{code}",name="wishlistshare", methods={"GET","HEAD"})
     */
    public function wishlistShare(Request $request,$code): Response
    {
        $repository = $this->getDoctrine()->getRepository(ListWishes::class);
        $ListWishes = $repository->findOneBy(['code' => $code]);
        $error = 0;
        if(!$ListWishes){
            $this->addFlash('no-code', 'Błędne zaproszenie!');
            $error = 1;
        }

        return $this->render('user/wish/wishlist/wishlistshare.html.twig',[
            'ListWishes' => $ListWishes,
            'error' => $error,
            ]);
    }
     /**
     * @Route("/wishlist/share/{code}/join",name="wishlistjoin", methods={"GET","HEAD"})
     */
    public function wishlistJoin(Request $request,$code){
        $repository = $this->getDoctrine()->getRepository(ListWishes::class);
        $ListWishes = $repository->findOneBy(['code' => $code]);

        if(!$ListWishes){
            $this->addFlash('no-code', 'Błędne zaproszenie!');
            return $this->render('wishlist/wishlist.html.twig',['ListWishes' => $ListWishes]);
        }

        $repository_2 = $this->getDoctrine()->getRepository(ListAccess::class);
        $ListAccess = $repository_2->findOneBy(['id_user' => $this->getUser()->getId(),'id_list' => $ListWishes->getId()]);

        if($ListAccess){
            $this->addFlash('no-code', 'Już dołączyłeś do tej listy!');
            return $this->render('wishlist/wishlist.html.twig',['ListWishes' => $ListWishes]);
        }

        $ViewList = new ListAccess();
        $ViewList->setIdUser($this->getUser()->getId());
        $ViewList->setIdList($ListWishes->getId());
        $em = $this->getDoctrine()->getManager();
        $em->persist($ViewList);
        $em->flush();
        $this->addFlash('success', 'Dolaczyles do wybranej listy!');


        return $this->render('user/wish/wishlist/wishlistsharejoin.html.twig',[
            'ViewList' => $ViewList,
        ]);
    }
  /**
     * @Route("/wishlist/share/{code}/view",name="wishlistviewcode", methods={"GET","HEAD"})
     */
    public function wishlistviewcode(Request $request,$code){
        $repository = $this->getDoctrine()->getRepository(ListWishes::class);
        $ListWishes = $repository->findOneBy(['code' => $code]);
        if(!$ListWishes){
            $this->addFlash('no-code', 'Nie ma takiej listy!');
            return $this->render('user/wish/wishlist/wishlist.html.twig',['ListWishes' => $ListWishes]);
        }
            
        $link = "http://".$_SERVER['SERVER_ADDR']."/wishlist/share/".$code;
        
        return $this->render('wishlist/wishlistviewcode.html.twig',[
            'link' => $link,
        ]);
    }
    /**
     * @Route("/wishlist/edit/{id}",name="wishlistedit", methods={"GET","HEAD"})
     */
    public function wishlistedit(Request $request,$id){
        $user = $this->getUser();
        $repository = $this->getDoctrine()->getRepository(ListWishes::class);
        $ListWishes = $repository->findOneBy(['id' => $id,'id_user' => $user->getId()]);

        if(!$ListWishes){
            $this->addFlash('no-code', 'Nie masz dostępu do tej listy!');
            return $this->render('user/wish/wishlist/wishlist.html.twig',['ListWishes' => NULL]);
        }

        $repository_2 = $this->getDoctrine()->getRepository(Wishes::class);
        $ListWishess = $repository_2->findby(['id_list' => $id]);

            $form = $this->createFormBuilder()
            ->add('name', TextType::class,['required' => true,'data' => $ListWishes->getName()])
            ->add('description',TextType::class,['data' => $ListWishes->getDescription()])
            ->add('category',ChoiceType::class,[
                'choices' =>[
                    'birthday' => 1,
                    'christmas' => 2,
                    'other' => 3,
                ],'data' => $ListWishes->getIdCategory()
            ])
            ->add('Edit',SubmitType::class)
            ->getForm();
            $form->handleRequest($request);
            if($form->isSubmitted()){
                $data = $form->getData();
                $ListWishes->setIdCategory($data["category"]);
                $ListWishes->setName($data["name"]);
                $ListWishes->setDescription($data["description"]);
                $em = $this->getDoctrine()->getManager();
                $em->persist($ListWishes);
                $em->flush();

                $name_wishes = $_POST["name_wish"];
                $desc_wishes = $_POST["desc_wish"];
                $link_wishes = $_POST["link_wish"];
                $id_wishes = $_POST["id_wish"];
    
                $count_array = count($name_wishes);
                    for($i=0;$i<$count_array;$i++){
                        if($name_wishes[$i] != NULL){
                            $Wishes  = new Wishes();
                            $Wishes->setIdList($lastId);
                            $Wishes->setIdUser($user->getId());
                            $Wishes->setActive(0);
                            $Wishes->setName($name_wishes[$i]);
                            $Wishes->setDescription($desc_wishes[$i]);
                            $url = filter_var($link_wishes[$i], FILTER_SANITIZE_URL);
                            if (!filter_var($url, FILTER_VALIDATE_URL) !== false) {
                                $link_wishes[$i] = NULL;
                            }
                            $Wishes->setLink($link_wishes[$i]);
    
                            $em = $this->getDoctrine()->getManager();
                            $em->persist($Wishes);
                            $em->flush();
                        }
                    }

                $this->addFlash('success', 'Edytowales wybrana liste!'); 
            }
           
            return $this->render('user/wish/wishlist/wishlistedit.html.twig',[
                'form' => $form->createView(),
            ]);
        
    }


      /**
     * @Route("/wishlist/add", name="wishlistadd",methods={"GET","HEAD","POST"})
     */
    public function wishlistAdd(Request $request) : Response {

        function generateRandomString($length = 16, $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ')
        {
            $charactersLength = strlen($characters);
            $randomString = '';
            for ($i = 0; $i < $length; $i++) {
                $randomString .= $characters[rand(0, $charactersLength - 1)];
            }
            return $randomString;
        }

        $form = $this->createFormBuilder()
        ->add('name', TextType::class,['required' => true])
        ->add('description',TextareaType::class)
        ->add('time_end',ChoiceType::class,[
            'choices' =>[
                '7days' => 7,
                '14days' => 14,
                '31days' => 31,
            ],
        ])
        ->add('category',ChoiceType::class,[
            'choices' =>[
                'birthday' => 1,
                'christmas' => 2,
                'other' => 3,
            ]
        ])
        ->add('Add',SubmitType::class)
        ->getForm();
        $form->handleRequest($request);
        if($form->isSubmitted()){
            $data = $form->getData();
            $ListWishes = new ListWishes();
            $user = $this->getUser();
            $ListWishes->setIdUser($user->getId());
            $ListWishes->setIdCategory($data["category"]);
            $ListWishes->setName($data["name"]);
            $ListWishes->setDescription($data["description"]);
            $ListWishes->setCode(generateRandomString());
            $date = new \DateTime();
            $ListWishes->setTime($date->format('Y-m-d'));
            $next_time = new \DateTime();
            $next_time->modify('+'.$data["time_end"].' day');
            $ListWishes->setTimeEnd($next_time->format('Y-m-d'));

            $em = $this->getDoctrine()->getManager();
            $em->persist($ListWishes);
            $em->flush();

            $lastId = $ListWishes->getId(); 

            $name_wishes = $_POST["name_wish"];
            $desc_wishes = $_POST["desc_wish"];
            $link_wishes = $_POST["link_wish"];

            $count_array = count($name_wishes);
                for($i=0;$i<$count_array;$i++){
                    if($name_wishes[$i] != NULL){
                        $Wishes  = new Wishes();
                        $Wishes->setIdList($lastId);
                        $Wishes->setIdUser($user->getId());
                        $Wishes->setActive(0);
                        $Wishes->setName($name_wishes[$i]);
                        $Wishes->setDescription($desc_wishes[$i]);
                        $url = filter_var($link_wishes[$i], FILTER_SANITIZE_URL);
                        if (!filter_var($url, FILTER_VALIDATE_URL) !== false) {
                            $link_wishes[$i] = NULL;
                        }
                        $Wishes->setLink($link_wishes[$i]);

                        $em = $this->getDoctrine()->getManager();
                        $em->persist($Wishes);
                        $em->flush();
                    }
                }
             $this->addFlash('success', 'Wishlist added!');
             return $this->redirect($this->generateUrl('wishlist'));
        }
        return $this->render('user/wish/wishlist/wishlistadd.html.twig',[
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/wishlist/delete/{id}", name="wishlistdelete",methods={"GET","HEAD"})
     */
    public function wishlistDelete(ListWishes $id){
        if($id == NULL){
            $this->addFlash('no-code', 'Nie ma takiej listy!');
            return $this->redirect($this->generateUrl('whishlist'));
        }
        if($id->getIdUser() != $this->getUser()->getId()){
            $this->addFlash('no-code', 'Nie masz dostępu do tej listy!');
            return $this->redirect($this->generateUrl('whishlist'));
        }
            $em = $this->getDoctrine()->getManager();
            $em->remove($id);
            $em->flush();
            $this->addFlash('success', 'Lista została skasowana!');
            return $this->redirect($this->generateUrl('whishlist'));
    }
     /**
     * @Route("/wishlist/open/{id}", name="wishlistopen",methods={"GET","HEAD"})
     */
    
    public function wishlistOpen(Request $request,ListWishes $id){
        if($id == NULL){
            $this->addFlash('no-code', 'Nie ma takiej listy!');
            return $this->redirect($this->generateUrl('whishlist'));
        }
        $repository_2 = $this->getDoctrine()->getRepository(User::class);
        $username_login = $this->getUser()->getUsername();
        if(!$id){
            $NameList = NULL;
            $DescList = NULL;
            $Username = NULL;
            $ListWishes = NULL;
            $ListLink[] = array("link" => NULL);
            $ListUser[] = array("username" => NULL);
        }else{
            $CheckUser = $repository_2->findby(['id' => $id->getIdUser()]);
            $repository_3 = $this->getDoctrine()->getRepository(Wishes::class);
            $ListWishes = $repository_3->findby(['id_list' => $id]);
            $ListUser = array();
            $ListLink = array();
            foreach($ListWishes as $LW){
                if($LW->getIdUser() != NULL){
                    $ListUser[] = array("username" => "Ukryte");
                }else{
                    $ListUser[] = array("username" => NULL);
                }
                if (!filter_var($LW->getLink(), FILTER_VALIDATE_URL) !== false) {
                    $ListLink[] = NULL;
                 }else{
                    $ListLink[] = $LW->getLink();
                }

            }
            $NameList = $id->getName();
            $DescList = $id->getDescription();
            $Username = $CheckUser[0]->getUsername();
        }

        return $this->render('user/wish/wishlist/wishlistOpen.html.twig',
    [
        'NameList'  => $NameList,
        'DescList'  => $DescList,
        'Username'  => $Username,
        'ListWishes' => $ListWishes,
        'username_login' =>  $username_login,
        'ListLink'  => $ListLink,
        'ListUser'  => $ListUser,
    ]);

     }
}
?>