# Desafio Hotelaria API

Projeto desenvolvido para o desafio técnico da Foco Multimídia.

A aplicação consiste em uma API REST para gerenciamento de hotéis,
quartos e reservas, desenvolvida utilizando Laravel, MySQL e Docker.

O sistema também realiza a importação automática dos dados fornecidos
através de arquivos XML.

---

## Funcionalidades

O projeto possui:

- Importação de hotéis através de XML
- Importação de quartos através de XML
- Importação de reservas através de XML
- Importação de hóspedes
- Importação de diárias
- Importação de pagamentos
- CRUD REST de quartos
- Cadastro de reservas através de API REST
- Validação dos dados recebidos pela API
- Relacionamento entre hotéis e quartos
- Relacionamento entre reservas, hóspedes, diárias e pagamentos
- Execução automática da importação utilizando Laravel Scheduler e CRON
- Ambiente de desenvolvimento utilizando Docker
- Documentação da API utilizando OpenAPI 3.0.0 e Swagger UI
- Testes automatizados utilizando PHPUnit
- Versionamento utilizando Git e GitHub
- Respostas da API no formato JSON

---

## Tecnologias utilizadas

- PHP
- Laravel
- MySQL
- Docker
- Docker Compose
- Git
- GitHub
- XML
- API REST
- OpenAPI 3.0.0
- Swagger UI
- PHPUnit

---

# Estrutura do projeto

```text
Desafio-hotelaria-api/
│
├── backend/
│   ├── app/
│   │   ├── Console/
│   │   │   └── Commands/
│   │   │       └── ImportXmlData.php
│   │   │
│   │   ├── Http/
│   │   │   └── Controllers/
│   │   │       ├── RoomController.php
│   │   │       └── ReserveController.php
│   │   │
│   │   └── Models/
│   │       └── Room.php
│   │
│   ├── routes/
│   │   ├── api.php
│   │   └── console.php
│   │
│   ├── tests/
│   │   └── Feature/
│   │       ├── RoomApiTest.php
│   │       └── ReserveApiTest.php
│   │
│   ├── .env
│   ├── .env.example
│   ├── artisan
│   └── composer.json
│
├── database/
│   ├── xml/
│   │   ├── hotels.xml
│   │   ├── rooms.xml
│   │   └── reserves.xml
│   │
│   └── sql/
│       └── schema.sql
│
│
├── docs/
│   ├── modelagem-banco.md
│   ├── cron.md
│   └── openapi.yaml
│
├── Dockerfile
├── compose.yaml
└── README.md
```

---

# Modelagem do banco de dados

O banco de dados foi modelado com base nos arquivos XML fornecidos
no desafio.

As principais tabelas são:

## Hotels

Responsável por armazenar os hotéis.

```text
hotels
----------------
id
name
```

---

## Rooms

Responsável por armazenar os quartos.

```text
rooms
----------------
id
hotel_id
name
```

Relacionamento:

```text
HOTELS 1:N ROOMS
```

Um hotel pode possuir vários quartos.

---

## Reserves

Responsável por armazenar as reservas.

```text
reserves
----------------
id
hotel_id
room_id
check_in
check_out
total
```

---

## Guests

Responsável por armazenar os hóspedes relacionados às reservas.

```text
guests
----------------
id
reserve_id
name
last_name
phone
```

Relacionamento:

```text
RESERVES 1:N GUESTS
```

---

## Dailies

Responsável por armazenar as diárias de cada reserva.

```text
dailies
----------------
id
reserve_id
date
value
```

Relacionamento:

```text
RESERVES 1:N DAILIES
```

---

## Payments

Responsável por armazenar pagamentos relacionados às reservas.

```text
payments
----------------
id
reserve_id
method
value
```

Relacionamento:

```text
RESERVES 1:N PAYMENTS
```

---

# Arquivos XML

Os arquivos XML utilizados pelo projeto estão localizados em:

```text
database/xml/
```

Arquivos:

```text
hotels.xml
rooms.xml
reserves.xml
```

O arquivo `hotels.xml` contém os hotéis.

O arquivo `rooms.xml` contém os quartos e o código do hotel ao qual
cada quarto pertence.

O arquivo `reserves.xml` contém:

- reservas;
- hóspedes;
- diárias;
- pagamentos.

---

# Executando o projeto

## Pré-requisitos

Para executar o projeto é necessário possuir:

- Docker Desktop
- Docker Compose
- Git

Utilizando Docker, não é necessário possuir PHP, Composer e MySQL
instalados diretamente no Windows.

---

## 1. Clonar o projeto

```bash
git clone URL_DO_SEU_REPOSITORIO
```

Entre na pasta:

```bash
cd Desafio-hotelaria-api
```

---

## 2. Configurar o ambiente Laravel

O arquivo `.env` não deve ser enviado para o GitHub.

Crie:

```text
backend/.env
```

utilizando como base:

```text
backend/.env.example
```

No Windows:

```bash
copy backend\.env.example backend\.env
```

Configure a conexão com o banco:

```env
DB_CONNECTION=mysql
DB_HOST=db
DB_PORT=3306
DB_DATABASE=hotelaria
DB_USERNAME=hotelaria
DB_PASSWORD=hotelaria

SESSION_DRIVER=file
CACHE_STORE=file
QUEUE_CONNECTION=sync
```

---

## 3. Instalar dependências

Caso a pasta `vendor` não esteja disponível:

```bash
docker run --rm -v "%cd%\backend:/app" -w /app composer:2 install
```

---

## 4. Construir os containers

```bash
docker compose build
```

Para uma reconstrução completa:

```bash
docker compose build --no-cache
```

---

## 5. Iniciar os containers

```bash
docker compose up -d
```

---

## 6. Verificar os containers

```bash
docker compose ps
```

Os serviços utilizados são:

```text
hotelaria_app
hotelaria_db
hotelaria_scheduler
hotelaria_swagger
```

---

## 7. Gerar a chave da aplicação

Na primeira execução:

```bash
docker compose exec app php artisan key:generate
```

---

# Acessos

Aplicação Laravel:

```text
http://localhost:8000
```

API:

```text
http://localhost:8000/api
```

API de quartos:

```text
http://localhost:8000/api/rooms
```

Swagger UI:

```text
http://localhost:8081
```

---

# Importação XML

A aplicação possui um comando Laravel responsável por importar os
dados dos arquivos XML para o MySQL.

O comando está localizado em:

```text
backend/app/Console/Commands/ImportXmlData.php
```

Para executar manualmente:

```bash
docker compose exec app php artisan hotelaria:import-xml
```

Fluxo da importação:

```text
hotels.xml
     ↓
hotels

rooms.xml
     ↓
rooms

reserves.xml
     ↓
reserves
├── guests
├── dailies
└── payments
```

A importação pode ser executada novamente sem duplicar hotéis,
quartos e reservas importados.

---

# CRON e Laravel Scheduler

A importação XML também pode ser executada automaticamente.

O agendamento está configurado em:

```text
backend/routes/console.php
```

Exemplo:

```php
use Illuminate\Support\Facades\Schedule;

Schedule::command('hotelaria:import-xml')
    ->everyFiveMinutes()
    ->withoutOverlapping();
```

O CRON chama o Laravel Scheduler periodicamente.

Para listar os agendamentos:

```bash
docker compose exec app php artisan schedule:list
```

Para executar manualmente o Scheduler:

```bash
docker compose exec app php artisan schedule:run
```

Para visualizar os logs:

```bash
docker compose logs -f scheduler
```

Mais informações:

```text
docs/cron.md
```

---

# API REST

Todas as respostas da API são retornadas no formato JSON.

Base:

```text
http://localhost:8000/api
```

---

# CRUD de quartos

## Listar quartos

```http
GET /api/rooms
```

Exemplo de resposta:

```json
[
    {
        "id": 1,
        "hotel_id": 1,
        "name": "Room 1 Hotel 1"
    }
]
```

---

## Buscar quarto

```http
GET /api/rooms/{id}
```

Exemplo:

```http
GET /api/rooms/1
```

---

## Cadastrar quarto

```http
POST /api/rooms
```

Exemplo:

```json
{
    "hotel_id": 1,
    "name": "Quarto Luxo"
}
```

---

## Atualizar quarto

```http
PUT /api/rooms/{id}
```

Exemplo:

```json
{
    "hotel_id": 1,
    "name": "Quarto Luxo Premium"
}
```

---

## Excluir quarto

```http
DELETE /api/rooms/{id}
```

---

# Cadastro de reservas

Endpoint:

```http
POST /api/reserves
```

Exemplo de requisição:

```json
{
    "hotel_id": 1,
    "room_id": 1,
    "check_in": "2026-11-10",
    "check_out": "2026-11-13",
    "total": 600.00,

    "guests": [
        {
            "name": "Arthur",
            "last_name": "Matos",
            "phone": "11999999999"
        }
    ],

    "dailies": [
        {
            "date": "2026-11-10",
            "value": 200.00
        },
        {
            "date": "2026-11-11",
            "value": 200.00
        },
        {
            "date": "2026-11-12",
            "value": 200.00
        }
    ],

    "payments": [
        {
            "method": 1,
            "value": 600.00
        }
    ]
}
```

Exemplo de resposta:

```json
{
    "message": "Reserva cadastrada com sucesso.",
    "reserve_id": 7
}
```

---

# Swagger / OpenAPI

A documentação da API utiliza:

```text
OpenAPI 3.0.0
Swagger UI
```

A especificação está localizada em:

```text
docs/openapi.yaml
```

Com os containers em execução, acesse:

```text
http://localhost:8081
```

No Swagger é possível visualizar e testar:

```text
GET     /api/rooms
POST    /api/rooms
GET     /api/rooms/{id}
PUT     /api/rooms/{id}
DELETE  /api/rooms/{id}

POST    /api/reserves
```

---

# Testes automatizados

O projeto possui testes automatizados utilizando PHPUnit.

Os testes estão localizados em:

```text
backend/tests/
```

Entre os testes implementados estão:

```text
RoomApiTest
ReserveApiTest
```

Para executar todos os testes:

```bash
docker compose exec app php artisan test
```

Os testes verificam principalmente as validações dos endpoints da API.

Exemplo de resultado esperado:

```text
PASS  Tests\Feature\RoomApiTest
PASS  Tests\Feature\ReserveApiTest
```

---

# Docker

O projeto utiliza containers separados:

```text
Docker
│
├── hotelaria_app
│   └── Laravel / PHP
│
├── hotelaria_db
│   └── MySQL
│
├── hotelaria_scheduler
│   └── CRON / Laravel Scheduler
│
└── hotelaria_swagger
    └── Swagger UI
```

O Laravel acessa o MySQL através de:

```env
DB_HOST=db
DB_PORT=3306
```

Externamente, o MySQL Docker utiliza:

```text
localhost:3307
```

---

# Comandos úteis

Iniciar o projeto:

```bash
docker compose up -d
```

Parar:

```bash
docker compose down
```

Ver containers:

```bash
docker compose ps
```

Visualizar logs:

```bash
docker compose logs
```

Logs do Laravel:

```bash
docker compose logs -f app
```

Logs do CRON:

```bash
docker compose logs -f scheduler
```

Executar importação:

```bash
docker compose exec app php artisan hotelaria:import-xml
```

Ver rotas:

```bash
docker compose exec app php artisan route:list
```

Ver tarefas agendadas:

```bash
docker compose exec app php artisan schedule:list
```

Executar testes:

```bash
docker compose exec app php artisan test
```

---

# Banco de dados

Para visualizar as tabelas:

```bash
docker compose exec db mysql -uhotelaria -photelaria -e "USE hotelaria; SHOW TABLES;"
```

Tabelas esperadas:

```text
hotels
rooms
reserves
guests
dailies
payments
```

---

# Segurança

O arquivo:

```text
backend/.env
```

não deve ser enviado ao GitHub.

Credenciais, senhas e configurações específicas do ambiente devem
permanecer apenas localmente.

O arquivo:

```text
backend/.env.example
```

serve como exemplo de configuração.

---

# Documentação adicional

A documentação complementar está localizada em:

```text
docs/
```

Arquivos:

```text
modelagem-banco.md
cron.md
openapi.yaml
```

---

# Versionamento

O projeto utiliza Git e GitHub.

Fluxo básico:

```bash
git status
git add .
git commit -m "mensagem do commit"
git push
```

Os commits são realizados conforme a evolução das funcionalidades.

---

# Encerrando o projeto

Para parar os containers:

```bash
docker compose down
```

---

# Status do projeto

Funcionalidades principais:

```text
Modelagem do banco       OK
Docker                   OK
Laravel                  OK
MySQL                    OK
Importação XML           OK
CRUD de quartos          OK
POST de reservas         OK
Laravel Scheduler        OK
CRON                     OK
OpenAPI / Swagger        OK
Testes PHPUnit           OK
Git / GitHub             OK
```

---

## Autor

Projeto desenvolvido para fins de avaliação técnica.