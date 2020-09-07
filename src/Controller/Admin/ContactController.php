<?php

namespace App\Controller\Admin;

use App\Entity\Contact;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;

/**
     * @Route("/admin/contact", name="admin_contact_")
     */
class ContactController extends AbstractController
{
    /**
     * @Route("/", options={"expose"=true}, name="index")
     */
    public function index(SerializerInterface $serializer)
    {
        $em = $this->getDoctrine()->getManager();
        $demandes = $em->getRepository(Contact::class)->findBy([], ['createAt' => 'DESC']);

        $demandes = $serializer->serialize($demandes, 'json', ['attributes' => [
            'id', 'firstname', 'email', 'message', 'createAtString', 'isSeen'
        ]]);

        return $this->render('root/admin/pages/contact/index.html.twig', [
            'demandes' => $demandes
        ]);
    }

    /**
    * @Route("/update/seen/{contact}", options={"expose"=true}, name="update_seen")
    */
    public function updateIsSeen(Contact $contact)
    {
        $em = $this->getDoctrine()->getManager();
        if(!$contact->getIsSeen()){
            $contact->setIsSeen(true);
            $em->persist($contact);
            $em->flush();

            return new JsonResponse([ 'code' => 1 ]);
        }

        return new JsonResponse([ 'code' => 0 ]);
    }

    /**
     * @Route("/delete/{contact}", options={"expose"=true}, name="delete")
     */
    public function delete($contact)
    {
        $em = $this->getDoctrine()->getManager();
        $contact = $em->getRepository(Contact::class)->find($contact);
        if(!$contact){
            return new JsonResponse(['code' => 0, 'message' => '[ERREUR] Cette demande de contact n\'existe pas.']);
        }

        if(!$contact->getIsSeen()){
            return new JsonResponse(['code' => 0, 'message' => 'Vous n\'avez pas consulté ce message.']);
        }

        $em->remove($contact); $em->flush();
        return new JsonResponse(['code' => 1]);
    }
}
