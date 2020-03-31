@echo off

setlocal enabledelayedexpansion
set ddfg=%2
php "%ddfg:"=%\pack_txt_to_bin.php" %1 

