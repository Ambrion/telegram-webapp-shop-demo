<?php

namespace App\Tests\Order\Application\Command\CreateOrder;

use App\Order\Application\UseCases\Command\CreateOrder\CreateOrderCommand;
use App\Order\Application\UseCases\Command\CreateOrder\CreateOrderCommandHandler;
use App\Order\Domain\Entity\Order;
use App\Order\Domain\Exception\InvoiceException;
use App\Order\Domain\Factory\OrderFactory;
use App\Order\Domain\Repository\OrderCommandRepositoryInterface;
use PHPUnit\Framework\TestCase;

class CreateOrderCommandHandlerMockTest extends TestCase
{
    protected function setUp(): void
    {
        $this->repository = $this->createMock(OrderCommandRepositoryInterface::class);
        $this->orderFactory = $this->createMock(OrderFactory::class);
        $this->order = $this->createMock(Order::class);

        $this->handler = new CreateOrderCommandHandler(
            $this->repository,
            $this->orderFactory
        );
    }

    public function test_invoke_should_create_and_add_order(): void
    {
        // Arrange
        $command = new CreateOrderCommand(
            '01ARZ3NDEKTSV4RRFFQ69G5FAV', // userUlid
            'USD', // currencyCode
            10000, // totalAmount
            'credit_card', // paymentMethod
            1, // orderStatusId
            [] // products
        );

        $orderId = 1;

        $this->orderFactory
            ->expects($this->once())
            ->method('create')
            ->with(
                $command->userUlid,
                $command->currencyCode,
                $command->totalAmount,
                $command->paymentMethod,
                $command->orderStatusId,
                $command->products
            )
            ->willReturn($this->order);

        $this->repository
            ->expects($this->once())
            ->method('add')
            ->with($this->order)
            ->willReturn($orderId);

        // Act
        $result = ($this->handler)($command);

        // Assert
        $this->assertEquals($orderId, $result);
    }

    public function test_invoke_should_throw_exception_when_products_are_empty(): void
    {
        // Assert
        $this->expectException(InvoiceException::class);
        $this->expectExceptionMessage('Error: the order was not created.');

        // Arrange
        $command = new CreateOrderCommand(
            '01ARZ3NDEKTSV4RRFFQ69G5FAV', // userUlid
            'USD', // currencyCode
            10000, // totalAmount
            'credit_card', // paymentMethod
            1, // orderStatusId
            [] // Empty products
        );

        // Act
        ($this->handler)($command);
    }
}
