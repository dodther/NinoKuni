setlocal enabledelayedexpansion
set ddfg=%2

php "%ddfg:"=%\unpack_pak.php" %1
