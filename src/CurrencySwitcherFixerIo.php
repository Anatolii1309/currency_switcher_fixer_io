<?php
/**
 * @file
 * Contains \Drupal\currency_switcher_fixer_io\CurrencySwitcherFixerIo.
 */

namespace Drupal\currency_switcher_fixer_io;

use Symfony\Component\DependencyInjection\ContainerInterface;
use Drupal\Component\Serialization\Json;
use GuzzleHttp\ClientInterface;
use Drupal\Core\Config\ConfigFactoryInterface;
use Drupal\Component\Utility\Unicode;

/**
 * Currency Switcher Fixer Io.
 */
class CurrencySwitcherFixerIo implements CurrencySwitcherFixerIoInterface {

  /**
   * The HTTP client to fetch the feed data with.
   *
   * @var \GuzzleHttp\ClientInterface
   */
  protected $httpClient;

  /**
   * A configFactory instance.
   *
   * @var \Psr\Log\ConfigFactoryInterface
   */
  protected $configFactory;

  /**
   * Constructs a currency form object.
   *
   * @param \GuzzleHttp\ClientInterface $http_client
   *   The Guzzle HTTP client.
   */
  /**
   * The Drupal configuration factory.
   *
   * @var \Drupal\Core\Config\ConfigFactoryInterface
   */

  public function __construct(ClientInterface $http_client, ConfigFactoryInterface $config_factory) {
    $this->httpClient = $http_client;
    $this->configFactory = $config_factory;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container) {
    return new static(
      $container->get('http_client'),
      $container->get('config.factory')
    );
  }

  /**
   * Get currency data.
   */
  public function currencyCheck($options = NULL) {
    $data = '';
    $url = 'api.fixer.io/latest?symbols=' . $options;
      $response = $this->httpClient->get($url);
      $data = (string) $response->getBody();
    if (!empty($data)) {
      $data = Json::decode($data);
    }
    return $data;
  }

  /**
   * Return of result currency.
   */
  public function  currencyValue($currency = ''){
    $result_list = $this->configFactory->get('currency_switcher_fixer_io.currency')->get('currency');
    $settings_list = $this->configFactory->get('currency_switcher_fixer_io.settings')->get('settings');
    $validate = in_array($currency, $settings_list);
    if (empty($currency)) {
        return $result_list;
    }
    elseif ($validate) {
        return $result_list['rates'][$currency];
    } else {
        return 'The currency you entered is incorrect';
    }
  }

}
