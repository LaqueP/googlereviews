# Google Customer Reviews for PrestaShop

Integrates **Google Customer Reviews** into your PrestaShop store, allowing you to collect product ratings and reviews directly from customers after they complete a purchase.

## Features

- Adds Google Customer Reviews opt-in code to the **order confirmation page**.
- Supports **product GTINs** (EAN13 or UPC).
- Allows setting a **Merchant ID** from the module configuration.
- Compatible with **multistore** (each shop can have its own Merchant ID).

## Requirements

- PrestaShop **1.7.x** or **8.x**.
- Your products should have **GTINs** (EAN13 or UPC) configured.
- A valid **Google Merchant Account** with Customer Reviews enabled.

## Installation

1. Download or clone this repository.
2. Compress the module folder (e.g., `googlecustomerreviews`) into a `.zip` file.
3. Go to your PrestaShop **Back Office**:
   - Navigate to **Modules > Module Manager**.
   - Click **Upload a module** and upload the `.zip` file.
4. Configure the module:
   - Go to **Modules > Module Manager**.
   - Search for **Google Customer Reviews**.
   - Enter your **Google Merchant ID** in the configuration form.

## Multistore Support

- This module supports **multistore**.
- Each store can have its **own Merchant ID**.
- To configure per store:
  - Switch to the desired store context in **Shop Parameters > General > Multistore**.
  - Go to **Modules > Module Manager**, configure **Google Customer Reviews**, and set the specific **Merchant ID** for that store.

## Configuration

- **Merchant ID:** Enter the ID provided by Google for your Merchant Account.
- **Estimated Delivery Time:** Currently set to **15 days** after order placement. You can adjust this in the PHP code if needed.

## How it works

- On the **order confirmation page**, the module injects Google’s **survey opt-in** JavaScript snippet with the required fields:
  - **merchant_id**
  - **order_id**
  - **customer email**
  - **delivery country**
  - **estimated delivery date**
  - **products** (GTINs)

Google then sends an email to the customer requesting a review after the delivery date.

## Customization

If your delivery times differ or you want to customize the module further:

- Edit the **estimated delivery date** logic in:
  
  ```php
  modules/googlecustomerreviews/googlecustomerreviews.php

  Locate:

php

$estimated_delivery_date = date('Y-m-d', strtotime('+15 days'));
Adjust the number of days as needed.

License
This module is released under the Open Software License (OSL 3.0). See LICENSE.txt for details.

Disclaimer
This module is provided as-is. It is your responsibility to ensure compliance with Google's policies and guidelines for Google Customer Reviews.
