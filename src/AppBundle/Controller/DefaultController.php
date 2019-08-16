<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Product;
use League\Csv\Reader;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class DefaultController extends Controller
{
    /**
     * @Route("/", name="homepage")
     */
    public function indexAction(Request $request)
    {
        // replace this example code with whatever you need
        return $this->render('default/index.html.twig', [
            'base_dir' => realpath($this->getParameter('kernel.project_dir')) . DIRECTORY_SEPARATOR,
        ]);
    }

    /**
     * It reads the content of a file using it is absolute path as an argument
     *
     *
     * @param $path
     */
    public function importDataFromFile($path)
    {
        $csv = Reader::createFromPath($path);
        foreach ($csv as $row) {
            if ($this->isRowValid($row)) {
                if ($row[0] !== "Product Code") {
                    $product = new Product(
                        $row[0],
                        $row[1],
                        $row[2],
                        $row[3],
                        (int)$row[4],
                        (float)$row[5]
                    );
                    $productRepo = $this->getDoctrine()->getRepository(Product::class);
                    $productRepo->insertProduct($product);
                }

            }
        }
    }

    /**
     * It checks if the row contains all the data required for an entity Product to be created
     * If some data is missing just returns false so we will not add this product to the DB.
     *
     * @param $row
     *
     * @return bool
     */
    private function isRowValid($row)
    {
        if (sizeof($row) === 6) {
            return true;
        } else {
            return false;
        }
    }
}
