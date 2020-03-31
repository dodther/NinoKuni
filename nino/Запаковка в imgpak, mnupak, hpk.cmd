setlocal enabledelayedexpansion
set ddfg=%2
rem указывать оригинальный файл
php "%ddfg:"=%\pack_to_pak.php" %1
