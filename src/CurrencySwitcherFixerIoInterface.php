<?php
/**
 * @file
 * Contains \Drupal\currency_switcher_fixer_io\CurrencySwitcherFixerIoInterface.
 */

namespace Drupal\currency_switcher_fixer_io;

/**
 * Currency Switcher Fixer Io Interface.
 */
interface CurrencySwitcherFixerIoInterface {

    /**
     * Get data.
     */
    public function currencyCheck($options = NULL);


    public function  currencyValue($currency = '');


}
