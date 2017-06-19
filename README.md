# currency_switcher_fixer_io
A simple module for Drupal 8 provides the Foreign exchange rates and currency conversion API Fixer.io
<br>
# Service currency_switcher_fixer_io
Getting the Entire Array 
<br>
$service = \Drupal::service('currency_switcher_fixer_io.controller')->currencyValue();
<br>
Getting the value of a currency
<br>
$service = \Drupal::service('currency_switcher_fixer_io.controller')->currencyValue('USD');
