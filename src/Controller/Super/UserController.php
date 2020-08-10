<?php

namespace App\Controller\Super;

use App\Entity\User;
use App\Service\FileUploader;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Serializer\SerializerInterface;

/**
 * @Route("/administrator/utilisateurs", name="super_users_")
 */
class UserController extends AbstractController
{
    /**
     * @Route("/", options={"expose"=true}, name="index")
     */
    public function index(SerializerInterface $serializer)
    {
        $em = $this->getDoctrine()->getManager();
        $users = $em->getRepository(User::class)->findBy([], ['username' => 'ASC']);

        $users = $serializer->serialize($users, 'json', ['attributes' => [
            'id', 'username', 'roles', 'email', 'isNew', 'avatar', 'highRole', 'highRoleCode', 'createAtString', 'renouvTimeString'
        ]]);
        return $this->render('root/super/pages/user/index.html.twig', [
            'users' => $users
        ]);
    }

    /**
     * @Route("/update/utilisateur/{user}", options={"expose"=true}, name="user_update")
     */
    public function update(Request $request, $user, FileUploader $fileUploader)
    {
        $em = $this->getDoctrine()->getManager();
        $user = $em->getRepository(User::class)->find($user);
        if(!$user){
            return new JsonResponse(['code' => 0, 'message' => '[ERREUR] Cet utilisateur n\'existe pas.']);
        }

        $data = json_decode($request->get('data'));
        $file = $request->files->get('file');

        if($file){
            $filename = $fileUploader->upload($file, 'avatar/', true);
            $user->setAvatar($filename);
        }

        $user->setUsername($data->username->value);
        $user->setEmail($data->email->value);
        $user->setRoles($data->roles->value);

        $em->persist($user); $em->flush();
        return new JsonResponse(['code' => 1, 'highRoleCode' => $user->getHighRoleCode(), 'highRole' => $user->getHighRole(), 'avatar' => $user->getAvatar()]);
    }

    /**
     * @Route("/add/utilisateur", options={"expose"=true}, name="user_add")
     */
    public function add(Request $request, FileUploader $fileUploader, UserPasswordEncoderInterface $passwordEncoder)
    {
        $em = $this->getDoctrine()->getManager();

        $data = json_decode($request->get('data'));
        $file = $request->files->get('file');

        $user = (new User())
            ->setUsername($data->username->value)
            ->setEmail($data->email->value)
            ->setRoles($data->roles->value)
        ;

        $user->setPassword($passwordEncoder->encodePassword(
            $user, 'azerty'
        ));

        if($file){
            $filename = $fileUploader->upload($file, 'avatar/', true);
            $user->setAvatar($filename);
        }

        $em->persist($user); $em->flush();
        return new JsonResponse(['code' => 1, 'highRoleCode' => $user->getHighRoleCode(), 'highRole' => $user->getHighRole(), 'avatar' => $user->getAvatar()]);
    }

    /**
     * @Route("/convert-is-new/utilisateur/{user}", options={"expose"=true}, name="user_convert_is_new")
     */
    public function convertIsNew($user)
    {
        $em = $this->getDoctrine()->getManager();
        $user = $em->getRepository(User::class)->find($user);
        if(!$user){
            return new JsonResponse(['code' => 0, 'message' => '[ERREUR] Cet utilisateur n\'existe pas.']);
        }

        $user->setIsNew(false);
        $em->persist($user); $em->flush();
        return new JsonResponse(['code' => 1]);
    }

    /**
     * @Route("/delete/utilisateur/{user}", options={"expose"=true}, name="user_delete")
     */
    public function delete($user)
    {
        $em = $this->getDoctrine()->getManager();
        $user = $em->getRepository(User::class)->find($user);
        if(!$user){
            return new JsonResponse(['code' => 0, 'message' => '[ERREUR] Cet utilisateur n\'existe pas.']);
        }

        if($user->getHighRoleCode() == User::CODE_ROLE_SUPER_ADMIN){
            return new JsonResponse(['code' => 0, 'message' => '[ERREUR] Cet utilisateur ne peut pas être supprimé.']);
        }

        $em->remove($user); $em->flush();
        return new JsonResponse(['code' => 1]);
    }
}
