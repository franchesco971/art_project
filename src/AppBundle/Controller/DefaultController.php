<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use AppBundle\Entity\Carte;
use AppBundle\Entity\Repere;
use AppBundle\Entity\Image;
use AppBundle\Entity\Texte;
use AppBundle\Entity\Couleur;
use JMS\Serializer\SerializerBuilder;
use Psr\Log\LoggerInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\Security\Core\User\User as CoreUser;

class DefaultController extends Controller
{
    /**
     * @Route("/test", name="homepage")
     */
    public function indexAction(Request $request)
    {
        // replace this example code with whatever you need
        return $this->render('default/index.html.twig', [
            'base_dir' => realpath($this->getParameter('kernel.project_dir')).DIRECTORY_SEPARATOR,
        ]);
    }
    
    /**
     * @Route("/", name="project")
     */
    public function projectAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $user = $this->getUser();
        $tabReperes = [];
        
        $carte = $em->getRepository(Carte::class)->findOneBy(['user'=>$user],['createdAt'=>'DESC']);
        
        if(!$carte) {
            
            if($user instanceof CoreUser){              
                
                $carte = $em->getRepository(Carte::class)->findOneBy(['user'=>null],['createdAt'=>'DESC']);
                if(!$carte) {
                    $carte = new Carte();
                    $em->persist($carte);
                    $em->flush();
                }
                
            } else {
                $carte = new Carte();
                
                $carte->setUser($user);
                $em->persist($carte);
                $em->flush();
            }            

            
        } else {
            $reperes = $em->getRepository(Repere::class)->findBy(['carte'=>$carte]);
            foreach ($reperes as $repere) {
                $couleur = $repere->getCouleur();
                $tabReperes[$repere->getAbcisse()][$repere->getOrdonnee()] = ($repere->getMajor())?$couleur->getMajor():$couleur->getProxy();
            }
//            dump($tabReperes);
        }       
                                                              
        return $this->render('default/project.html.twig', [
            'carte' => $carte,
            'reperes' => $tabReperes
        ]);
    }
    
    /**
     * @Route("/caculate-repere", name="caculateRepere")
     */
    public function caculateRepereAction(Request $request,LoggerInterface $logger)
    {
        $em = $this->getDoctrine()->getManager();
        $data = $request->request->all();
        $carteId = (int)$data['carteId'];
        $abcissse = (int)$data['abcisse'];
        $ordonnee = (int)$data['ordonnee'];
        
        $previmage = (int)$data['previmage'];
        $prevtexte1 = (int)$data['prevtexte1'];
        $prevtexte2 = (int)$data['prevtexte2'];
        
        $carte = $em->getRepository(Carte::class)->find($carteId);
//        dump($carte);
        
//        $encoder = [new JsonEncoder()];
//        $normalizers = [new ObjectNormalizer()];
        $serializer = SerializerBuilder::create()->build();
        
        $repere = $em->getRepository(Repere::class)->findOneBy(['carte'=>$carteId, 'abcisse'=>$abcissse, 'ordonnee'=>$ordonnee]);
        $type = "old";
        
        if(!$repere) {            
//            dump("le repere n'existe pas");
            $logger->info("caculateRepereAction : le repere n'existe pas");
    //       A metre dans l'entité carte largeur/20
            $maxItemCarte = 150;
            $rayon = 2;

            $abcissseMin = $abcissse-$rayon;
    //        while ($abcissse < 0) {
    //            $abcissse++;
    //        }
            $abcisseMax = $abcissse+$rayon;

            $ordonneeMin = $ordonnee-$rayon;
    //        while ($ordonnee > $maxItemCarte) {
    //            $maxItemCarte--;
    //        }
            $ordonneeMax = $ordonnee+$rayon;

            $reperes = $images = $textes = [];
            $couleur = null;
            
            for($i = $abcissseMin;$i <=$abcisseMax; $i++){
                for($j = $ordonneeMin;$j <=$ordonneeMax; $j++){
                    //une petite fonction?
//                    dump($i);dump($j);
//                    dump(($i >= 0 && $i<=$maxItemCarte));
//                    dump(($j >= 0 && $j <= $maxItemCarte));
//                    dump(($i != $abcissse));
//                    dump(($i == $abcissse && $j == $ordonnee));
//                    dump(!($i == $abcissse && $j == $ordonnee));
                    if($i >= 0 && $i<=$maxItemCarte && $j >= 0 && $j <= $maxItemCarte && !($i == $abcissse && $j == $ordonnee)){
                        $repere = $em->getRepository(Repere::class)->findOneBy(['carte'=>$carteId, 'abcisse'=>$i, 'ordonnee'=>$j]);
//                        dump($data['carteId']);dump($i);dump($j);
//                        dump($repere);
                        if($repere){
                            $texteTab = [];
                            foreach ($repere->getTextes() as $texte) {
                                $texteTab[] = $texte->getId();
                            }
                            $reperes[] = [$repere->getImage()->getId()=>$texteTab];
                            $images[] = $repere->getImage();
                            $textes = array_merge($textes, $repere->getTextes()->toArray()) ;
                            if($repere->getMajor()){
                                $couleur = $repere->getCouleur();
                            }
                        }

                    }
                }
            }
            
            $nbReperes = count($reperes);
            if($nbReperes > 0)// il y dejà des points
            {
//                dump("il y dejà des points");
                $logger->info("caculateRepereAction : il y dejà des points");
                $resultat = array_rand(['texte','image']);
                $exist_repere = null;
                $repere = new Repere();
                $tour = 0;
                
                $repere->setAbcisse($abcissse)
                        ->setOrdonnee($ordonnee)
                        ->setMajor(false)
                        ->setCarte($carte);

                if($resultat == 'texte') // on choisit un texte aléatoirement
                {                    
                    do {                    
                        $texte = $em->getRepository(Texte::class)->getRandomEntity();
//
                        $image_rand = $images[array_rand($images)];
//                        
                        do {
                            $texte_rand = $textes[array_rand($textes)];
                        } while ($texte->getId() == $texte_rand->getId());                        
                        
                        $tabTxtToCompare = [$texte->getId(), $texte_rand->getId()];
//                        $exist_repere = $em->getRepository(Repere::class)->getByTexteImage($carteId, $image_rand->getId(), $texte->getId(), $texte_rand->getId());
//                    } while (!empty($exist_repere));
                        
                        $exist = false;
                        
                        foreach ($reperes as $repereTab) {                        
                            if(!$exist) {
                                foreach ($repereTab as $imageId => $tabTexte) {
                                    if($imageId == $image_rand->getId()) {
                                        $intersect = array_intersect($tabTexte, $tabTxtToCompare);
//                                        dump($tabTexte);dump($tabTxtToCompare);

                                        if(count($intersect) == 2) {
                                            $logger->info("caculateRepereAction : exist ".$imageId."-".$texte->getId()."-".$texte_rand->getId());
                                            $exist = true;
                                            continue;
                                        }
                                    }

                                }    
                            } else {
                                continue;
                            }
                        }
                        
                        if($tour > $nbReperes) {
                            throw new \Exception();
                        }
                        $tour++;
                    } while ($exist);
                    
                    $repere->setImage($image_rand)->addTexte($texte)->addTexte($texte_rand);
                        
                } else {
                    
                    do {                    
                        $image = $em->getRepository(Image::class)->getRandomEntity();

                        $texte_rand1 = $textes[array_rand($textes)];

                        do {
                            $texte_rand2 = $textes[array_rand($textes)];
                        } while ($texte_rand1->getId() == $texte_rand2->getId());  
                        
                        $tabTxtToCompare = [$texte_rand1->getId(), $texte_rand2->getId()];

//                        $exist_repere = $em->getRepository(Repere::class)->getByTexteImage($carteId, $image->getId(), $texte_rand1->getId(), $texte_rand2->getId());
                        $exist = false;
                        
                        foreach ($reperes as $repereTab) {                        
                            if(!$exist) {
                                foreach ($repereTab as $imageId => $tabTexte) {
                                    if($imageId == $image->getId()) {
                                        $intersect = array_intersect($tabTexte, $tabTxtToCompare);
                                        dump($tabTexte);dump($tabTxtToCompare);

                                        if(count($intersect) == 2) {
                                            $logger->info("caculateRepereAction : exist ".$imageId."-".$texte_rand1->getId()."-".$texte_rand2->getId());
                                            $exist = true;
                                            continue;
                                        }
                                    }

                                }    
                            } else {
                                continue;
                            }                           
                        }
                        
                        if($tour > $nbReperes) {
                            throw new \Exception();
                        }
                        $tour++;
                        
                    } while ($exist); 
//                    } while (!empty($exist_repere));                                       
                    
                    $repere->setImage($image)->addTexte($texte_rand1)->addTexte($texte_rand2);                    
                }
                
//                dump("repere choisi");
                $logger->info("caculateRepereAction : repere choisi");
//                dump($couleur);
                if(!$couleur){
                    $couleur = $em->getRepository(Couleur::class)->getRandomEntity();                
                }
                
                $repere->setCouleur($couleur);
                
                $em->persist($repere);



            } else {
                $type = "new";
//                dump("pas de points");
                $repere = new Repere();
                $repere->setAbcisse($abcissse)
                        ->setOrdonnee($ordonnee)
                        ->setMajor(true)
                        ->setCarte($carte);

                $couleur = $em->getRepository(Couleur::class)->getRandomEntity();
                $repere->setCouleur($couleur);

                $image = $em->getRepository(Image::class)->getRandomEntity();
                $repere->setImage($image);

                $text1 = $em->getRepository(Texte::class)->getRandomEntity($prevtexte1);
                $text2 = $em->getRepository(Texte::class)->getSecondRandomEntity($text1->getAuteur(),$text1->getId());
                $repere->addTexte($text1);
                $repere->addTexte($text2);
                
                
                //TODO vérifier existatnce avant persist
                $em->persist($repere);
            }
            
            $em->flush();
            
            
        
        } 
//        else {
//            dump('le repere existe');
//            dump($exist_repere);
//            $result = json_encode($exist_repere);
//            dump($result);
//        }
//        dump($repere);
        $logger->info("caculateRepereAction : before serialize");
        $result = $serializer->serialize($repere, 'json');
        $logger->info("caculateRepereAction : after serialize");
        
        return new JsonResponse(['result'=>$result,'type'=>$type]);
    }
    
    /**
     * @Route("/carte/new", name="new_carte")
     */
    public function newCarteAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $user = $this->getUser();
        
        $carte = new Carte();
        $carte->setUser($user);

        $em->persist($carte);
        $em->flush();
        
        return $this->redirectToRoute('project');
    }
    
    /**
     * @Route("/first_login", name="first_login")
     * @Method({"GET", "POST"})
     */
    public function firstLoginAction(Request $request, AuthenticationUtils $authenticationUtils)
    {
        $user = $this->getUser();
        if ($user instanceof UserInterface) {
          return $this->redirectToRoute('project');
        }

        /** @var AuthenticationException $exception */
        $exception = $this->get('security.authentication_utils')
          ->getLastAuthenticationError();

        return $this->render('admin/firstLogin.html.twig', [
          'error' => $exception ? $exception->getMessage() : NULL,
        ]);
    }
    
    /**
     * @Route("/first_logout", name="first_logout")
     * @Method({"GET", "POST"})
     */
    public function firstLogoutAction(Request $request)
    {        
        return $this->redirectToRoute('first_login');
    }
    
    /**
     * @Route("/infos", name="infos")
     */
    public function infosAction(Request $request)
    {
        // replace this example code with whatever you need
        return $this->render('default/infos.html.twig', [
            'base_dir' => realpath($this->getParameter('kernel.project_dir')).DIRECTORY_SEPARATOR,
        ]);
    }
    
    /**
     * @Route("/outils", name="outils")
     */
    public function outilsAction(Request $request)
    {
        // replace this example code with whatever you need
        return $this->render('default/outils.html.twig', [
            'base_dir' => realpath($this->getParameter('kernel.project_dir')).DIRECTORY_SEPARATOR,
        ]);
    }
    
    /**
     * @Route("/carte/liste", name="carte_liste")
     */
    public function carteListeAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $user = $this->getUser();
        $cartes = $em->getRepository(Carte::class)->findBy(['user'=>$user]);
        

        // replace this example code with whatever you need
        return $this->render('carte/index.html.twig', [
            'cartes' => $cartes,
        ]);
    }
    
    /**
     * @Route("/carte/{id}", name="carte_show", requirements={"id" = "\d+"})
     */
    public function carteShowAction(Request $request, Carte $carte)
    {
        $em = $this->getDoctrine()->getManager();
        $tabReperes = [];
        
        $reperes = $em->getRepository(Repere::class)->findBy(['carte'=>$carte]);
        foreach ($reperes as $repere) {
            $couleur = $repere->getCouleur();
            $tabReperes[$repere->getAbcisse()][$repere->getOrdonnee()] = ($repere->getMajor())?$couleur->getMajor():$couleur->getProxy();
        }
        

        // replace this example code with whatever you need
        return $this->render('default/project.html.twig', [
            'carte' => $carte,
            'reperes' => $tabReperes
        ]);
    }
    
    /**
     * @Route("/carte_edit/{id}", name="carte_edit", requirements={"id" = "\d+"})
     * @Method({"POST"})
     */
    public function carteEditAction(Request $request, Carte $carte)
    {
        $em = $this->getDoctrine()->getManager();
        $label = $request->request->get('label');
        
        if($label)
        {
            $carte->setCarteLabel($label);
            $em->flush();
        } else {
            throw new Exception();
        }
        
        
        return $this->redirectToRoute('carte_liste');
    }
}
