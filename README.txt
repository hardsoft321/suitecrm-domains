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

Настройки конкретного домена сохраняются в файл domains/<домен>/config.php.
То есть настройки sugar_config подключаются в следующей последовательности
* config.php
* config_override.php
* domains/<домен>/config.php

Пакеты типа 'langpack' устанавливать только в админском домене, т.к. они переписывают config.php.
На случай, если конфиг поддомена перепишет основной config.php, рекомендуется продублировать
настройки админской базы, папки и т.д. в domains/admin/config.php.

Есть upgrade unsafe файлы.
Есть файлы, скопированные из SuiteCRM 7.6.4 и переделанные - в modules/Domains/install/

Запуск команд для каждого домена:
  php domains-foreach.php <shell_command>
В тексте команды подстрока @@DOMAIN@@ заменяется на имя домена
например
  php domains-foreach.php "spm repair | spm dbquery > domains/@@DOMAIN@@/repair.log"

При этом имя домена доступно через переменную окружения SUGAR_DOMAIN.

Запуск крон для каждого домена:
  php domains-foreach.php "php domains-precron.php && php cron.php && php domains-postcron.php"

Запуск команды для одного домена:
  php domains-with.php <domain> <shell_command>
например
  php domains-with.php dom1 "spm sbstatus"
