<?php

namespace App\Controller;

use App\Entity\Order;
use App\Form\OrderType;
use App\Repository\OrderRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/order')]
class OrderController extends AbstractController
{
    private $entityManager;
    private $orderRepository;

    public function __construct(EntityManagerInterface $entityManager, OrderRepository $orderRepository)
    {
        $this->entityManager = $entityManager;
        $this->orderRepository = $orderRepository;
    }

    #[Route('/', name: 'order_index', methods: ['GET'])]
    public function index(): Response
    {
        return $this->render('order/index.html.twig', [
            'orders' => $this->orderRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'order_new', methods: ['GET', 'POST'])]
    public function new(Request $request): Response
    {
        $order = new Order();
        $form = $this->createForm(OrderType::class, $order);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->entityManager->persist($order);
            $this->entityManager->flush();

            return $this->redirectToRoute('order_index');
        }

        return $this->render('order/new.html.twig', [
            'order' => $order,
            'form' => $form->createView(),
        ]);
    }

    #[Route('/{id}', name: 'order_show', methods: ['GET'])]
    public function show(Order $order): Response
    {
        return $this->render('order/show.html.twig', [
            'order' => $order,
        ]);
    }

    #[Route('/{id}/edit', name: 'order_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Order $order): Response
    {
        $form = $this->createForm(OrderType::class, $order);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->entityManager->flush();

            return $this->redirectToRoute('order_index');
        }

        return $this->render('order/edit.html.twig', [
            'order' => $order,
            'form' => $form->createView(),
        ]);
    }

    #[Route('/{id}', name: 'order_delete', methods: ['POST'])]
    public function deleteOrder(Request $request, Order $order): Response
    {
        if ($this->isCsrfTokenValid('delete'.$order->getId(), $request->request->get('_token'))) {
            $this->entityManager->remove($order);
            $this->entityManager->flush();
        }

        return $this->redirectToRoute('order_index');
    }

    #[Route('/create', name: 'order_create', methods: ['POST'])]
    public function create(Request $request): Response
    {
        $order = new Order();
        $order->setOrderNumber($request->get('order_number'));
        $order->setDate(new \DateTime($request->get('date')));
        $order->setTotalAmount($request->get('total_amount'));

        $this->entityManager->persist($order);
        $this->entityManager->flush();

        return new Response('Order created successfully');
    }

    #[Route('/read/{id}', name: 'order_read', methods: ['GET'])]
    public function read(int $id): Response
    {
        $order = $this->orderRepository->find($id);

        if (!$order) {
            throw $this->createNotFoundException('No order found for id ' . $id);
        }

        return $this->json($order);
    }

    #[Route('/update/{id}', name: 'order_update', methods: ['PUT'])]
    public function update(Request $request, int $id): Response
    {
        $order = $this->orderRepository->find($id);

        if (!$order) {
            throw $this->createNotFoundException('No order found for id ' . $id);
        }

        $order->setOrderNumber($request->get('order_number'));
        $order->setDate(new \DateTime($request->get('date')));
        $order->setTotalAmount($request->get('total_amount'));

        $this->entityManager->flush();

        return new Response('Order updated successfully');
    }

    #[Route('/delete/{id}', name: 'order_delete', methods: ['DELETE'])]
    public function delete(int $id): Response
    {
        $order = $this->orderRepository->find($id);

        if (!$order) {
            throw $this->createNotFoundException('No order found for id ' . $id);
        }

        $this->entityManager->remove($order);
        $this->entityManager->flush();

        return new Response('Order deleted successfully');
    }

    #[Route('/products', name: 'products_index', methods: ['GET'])]
    public function products(): Response
    {
        $products = [
            ['id' => 1, 'name' => 'Product 1', 'price' => 100],
            ['id' => 2, 'name' => 'Product 2', 'price' => 200],
        ];

        return $this->json($products);
    }

    #[Route('/shop', name: 'shop_index', methods: ['GET'])]
    public function shop(): Response
    {
        return $this->render('shop/index.html.twig');
    }
}
