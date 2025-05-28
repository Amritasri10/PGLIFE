# PG Life Web Application

PG Life is a **Full-Stack Web Application** developed during my **Internshala Web Development Internship Training**.  
Guided by my mentors, I created this app with my own understanding and customized it for better user experience and functionalities.

---

## Features

### 1. Home Page
- Search bar to enter any city name (case insensitive) and view PGs listed in that city (if available).
- Displays popular cities as clickable circular sections to quickly view PG listings.

### 2. PG List Page
- Shows PGs in the selected city as cards highlighting key features.
- Filter bar to sort PGs by rent and rating (ascending/descending).
- Displays the number of users interested in each PG.
- Logged-in users can mark/unmark PGs as interested by toggling a heart icon that updates dynamically.

### 3. PG Details Page
- Detailed information about the selected PG, accessible by clicking "View" on any PG card.
- Image carousel showcasing PG photos.
- Lists amenities, testimonials, and address neatly.
- Shows the popularity of the PG by interested user count.
- Logged-in users can toggle interest status via the heart icon dynamically.

### 4. User Dashboard
- Accessible only to logged-in users.
- Displays logged-in user’s account details.
- Lists PGs marked interested by the user across all cities.
- Users can remove PGs from their interested list dynamically.

### 5. Navbar
- Brand name displayed prominently.
- Shows **Signup** and **Login** options when not logged in.
- Shows **Dashboard**, **Logout**, and user’s first name when logged in.
- Fully responsive with a collapsible toggler.

### 6. Breadcrumb Navigation
- Displays the user's current location within the app.
- Contains clickable links for easy navigation.

### 7. Footer
- Lists popular city PG links.
- Shows copyright information.

---

## Additional Details

- The web app is **fully responsive** and works smoothly on all device sizes.
- Users can browse without logging in, but features like dashboard and marking interest require login.
- All exceptions and errors are handled gracefully with custom UI messages to ensure clarity.

---

## Technology Stack

- **Frontend:** HTML, CSS, Bootstrap 5, JavaScript, AJAX,React
- **Backend:** PHP  
- **Database:** MySQL

---

## How to Run

1. Clone the repository:  
   ``` git clone https://github.com/Amritasri10/PGLIFE.git ```
2. Import the database from ```pg_life_database/pg_life.sql``` into your        MySQL server.
3. Set up your PHP server (e.g., XAMPP, WAMP).
4. Open the project folder in your server's root directory.
5. Access the application via your browser at         
    ```http://localhost/PGLIFE/index.php```.

---

## License

This project is for **educational purposes only** and was created as part of an internship.

Thank you for visiting the PG Life project!
