:: builds all versions of this app, removing any temporary directories
call gulp dev
call rd /s /q "temp"

call gulp prod
call rd /s /q "temp"