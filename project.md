So I need to make some changes in my config/auth file to ensure that the model selected is userProfiles and not the User class. 

Also in the config/app file, I need to ensure that I register my middleware which I will be using in my routes file. 

Also I should use the attempt method in the auth class to allow the correct credentials to be picked from the db and for them to be checked. 

I need to find a way to ensure that this auth process happens in my middleware and not my controller file.

My controller file needs to only have crud operations only, the rest, a service file and my middleware file should take care of the rest. 