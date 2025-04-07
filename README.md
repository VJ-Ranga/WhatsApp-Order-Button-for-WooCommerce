# WhatsApp Order Button for WooCommerce

A simple code snippet that adds a WhatsApp ordering button to WooCommerce product pages, allowing customers to place orders directly through WhatsApp.

## Description

This code snippet adds a prominently displayed "Order via WhatsApp" button on all WooCommerce product pages. When customers click the button, it opens WhatsApp with a pre-filled message containing:

- Product name
- Product price
- Selected variant details (if applicable)
- Product URL

Perfect for businesses that prefer to handle orders through WhatsApp rather than the standard WooCommerce checkout process.

## Features

- Clean, visually appealing WhatsApp button with icon
- Automatically captures product details including name, price, and URL
- Captures selected variations when customers choose product options
- Mobile and desktop compatible
- Lightweight with no dependencies

## Installation

1. Install and activate a code snippets plugin like "Code Snippets" from the WordPress repository.
2. Create a new snippet and paste the provided code.
3. Edit the WhatsApp number in the code to your business WhatsApp number.
4. Save and activate the snippet.

## The Code

```php
function add_whatsapp_button_to_product_page() {
    if (!is_product()) return; // Ensure it runs only on the WooCommerce single product page
    
    global $product;
    if (!$product) return;
    $product_name   = esc_js($product->get_name());
    $currency_symbol = get_woocommerce_currency_symbol();
    $product_url    = esc_url(get_permalink($product->get_id()));
    $whatsapp_number = "1234567890"; // Demo number - Replace with your WhatsApp number
    
    echo '<a href="#" id="whatsapp-order-btn" class="whatsapp-button" style="display: flex; align-items: center; justify-content: center; gap: 8px; margin-top: 15px; padding: 10px 15px; background-color: #25D366; color: #fff; font-size: 16px; text-align: center; border-radius: 5px; text-decoration: none;">
            <img src="https://upload.wikimedia.org/wikipedia/commons/6/6b/WhatsApp.svg" alt="WhatsApp" style="width: 20px; height: 20px;"> Order via WhatsApp
          </a>';
    ?>
    <script>
        document.addEventListener("DOMContentLoaded", function () {
            var whatsappButton = document.getElementById("whatsapp-order-btn");
            if (!whatsappButton) return;
            whatsappButton.addEventListener("click", function (event) {
                event.preventDefault();
                var selectedVariant = "No variant selected";
                var selectedPrice = "<?php echo esc_js($currency_symbol . number_format((float) $product->get_price(), 2, '.', ',')); ?>";
                var variationId = document.querySelector('input.variation_id');
                
                if (variationId && variationId.value) {
                    selectedVariant = "";
                    document.querySelectorAll(".variations_form select").forEach(select => {
                        if (select.value) {
                            var attributeName = select.name.replace("attribute_", "").replace(/-/g, " ");
                            selectedVariant += attributeName.charAt(0).toUpperCase() + attributeName.slice(1) + ": " + select.options[select.selectedIndex].text + "\n";
                        }
                    });
                    var variationPrice = document.querySelector('.woocommerce-variation-price .price .amount');
                    if (variationPrice) {
                        selectedPrice = variationPrice.textContent.trim();
                    }
                }
                var whatsappMessage = "🛒 *Product:* <?php echo esc_js($product_name); ?>\n💰 *Price:* " + selectedPrice + "\n📌 *Selected Variant:*\n" + selectedVariant + "🔗 *Link:* <?php echo esc_url($product_url); ?>";
                window.open("https://api.whatsapp.com/send?phone=<?php echo esc_js($whatsapp_number); ?>&text=" + encodeURIComponent(whatsappMessage), "_blank");
            });
        });
    </script>
    <?php
}
add_action('woocommerce_single_product_summary', 'add_whatsapp_button_to_product_page', 30);
```

## Configuration

Modify the following line with your WhatsApp number:

```php
$whatsapp_number = "1234567890"; // Demo number - Replace with your WhatsApp number
```

Be sure to include the country code without the '+' symbol.

## Customization

You can customize the button's appearance by modifying the CSS styles in the code:

- Change button colors by editing the `background-color` property
- Adjust button size and spacing through the `padding` and `margin` properties
- Modify text size using the `font-size` property
- Change button shape with the `border-radius` property

## Usage

Once the snippet is activated, the button will automatically appear on all WooCommerce product pages. No additional configuration is needed.

## How It Works

The snippet adds a WhatsApp button to the product summary area on WooCommerce product pages. When clicked, the button:

1. Collects the current product information
2. Captures any selected variations if applicable
3. Formats a message with all the details
4. Opens WhatsApp with this pre-filled message

## Compatibility

- WordPress 6.7+
- WooCommerce 9.7+
- Works with variable products and simple products

## Credits

This snippet uses the WhatsApp logo from Wikimedia Commons for the button icon.
