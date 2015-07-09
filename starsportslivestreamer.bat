@ echo off
call set /p link=paste the link:
call set folder="%~dp0\videos\\"
call set livestreamer="%~dp0\tools\livestreamer\\"
call "%~dp0\tools\php5.4\php.exe" starsportslivestreamer.php "%%link%%"
call set /p quality=write quality (example write 720p):
call "%~dp0\tools\php5.4\php.exe" starsportslivestreamer.php "%%link%%" "%%quality%%" "%%folder%%" "%%livestreamer%%"
pause
:end
