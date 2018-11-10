<?php declare(strict_types=1);

namespace App\Controller;

use App\Entity\BankTransaction;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ApiController extends AbstractController
{

    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     *
     * @Route("/transaction", name="new_transaction", methods={"POST"})
     */
    public function new(Request $request)
    {
        $data = json_decode($request->getContent());
        if ($data === null) {
            return new JsonResponse(["error" => "Error parsing json: " . json_last_error_msg()], Response::HTTP_BAD_REQUEST);
        }

//        try {
            $bankTransaction = BankTransaction::fromJsonObject($data);

            $this->entityManager->persist($bankTransaction);
            $this->entityManager->flush();

            return new JsonResponse(["uuid" => $bankTransaction->getUuid()], Response::HTTP_CREATED);
//        } catch (\Exception $e) {
//            return new JsonResponse(["error" => "Error creating transaction: " . $e->getMessage()], Response::HTTP_BAD_REQUEST);
//        }
    }
}