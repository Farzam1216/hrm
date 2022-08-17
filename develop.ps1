param(
    [string]$command,
    [string[]][Parameter(ValueFromRemainingArguments)]$passthrough
)

$env:APP_PORT = 80
$env:DB_PORT = 3306
$env:DB_ROOT_PASS = "root"
$env:DB_NAME = "laravel"

If ($command -eq "artisan") {
    docker-compose run --rm -w /var/www/html php php artisan $passthrough
} ElseIf ($command -eq "composer") {
    docker-compose run --rm -w /var/www/html php composer $passthrough
} ElseIf ($command -eq "test") {
    docker-compose run --rm -w /var/www/html php ./vendor/bin/phpunit $passthrough
} ElseIf ($command -eq "npm") {
    docker-compose run --rm -w /var/www/html node npm $passthrough
} Else {
    docker-compose $command $passthrough
}