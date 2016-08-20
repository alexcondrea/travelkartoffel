<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class StorageController extends Controller
{
    /**
     * @Route("/kartoffel/api/storage")
     */
    public function indexAction()
    {
        return $this->json($this->getStorage());
    }

    /**
     * @Route("/kartoffel/api/storage/{id}")
     */
    public function detailsAction($id)
    {
        foreach($this->getStorage() as $storage) {
            if($storage['id'] == $id) {
                return $this->json($storage);
            }
        }

        throw $this->createNotFoundException('Element not found');
    }

    /**
     * @return array
     */
    private function getStorage()
    {
        return json_decode(file_get_contents(dirname(__DIR__) . '/Fixtures/pre-defined-routes.json'), true);
    }
}
