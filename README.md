# Bank
 
A very simple bank transaction api build on docker and symfony.
 
### Running Bank
 
For bank to run you need to have docker installed and add an entry to your /etc/hosts
```
 127.0.0.1 symfony.localhost
```
Now you can run the following commands to start bank. We start bank first, because mysql can take some time to start.
```
 docker-compose up -d db
 docker-compose up
```
Bank is now available on symfony.localhost:8080


### Using Bank
Try it out with curl

```
curl -X POST \
  http://symfony.localhost:8080/transaction \
  -H 'Content-Type: application/json' \
  -d '{
  "amount": 9.99,
  "booking_date": "2018-01-01 12:00:01",
  "parts": [
    {
      "reason": "debtor_payback",
      "amount": 2.00
    },
    {
      "reason": "bank_charge",
      "amount": 1.00
    },
    {
      "reason": "payment_request",
      "amount": 1.50
    },
    {
      "reason": "unidentified",
      "amount": 1.50
    },
    {
      "reason": "unidentified",
      "amount": 2.00
    },
    {
      "reason": "debtor_payback",
      "amount": 1.99
    }
  ]
}'
```

### Testing Bank

Because I'm not spending tons of time on this project the testing is very straightforward.
Bank uses PHPUnit tests which run on the host and not in the container. For a real project I would refactor this, 
to run the tests inside the container so the host doesn't need php.

To run the tests, make sure all container from docker-compose are running like explained above and run this.
The testing data is not removed from the database, in a real project you would test on a different database or mock out
the database for unit tests.
```
php symfony/bin/phpunit -c symfony/phpunit.xml
```