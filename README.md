
# DiaToy Package

DiaToy is a PHP package for integrating with the Toyota API, generating QR codes, and handling requests.

## Installation

To install the package, use Composer:

```bash
composer require web-systems/dia-toy-api
```

## Usage

### Initialization

To use the DiaToy class, you need to initialize it with the appropriate parameters:

```php
use WebSystems\Toyota\DiaToy;

$login = 'your_login';
$password = 'your_password';
$baseApiUri = 'https://diapi.toyota.ua/api/';
$waitResponse = 5;
$exceptionMessage = 'Providing a VIN number via Diya is currently not available';

$diaToy = new DiaToy($login, $password, $baseApiUri, $waitResponse, $exceptionMessage);
```

### Get Request ID

To get the request ID and the QR code source:

```php
try {
    $request = $diaToy->getRequestId();
    print_r($request);
} catch (Exception $e) {
    echo 'Error: ' . $e->getMessage();
}
```

### Get Data

To get data using the request ID:

```php
try {
    $requestId = 'your_request_id';
    $data = $diaToy->getDate($requestId);
    print_r($data);
} catch (Exception $e) {
    echo 'Error: ' . $e->getMessage();
}
```

## Running Tests

To run the tests, use PHPUnit. Make sure you have it installed and your `composer.json` includes the required dependencies:

```json
"require-dev": {
    "phpunit/phpunit": "^9.5"
}
```

### Example Test Command

```bash
./vendor/bin/phpunit tests
```

## Contributing

Feel free to submit issues and pull requests. For major changes, please open an issue first to discuss what you would like to change.

## License

This project is licensed under the MIT License - see the LICENSE file for details.

## Author

Jeka Morozov - [morrowthoff@gmail.com](mailto:morrowthoff@gmail.com)

## Acknowledgements

Special thanks to the [Web Systems Solutions](https://web-systems.solutions) team.
