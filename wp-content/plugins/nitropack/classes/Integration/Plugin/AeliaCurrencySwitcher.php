<?php

namespace NitroPack\Integration\Plugin;

class AeliaCurrencySwitcher {
    public const STAGE = "very_early";
    public const customVariationCookies = ['aelia_cs_selected_currency', 'aelia_customer_country'];

    public static function isActive() {
        return class_exists("\Aelia\WC\CurrencySwitcher\WC_Aelia_CurrencySwitcher");
    }

    public function init($stage) {
        $siteConfig = get_nitropack()->getSiteConfig();

        if (empty($siteConfig["isAeliaCurrencySwitcherActive"])) {
            return true; // ACS is not active
        }

        // use CloudFlare geolocation if available
        if (isset($_SERVER["HTTP_CF_IPCOUNTRY"])) {
            add_action('set_nitropack_geo_cache_prefix', function() {
                \NitroPack\SDK\NitroPack::addCustomCachePrefix($_SERVER["HTTP_CF_IPCOUNTRY"]);
            });
            return true;
        }

        add_filter("nitropack_passes_cookie_requirements", [$this, "canServeCache"]);
    }

    public static function configureVariationCookies() {
        $siteConfig = get_nitropack()->getSiteConfig();

        if (empty($siteConfig["isAeliaCurrencySwitcherActive"])) {
            self::resetVariationCookies();
            return true;
        }

        // Check if Woocommerce Geolocation is configured to handle cache
        if (!empty($siteConfig['isWoocommerceActive'])
         && !empty($siteConfig['options_cache']['woocommerce_default_customer_address'])
         && "geolocation_ajax" === $siteConfig['options_cache']['woocommerce_default_customer_address']
         ) {
            self::resetVariationCookies();
            return true;
        }

        // Check if Woocommerce Cache Handler is configured
        if (!empty($siteConfig['isWoocommerceCacheHandlerActive'])
         && !empty($siteConfig['options_cache']['cache_handler_cache_handler'])
         && in_array($siteConfig['options_cache']['cache_handler_cache_handler'], ['enable_redirect', 'enable_ajax'])
         ) {
            self::resetVariationCookies();
            return true;
        }

        // standard cookie integration
        self::initVariationCookies();
    }

    public static function initVariationCookies() {
        $api = get_nitropack_sdk()->getApi();
        $customVariationCookies = self::customVariationCookies;
        try {
            $variationCookies = $api->getVariationCookies();
            foreach ($variationCookies as $cookie) {
                $index = array_search($cookie["name"], $customVariationCookies);
                if ($index !== false) {
                    array_splice($customVariationCookies, $index, 1);
                }
            }

            foreach ($customVariationCookies as $cookieName) {
                $api->setVariationCookie($cookieName);
            }
        } catch (\Exception $e) {
            // what to do here? possible reason for exception is the API not responding
            return false;
        }
    }

    public static function resetVariationCookies() {
        $api = get_nitropack_sdk()->getApi();
        try {
            $variationCookies = $api->getVariationCookies();
            foreach ($variationCookies as $cookie) {
                if (in_array($cookie["name"], self::customVariationCookies)) {
                    $api->unsetVariationCookie($cookie["name"]);
                }
            }
        } catch (\Exception $e) {
            // what to do here? possible reason for exception is the API not responding
            return false;
        }
    }

    public function canServeCache($currentState) {
        // some websites only use aelia_cs_selected_currency, but check all cookies just in case.
        if (empty($_COOKIE["aelia_cs_selected_currency"])
            && empty($_COOKIE["aelia_customer_country"])
            && empty($_COOKIE["aelia_customer_state"])
            && empty($_COOKIE["aelia_tax_exempt"])
        ) {
            header("X-Nitro-Disabled-Reason: Aelia cookie bypass");
            return false;
        }

        return $currentState;
    }
}
