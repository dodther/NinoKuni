rem @echo off
rem 㪠�뢠�� p3img 䠩�
setlocal enabledelayedexpansion
set ddfg=%2
php.exe  "%ddfg:"=%\unpack_p3igg.php" %1
