<?php


namespace App\Controller\Admin;


use App\Entity\Option;
use App\Entity\Property;
use App\Form\PropertyType;
use App\Repository\PropertyRepository;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use function MongoDB\BSON\toJSON;

class AdminPropertyController extends AbstractController
{

    /**
     * @var PropertyRepository
     */
    private $propertyRepository;

    /**
     * @var ObjectManager
     */
    private $objectManager;

    public function __construct(PropertyRepository $propertyRepository, ObjectManager $objectManager)
   {
       $this->propertyRepository = $propertyRepository;
       $this->objectManager = $objectManager;
   }

    /**
     * @Route(path="/admin", name="admin.property.index")
     * @return Response
     */
    public function index(): Response
    {
        $properties = $this->propertyRepository->findAll();
        return $this->render('admin/property/index.html.twig', [
            'properties' => $properties
        ]);
   }

    /**
     * @Route(path="/admin/property/create", name="admin.property.create")
     * @param Request $request
     *
     *
     * @return RedirectResponse|Response
     */
    public function create(Request $request)
    {
        $property = new Property();
        $form = $this->createForm(PropertyType::class, $property);

        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid())
        {
            $this->objectManager->persist($property);
            $this->objectManager->flush();
            $this->addFlash('success', 'Bien créé avec succès !');
            return $this->redirectToRoute('admin.property.index');
        }

        return $this->render('admin/property/create.html.twig', [
            'property' => $property,
            'form' => $form->createView(),
        ]);
    }


    /**
     * @Route(path="/admin/property/{id}", name="admin.property.edit", methods="GET|POST")
     * @param Property $property
     *
     * @param Request  $request
     *
     * @return Response
     */
    public function edit(Property $property, Request $request)
    {
        $form = $this->createForm(PropertyType::class, $property);

        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid())
        {
            $this->objectManager->flush();
            $this->addFlash('success', 'Bien modifié avec succès !');
            return $this->redirectToRoute('admin.property.index');
        }

        return $this->render('admin/property/edit.html.twig', [
            'property' => $property,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route(path="/admin/property/{id}", name="admin.property.delete", methods="DELETE")
     * @param Property $property
     *
     * @param Request  $request
     *
     * @return RedirectResponse
     */
    public function delete(Property $property, Request $request)
    {
        if($this->isCsrfTokenValid('delete' . $property->getId(), $request->get('_token')))
        {
            $this->objectManager->remove($property);
            $this->objectManager->flush();
            $this->addFlash('success', 'Bien supprimé avec succès !');
        }
        return $this->redirectToRoute('admin.property.index');
    }
}