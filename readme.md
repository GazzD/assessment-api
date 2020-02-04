Assessment API 
====================
Assesment API made in Lumen with multiple data sources to handle two different resources :

- Multiple-choice questions 
- Choices related to a question

---

 - **[Requierements](#requirements)**
 - **[Installation](#installation)**
 - **[Endpoints](#endpoints)**

## Requirements 
- PHP >= 7.2
- OpenSSL PHP Extension
- PDO PHP Extension
- Mbstring PHP Extension

## Installation

- composer install
- rename .env.example to .env
- run server (example php -S localhost:8000 -t public)

## Endpoints

Endpoints are defined in open-api.yaml

    - Examples:
        - [GET] localhost:8000/api/v1/questions
        - [POST] localhost:8000/api/v1/questions
