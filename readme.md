# Trip Advisor
## Description
RESTApi endpoint services to keep track of user trips to certain destinations.
The project was based on the following functional requirements. All the functional specification has been met.
This implementation was carried through with the following technical specifications:
- PHP Framework Symfony 4.4
- MariaDb 10.4 but Mysql 5.7 could be used as well.
- PHP 7.4.
- Nginx 1.17.6.
- For the local development environment, docker was used. Install docker(v19.03.5) and docker-compose(v1.24.1,) for automated development environment.

## Requirements
The requirements for the test project are:
- Write an REST API using any Symfony version for tracking trips to
different countries in Europe and Asia
- Sync all countries in Europe and Asia from https://restcountries.eu/
- Users need to create an account (username, password, email)
- Users should be able to update and delete their accountuse
- Users should be able to create, update and delete trips to countries**
- Trips are only visible to the user who created them
- A trip needs to have following informations: Country, start date, end
date and notes
- A trip can start at the end date of another trip, but they are not
allowed to overlap
- Users should be able to search their trips by date range and/or country

Bonus:
- Documentation
- Unit-Tests

## Installation
### Docker dev environment.
1. Install docker and dcoker-compose
2. Clone repository [https://github.com/redilinxa/trip-tracker.git].
3. Enter in {project_dir}/docker and run docker-compose up.
4. Open a new terminal window and go again in the {project_root} directory to run: `docker exec -it docker_php-fpm_1 bin/console d:m:m`
5. On the same directory run the countries synchronization command. `docker exec -it docker_php-fpm_1 bin/console app:syncCountries`
6. Execute the unit tests: docker exec -it docker_php-fpm_1 ./vendor/bin/simple-phpunit tests/UserTripTest.php

### Manual setup with Symfony built in server.
Please follow Symfony installation process on:
1. https://symfony.com/doc/current/setup.html
2. https://symfony.com/doc/current/configuration.html
Make sure you have a local environment suitable for this application based on the version mentioned above.


## Usage
Project routes
 ----------------------- -------- -------------------------------------- 
  Name                    Method   Path                                  
 ----------------------- -------- -------------------------------------- 
  trip_api.users.list     GET      /api/users/list                       
  trip_api.users.create   POST     /api/users/create                     
  trip_api.users.show     GET      /api/users/{id}/show                  
  trip_api.users.update   PUT      /api/users/{id}/update                
  trip_api.users.delete   DELETE   /api/users/{id}/delete                
  trip_api.trips.list     GET      /api/trips/user/{user}/list           
  trip_api.trips.create   POST     /api/trips/user/{user}/create         
  trip_api.trips.show     GET      /api/trips/{trip}/user/{user}/show    
  trip_api.trips.update   PUT      /api/trips/{trip}/user/{user}/update  
  trip_api.trips.delete   DELETE   /api/trips/{trip}/user/{user}/delete  
 ----------------------- -------- -------------------------------------- 
* The {trip} and {user} parameter are respectively the id of both trip and user. 
Sample of the call could be found on the test/UserTripTest.php
Also, if you are using postman, i have created e collection of routes with sample calls
for an easy import. Please import the trip `trip_advisor.postman_collection.json` on the project route.
