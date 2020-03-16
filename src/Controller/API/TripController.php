<?php


namespace App\Controller\API;


use App\Entity\Country;
use App\Entity\Trip;
use App\Entity\User;
use App\Form\TripType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Exception\ResourceNotFoundException;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

class TripController extends BaseController
{
    /**
     * @ParamConverter("user", class="App:User")
     * @param Request $request
     * @param User|null $user
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function list(Request $request, User $user=null){
        if (!$user) {
            return $this->handleView($this->failView(new ResourceNotFoundException('The user was not found')));
        }

        $trips = $this->getDoctrine()->getManager()->getRepository(Trip::class)->findBy(['user'=>$user]);

        return $this->handleView($this->successView([
                $trips,
                sprintf('All Trips for user: %s!', $user->getUsername())
            ]
        ));
    }

    /**
     * @ParamConverter("user", class="App:User")
     * @param Request $request
     * @param User $user
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function create(Request $request, User $user = null){
        try {
            if (!$user) {
                return $this->handleView($this->failView(new ResourceNotFoundException('The user was not found')));
            }
            $trip = new Trip();
            // at this point we don't have to check as the user is found by ParamConverter.
            $request->request->set('user', $user->getId());// inject the user value to the request parameters.
            //find country by several values id, name , country code
            $country = $this->getDoctrine()->getManager()->getRepository(Country::class)->findOneByMultiple($request->request->get('country'));
            if ($country){//if this is not found, the validation will tke of the notification.
                $request->request->set('country', $country->getId());//
            }
            $form = $this->createForm(TripType::class, $trip, ['validation_groups' => ['CreateTrip']]);//Adding validation groups support
            $form->submit($request->request->all());
            if ($form->isSubmitted()) {
                if ($form->isValid()) {
                    $em = $this->getDoctrine()->getManager();
                    $trip = $form->getData();
                    $em->persist($trip);
                    $em->flush();
                }
            }
            if (!$form->isValid()){
                return $this->handleView($this->invalidView([
                    'trip' => $form->getData(),
                    'errors' => $this->getErrorsFromForm($form),
                ],"Invalid Form"));
            }

            return $this->handleView($this->successView([
                'trip' => $form->getData()
            ],'The trip is created!'));
        }catch (\Exception $exception){
            return $this->handleView($this->failView($exception));
        }
    }

    /**
     * @ParamConverter("trip", class="App:Trip")
     * @ParamConverter("user", class="App:User")
     * @param Request $request
     * @param Trip $trip
     * @param User $user
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function show(Request $request, Trip $trip=null, User $user=null){
        try {
            if (!$user) {
                return $this->handleView($this->failView(new ResourceNotFoundException('The user was not found')));
            }
            if (!$trip) {
                return $this->handleView($this->failView(new ResourceNotFoundException('The trip was not found')));
            }
            return $this->handleView($this->successView([
                'trip' => $user
            ], 'The trip is displayed!'));
        }catch (\Exception $exception){
            return $this->handleView($this->failView($exception));
        }
    }

    /**
     * @ParamConverter("trip", class="App:Trip")
     * @ParamConverter("user", class="App:User")
     * @param Request $request
     * @param Trip $trip
     * @param User $user
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function update(Request $request, Trip $trip=null, User $user=null){
        try {
            if (!$user) {
                return $this->handleView($this->failView(new ResourceNotFoundException('The user was not found')));
            }
            if (!$trip) {
                return $this->handleView($this->failView(new ResourceNotFoundException('The trip was not found')));
            }
            if ($trip->getUser() !== $user){
                return $this->handleView($this->failView(new AccessDeniedException('You are not eligible to update this user')));
            }
            //find country by several values id, name , country code
            $country = $this->getDoctrine()->getManager()->getRepository(Country::class)->findOneByMultiple($request->request->get('country'));
            if ($country){//if this is not found, the validation will tke of the notification.
                $request->request->set('country', $country->getId());//
            }
            $form = $this->createForm(TripType::class, $trip, ['validation_groups' => ['UpdateTrip']]);//Adding validation groups support
            $form->remove('user');// remove the user as we are not going to update it.
            $form->submit($request->request->all());
            if ($form->isSubmitted()) {
                if ($form->isValid()) {
                    $em = $this->getDoctrine()->getManager();
                    $trip = $form->getData();
                    $trip->setUser($user);
                    $em->persist($trip);
                    $em->flush();
                }
            }
            if (!$form->isValid()){
                return $this->handleView($this->invalidView([
                    'trip' => $form->getData(),
                    'errors' => $this->getErrorsFromForm($form),
                ],"Invalid Form"));
            }

            return $this->handleView($this->successView([
                'trip' => $form->getData()
            ],'The Trip is updated!'));
        }catch (\Exception $exception){
            return $this->handleView($this->failView($exception));
        }
    }

    /**
     * @ParamConverter("trip", class="App:Trip")
     * @ParamConverter("user", class="App:User")
     * @param Request $request
     * @param Trip $trip
     * @param User $user
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function delete(Request $request, Trip $trip=null, User $user = null){
        try {
            if (!$user) {
                return $this->handleView($this->failView(new ResourceNotFoundException('The user was not found')));
            }
            if (!$trip) {
                return $this->handleView($this->failView(new ResourceNotFoundException('The trip was not found')));
            }
            if ($trip->getUser() !== $user){
                return $this->handleView($this->failView(new AccessDeniedException('You are not eligible to delete this user')));
            }
            $em = $this->getDoctrine()->getManager();
            $em->remove($trip);
            $em->flush();
            return $this->handleView($this->successView([
                'trip' => $user
            ], sprintf('The trip is deleted!')));
        }catch (\Exception $exception){
            return $this->handleView($this->failView($exception));
        }
    }
}