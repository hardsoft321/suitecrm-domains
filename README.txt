Поддомены

Реализует использование отдельных баз для поддоменов.

Используемые настройки в sugar_config:
* domain_level - уровень домена в url; например, для url admin.example.com поддомен admin находится на уровне 3
* domain_db_prefix - префикс имени базы данных при создании новой базы
Необходимо корректно (с поддоменом admin) заполнить в /config.php
* site_url
* host_name
* fromaddress из настроек email
Тогда эти настройки будут скопированы с заменой домена.

Есть upgrade unsafe файлы.
Есть файлы, скопированные из SuiteCRM 7.6.4 и переделанные - в modules/Domains/install/

Запуск команд для каждого домена:
  php domains-foreach.php <shell_command>
например
  php domains-foreach.php "spm repair | spm dbquery"

Запуск крон для каждого домена:
  php domains-foreach.php "php domains-precron.php && php cron.php && php domains-postcron.php"
