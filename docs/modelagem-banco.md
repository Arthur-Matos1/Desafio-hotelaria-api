# Desafio Hotelaria API

Projeto desenvolvido para o desafio técnico da Foco Multimídia.

O objetivo do projeto é desenvolver uma aplicação para gerenciamento de hotéis, quartos e reservas.

## Funcionalidades

O projeto deverá possuir:

- Importação de dados através de arquivos XML
- Persistência dos dados em banco de dados MySQL
- API REST
- CRUD de quartos
- Cadastro de reservas
- Documentação do projeto

## Tecnologias

As principais tecnologias utilizadas serão:

- PHP
- Laravel
- MySQL
- Git
- GitHub

## Estrutura inicial

Os arquivos XML fornecidos para o projeto estão localizados em:

database/xml/

Arquivos:

- hotels.xml
- rooms.xml
- reserves.xml

## Status do projeto

Em desenvolvimento.

## Reservas

A tabela `reserves` representa as reservas realizadas no sistema.

Campos:

- id: identificador da reserva.
- hotel_id: hotel relacionado à reserva.
- room_id: quarto reservado.
- check_in: data de entrada.
- check_out: data de saída.
- total: valor total da reserva.

## Hóspedes

A tabela `guests` armazena os hóspedes vinculados às reservas.

Campos:

- id: identificador do hóspede.
- reserve_id: reserva relacionada.
- name: nome.
- last_name: sobrenome.
- phone: telefone.

Relacionamento:

RESERVES 1:N GUESTS

## Diárias

A tabela `dailies` armazena as diárias de cada reserva.

Campos:

- id: identificador da diária.
- reserve_id: reserva relacionada.
- date: data da diária.
- value: valor da diária.

Relacionamento:

RESERVES 1:N DAILIES

## Pagamentos

A tabela `payments` armazena pagamentos vinculados às reservas.

Campos:

- id: identificador do pagamento.
- reserve_id: reserva relacionada.
- method: código do método de pagamento.
- value: valor pago.

Relacionamento:

RESERVES 1:N PAYMENTS

