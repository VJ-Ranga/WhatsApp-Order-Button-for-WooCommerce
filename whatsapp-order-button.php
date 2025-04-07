<?php
/**
 * WhatsApp Order Button for WooCommerce
 * 
 * Adds a WhatsApp ordering button to WooCommerce product pages,
 * allowing customers to place orders directly through WhatsApp.
 */

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Add WhatsApp Order Button to WooCommerce product pages
 */
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
