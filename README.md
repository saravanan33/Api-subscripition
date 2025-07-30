
# Api Subscription service

This project is about a user who can access a route register and with subscripition model with 3 tires

##### 1. Free Tire - 100 request per day.
##### 2. Standard Tier - 1,000 request per day.
##### 3. Premium Tier - 10,000 per days and still can user with a cost of 0.01$ per request.





## Appendix

Any additional information goes here


## Documentation



For This about Apis postman collections attached in the repo with example with referance.


And the Database ER diagrame also attached as a png format

## API Reference

#### Get Register
##### User Register Api
```http
  Post /api/register
```

| Parameter | Type     | Description                |
| :-------- | :------- | :------------------------- |
| `name `   | `string` | **Required** name of the user      |
| `email`   | `string` | **Required** user mail id  |
| `password`| `string` | **Required** user password.              |
|`tier_id`| `int`|**Required** subscription plan id|


#### Login Get Api Token 
##### User Login Api
```http
  Post /api/login
```

| Parameter | Type     | Description                       |
| :-------- | :------- | :-------------------------------- |
| `email`      | `string` | **Required**. email id |
|`password`|`string`|**Required**. password|


#### Get User Details 

##### Users usage of the api
```http
  GET /api/userDetails
```
 
 |`response`| `Description`|
 |:-----|:---|
 |`todayUsage`|**Total usage of the Api Request**.|
 |`subscriptionPlanName`|**subscription plan name**.|
 |`extraCallUsed`|**Extra Api Used Count**|



#### Get Data
##### Which is a dummy api used to rate limit a api with subscripition tier
```http
  GET /api/data
```
 
 |`response`| `Description`|
 |:-----|:---|
 |`json`|**Country List Json**.|




## Deployment

To deploy this project run

#### execute table migration
```bash
    php artisan migrate -- migrate a tables
```

#### Seed Data to tables
```bash
    php artisan db-seed -- seed data to the tables
```

#### Run a cron command manually 
##### to generate a monthly daily report so far report only persent on the Logs
``` bash
    php artisan GenerateDailyBill:GenerateDailyBill
```


## Screenshots

![App Screenshot](https://via.placeholder.com/468x300?text=App+Screenshot+Here)
