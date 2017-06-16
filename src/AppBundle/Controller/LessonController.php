<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

use AppBundle\Entity\Booking;
use AppBundle\Entity\Lesson;

class LessonController extends Controller
{
    /**
     * @Route("/", name="show-lesson")
     */
    public function showAction(Request $request)
    {
		$em = $this->getDoctrine()->getEntityManager();
		$lessonsRepository = $em->getRepository('AppBundle:Lesson');
		$lessons = $lessonsRepository->findAll();

        return $this->render('default/show-lesson.html.twig', array(
            'lessons' => $lessons,
        ));
    }

    /**
     * @Route("/lesson/list", name="list-lesson")
     */
    public function listAction(Request $request)
    {
		$em = $this->getDoctrine()->getEntityManager();
		$lessonsRepository = $em->getRepository('AppBundle:Lesson');
		$lessons = $lessonsRepository->findAll();

        return $this->render('admin/list-lesson.html.twig', array(
            'lessons' => $lessons,
        ));
    }

    /**
     * @Route("/lesson/create", name="create-lesson")
     */
    public function createAction(Request $request)
    {
		$lesson = new Lesson();
		$lesson->setTime(new \DateTime('tomorrow'));

		$form = $this->createFormBuilder($lesson)
            ->add('code', TextType::class)
            ->add('name', TextType::class)
            ->add('mode', TextType::class)
            ->add('session', TextType::class)
            ->add('teacher', TextType::class)
            ->add('time', DateTimeType::class)
            ->add('place', TextType::class)
            ->add('price', MoneyType::class)
            ->add('description', TextareaType::class)
            ->add('status', TextType::class)
            ->add('save', SubmitType::class, array('label' => 'Save lesson'))
            ->getForm();

		$form->handleRequest($request);
	
		if ($form->isSubmitted() && $form->isValid()) {
	 
			// $form->getData() holds the submitted values
			// but, the original `$booking` variable has also been updated
			$lesson = $form->getData();
	 
			// ... perform some action, such as saving the task to the database
			// Booking is a Doctrine entity, save it!
			$em = $this->getDoctrine()->getManager();
			$em->persist($lesson);
			$em->flush();
	 
			return $this->redirectToRoute('create-lesson');
		}
 
        return $this->render('admin/create-lesson.html.twig', array(
            'form' => $form->createView(),
        ));
    }

    /**
     * @Route("/lesson/edit/{lesson_id}", name="edit-lesson")
     */
    public function editAction(Request $request, $lesson_id)
    {
		$em = $this->getDoctrine()->getEntityManager();
		$lessonsRepository = $em->getRepository('AppBundle:Lesson');
		$lesson = $lessonsRepository->find($lesson_id);

		$form = $this->createFormBuilder($lesson)
            ->add('code', TextType::class)
            ->add('name', TextType::class)
            ->add('mode', TextType::class)
            ->add('session', TextType::class)
            ->add('teacher', TextType::class)
            ->add('time', DateTimeType::class)
            ->add('place', TextType::class)
            ->add('price', MoneyType::class)
            ->add('description', TextareaType::class)
            ->add('status', TextType::class)
            ->add('save', SubmitType::class, array('label' => 'Save lesson'))
            ->getForm();

		$form->handleRequest($request);
	
		if ($form->isSubmitted() && $form->isValid()) {
	 
			// $form->getData() holds the submitted values
			// but, the original `$booking` variable has also been updated
			$lesson = $form->getData();
	 
			// ... perform some action, such as saving the task to the database
			// Booking is a Doctrine entity, save it!
			$em = $this->getDoctrine()->getManager();
			$em->persist($lesson);
			$em->flush();
	 
			return $this->redirectToRoute('create-lesson');
		}
 
        return $this->render('admin/create-lesson.html.twig', array(
            'form' => $form->createView(),
        ));
    }
}
