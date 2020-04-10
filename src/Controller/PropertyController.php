<?php


namespace App\Controller;


use App\Entity\Property;
use App\Repository\PropertyRepository;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class PropertyController extends AbstractController
{
    /**
     * @var PropertyRepository
     */
    private $repository;

    /**
     * @var ObjectManager
     */
    private $objectManager;

    /**
     * PropertyController constructor.
     *
     * @param PropertyRepository $repository
     * @param ObjectManager      $objectManager
     */
    public function __construct(PropertyRepository $repository, ObjectManager $objectManager)
    {
        $this->repository = $repository;
        $this->objectManager = $objectManager;
    }

    /**
     * @Route(path="/biens", name="property.index")
     */
    public function index(): Response
    {
        return $this->render('property/index.html.twig', [
            'current_menu' => 'properties'
        ]);
    }

    /**
     * @Route(path="/biens/{slug}-{id}", name="property.show", requirements={"slug": "[a-z0-9\-]*"})
     * @param Property $property
     *
     * @param string   $slug
     *
     * @return Response
     */
    public function show(Property $property, string $slug): Response
    {
        if($property->getSlug() !== $slug)
        {
            return $this->redirectToRoute('property.show', [
                'id' => $property->getId(),
                'slug' => $property->getSlug()
            ], 301);
        }
        return $this->render('property/show.html.twig', [
            'property' => $property,
            'current_menu' => 'properties'
        ]);
    }

}