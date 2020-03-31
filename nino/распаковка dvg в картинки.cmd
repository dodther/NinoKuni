@echo off
setlocal enabledelayedexpansion
set ddfg=%2
php "%ddfg:"=%\unpack_dvg_to_img.php" %1

