<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use AppBundle\Entity\Carte;
use AppBundle\Entity\Repere;
use AppBundle\Entity\Image;
use AppBundle\Entity\Texte;
use AppBundle\Entity\Couleur;

class DefaultController extends Controller
{
    /**
     * @Route("/", name="homepage")
     */
    public function indexAction(Request $request)
    {
        // replace this example code with whatever you need
        return $this->render('default/index.html.twig', [
            'base_dir' => realpath($this->getParameter('kernel.project_dir')).DIRECTORY_SEPARATOR,
        ]);
    }
    
    /**
     * @Route("/project", name="project")
     */
    public function projectAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        
        $user = $this->getUser();
        $carte = new Carte();
        $carte->setUser($user);
        
        $em->persist($carte);
        $em->flush();                                      
        
        return $this->render('default/project.html.twig', [
            'carte' => $carte,
        ]);
    }
    
    /**
     * @Route("/caculate-repere", name="caculateRepere")
     */
    public function caculateRepereAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $data = $request->request->all();
        $carte = $em->getRepository(Carte::class)->find($data['carteId']);
        dump($carte);
        $abcissse = $data['abcisse'];
        $ordonnee = $data['ordonnee'];
        
        $exist_repere = $em->getRepository(Repere::class)->findOneBy(['carte'=>$data['carteId'], 'abcisse'=>$abcissse, 'ordonnee'=>$ordonnee]);
        
        if(!$exist_repere)
        {
        
    //       A metre dans l'entité carte
            $maxItemCarte = 100;

            $abcissseMin = $abcissse--;
    //        while ($abcissse < 0) {
    //            $abcissse++;
    //        }
            $abcisseMax = $abcissse++;

            $ordonneeMin = $ordonnee--;
    //        while ($ordonnee > $maxItemCarte) {
    //            $maxItemCarte--;
    //        }
            $ordonneeMax = $ordonnee++;

            $reperes = $images = $textes = [];
            $couleur = null;
            for($i = $abcissseMin;$i <=$abcisseMax; $i++){
                for($j = $ordonneeMin;$j <=$ordonneeMax; $j++){
                    //une petite fonction?
                    if(0 >= $i && $i<=$maxItemCarte && 0 >= $j && $j <= $maxItemCarte && $i != $abcissse && $j != $ordonnee){
                        $repere = $em->getRepository(Repere::class)->findOneBy(['carte'=>$data['carteId'], 'abcisse'=>$abcissse, 'ordonnee'=>$ordonnee]);
                        if($repere){
                            $reperes[] = $repere;
                            $images[] = $repere->getImage();
                            $textes = array_merge($textes, $repere->getTextes()->toArray()) ;
                            if($repere->getMajor()){
                                $couleur = $repere->getCouleur();
                            }
                        }

                    }
                }
            }

            if(count($reperes)>0)// il y dejà des points
            {
                $resultat = array_rand(['texte','image']);
                $exist_repere = null;
                $repere = new Repere();
                
                $repere->setAbcisse($abcissse)
                        ->setOrdonnee($ordonnee)
                        ->setMajor(true)
                        ->setCarte($carte);

                if($resultat == 'texte') // on choisit un texte aléatoirement
                {                    
                    do {
                    
                        $texte = $em->getRepository(Texte::class)->getRandomEntity();

                        $image_rand = array_rand($images);

                        $texte_rand = array_rand($textes);

                        $exist_repere = $em->getRepository(Repere::class)->findOneBy(['carte'=>$data['carteId'], 'image'=>$image_rand, 'textes'=>[$texte,$texte_rand]]);
                    } while ($exist_repere);
                    
                    $repere->setImage($image_rand)->addTexte($texte)->addTexte($texte_rand);
                        
                } else {
                    
                    do {
                    
                    $image = $em->getRepository(Image::class)->getRandomEntity();

                    $texte_rand1 = array_rand($textes);
                    
                    $texte_rand2 = array_rand($textes);

                    $exist_repere = $em->getRepository(Repere::class)->findOneBy(['carte'=>$data['carteId'], 'image'=>$image, 'textes'=>[$texte_rand1,$texte_rand2]]);
                    } while ($exist_repere);
                    
                    $repere->setImage($image)->addTexte($texte_rand1)->addTexte($texte_rand2);                    
                }
                
                if(!$couleur){
                    $couleur = $em->getRepository(Couleur::class)->getRandomEntity();                
                }
                
                $repere->setCouleur($couleur);
                
                $em->persist($repere);



            } else {
                $repere = new Repere();
                $repere->setAbcisse($abcissse)
                        ->setOrdonnee($ordonnee)
                        ->setMajor(true)
                        ->setCarte($carte);

                $couleur = $em->getRepository(Couleur::class)->getRandomEntity();
                $repere->setCouleur($couleur);

                $image = $em->getRepository(Image::class)->getRandomEntity();
                $repere->setImage($image);

                $text1 = $em->getRepository(Texte::class)->getRandomEntity();
                $text2 = $em->getRepository(Texte::class)->getSecondRandomEntity($text1->getAuteur());
                $repere->addTexte($text1);
                $repere->addTexte($text2);

                $em->persist($repere);
            }
            
            $em->flush();
            
            $result = json_encode($repere);
        
        } else {
            $result = json_encode($exist_repere);
        }
        
        return new JsonResponse(['result'=>$result]);
    }
}
