# Acme Widget Co Sales System

## Overview

This system provides a proof of concept for Acme Widget Co's sales system, including product management, delivery charges, and special offers.

## Installation
1. Ensure PHP is installed on your system.
2. Clone the repository.
3. Run `php index.php` to see the example usage.

## Files

1. index.php:
All products and cart items are displayed in `index.php` file. When user hit the 'Add to cart' button 
items are are added to the cart and are stored in the session. When user clicks the 'Clear Session' button we clear and destory the current session so that we can use it again.

2. Basket.php:
`Basket.php` is where we have defined a class `Basket` which we initilize when we run the index.php file which contain different cart manuplation methods.

3. clear_session.php:
This file is used to clear the current session and user is redirected the index.php file.

4. products.php:
We have definded all of our products in `products.php` file. We can increase or decrease the number of products using that file.

5. functions.php

There are two functions. One is used to calculate the red widget offer discount amount and the one is uesed to format the price based on the currency we are using.

6. css:
All css and styling rules are defined in the `styles.css` file.



## Usage

- Initialize the `Basket` class with products, delivery rules, and offers.
- Use the `add` method to add products the `cart`. We are using session to store that `cart`.
- Use the `total` method to calculate the total price, including offers and delivery charges.
- Use the `remove` method to remove the items from the cart.
- Use the `calculatePrice` method to calculate the price of the items added to the cart.
- Use the `calculateDuties` method to calculate the delivery charges based on applied rules
        e.g if 
        itemsPrice <50 ===> delivery charges = 4.95
        itemsPrice <90 ===> delivery charges = 2.95
        itemsPrice >90 ===> delivery charges = 0

## Offers

Currently, the system supports "buy one red widget, get the second half price" for red widgets (R01). We
are using `redWidgetOffer` function to calcualte the red widget offer

# Assumptions

- All prices are in USD.
- Delivery charges are based on the total basket value before applying offers.
