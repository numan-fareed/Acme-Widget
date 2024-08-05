<?php

class Basket
{
    private $products = []; // Array to store all available products
    private $cart = []; // Array to store the products added to the cart

    // Constructor to initialize the Basket with products and optionally a cart from the session
    public function __construct($products)
    {
        $this->products = $products; // Initialize the products array
        if (isset($_SESSION['cart'])) {
            $this->cart = $_SESSION['cart']; // If cart exists in session, initialize it
        }
    }

    // Method to add a product to the cart
    public function add($prcode)
    {
        if (isset($this->products[$prcode])) { // Check if the product code exists in the products array
            if (isset($this->cart[$prcode])) {
                $this->cart[$prcode]['quantity']++; // If the product is already in the cart, increment the quantity
            } else {
                // If the product is not in the cart, add it with quantity 1
                $this->cart[$prcode] = [
                    'name' => $this->products[$prcode]['name'],
                    'price' => $this->products[$prcode]['price'],
                    'quantity' => 1
                ];
            }
            $_SESSION['cart'] = $this->cart; // Update the session with the modified cart
        } else {
            throw new Exception("Product code $prcode not found."); // Throw an exception if the product code is not found
        }
    }

    // Method to remove a product from the cart
    public function remove($prcode)
    {
        if (isset($this->cart[$prcode]) && $this->cart[$prcode]['quantity'] > 1) {
            $this->cart[$prcode]['quantity']--; // If the product quantity is greater than 1, decrement the quantity
            $_SESSION['cart'] = $this->cart; // Update the session with the modified cart
        } else {
            unset($this->cart[$prcode]); // If the product quantity is 1, remove the product from the cart
            $_SESSION['cart'] = $this->cart; // Update the session with the modified cart
        }
    }

    // Method to get the current state of the cart
    public function getCart()
    {
        return $this->cart;
    }

    // Method to calculate the total price of items in the cart
    public function calculatePrice()
    {
        $total = 0;
        foreach ($this->cart as $item) {
            $total += $item['price'] * $item['quantity']; // Sum the total price of each item in the cart
        }
        return $total;
    }

    // Method to calculate the delivery charges based on the total price of items in the cart
    public function calculateDuties()
    {
        $duties = 0;
        $total = $this->calculatePrice(); // Calculate the total price of items in the cart
        if (count($this->cart) > 0) { // If the cart is not empty, calculate duties
            if ($total < 50) {
                $duties = 4.95; // Delivery charge is 4.95 if total price is less than 50
            } elseif ($total < 90) {
                $duties = 2.95; // Delivery charge is 2.95 if total price is less than 90
            }
        }
        return $duties; // Return the calculated duties
    }
}
