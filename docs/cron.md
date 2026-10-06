# Execução automática da importação XML

O projeto utiliza o Laravel Scheduler em conjunto com CRON para
executar automaticamente a importação dos arquivos XML.

## Importação manual

A importação também pode ser executada manualmente:

```bash
docker compose exec app php artisan hotelaria:import-xml
```

## Laravel Scheduler

O agendamento está configurado em:

`backend/routes/console.php`

```php
Schedule::command('hotelaria:import-xml')
    ->everyFiveMinutes()
    ->withoutOverlapping();
```

O container `hotelaria_scheduler` executa o CRON continuamente.

O CRON chama:

```bash
php artisan schedule:run
```

a cada minuto, e o Laravel verifica quais tarefas precisam ser
executadas naquele momento.

## Verificar tarefas agendadas

```bash
docker compose exec app php artisan schedule:list
```

## Executar o scheduler manualmente

```bash
docker compose exec app php artisan schedule:run
```

## Visualizar logs

```bash
docker compose logs -f scheduler
```

Para sair da visualização dos logs:

```text
Ctrl + C
```