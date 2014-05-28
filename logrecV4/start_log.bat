@echo off
@title Log Recorder v4
color f0
REM set window size
mode con: cols=50 lines=5
REM line below helps php from apache open this file
cd ..\logrecV4
REM open log recorder
php-cli.exe -f "recorder.php"