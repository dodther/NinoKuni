rem @echo off
rem указывать p3img файл
setlocal enabledelayedexpansion
set ddfg=%2
php.exe  "%ddfg:"=%\unpack_p3igg.php" %1
