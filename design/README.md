## Database Design Overview

To barter means to trade goods and services for other goods and services. Barter Bin will be a unique web application that allows its users to trade their goods and services with each other without the need of cash or other currency. For this project, I will be utilizing a MySQL database. I feel this is a suitable choice for the web application based on the nature of the data that it will be managing.

MySQL is a very popular relational database management system or RDBMS that provides great flexibility, scalability, and performance. It’s designed to manage large amounts of data efficiently and effectively and it’s commonly used for web applications that require reliable and quick data storage and retrieval. Regarding the Barter Bin web application, MySQL can support the complex transactions and queries needed to handle trades between users, updating trade statuses, and ensuring the consistency of data. MySQL’s ability to handle concurrent transactions is also important because multiple users will be accessing the system at the same time.

### Brief overview of how the database will interact with the PHP service layer

The database will interact with PHP in many ways to include data insertion and modification, data retrieval, validation and sanitization of data, authentication, and authorization. The PHP service layer will act as the “middle man” between the MySQL database and the front-end user interface. It will return the appropriate response to the user interface to aid in providing an efficient and seamless user experience.

PLEASE VIEW THE DATABASE DESIGN DOCUMENT FOR MORE INFORMATION.

## Service Layer Design Overview

I will be utilizing PHP, the PHP framework Laravel, and RESTful API to communicate with the backend of the web application. This will provide the set of tools and technologies necessary to handle requests, access data, and return responses to users. A quick overview of how these components will work together is as follows:

PHP will provide the underlying scripting language that will power the web application. Developers primarily use PHP to write server-side code that’s typically used to generate dynamic web content, database interactions, and the handling of user requests.

Laravel is a popular PHP framework that provides a set of tools and libraries for building web applications. It’s designed to have an easy learning curve and it’s flexible, making it a common choice for building RESTful APIs.

RESTful APIs are used to expose functionality to web applications, various systems, and clients over the internet. The Barter Bin application will be able to communicate with front-end clients and other web services.

These components will work together to build an efficient and scalable backend for the Barter Bin web application.

### Overview of Functionality

The PHP service layer will be created using the Laravel framework. The service layer contains the application logic for interacting with MySQL and handling requests from the user interface. The service layer will also include functions to perform searches, and to match users based on their wants and needs. User authentication and the ratings and review system will also be implemented in the service layer.

The PHP service layer will interact with the database via MySQL queries to retrieve and modify data. The user interface will communicate with the service layer by sending HTTP requests to specific endpoints. The service layer will also process the requests and return the appropriate response. This will be hosted on an Amazon EC2 web server, where it will receive requests from the client to communicate with the database to retrieve and modify said data.

The endpoints required for Barter Bin’s minimal viable product or MVP will include the endpoints necessary to handle user login, item posting, searching, and matching. Rating and review posting can support the MVP, but it is not considered a “core” feature and functionality of Barter Bin. Ratings and reviews will be designated as a stretch feature. It’s service layer design and flow are still unchanged.

PLEASE VIEW THE SERVICE LAYER DOCUMENT FOR MORE INFORMATION.
