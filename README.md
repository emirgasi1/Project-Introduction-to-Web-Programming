This is a Food Ordering System for a Sandwich Shop, designed as a Single Page Application (SPA). This project is meant allow customers to browse the menu, add items to their cart, and place orders online.

The project consists of:

-Frontend: HTML, CSS, Bootstrap, JavaScript (jQuery, SPApp.js)
-Database: MySQL(DBeaver)



Project Structure

The project structures is composed of frontend and backend folders for better organization.

The database is designed using MySQL, with 6 main tables to support orders, users, and payments.



Entity-Relationship Diagram (ERD)

The database is structured to ensure scalability and efficiency. Here's why each table exists:

Users -Stores customer data, including login credentials and roles (admin/user).

Products -Contains the details of each sandwich (name, description, price, image).

Categories -Groups similar products together (e.g., "Sandwiches", "Sides", "Drinks").

Orders -Stores user orders with their total price and status (pending, completed, etc.).

Order Items -Links orders to products, storing the quantity of each item in an order.

Payments -Tracks payment details for each order, including method and status.

Relationships

One user can have many orders.

One order contains multiple order items.

Each order item is linked to a product.

Each product belongs to one category.

One order can have one payment.

The ERD diagram for this project is available in the **assets/** folder.

!!!!![ERD Diagram](assets/erd-diagram.png)!!!!!