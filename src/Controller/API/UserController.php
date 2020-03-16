<?php


namespace App\Controller\API;

use App\Entity\User;
use App\Form\UserType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\HttpFoundation\Request;

class UserController extends BaseController
{
    /**
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function list(){
        $users = $this->getDoctrine()->getManager()->getRepository(User::class)->findAll();

        return $this->handleView($this->successView([
            $users,
            "All Users!"
            ]
        ));
    }

    /**
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function create(Request $request){
        try {
            $user = new User();
            $form = $this->createForm(UserType::class, $user);
            $form->submit($request->request->all());
            if ($form->isSubmitted()) {
                if ($form->isValid()) {
                    $em = $this->getDoctrine()->getManager();
                    $user = $form->getData();
                    $em->persist($user);

                    $em->flush();
                }
            }
            if (!$form->isValid()){
                return $this->handleView($this->invalidView([
                    'user' => $form->getData(),
                    'errors' => $this->getErrorsFromForm($form),
                ],"Invalid Form"));
            }

            return $this->handleView($this->successView([
                'user' => $form->getData()
            ],'The User is created!'));
        }catch (\Exception $exception){
            return $this->handleView($this->failView($exception));
        }
    }

    /**
     * @ParamConverter("user", class="App:User")
     * @param Request $request
     * @param User $user
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function show(Request $request, User $user){
        try {
            return $this->handleView($this->successView([
                'user' => $user
            ], sprintf('The User %s is updated!',$user->getUserName())));
        }catch (\Exception $exception){
            return $this->handleView($this->failView($exception));
        }
    }

    /**
     * @ParamConverter("user", class="App:User")
     * @param Request $request
     * @param User $user
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function update(Request $request, User $user){
        try {
            $form = $this->createForm(UserType::class, $user);
            $form->submit($request->request->all());
            if ($form->isSubmitted()) {
                if ($form->isValid()) {
                    $em = $this->getDoctrine()->getManager();
                    $user = $form->getData();
                    $em->persist($user);

                    $em->flush();
                }
            }
            if (!$form->isValid()){
                return $this->handleView($this->invalidView([
                    'user' => $form->getData(),
                    'errors' => $this->getErrorsFromForm($form),
                ],'Invalid Form'));
            }

            return $this->handleView($this->successView([
                'user' => $form->getData()
            ], sprintf('The User %s is updated!',$user->getUserName())));
        }catch (\Exception $exception){
            return $this->handleView($this->failView($exception));
        }
    }

    /**
     * @ParamConverter("user", class="App:User")
     * @param Request $request
     * @param User $user
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function delete(Request $request, User $user){
        try {
            $em = $this->getDoctrine()->getManager();
            $em->remove($user);
            $em->flush();
            return $this->handleView($this->successView([
                'user' => $user
            ], sprintf('The User %s is deleted!',$user->getUserName())));
        }catch (\Exception $exception){
            return $this->handleView($this->failView($exception));
        }
    }
}