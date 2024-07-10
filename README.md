# custom-php-mvc-framework
Model View Controller (MVC) framework based on tutorials I have been following from Freeskills on YT.

Beginning on Friday, June 15, 2024 all updates are driven by me unless other contributors are involved.

## About
The Model View Controller is a style of programming that allows developers to efficiently manage interactions between users, the user interface, and the database of a web application.  The models manages the data, logic and rules of the application.  The views are what the user sees and interacts with.  Finally, the controller manages interactions between the user, views, and models.

## What does this MVC support?
It supports everything described above.  This sample application natively comes with support for user login, registration, and sessions associated with each user.

## Getting Started
1. Navigate to where your development projects are located in CMD or Terminal.
2. Run the command git clone git@github.com:chapmancbVCU/custom-php-mvc-framework.git
3. Make a copy of .env.sample in project root and name it .env.  Fill in the following information:
   a. DB_USER
   b. DB_PASSWORD
   c. CURRENT_USER_SESSION_NAME: should be a long string of upper and lower case characters and numbers.
   d. REMEMBER_ME_COOKIE_NAME:  should be a long string of upper and lower case characters and numbers.
4. profileImage directory:

## Goals
1. Add additional front-end and back-end form validation (Done)
2. Resolve issue for warnings about creating of dynamic properties so the framework is fully compatible with PHP 8
3. Test with nginx
4. Update jQuery and Bootstrap to modern builds and add support to maintain similar look and feel of front end (Done)
5. Add support for additional form elements in FormHelpers (In progress)
6. Add user guide (Update as needed)
7. Add management system for Users model to in include admin so administrators can manage other users, change user type, and perform password reset operations.
8. Add types to functions (Done)
9. Add types to instance variables
10. Add TinyMCE (Done)
11. Add file upload support (Done)
12. Add user's guide (In progress)

## Credits
1. “mvc” icon by iconixar, from thenounproject.com.
2. Freeskills on YT (https://www.youtube.com/playlist?list=PLFPkAJFH7I0keB1qpWk5qVVUYdNLTEUs3)
