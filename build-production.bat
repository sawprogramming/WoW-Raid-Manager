:: builds the prod version of this app and removes the temporary directory
call gulp prod
call rd /s /q "temp"