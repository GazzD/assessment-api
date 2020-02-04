Assessment API 
====================
Assesment API with multiple data source that handle two different resources :

- Multiple-choice questions 
- Choices related to a question

---

 - **[Installation](#installation)**
 - **[Endpoints](#endpoints)**

 

## Installation

- composer install
- rename .env-example to .env
- run server (example php -S localhost:8000 -t public)

## Endpoints

Endpoints are defined in open-api.yaml

    - Examples:
        - [GET] localhost:8000/api/v1/questions
        - [POST] localhost:8000/api/v1/questions
