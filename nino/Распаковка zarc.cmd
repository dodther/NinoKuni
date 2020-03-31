setlocal enabledelayedexpansion
set ddfg=%2

php.exe  "%ddfg:"=%unpack_zarc.php" %1
del %1
