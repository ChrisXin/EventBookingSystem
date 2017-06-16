<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;

use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

use AppBundle\Entity\Booking;
use AppBundle\Entity\Lesson;

class BookController extends Controller
{
    /**
     * @Route("/book/{lesson_id}", name="book-lesson")
     */
    public function bookAction(Request $request, $lesson_id)
    {
		$booking = new Booking();
		$booking->setPaidStatus(false);
		
		$lesson = $this->getDoctrine()->getRepository('AppBundle:Lesson')->find($lesson_id);
		if (empty($lesson)) {
			return new Response('Lesson ID is not vaild.');	
		}
		else {
			$booking->setLesson($lesson);
		}

		$form = $this->createFormBuilder($booking)
            ->add('firstName', TextType::class)
            ->add('lastName', TextType::class)
            ->add('phoneNumber', TextType::class)
            ->add('wechat', TextType::class)
            ->add('email', EmailType::class)
            ->add('save', SubmitType::class, array('label' => 'Book this lesson!'))
            ->getForm();

		$form->handleRequest($request);
	
		if ($form->isSubmitted() && $form->isValid()) {
	 
			// $form->getData() holds the submitted values
			// but, the original `$booking` variable has also been updated
			$booking = $form->getData();
	 
			// ... perform some action, such as saving the task to the database
			// Booking is a Doctrine entity, save it!
			$em = $this->getDoctrine()->getManager();
			$em->persist($booking);
			$em->flush();
	 
			return $this->redirectToRoute('show-lesson');
		}
 
        return $this->render('default/book-lesson.html.twig', array(
            'form' => $form->createView(),
        ));
    }

    /**
     * @Route("/subscribe/list", name="subscribe-list")
     */
    public function subscribeListAction(Request $request)
    {
		$em = $this->getDoctrine()->getEntityManager();
		$bookingRepository = $em->getRepository('AppBundle:Booking');
		$subscribes = $bookingRepository->findAll();

        return $this->render('admin/subscribe-list.html.twig', array(
            'subscribes' => $subscribes,
        ));
	}
	
    /**
     * @Route("/subscribe/{sub_id}/pay/{paid_status}")
     */
    public function subscribePayAction(Request $request, $sub_id, $paid_status)
    {
		$em = $this->getDoctrine()->getEntityManager();
		$bookingRepository = $em->getRepository('AppBundle:Booking');
		$subscribe = $bookingRepository->find($sub_id);

		if (!$subscribe) {
			throw $this->createNotFoundException('No subscribe found for id '.$id);
		}
		$subscribe->setPaidStatus($paid_status);
		$em->flush();

		return $this->redirectToRoute('subscribe-list');
	}
}
