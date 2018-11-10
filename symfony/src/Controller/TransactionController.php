<?php declare(strict_types=1);

namespace App\Controller;

use App\Entity\BankTransaction;
use App\Repository\BankTransactionRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class TransactionController extends AbstractController
{
    /**
     * @var BankTransactionRepository
     */
    private $bankTransactionRepository;

    public function __construct(BankTransactionRepository $bankTransactionRepository)
    {
        $this->bankTransactionRepository = $bankTransactionRepository;
    }

    /**
     *
     * @Route("/transaction", name="new_transaction", methods={"POST"})
     */
    public function newTransaction(Request $request)
    {
        $data = json_decode($request->getContent());
        if ($data === null) {
            return new JsonResponse(["error" => "Error parsing json: " . json_last_error_msg()], Response::HTTP_BAD_REQUEST);
        }

        try {
            $bankTransaction = BankTransaction::fromJsonObject($data);

            $this->bankTransactionRepository->persist($bankTransaction);
            $this->bankTransactionRepository->flush();

            return new JsonResponse(["uuid" => $bankTransaction->getUuid()], Response::HTTP_CREATED);
        } catch (\Exception $e) {
            return new JsonResponse(["error" => "Error creating transaction: " . $e->getMessage()], Response::HTTP_BAD_REQUEST);
        }
    }

    /**
     *
     * @Route("/transaction/{uuid}", name="get_transaction", methods={"GET"})
     */
    public function getTransaction(string $uuid)
    {
       $transactions = $this->bankTransactionRepository->findBy(["uuid" => $uuid]);

       if (count($transactions) === 0 || $transactions[0] === null) {
           return new JsonResponse(["error" => "No such transaction found"], Response::HTTP_NOT_FOUND);
       }

       return new JsonResponse($transactions[0], Response::HTTP_OK);
    }
}