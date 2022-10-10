## Git Clone 
git clone https://github.com/puneetsingh13/symfony-order-api.git

## GO to dir
cd smfony-api

## Exec: command
composer install

## Start Server
symfony server:start

## API Details:
Dir:: smfony-api/thunder-collection_Order API.json

## Hit via Curl

curl -X GET \
  'http://127.0.0.1:8000/api/order/19324359' \
  --header 'Accept: */*' \
  --header 'User-Agent: Thunder Client (https://www.thunderclient.com)'





