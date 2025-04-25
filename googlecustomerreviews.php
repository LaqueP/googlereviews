<?php
/**
 * 2007-2023 PrestaShop and Contributors
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Open Software License (OSL 3.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * https://opensource.org/licenses/OSL-3.0
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@prestashop.com so we can send you a copy immediately.
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade PrestaShop to newer
 * versions in the future. If you wish to customize PrestaShop for your
 * needs please refer to https://devdocs.prestashop.com/ for more information.
 */
if (!defined('_PS_VERSION_')) {
    exit;
}

class GoogleCustomerReviews extends Module
{
    public function __construct()
    {
        $this->name = 'googlecustomerreviews';
        $this->tab = 'front_office_features';
        $this->version = '1.0.0';
        $this->author = 'LaqueP';
        $this->need_instance = 0;
        $this->ps_versions_compliancy = ['min' => '1.7', 'max' => _PS_VERSION_];
        $this->bootstrap = true;

        parent::__construct();

        $this->displayName = $this->l('Google Customer Reviews');
        $this->description = $this->l('Integration of Google Customer Reviews in the order confirmation page. Version multitienda.');
    }

    public function install()
    {
        return parent::install() && $this->registerHook('displayOrderConfirmation');
    }

    public function uninstall()
    {
        return parent::uninstall();
    }

    public function hookDisplayOrderConfirmation($params)
    {
        if (!isset($params['order'])) {
            return;
        }

        $order = $params['order'];
        $customer = new Customer($order->id_customer);

        // Get necessary information
        $merchant_id = Configuration::get('GOOGLE_CUSTOMER_REVIEWS_MERCHANT_ID', null, null, $this->context->shop->id);
        if (!$merchant_id) {
            return; // Si no hay Merchant ID para la tienda actual, no hacemos nada
        }
        $order_id = $order->reference;
        $email = $customer->email;
        $delivery_address = new Address($order->id_address_delivery);
        $delivery_country = Country::getIsoById($delivery_address->id_country);
        $estimated_delivery_date = date('Y-m-d', strtotime('+15 days')); // Adjust according to your delivery times

        // Preparar productos con GTIN (si aplica)
        $products = [];
        foreach ($order->getProducts() as $product) {
            $product_gtin = $product['ean13'] ?? $product['upc'] ?? '';
            if (!empty($product_gtin)) {
                $products[] = ['gtin' => $product_gtin];
            }
        }

        // Assign variables to Smarty
        $this->context->smarty->assign([
            'merchant_id' => (int) $merchant_id,
            'order_id' => addslashes($order_id),
            'email' => addslashes($email),
            'delivery_country' => addslashes($delivery_country),
            'estimated_delivery_date' => addslashes($estimated_delivery_date),
            'products' => json_encode($products),
        ]);

        // Return the template
        return $this->display(__FILE__, 'views/templates/hook/google_customer_reviews.tpl');
    }

    public function getContent()
    {
        $output = '';
        if (Tools::isSubmit('submit' . $this->name)) {
            $merchant_id = Tools::getValue('GOOGLE_CUSTOMER_REVIEWS_MERCHANT_ID');
            if (!$merchant_id || empty($merchant_id)) {
                $output .= $this->displayError($this->l('Merchant ID is required.'));
            } else {
                Configuration::updateValue(
                    'GOOGLE_CUSTOMER_REVIEWS_MERCHANT_ID',
                    $merchant_id,
                    false,
                    null,
                    $this->context->shop->id
                );
                $output .= $this->displayConfirmation($this->l('Settings updated'));
            }
        }

        return $output . $this->renderForm();
    }

    public function renderForm()
    {
        $fields_form = [
            'form' => [
                'legend' => [
                    'title' => $this->l('Settings'),
                ],
                'input' => [
                    [
                        'type' => 'text',
                        'label' => $this->l('Merchant ID'),
                        'name' => 'GOOGLE_CUSTOMER_REVIEWS_MERCHANT_ID',
                        'size' => 20,
                        'required' => true,
                    ],
                ],
                'submit' => [
                    'title' => $this->l('Save'),
                ],
                // Añadir soporte multitienda
                'multistore_configuration' => true,
            ],
        ];

        $helper = new HelperForm();
        $helper->submit_action = 'submit' . $this->name;
        $helper->currentIndex = $this->context->link->getAdminLink('AdminModules', false)
            . '&configure=' . $this->name
            . '&tab_module=' . $this->tab
            . '&module_name=' . $this->name;
        $helper->token = Tools::getAdminTokenLite('AdminModules');

        $helper->show_toolbar = false;

        $helper->fields_value['GOOGLE_CUSTOMER_REVIEWS_MERCHANT_ID'] = Configuration::get(
            'GOOGLE_CUSTOMER_REVIEWS_MERCHANT_ID',
            null,
            null,
            $this->context->shop->id
        );

        return $helper->generateForm([$fields_form]);
    }
}
