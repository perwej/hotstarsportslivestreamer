@ echo off
call set /p link=paste the link:
call set last=%%link:~-1,1%% 
call set folder="%~dp0\videos\\"
call set livestreamer="%~dp0\tools\livestreamer\\"
call "%~dp0\tools\php5.4\php.exe" hotstarlivestreamer.php "%%link%%"
IF  %last% NEQ c GOTO:nocollection
call set /p id=enter the Id of the video (example write 1000021386):
call "%~dp0\tools\php5.4\php.exe" hotstarlivestreamer.php "%%link%%" "%%id%%"
call set /p quality=write quality (example write 720p):
call "%~dp0\tools\php5.4\php.exe" hotstarlivestreamer.php "%%link%%" "%%id%%" "%%quality%%" "%%folder%%" "%%livestreamer%%"
GOTO end1
:nocollection
call set /p quality=write quality (example write 720p):
call "%~dp0\tools\php5.4\php.exe" hotstarlivestreamer.php "%%link%%" "%%quality%%" "%%folder%%" "%%livestreamer%%"
:end1
pause
:end
