<?php

namespace AppBundle\Controller;
use AppBundle\Entity\Posts;
use Symfony\Component\DomCrawler\Crawler;
use Symfony\Component\CssSelector\CssSelector;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller {
	
	
	public function indexAction() {
            $nbp = 15;
            for($ii = 0; $ii <= $nbp; $ii++){
                if($ii == 0){
                    $myurl = 'http://www.viedemerde.fr/';
                } else {
                    $myurl = 'http://www.viedemerde.fr/?page='.$ii;
                }
                $curl = curl_init();
                curl_setopt($curl, CURLOPT_URL, $myurl);
                curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

                $resultat = curl_exec($curl);
                curl_close($curl);

                $crawler = new Crawler($resultat);

                // Recherche les informations 
                $attributes =  $crawler->filter('.article > p , .right_part > p:nth-of-type(2)')->each(function ($node, $i) {     
                        return $node->text();
                });
                
                if($ii === $nbp){
                    $this->addLastPost($attributes);
                } else {
                    $this->addPost($attributes);
                }
                   
               
            }
            $result = "Sucess";
            

               
            // Distibut à la view
            return $this->render('AppBundle:Default:index.html.twig', array(
					'result' => $result, 
                                        
            ));
        }
        
        public function check(array $attributes){
        
        // Vérifie si post est vide 
        $em = $this->getDoctrine();
        $post = $em->getRepository('AppBundle:Posts')->findAll();    
        
        
        // Si il n'est pas vide on vide la table
            if(!$post){
                
                $this->dropTable('posts');
                $query->execute();
                return $post;                
            } 
        }
        
        public function addPost( array $attributes){
            
            // On boucle pour ajouter les données dans la BDD
            for ($ii = 0; $ii < count($attributes); $ii+=2){
                    
                    $post[$ii] = new Posts();
                    $post[$ii]->setContents($attributes[$ii]);
                    $post[$ii]->setAuthor($attributes[$ii+1]);

                    $em = $this->getDoctrine()->getManager();
                    $em->persist($post[$ii]);
                    $em->flush(); 
                }
        }
        
        public function addLastPost( array $attributes){
            
            // On boucle pour ajouter les données dans la BDD
            for ($ii = 0; $ii <= 9 ; $ii+=2){
                    
                    $post[$ii] = new Posts();
                    $post[$ii]->setContents($attributes[$ii]);
                    $post[$ii]->setAuthor($attributes[$ii+1]);

                    $em = $this->getDoctrine()->getManager();
                    $em->persist($post[$ii]);
                    $em->flush(); 
                }
        }

}