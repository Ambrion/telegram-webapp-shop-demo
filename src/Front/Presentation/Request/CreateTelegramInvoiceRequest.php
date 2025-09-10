<?php

declare(strict_types=1);

namespace App\Front\Presentation\Request;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Constraints as Assert;

class CreateTelegramInvoiceRequest
{
    /**
     * @var array<string|mixed>
     */
    #[Assert\NotBlank]
    #[Assert\Type('array')]
    private array $cart;

    /**
     * @var array<string|mixed>
     */
    #[Assert\NotBlank]
    #[Assert\Type('array')]
    private array $initData;

    /**
     * @var array<string|mixed>
     */
    #[Assert\NotBlank]
    #[Assert\Type('array')]
    private array $user;

    public function __construct(Request $request)
    {
        $data = json_decode($request->getContent(), true);

        if (empty($data['cart'])) {
            throw new \InvalidArgumentException('Cart cannot be empty');
        }

        if (empty($data['initData'])) {
            throw new \InvalidArgumentException('initData cannot be empty');
        }

        $this->cart = $data['cart'];

        parse_str($data['initData'], $params);

        $this->initData = $params;

        $userData = json_decode($this->initData['user'], true);

        if (empty($userData['id'])) {
            throw new \InvalidArgumentException('User telegram ID cannot be empty');
        }

        $this->user = $userData;
    }

    /**
     * @return array<string|mixed>
     */
    public function getCart(): array
    {
        return $this->cart;
    }

    /**
     * @return array<string|mixed>
     */
    public function getInitData(): array
    {
        return $this->initData;
    }

    /**
     * @return array<string|mixed>
     */
    public function getUser(): array
    {
        return $this->user;
    }
}
