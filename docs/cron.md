# Execução automática da importação XML

O projeto utiliza o Laravel Scheduler em conjunto com CRON para
executar automaticamente a importação dos arquivos XML.

## Comando de importação

A importação pode ser executada manualmente através do comando:

```bash
docker compose exec app php artisan hotelaria:import-xml