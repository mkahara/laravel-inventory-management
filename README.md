# Laravel web application for inventory management
This application allows a user to create a list of products through a form. The details required are product name, quantity and price. This data is then stored in a JSON file including a unique id for every product and the date submitted. 

The products are then listed in a table, with an option to edit or delete each product. The total value column displays the amount, using this formula (Quantity in stock * Price per item). The last row displays a sum total of all of the total value numbers.

### Technology
1. The application runs on Laravel 8 and MySQL database
2. I have used jQuery and Twitter Bootstrap as the only dependencies.
3. npm manages the packages and dependencies in the project.
4. I used Laravel Mix together with Webpack for asset compilation and bundling during development.
5. The products are stored in JSON format in a file (products.json) inside storage/app directory.

### Installation
1. To make it easy to run this application, I have created a [Github repository](https://github.com/mkahara/laravel-inventory-management) whereby one can clone and run the project.
   
2. Navigate to the project directory.
    ```bash
    cd your-laravel-project
    ```
3. Clone the repository
    ```bash
   git clone https://github.com/mkahara/laravel-inventory-management.git
   ```
4. Install all dependencies.
   ```bash
   npm install
   ```
5. Run the project to generate a url accessible on the browser.
   ```bash
   php artisan serve
   ```

### Usage
This application is a single page web application. It has a form which allows for product creation and a table to list the products. The table has two buttons which allow for editing and deleting the products.

### License
The Laravel framework is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).

### Author
Samuel Kahara - Github: [mkahara](https://github.com/mkahara)

