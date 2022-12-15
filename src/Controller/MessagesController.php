<?php

namespace App\Controller;

use App\Entity\Messages;
use App\Form\MessagesType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MessagesController extends AbstractController
{
    //template fait appel a ce route dans bouton accueil
    /**
     * @Route("/messages", name="app_messages")
     */
    public function index(): Response
    {
        return $this->render('messages/index.html.twig.twig.twig', [
            'controller_name' => 'MessagesController',
        ]);
    }

    // fonction pour envoyer les messages (on a l'attribut form )
    //"form"==>on recuperer les donneés de l api send a partir de l attribut form dans View send.html.twig
//on recupure les api avec name

    /**
     * @Route("/send", name="send")
     */
    public function send(Request $request): Response
    {
        $message = new Messages;
        $form = $this->createForm(MessagesType::class, $message);


        //traitement de formulaire (handlerequest(recuperer le formulaire dans la requete) et de le traiter
        //et verifier si le formulaire est valide
        //issubmted et isvalide
        $form->handleRequest($request);
        if ($form->isSubmitted()&&$form->isValid())
        {
            //user connecter
            $message->setSender($this->getUser());
            //stocker donner
            $em=$this->getDoctrine()->getManager();
            //ecrire en bd
            $em->persist($message);
            $em->flush();

            $this->addFlash("message","message envoyer avec success");
            return $this->redirectToRoute("app_messages");
        }

        return $this->render("messages/send.html.twig",[
"form"=>$form->createView()
    ]);
    }
//fonction pour lire le message dans bdd le valeur 0 apres le lire sera 1
    /**
     * @Route("/read/{id}", name="read")
     */
    public function read(Messages $message): Response
    {
        $message->setIsRead(true);
        $em = $this->getDoctrine()->getManager();
        $em->persist($message);
        $em->flush();

        return $this->render('messages/read.html.twig', compact("message"));
    }

    /**
     * @Route("/received", name="received")
     */
    public function received(): Response
    {
        return $this->render('messages/received.html.twig');
    }
//le fonction de supprision des messages dans twig received
    /**
     * @Route("/delete/{id}", name="delete")
     */
    public function delete(Messages $message): Response
    {
        $em = $this->getDoctrine()->getManager();
        $em->remove($message);
        $em->flush();

        return $this->redirectToRoute("received");    }

//pour les elements envoyer
    /**
     * @Route("/sent", name="sent")
     */
    public function sent(): Response
    {
        return $this->render('messages/sent.html.twig');
    }

    //template fait appel a ce route dans bouton accueil
    /**
     * @Route("/messanger", name="messanger")
     */
    public function messanger(): Response
    {
        return $this->render('index.html.twig', [
            'controller_name' => 'MessagesController',
        ]);
    }


}
