# Simple REST chat

Simple REST chat application for demo purposes.
This application could be also possible using ReactPHP and WebSockets.

## Setup
Information for setting up the development environment is at [setup.md][0]
 
## Users
There are 2 users for demo purposes
```
# Demo User 1
user1@demo.com
user1password

# Demo User 2
user2@demo.com
user2password
```

## Authentication
The application is using simple authentication.
You need to login using user credentials, retrieve the auto-generated token
an pass an Authorisation header

Any attempt for unauthorised access throws JSON Response with 401 status code.
```bash
GET api/message/user/1
> {"error":"Unauthorized"}
```
A user must login in order to retrieve his api_key
```bash
GET api/login?email=user1@demo.com&password=user1pass
> {"status":"success","api_key":"XXXXXXXX"}
```

## Post new message
A user can post a new message to a specific receiver (another user id).
```bash
# Headers/key: Authorization (or HTTP_Authorization) : XXXXXXXX
POST api/message
> 200 {"status":"success","result":{}}
```

## Retrieve MessagesBySender
A user can retrieve all its messages from a specific sender (another user id).
```bash
# Headers/key: Authorization (or HTTP_Authorization) : XXXXXXXX
GET api/message/user/1
> 200 {"status":"success","result":{}}
```

## Tests
Tests are located under the `/tests` directory
```bash
vendor/bin/phpunit
```

## Teardown
You can teardown be using the following command:
```bash
docker-compose -f docker-compose.local.yml down
```

[0]: setup.md