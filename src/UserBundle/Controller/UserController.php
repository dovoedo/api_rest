<?php

namespace UserBundle\Controller;

use FOS\RestBundle\Controller\FOSRestController;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use FOS\RestBundle\Controller\Annotations as Rest;
use UserBundle\Entity\User;

class UserController extends FOSRestController
{
//    public function indexAllAction($name)
//    {
//        return $this->render('', array('name' => $name));
//    }

    /**
     * @Rest\View()
     * @Rest\Post("create/user", name="create_user")
     */
    public function newAction(Request $request)
    {

        try {
            if (!is_null($request->get('prenom') && !is_null($request->get('nom')))
                && $request->get('nom') != '' && $request->get('prenom') != '') {
                $em = $this->getDoctrine()->getManager();
                $user = new User();
                $user->setFirstname($request->get('prenom'));
                $user->setLastname($request->get('nom'));
                $user->setCreationdate(new \DateTime());
                $user->setUpdatedate(new \DateTime());
                $em->persist($user);
                $em->flush();
                $response = [
                    "code" => 200,
                    "message" => 'Enregistrement effectué avec succès'
                ];
            } else {
                $response = [
                    "code" => -200,
                    "message" => 'Enregistrement échoué.' . ' ' . 'Veuillez vérifier les champs'
                ];
            }
            return $response;
        } catch (\Exception $ex) {
            return ('L\'erreur suivante s\'est produite :' . ' ' . $ex->getCode() . ' ' . $ex->getMessage());
        }
    }

    /**
     * @Rest\View(statusCode=200)
     * @Rest\Get("get/users", name="index_user")
     */
    public function indexAction(Request $request)
    {
        try {
            $em = $this->getDoctrine()->getManager();
            $users = $em->getRepository(User::class)->findAll();
            return $users;
        } catch (\Exception $ex) {
            return ('L\'erreur suivante s\'est produite lors de la récupération:' . ' ' . $ex->getCode() . ' ' . $ex->getMessage());
        }
    }

    /**
     * @Rest\View(statusCode=200)
     * @Rest\Get("get/user/{id}", name="get_user")
     */
    public function getUserAction(Request $request)
    {
        try {
            if (!is_null($request->get('id'))) {
                $em = $this->getDoctrine()->getManager();
                $user = $em->getRepository(User::class)->findOneBy(array('id' => $request->get('id')));
                if (!is_null($user)) {
                    return $user;
                } else {
                    $response = [
                        "code" => -200,
                        "message" => 'L\'element recherché est introuvable'
                    ];
                    return $response;
                }
            } else {
                $response = [
                    "code" => -200,
                    "message" => 'il manque le paramètre id de l\'utilisateur'
                ];
                return $response;
            }
        } catch (\Exception $ex) {
            return ('L\'erreur suivante s\'est produite lors de la récupération :' . ' ' . $ex->getCode() . ' ' . $ex->getMessage());
        }
    }

    /**
     * @Rest\View(statusCode=200)
     * @Rest\Put("put/user/{id}", name="put_user")
     */
    public function putUserAction(Request $request)
    {
        try {
            if (!is_null($request->get('id'))) {
                $em = $this->getDoctrine()->getManager();
                $user = $em->getRepository(User::class)->findOneBy(array('id' => $request->get('id')));
                if (!is_null($user)) {
                    if (!is_null($request->get('prenom') && !is_null($request->get('nom')))
                        && $request->get('nom') != '' && $request->get('prenom') != '') {
                        $user->setFirstname($request->get('prenom'));
                        $user->setLastname($request->get('nom'));
                        $user->setUpdatedate(new \DateTime());
                        $em->persist($user);
                        $em->flush();
                        $response = [
                            "code" => 200,
                            "message" => 'Mise à jour effectuée avec succès'
                        ];
                    } else {
                        $response = [
                            "code" => -200,
                            "message" => 'Les champs sont vides ou nuls.'
                        ];
                    }
                } else {
                    $response = [
                        "code" => -200,
                        "message" => 'L\'element à modifier est introuvable'
                    ];
                }
                return $response;
            } else {
                return 'il manque le paramètre id de l\'utilisateur';
            }
        } catch (\Exception $ex) {
            return ('L\'erreur suivante s\'est produite lors de la mise à jour :' . ' ' . $ex->getCode() . ' ' . $ex->getMessage());
        }
    }

    /**
     * @Rest\View(statusCode=200)
     * @Rest\Delete("delete/user/{id}", name="delete_user")
     */
    public function deleteUserAction(Request $request)
    {
        try {
            if (!is_null($request->get('id'))) {
                $em = $this->getDoctrine()->getManager();
                $user = $em->getRepository(User::class)->findOneBy(array('id' => $request->get('id')));
                if (!is_null($user)) {
                    $em->persist($user);
                    $em->remove($user);
                    $em->flush();
                    $response = [
                        "code" => 200,
                        "message" => 'Suppression effectuée avec succès'
                    ];
                } else {
                    $response = [
                        "code" => -200,
                        "message" => 'L\'element à supprimer est introuvable'
                    ];
                }
            } else {
                $response = [
                    "code" => -200,
                    "message" => 'il manque le paramètre id de l\'utilisateur'
                ];
            }
            return $response;
        } catch (\Exception $ex) {
            return ('L\'erreur suivante s\'est produite lors de la suppression:' . ' ' . $ex->getCode() . ' ' . $ex->getMessage());
        }
    }
}
