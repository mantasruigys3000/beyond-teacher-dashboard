# Beyond Teacher Dashboard
View students of any class by school employee

## Project setup
`git clone https://github.com/mantasruigys3000/beyond-teacher-dashboard.git`\
`cd beyond-teacher-dashboard`\

copy env from example, ensure to set your school id and token
optional: set your queue_connection to database/redis and set mail credentials to mailtrap

`cp .env.example .env`\
`composer install`\
`npm install`\
`php artisan key:generate`\
`php artisan migrate`\
`npx vite build`


