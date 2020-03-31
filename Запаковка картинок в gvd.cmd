@echo off

setlocal enabledelayedexpansion
set ddfg=%2
php "%ddfg:"=%\pack_img_to_gvd.php" %1 
