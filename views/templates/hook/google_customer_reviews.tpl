{*
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
   *}
    <!DOCTYPE HTML>
    <script src="https://apis.google.com/js/platform.js?onload=renderOptIn" async defer></script>
    
    <script>
      window.renderOptIn = function() {
        window.gapi.load('surveyoptin', function() {
          window.gapi.surveyoptin.render(
            {
              // REQUIRED FIELDS
              "merchant_id": {$merchant_id|intval},
              "order_id": "{$order_id|escape:'javascript':'UTF-8'}",
              "email": "{$email|escape:'javascript':'UTF-8'}",
              "delivery_country": "{$delivery_country|escape:'javascript':'UTF-8'}",
              "estimated_delivery_date": "{$estimated_delivery_date|escape:'javascript':'UTF-8'}",
    
              // OPTIONAL FIELDS
              {if $products}
              "products": {$products nofilter}
              {/if}
            });
        });
      }
    </script>
